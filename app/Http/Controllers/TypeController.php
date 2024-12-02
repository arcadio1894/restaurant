<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
    public function index()
    {
        $coupons = Type::all();
        return view('type.index', compact('coupons'));
    }

    public function getDataTypes(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $name = $request->input('name');

        $query = Type::orderBy('id');

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

        $types = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $types as $type )
        {
            if ( $type->active == 1 )
            {
                $stateText = '<span class="badge bg-success">Activo</span>';
            } else {
                $stateText = '<span class="badge bg-danger">Inactivo</span>';
            }

            array_push($array, [
                "id" => $type->id,
                "name" => $type->name,
                "size" => $type->size,
                "price" => $type->price,
                "active" => $type->active,
                "stateText" => $stateText
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
        return view('type.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'name' => 'required|string|unique:types,name',
                'size' => 'required|string',
                'price' => 'nullable|numeric',
            ]);

            Type::create([
                'name' => $request->get('name'),
                'size' => $request->get('size'),
                'price' => $request->get('price'),
                'active' => 1
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);
    }

    public function edit(Type $type)
    {
        return view('type.edit', compact('type'));
    }

    public function update(Request $request, Type $type)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|max:255|unique:types,name,' . $type->id,
                'size' => 'nullable|string',
                'price' => 'nullable|numeric',
            ]);

            $type->update($request->all());

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $type = Type::find($request->get('type_id'));

            if ($type->active == 1)
            {
                $type->update(['active' => 0]); // Cambiar estado a inactivo
            } else {
                $type->update(['active' => 1]); // Cambiar estado a inactivo
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tipo cambiado de estado con éxito.'], 200);

    }
}
