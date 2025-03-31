<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\DataGeneral;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductDay;
use App\Models\ProductType;
use App\Models\Selection;
use App\Models\Topping;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function getDataProducts(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $full_name = $request->input('full_name');
        $code = $request->input('code');
        $category = $request->input('category');

        $query = Product::with('category:id,name')
            ->where('enable_status', '<>', 2)
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
            } elseif ( $product->enable_status == 0 ) {
                $estado = '<span class="badge bg-warning">INACTIVO</span>';
                $textEstado = "inactivo";
            } else {
                $estado = '<span class="badge bg-danger">ELIMINADO</span>';
                $textEstado = "inactivo";
            }

            if ( $product->visibility_price_real == 1 )
            {
                $estado_visibility = '<span class="badge bg-success">VISIBLE</span>';
            } else {
                $estado_visibility = '<span class="badge bg-primary">NO VISIBLE</span>';
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
                "slug" => $product->slug,
                "visibility_price_real" => $estado_visibility,
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

    public function getDataProductsDeleted(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $full_name = $request->input('full_name');
        $code = $request->input('code');
        $category = $request->input('category');

        $query = Product::with('category:id,name')
            ->where('enable_status', 2)
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
            } elseif ( $product->enable_status == 0 ) {
                $estado = '<span class="badge bg-warning">INACTIVO</span>';
                $textEstado = "inactivo";
            } else {
                $estado = '<span class="badge bg-danger">ELIMINADO</span>';
                $textEstado = "inactivo";
            }

            if ( $product->visibility_price_real == 1 )
            {
                $estado_visibility = '<span class="badge bg-success">VISIBLE</span>';
            } else {
                $estado_visibility = '<span class="badge bg-primary">NO VISIBLE</span>';
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
                "slug" => $product->slug,
                "visibility_price_real" => $estado_visibility,
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

    public function indexAdminDeleted()
    {
        $user = Auth::user();
        /*$permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();*/

        $arrayCategories = Category::select('id', 'name')->get()->toArray();

        return view('product.indexv2Deleted', compact( 'arrayCategories'));

    }

    public function create()
    {
        $categories = Category::all();
        $types = Type::all();

        $products = Product::all();

        $days = [
            ['day' => 'domingo', 'number' => 0],
            ['day' => 'lunes', 'number' => 1],
            ['day' => 'martes', 'number' => 2],
            ['day' => 'miércoles', 'number' => 3],
            ['day' => 'jueves', 'number' => 4],
            ['day' => 'viernes', 'number' => 5],
            ['day' => 'sábado', 'number' => 6],
        ];

        return view('product.create', compact('categories', 'types', 'products', 'days'));
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
                'enable_status' => 1,
                'visibility_price_real' => $request->get('visibility_price_real') === 'on' ? 1 : 0,
            ]);

            $length = 5;
            $string = $product->id;
            $code = 'P-'.str_pad($string,$length,"0", STR_PAD_LEFT);
            //output: 0012345

            $product->code = $code;
            $product->save();

            // Procesar la imagen
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $destinationPath = public_path('/images/products/'); // Carpeta destino

                // Asegúrate de que la carpeta exista
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Generar el nombre del archivo
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;

                try {
                    // Procesar la imagen con Intervention
                    $image = Image::make($file->getRealPath());

                    // Guardar la imagen en la carpeta destino
                    $image->save($destinationPath . $filename);

                    // Asignar el nombre de la imagen al producto
                    $product->image = $filename;

                } catch (\Exception $e) {
                    return response()->json(['error' => 'Error al procesar la imagen: ' . $e->getMessage()], 500);
                }
            } else {
                // Si no se sube una imagen, asignar la predeterminada
                $product->image = 'no_image.png';
            }

            // Guardar el producto en la base de datos
            $product->save();

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

            // **Lógica para guardar los días activos (ProductDay)**
            $days = $request->input('days', []); // Obtenemos los días seleccionados
            $dateFinish = $request->input('date_validate'); // Obtenemos la fecha límite si se proporcionó

            foreach ($days as $dayNumber => $isChecked) {
                if ($isChecked) {
                    ProductDay::create([
                        'product_id' => $product->id,
                        'day' => $dayNumber,
                        'date_finish' => $dateFinish ? Carbon::createFromFormat('d/m/Y', $dateFinish) : null, // Convertimos el formato
                    ]);
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

        $days = [
            ['day' => 'domingo', 'number' => 0],
            ['day' => 'lunes', 'number' => 1],
            ['day' => 'martes', 'number' => 2],
            ['day' => 'miércoles', 'number' => 3],
            ['day' => 'jueves', 'number' => 4],
            ['day' => 'viernes', 'number' => 5],
            ['day' => 'sábado', 'number' => 6],
        ];

        $productDays = ProductDay::where('product_id', $id)->get()->keyBy('day')->toArray();

        return view('product.edit', compact('product', 'categories', 'types', 'priceTypes', 'products', 'days', 'productDays'));

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
            $product->visibility_price_real = $request->get('visibility_price_real') === 'on' ? 1 : 0;
            $product->save();

            // Verificar si hay un archivo cargado
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Define la carpeta de destino
                $destinationPath = public_path('/images/products/');

                // Asegúrate de que la carpeta existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Genera el nombre del archivo con extensión
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                try {
                    // Verifica si ya existe una imagen con el mismo nombre
                    $existingFile = $destinationPath . $filename;
                    if (file_exists($existingFile)) {
                        unlink($existingFile); // Elimina la imagen existente
                    }

                    // Procesar la imagen con Intervention
                    $image = Image::make($file->getRealPath());

                    // Guardar la imagen en la carpeta
                    $image->save($destinationPath . $filename);

                    // Actualizar el registro del producto
                    $product->image = $filename;
                    $product->save();

                } catch (\Exception $e) {
                    return response()->json(['error' => 'Error al procesar la imagen: ' . $e->getMessage()], 500);
                }
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

            // Manejo de ProductDays
            $selectedDays = $request->input('days', []); // Días seleccionados (array)
            $dateFinish = $request->input('date_validate'); // Fecha de finalización
            $dateFinish = $dateFinish ? Carbon::createFromFormat('d/m/Y', $dateFinish)->format('Y-m-d') : null;

            // Obtener los días actuales del producto
            $existingProductDays = ProductDay::where('product_id', $product->id)->get();

            // Procesar días seleccionados
            foreach ($selectedDays as $dayNumber => $value) {
                $productDay = $existingProductDays->firstWhere('day', $dayNumber);
                if ($productDay) {
                    // Actualizar día existente
                    $productDay->update(['date_finish' => $dateFinish]);
                } else {
                    // Crear nuevo día
                    ProductDay::create([
                        'product_id' => $product->id,
                        'day' => $dayNumber,
                        'date_finish' => $dateFinish,
                    ]);
                }
            }

            // Eliminar días no seleccionados
            $daysToKeep = array_keys($selectedDays);
            $existingProductDays->each(function ($productDay) use ($daysToKeep) {
                if (!in_array($productDay->day, $daysToKeep)) {
                    $productDay->delete();
                }
            });

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
            $product->date_reactivate = null;
        } else {
            $product->enable_status = 1;
            $product->date_reactivate = null;
        }

        $product->save();

        return response()->json(['message' => 'Producto cambiado su estado con éxito.'], 200);

    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Obtener los tipos relacionados al producto
        /*$productTypes = $product->productTypes()
            ->whereHas('type', function ($query) {
                $query->where('active', 1); // Filtra solo los tipos activos
            })
            ->with('type')
            ->get();*/
        $productTypes = $product->productTypes()
            ->with('type')
            ->get();

        // Verificar si el producto tiene solo un productType y si su type está inactivo
        if ($productTypes->count() === 1 && optional($productTypes->first()->type)->active == 0) {
            return redirect()->route('welcome'); // Redirigir si la condición se cumple
        }

        // Filtrar solo los tipos activos
        $productTypes = $productTypes->where('type.active', 1);

        // Obtener el tipo por defecto
        $defaultProductType = $productTypes->where('default', true)->first();

        $options = Option::where('product_id', $product->id)
            ->where('active', 1) // Solo opciones activas
            ->with(['selections' => function ($query) {
                $query->where('active', 1); // Solo selecciones activas
            }])
            ->get();

        // Asignar precio basado en el tipo de producto predeterminado
        if ($defaultProductType) {
            foreach ($options as $option) {
                foreach ($option->selections as $selection) {
                    // Verifica si existe un ProductType asociado, si no, usa el price_default
                    $price = ProductType::where('product_id', $selection->product_id)
                        ->where('type_id', $defaultProductType->type_id)
                        ->value('price');

                    // Asigna el precio desde ProductType o el price_default
                    $price = ($price !== null) ? $price : $selection->product->price_default;


                    // Almacena el precio en la relación (propiedad temporal)
                    $selection->product_price = $price;
                }
            }
        }

        //dd($options);
        /*$adicionales = Product::whereHas('category', function ($query) {
            $query->where('visible', true);
        })->orWhere('category_id', 5)->orWhere('category_id', 6)->with('category')->get();*/
        $adicionales = Product::whereIn('category_id', [5, 6])
            ->with(['category', 'productTypes.type'])
            ->get();

        //dd($adicionales);

        $chunkedAdicionales = $adicionales->chunk(4);

        //dd($chunkedAdicionales);

        //return view('product.show', compact('product', 'productTypes', 'defaultProductType', 'options', 'adicionales'));
        return view('product.show2', compact('product', 'productTypes', 'defaultProductType', 'options', 'adicionales'));
    }

    public function customPizza()
    {
        //$product = Product::where('id', 1)->firstOrFail();
        $dataGeneral = DataGeneral::where('name', 'product_id_custom')->firstOrFail();
        $product = Product::where('id', $dataGeneral->valueNumber)->firstOrFail();
        // Obtener los tipos relacionados al producto
        $productTypes = $product->productTypes()
            ->whereHas('type', function ($query) {
                $query->where('active', 1); // Filtra solo los tipos activos
            })
            ->with('type')
            ->get();

        // Obtener el tipo por defecto
        $defaultProductType = $productTypes->where('default', true)->first();

        $adicionales = Product::whereIn('category_id', [5, 6])->with('category')->get();

        $toppingMeats = Topping::where('type', 'meat')->get();

        $toppingVeggies = Topping::where('type', 'veggie')->get();

        // Mapeo de los IDs de la base de datos a los nombres personalizados
        $typeMap = [
            1 => ['id' => 'familiar', 'label' => 'Familiar'],
            2 => ['id' => 'large', 'label' => 'Grande'],
            3 => ['id' => 'personal', 'label' => 'Personal']
        ];

        // Filtrar solo los tipos que existen en la base de datos y agregar información extra
        $sizes = [];
        foreach ($productTypes as $productType) {
            if (isset($typeMap[$productType->type_id])) {
                $sizes[] = [
                    'id' => $typeMap[$productType->type_id]['id'],
                    'label' => $typeMap[$productType->type_id]['label'],
                    'checked' => $productType->default ? 'checked' : ''
                ];
            }
        }

        return view('product.custom', compact('product', 'sizes', 'productTypes', 'defaultProductType', 'adicionales', 'toppingMeats', 'toppingVeggies'));
    }

    public function getProduct($id, $productTypeId)
    {
        // Buscar el producto por ID
        $product = Product::find($id);
        $productType = ProductType::find($productTypeId);
        $price = 0;
        if (!$product) {
            // Si el producto no existe, devolver error 404
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        if (isset($productType)) {
            $price = $productType->price;
        } else {
            $price = $product->price_default;
        }
        //$productType = ProductType::where('product_id', $product->id)->where('default', true)->first();

        $productTypeText = "";
        if ($productType) {
            $productTypeText = "Tipo: " . $productType->type->name . " (" . $productType->type->size . ")";
        }

        // Formatear la respuesta
        return response()->json([
            'id' => $product->id,
            'name' => $product->full_name,
            'price' => (float)$price,
            'image_url' => $product->image,
            'product_type' => $productTypeText
        ]);
    }

    public function fillSlugs()
    {
        // Obtener los productos que no tienen slug o donde el slug está vacío.
        $products = Product::whereNull('slug')->orWhere('slug', '')->get();

        foreach ($products as $product) {
            // Generar el slug a partir del full_name.
            $product->slug = Str::slug($product->full_name);

            // Guardar los cambios en la base de datos.
            $product->save();
        }

        return response()->json([
            'message' => 'Slugs actualizados correctamente.',
            'updated_count' => $products->count()

        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);

            $product->enable_status = 2;

            $product->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Producto eliminado con éxito.'], 200);

    }

    public function reactivar($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);

            $product->enable_status = 1;

            $product->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Producto reactivado con éxito.'], 200);

    }

    public function desactivar( Request $request, $id)
    {
        $product_id = $request->get('id_product');
        $time = trim($request->get('time')); // Elimina espacios en blanco

        DB::beginTransaction();
        try {
            $product = Product::find($product_id);

            // Logica para hallar la fecha de reactivacion del producto y colocar en el campo
            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado.'], 404);
            }

            // Calcular la fecha de reactivación
            $fechaActual = Carbon::now();

            if ($time === 'siempre') {
                $fechaReactivacion = $fechaActual->addDays(100); // Sumar 100 días
            } else {
                $horas = intval($time); // Convertir a número entero
                $fechaReactivacion = $fechaActual->addHours($horas); // Sumar horas
            }

            // Guardar la fecha de reactivación en el producto
            $product->enable_status = 0;
            $product->date_reactivate = $fechaReactivacion;
            $product->save();

            DB::commit();

            return response()->json(['message' => 'Producto desactivado con éxito.'], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function initializeProductDays()
    {
        DB::beginTransaction();
        try {
            // Obtener todos los productos activos
            $activeProducts = Product::where('enable_status', '<>', 2)->get();

            // Insertar los días de activación para cada producto
            foreach ($activeProducts as $product) {
                for ($day = 0; $day <= 6; $day++) { // Días de la semana (0=domingo, 6=sábado)
                    ProductDay::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'day' => $day, // Asumiendo que este campo representa los días
                        ],
                        [
                            'date_finish' => null, // Sin fecha de fin
                        ]
                    );
                }
            }

            DB::commit();
            return response()->json(['message' => 'Días de activación inicializados para productos activos.'], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
