<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('coupon.index', compact('coupons'));
    }

    public function getDataCoupons(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $name = $request->input('name');

        $query = Coupon::orderBy('id');

        // Aplicar filtros si se proporcionan
        if ($name != "") {
            // Convertir la cadena de búsqueda en un array de palabras clave
            $keywords = explode(' ', $name);

            // Construir la consulta para buscar todas las palabras clave en el campo full_name
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('name', 'LIKE', '%' . $keyword . '%');
                }
            });

            // Asegurarse de que todas las palabras clave estén presentes en la descripción
            foreach ($keywords as $keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $coupons = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $coupons as $coupon )
        {
            if ( $coupon->status == 'active' )
            {
                $stateText = '<span class="badge bg-success">Activo</span>';
            } else {
                $stateText = '<span class="badge bg-danger">Inactivo</span>';
            }

            if ( $coupon->type == 'detail' )
            {
                $typeText = '<span class="badge bg-primary">A DETALLES</span>';
            } else {
                $typeText = '<span class="badge bg-success">AL TOTAL</span>';
            }

            if ( $coupon->special == 1 )
            {
                $specialText = '<span class="badge bg-success">NO CADUCA</span>';
            } else {
                $specialText = '<span class="badge bg-primary">UNA SOLA VEZ</span>';
            }

            array_push($array, [
                "id" => $coupon->id,
                "nombre" => $coupon->name,
                "descripcion" => $coupon->description,
                "precio" => $coupon->amount,
                "porcentaje" => $coupon->percentage,
                "estado" => $stateText,
                "status" => $coupon->status,
                "typeText" => $typeText,
                "specialText" => $specialText
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

    public function create()
    {
        return view('coupon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:coupons,name|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
            'special' => 'nullable|string', // Validación para el checkbox especial
            'type' => 'nullable|string', // Validación para el checkbox status
        ]);
        //dd($request);
        //$validated = $request->validated();

        DB::beginTransaction();
        try {



            // Convertimos el valor de "status" y "special" a booleano
            $type = $request->get('type') === 'on' ? 'total' : 'detail';
            $special = $request->get('special') === 'on' ? 1 : 0;

            // Creamos el cupón
            Coupon::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'amount' => $request->get('amount'),
                'percentage' => $request->get('percentage'),
                'special' => $special,
                'type' => $type,
            ]);

            DB::commit();
            return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

    }

    public function edit(Coupon $coupon)
    {
        return view('coupon.edit', compact('coupon'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'coupon_id' => 'required|exists:coupons,id',
                'name' => 'required|unique:coupons,name,' . $request->get('coupon_id') . '|max:255',
                'description' => 'nullable|string',
                'amount' => 'nullable|numeric',
                'percentage' => 'nullable|numeric',
                'special' => 'nullable|string',
                'type' => 'nullable|string',
            ]);

            // Obtener el cupón a actualizar
            $coupon = Coupon::findOrFail($request->get('coupon_id'));

            // Convertir valores de checkboxes
            $type = $request->get('type') === 'on' ? 'total' : 'detail';
            $special = $request->get('special') === 'on' ? 1 : 0;

            // Actualizar los datos del cupón
            $coupon->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'amount' => $request->get('amount'),
                'percentage' => $request->get('percentage'),
                'type' => $type,
                'special' => $special,
            ]);

            DB::commit();
            return response()->json(['message' => 'Cupón actualizado con éxito.'], 200);

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }


    }

    public function destroy(Coupon $coupon)
    {
        DB::beginTransaction();
        try {

            $coupon->update(['status' => 'inactive']); // Cambiar estado a inactivo

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cupón desactivado con éxito.'], 200);

    }
}
