<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        //$coupons = Type::all();
        return view('category.index');
    }

    public function getDataCategories(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $name = $request->input('name');

        $query = Category::orderBy('id');

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
            if ( $type->visible == 1 )
            {
                $visibleText = '<span class="badge bg-success">VISIBLE</span>';
            } else {
                $visibleText = '<span class="badge bg-danger">OCULTO</span>';
            }

            if ( $type->enable_status == 1 )
            {
                $enableText = '<span class="badge bg-success">ACTIVO</span>';
            } else {
                $enableText = '<span class="badge bg-danger">INACTIVO</span>';
            }

            array_push($array, [
                "id" => $type->id,
                "name" => $type->name,
                "description" => $type->description,
                "visible" => $type->visible,
                "visibleText" => $visibleText,
                "enable_status" => $type->enable_status,
                "enableText" => $enableText
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
        return view('category.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'name' => 'required|string|unique:categories,name',
                'description' => 'nullable|string',
            ]);

            // Procesar el estado de visibilidad
            $isActive = $request->has('active') ? 1 : 0; // Si 'active' no está presente, será 0

            // Crear la categoría
            $category = new Category();
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->visible = $isActive; // Asignar el estado de visibilidad
            $category->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
            ]);

            $category = Category::find($category->id);
            // Procesar el estado de visibilidad
            $isActive = $request->has('active') ? 1 : 0; // Si 'active' no está presente, será 0

            // Crear la categoría
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->visible = $isActive; // Asignar el estado de visibilidad
            $category->save();

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

            $category = Category::find($request->get('category_id'));

            if ($category->enable_status == 1)
            {
                $category->update(['enable_status' => 0]); // Cambiar estado a inactivo
            } else {
                $category->update(['enable_status' => 1]); // Cambiar estado a inactivo
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Categoría cambiada de estado con éxito.'], 200);

    }
}
