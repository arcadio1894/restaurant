<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReclamacionRequest;
use App\Mail\CambioEstadoReclamo;
use App\Mail\ReclamacionRecepcionada;
use App\Models\Department;
use App\Models\District;
use App\Models\Motivo;
use App\Models\Province;
use App\Models\Reclamacion;
use App\Models\Submotivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Exception;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ReclamacionController extends Controller
{
    public function reclamaciones()
    {
        $departments = Department::all();
        $motivos = Motivo::all();
        return view('welcome.reclamaciones', compact('departments', 'motivos'));
    }

    public function estadoReclamos()
    {
        return view('welcome.estadoReclamos');
    }

    public function getProvinces($departmentId)
    {
        $provinces = Province::where('department_id', $departmentId)->get();
        return response()->json($provinces);
    }

    public function getDistricts($provinceId)
    {
        $districts = District::where('province_id', $provinceId)->get();
        return response()->json($districts);
    }

    public function getSubmotivos($motivoId)
    {
        $submotivos = Submotivo::where('motivo_id', $motivoId)->get();
        return response()->json($submotivos);
    }

    public function store(StoreReclamacionRequest $request)
    {
        try {
            // Iniciar una transacción
            DB::beginTransaction();

            // Validar reCAPTCHA
            $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
            ]);

            if (!$recaptchaResponse->json()['success']) {
                throw new Exception('El CAPTCHA es inválido. Por favor intenta nuevamente.');
            }

            // Validación adicional para menores de edad
            if ($request->input('menor_edad') === 'si') {
                $request->validate([
                    'nombre_representante' => 'required|string|max:255',
                    'telefono_representante' => 'required|string|max:15',
                    'direccion_representante' => 'required|string|max:255',
                    'correo_representante' => 'required|email|max:255',
                ]);
            }

            // Convertir el valor de menor_edad a booleano
            $menorEdad = $request->input('menor_edad') === 'si';

            // Generar código único en el formato 'RECXXXXXX'
            $codigo = 'REC' . Str::upper(Str::random(6));

            // Manejar el archivo de comprobante
            $comprobantePath = null;
            if ($request->hasFile('comprobante')) {
                $file = $request->file('comprobante');
                $extension = $file->getClientOriginalExtension();
                $fileName = "reclamos/" . $codigo . '.' . $extension;

                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    // Procesar y guardar la imagen
                    $image = Image::make($file->getRealPath());
                    $image->save(public_path($fileName));
                } elseif ($extension === 'pdf') {
                    // Guardar el archivo PDF directamente
                    $file->move(public_path('reclamos'), $fileName);
                } else {
                    throw new Exception('Formato de archivo no permitido.');
                }

                $comprobantePath = $fileName;
            }

            // Crear la reclamación en la base de datos
            $reclamacion = Reclamacion::create(array_merge($request->all(), ['codigo' => $codigo, 'menor_edad' => $menorEdad, 'comprobante' => $comprobantePath]));

            // Enviar correo electrónico al cliente
            Mail::to($reclamacion->email)->send(new ReclamacionRecepcionada($reclamacion));

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Reclamo enviado con éxito.', 'codigo' => $codigo]);

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return response()->json(['message' => 'Ocurrió un error durante el proceso: ' . $e->getMessage()], 500);
        }
    }

    public function consultarEstado(Request $request)
    {
        try {
            // Validar reCAPTCHA
            $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
            ]);

            if (!$recaptchaResponse->json()['success']) {
                return response()->json(['errors' => ['captcha' => 'El CAPTCHA es inválido. Por favor intenta nuevamente.']], 422);
            }

            // Validar que se haya enviado un código de reclamo
            $request->validate([
                'codigo' => 'required|string|max:10'
            ]);

            // Buscar el reclamo por el código
            $reclamo = Reclamacion::where('codigo', $request->input('codigo'))->first();

            if (!$reclamo) {
                return response()->json(['message' => 'No se encontró ningún reclamo con ese código.', 'reclamo' => null]);
            }

            // Formatear la respuesta
            $reclamoData = [
                'fecha' => $reclamo->created_at->format('d/m/Y'),
                'codigo' => $reclamo->codigo,
                'estado' => $reclamo->status,
                'solucion' => ($reclamo->solucion != null) ? $reclamo->solucion : 'Sin solución'
            ];

            return response()->json(['message' => 'Consulta realizada con éxito.', 'reclamo' => $reclamoData]);

        } catch (Exception $e) {
            return response()->json(['errors' => ['error' => 'Ocurrió un error durante la consulta.']], 500);
        }
    }

    public function index()
    {
        $motivos = Motivo::all();
        return view('reclamaciones.index', compact('motivos'));
    }

    public function getDataReclamos(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $codigo = $request->input('codigo');
        $tipo_reclamo = $request->input('tipo_reclamo');
        $documento = $request->input('documento');
        $canal = $request->input('canal');
        $motivo = $request->input('motivo');
        $submotivo = $request->input('submotivo');

        $query = Reclamacion::whereIn('estado', ['pendiente', 'revisado'])->orderBy('created_at');

        // Aplicar filtros si se proporcionan
        if ($codigo != "") {
            $query->where('codigo', $codigo);
        }

        if ($tipo_reclamo != "") {
            $query->where('tipo_reclamacion', $tipo_reclamo);
        }

        if ($documento != "") {
            $query->where('numero_documento', $documento);
        }

        if ($canal != "") {
            $query->where('canal', $canal);
        }

        if ($motivo != "") {
            $query->where('motivo', $motivo);
        }

        if ($submotivo != "") {
            $query->where('submotivo', $submotivo);
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $reclamos = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $reclamos as $reclamo )
        {
            array_push($array, [
                "id" => $reclamo->id,
                "codigo" => $reclamo->codigo,
                "fecha" => $reclamo->created_at->format('d/m/Y'),
                "cliente" => $reclamo->nombre." ".$reclamo->apellido,
                "estado" => $reclamo->status,
                "solucion" => $reclamo->respuesta,
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $array, 'pagination' => $pagination];
    }

    public function show($id)
    {
        $reclamo = Reclamacion::find($id);
        return view('reclamaciones.show', compact('reclamo'));
    }

    public function guardarRespuesta(Request $request)
    {
        $request->validate([
            'respuesta' => 'required|string|max:300',
            'estado' => 'required|string|in:revisado,solucionado,anulado',
        ]);

        try {
            // Buscar el reclamo (se asume que el código o ID llega desde el frontend)
            $reclamo = Reclamacion::findOrFail($request->input('reclamo_id'));

            // Actualizar la respuesta y el estado
            $reclamo->respuesta = $request->input('respuesta');
            $reclamo->estado = $request->input('estado');
            $reclamo->save();

            // Enviar notificación por correo electrónico
            Mail::to($reclamo->email)->send(new CambioEstadoReclamo($reclamo));

            return response()->json(['message' => 'Respuesta guardada exitosamente.']);
        } catch (Exception $e) {
            return response()->json(['errors' => ['error' => 'Ocurrió un error al guardar la respuesta.']], 500);
        }
    }

    public function getDataReclamosFinalizados(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $codigo = $request->input('codigo');
        $tipo_reclamo = $request->input('tipo_reclamo');
        $documento = $request->input('documento');
        $canal = $request->input('canal');
        $motivo = $request->input('motivo');
        $submotivo = $request->input('submotivo');

        $query = Reclamacion::whereIn('estado', ['solucionado', 'anulado'])->orderBy('created_at');

        // Aplicar filtros si se proporcionan
        if ($codigo != "") {
            $query->where('codigo', $codigo);
        }

        if ($tipo_reclamo != "") {
            $query->where('tipo_reclamacion', $tipo_reclamo);
        }

        if ($documento != "") {
            $query->where('numero_documento', $documento);
        }

        if ($canal != "") {
            $query->where('canal', $canal);
        }

        if ($motivo != "") {
            $query->where('motivo', $motivo);
        }

        if ($submotivo != "") {
            $query->where('submotivo', $submotivo);
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $reclamos = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $reclamos as $reclamo )
        {
            array_push($array, [
                "id" => $reclamo->id,
                "codigo" => $reclamo->codigo,
                "fecha" => $reclamo->created_at->format('d/m/Y'),
                "cliente" => $reclamo->nombre." ".$reclamo->apellido,
                "estado" => $reclamo->status,
                "solucion" => $reclamo->respuesta,
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $array, 'pagination' => $pagination];
    }

    public function indexFinalizados()
    {
        $motivos = Motivo::all();
        return view('reclamaciones.indexFinalizados', compact('motivos'));
    }

    public function showFinalizado($id)
    {
        $reclamo = Reclamacion::find($id);
        return view('reclamaciones.showFinalizado', compact('reclamo'));
    }

}
