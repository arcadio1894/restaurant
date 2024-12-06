<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Selection;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getDataProducts(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $full_name = $request->input('full_name');
        $code = $request->input('code');
        $category = $request->input('category');

        $query = Product::with('category:id,name')
            ->orderBy('id');

        // Aplicar filtros si se proporcionan
        if ($full_name != "") {
            // Convertir la cadena de búsqueda en un array de palabras clave
            $keywords = explode(' ', $full_name);

            // Construir la consulta para buscar todas las palabras clave en el campo full_name
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('full_name', 'LIKE', '%' . $keyword . '%');
                }
            });

            // Asegurarse de que todas las palabras clave estén presentes en la descripción
            foreach ($keywords as $keyword) {
                $query->where('full_name', 'LIKE', '%' . $keyword . '%');
            }
        }

        if ($code != "") {
            $query->where('code', 'LIKE', '%'.$code.'%');
        }

        if ($category != "") {
            $query->where('category_id', $category);
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $products = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $products as $product )
        {
            if ( $product->enable_status == 1 )
            {
                $estado = '<span class="badge bg-success">ACTIVO</span>';
                $textEstado = "activo";
            } else {
                $estado = '<span class="badge bg-danger">INACTIVO</span>';
                $textEstado = "inactivo";
            }
            array_push($array, [
                "id" => $product->id,
                "codigo" => $product->code,
                "descripcion" => $product->description,
                "nombre" => $product->full_name,
                "precio" => $product->price_default,
                "categoria" => ($product->category == null) ? '': $product->category->name,
                "ingredientes" => $product->ingredients,
                "state" => $product->enable_status,
                "estado" => $estado,
                "textEstado" => $textEstado,
                "image" => ($product->image == null || $product->image == "" ) ? 'no_image.png':$product->image,

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

    public function indexAdmin()
    {
        $user = Auth::user();
        /*$permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();*/

        $arrayCategories = Category::select('id', 'name')->get()->toArray();

        return view('product.indexv2', compact( 'arrayCategories'));

    }

    public function create()
    {
        $categories = Category::all();
        $types = Type::all();

        $products = Product::all();
        return view('product.create', compact('categories', 'types', 'products'));
    }

    public function store(StoreProductRequest $request)
    {
        //dd($request);
        $validated = $request->validated();
        DB::beginTransaction();
        try {

            $product = Product::create([
                'description' => $request->get('description'),
                'full_name' => $request->get('full_name'),
                'unit_price' => $request->get('unit_price'),
                'category_id' => $request->get('category'),
                'ingredients' => $request->get('ingredients'),
                'enable_status' => 1
            ]);

            $length = 5;
            $string = $product->id;
            $code = 'P-'.str_pad($string,$length,"0", STR_PAD_LEFT);
            //output: 0012345

            $product->code = $code;
            $product->save();

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                $product->image = 'no_image.png';
                $product->save();
            } else {
                $path = public_path().'/images/products/';
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $product->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $product->image = $filename;
                $product->save();
            }

            //$mat = $material;
            // TODO: Guardar las promociones
            $types = $request->input('type', []);
            $priceTypes = $request->input('productPrice', []);
            $defaultType = $request->input('defaultType');

            if (!$defaultType && count($types) > 0) {
                return response()->json(['message' => 'El tipo por defecto no se seleccionó.'], 422);
            }

            foreach ($types as $typeId => $value) {
                // Verificamos si el checkbox fue marcado
                if (isset($value)) {
                    // Obtenemos el precio correspondiente
                    $priceType = isset($priceTypes[$typeId]) ? $priceTypes[$typeId] : null;

                    // Guardamos la información en la base de datos
                    ProductType::create([
                        'product_id' => $product->id,
                        'type_id' => $typeId,
                        'price' => $priceType,
                        'default' => ($typeId == $defaultType), // Solo el seleccionado será default
                    ]);
                }
            }

            // Decodificar las opciones enviadas como JSON
            $options = json_decode($request->input('options'), true);

            if ($options) {
                foreach ($options as $option) {
                    $newOption = Option::create([
                        'product_id' => $product->id,
                        'description' => $option['description'],
                        'quantity' => $option['quantity'],
                        'type' => $option['type'],
                    ]);

                    foreach ($option['selections'] as $selection) {
                        Selection::create([
                            'option_id' => $newOption->id,
                            'product_id' => $selection['product_id'],
                            'additional_price' => ($selection['additional_price'] == null || $selection['additional_price'] == "") ? null : $selection['additional_price'],
                        ]);
                    }
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Producto guardado con éxito.'], 200);

    }

    public function edit($id)
    {
        $product = Product::with([
            'category',
            'options' => function ($query) {
                $query->where('active', 1); // Filtrar opciones activas
            },
            'options.selections' => function ($query) {
                $query->where('active', 1); // Filtrar selecciones activas
            }
        ])->find($id);

        $categories = Category::all();
        $types = Type::all();
        $priceTypes = ProductType::where('product_id', $id)
            ->get()
            ->keyBy('type_id')
            ->map(function($item) {
                return [
                    'price' => $item->price,
                    'default' => $item->default,
                ];
            })
            ->toArray();
        //dd($priceTypes);
        $products = Product::all();

        return view('product.edit', compact('product', 'categories', 'types', 'priceTypes', 'products'));

    }

    public function update(UpdateProductRequest $request)
    {
        //dd($request);
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $product = Product::find($request->get('product_id'));
            $product->full_name = $request->get('full_name');
            $product->description = $request->get('description');
            $product->unit_price = $request->get('unit_price');
            $product->category_id = $request->get('category');
            $product->ingredients = $request->get('ingredients');
            $product->save();

            // TODO: Tratamiento de un archivo de forma tradicional
            if (!$request->file('image')) {
                if ($product->image == 'no_image.png' || $product->image == null) {
                    $product->image = 'no_image.png';
                    $product->save();
                }
            } else {
                $path = public_path().'/images/products/';
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $product->id . '.' . $extension;
                $request->file('image')->move($path, $filename);
                $product->image = $filename;
                $product->save();
            }

            // TODO: Guardar las product types
            $types = $request->input('type', []); // Tipos seleccionados
            $priceTypes = $request->input('productPrice', []); // Precios de los tipos
            $defaultType = $request->input('defaultType'); // Tipo marcado como default

            // Obtener los tipos actuales de la base de datos
            $existingProductTypes = ProductType::where('product_id', $product->id)->get();

            // Convertir los tipos existentes a un array asociativo para fácil acceso
            $existingProductTypesMap = $existingProductTypes->keyBy('type_id');

            // Recorrer los tipos enviados desde el formulario
            foreach ($types as $typeId => $value) {
                if (isset($value)) {
                    // Obtenemos el precio correspondiente
                    $priceType = isset($priceTypes[$typeId]) ? $priceTypes[$typeId] : null;

                    if (isset($existingProductTypesMap[$typeId])) {
                        // Si ya existe, actualizamos los datos
                        $existingProductType = $existingProductTypesMap[$typeId];
                        $existingProductType->update([
                            'price' => $priceType,
                            'default' => ($typeId == $defaultType),
                        ]);
                    } else {
                        // Si no existe, lo creamos
                        ProductType::create([
                            'product_id' => $product->id,
                            'type_id' => $typeId,
                            'price' => $priceType,
                            'default' => ($typeId == $defaultType),
                        ]);
                    }
                }
            }

            // Eliminar los tipos que no están en la nueva lista
            $typesToKeep = array_keys($types); // IDs de los tipos seleccionados
            $existingProductTypes->each(function ($productType) use ($typesToKeep) {
                if (!in_array($productType->type_id, $typesToKeep)) {
                    $productType->delete();
                }
            });

            $options = json_decode($request->input('options'), true);
            // Obtener todas las opciones actuales del producto
            $existingOptions = Option::where('product_id', $product->id)->with('selections')->get();

            // IDs de opciones y selecciones procesadas
            $processedOptionIds = [];
            $processedSelectionIds = [];
            foreach ($options as $optionData) {
                if (!empty($optionData['id'])) {
                    // Si la opción ya existe, actualizarla
                    $option = Option::find($optionData['id']);
                    if ($option) {
                        $option->update([
                            'description' => $optionData['description'],
                            'quantity' => $optionData['quantity'],
                            'type' => $optionData['type'],
                            'active' => 1, // Reactivar la opción
                        ]);
                        $processedOptionIds[] = $option->id;
                    }
                } else {
                    // Crear nueva opción
                    $option = Option::create([
                        'product_id' => $product->id,
                        'description' => $optionData['description'],
                        'quantity' => $optionData['quantity'],
                        'type' => $optionData['type'],
                        'active' => 1,
                    ]);
                    $processedOptionIds[] = $option->id;
                }

                // Procesar selecciones asociadas a la opción
                foreach ($optionData['selections'] as $selectionData) {
                    if (!empty($selectionData['id'])) {
                        // Si la selección ya existe, actualizarla
                        $selection = Selection::find($selectionData['id']);
                        if ($selection) {
                            $selection->update([
                                'product_id' => $selectionData['product_id'],
                                'additional_price' => ($selectionData['additional_price'] == null || $selectionData['additional_price'] == "") ? null : $selectionData['additional_price'],
                                'active' => 1, // Reactivar la selección
                            ]);
                            $processedSelectionIds[] = $selection->id;
                        }
                    } else {
                        // Crear nueva selección
                        $newSelection = Selection::create([
                            'option_id' => $option->id,
                            'product_id' => $selectionData['product_id'],
                            'additional_price' => ($selectionData['additional_price'] == null || $selectionData['additional_price'] == "") ? null : $selectionData['additional_price'],
                            'active' => 1,
                        ]);
                        $processedSelectionIds[] = $newSelection->id;
                    }
                }

                // Marcar como inactivas las selecciones no enviadas
                $existingSelections = $option->selections;
                foreach ($existingSelections as $existingSelection) {
                    if (!in_array($existingSelection->id, $processedSelectionIds)) {
                        $existingSelection->update(['active' => 0]);
                    }
                }
            }

            // Marcar como inactivas las opciones no enviadas
            foreach ($existingOptions as $existingOption) {
                if (!in_array($existingOption->id, $processedOptionIds)) {
                    $existingOption->update(['active' => 0]);
                    foreach ($existingOption->selections as $selection) {
                        $selection->update(['active' => 0]);
                    }
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

    }

    public function delete(DeleteProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::find($request->get('product_id'));

        $currentState = $product->enable_status;

        if ($currentState == 1)
        {
            $product->enable_status = 0;
        } else {
            $product->enable_status = 1;
        }

        $product->save();

        return response()->json(['message' => 'Producto cambiado su estado con éxito.'], 200);

    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Obtener los tipos relacionados al producto
        $productTypes = $product->productTypes()
            ->whereHas('type', function ($query) {
                $query->where('active', 1); // Filtra solo los tipos activos
            })
            ->with('type')
            ->get();

        // Obtener el tipo por defecto
        $defaultProductType = $productTypes->where('default', true)->first();

        $options = Option::where('product_id', $id)
            ->where('active', 1) // Solo opciones activas
            ->with(['selections' => function ($query) {
                $query->where('active', 1); // Solo selecciones activas
            }])
            ->get();

        //dd($options);
        $adicionales = Product::whereHas('category', function ($query) {
            $query->where('visible', false);
        })->with('category')->get();

        //dd($adicionales);

        $chunkedAdicionales = $adicionales->chunk(4);

        //dd($chunkedAdicionales);

        return view('product.show', compact('product', 'productTypes', 'defaultProductType', 'options', 'adicionales'));
    }

}
