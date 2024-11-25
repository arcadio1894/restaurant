<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
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
            ->where('enable_status', 1)
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
            array_push($array, [
                "id" => $product->id,
                "codigo" => $product->code,
                "descripcion" => $product->description,
                "nombre" => $product->full_name,
                "precio" => $product->price_default,
                "categoria" => ($product->category == null) ? '': $product->category->name,
                "ingredientes" => $product->ingredients,
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
        return view('product.create', compact('categories', 'types'));
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

            if (!$defaultType) {
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
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Producto guardado con éxito.'], 200);

    }

    public function edit($id)
    {
        $product = Product::with(['category'])->find($id);
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

        return view('product.edit', compact('product', 'categories', 'types', 'priceTypes'));

    }

    public function update(UpdateProductRequest $request)
    {
        //dd($request->get('typescrap'));
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

            // TODO: Guardar las promociones
            $old_productTypes = ProductType::where('product_id',$product->id)->get();
            foreach ( $old_productTypes as $old_productType )
            {
                $old_productType->delete();
            }

            $types = $request->input('type', []);
            $priceTypes = $request->input('productPrice', []);
            $defaultType = $request->input('defaultType');

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

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

    }

    public function destroy(DeleteProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::find($request->get('material_id'));

        $product->enable_status = 0;

        return response()->json(['message' => 'Producto eliminado con éxito.'], 200);

    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Obtener los tipos relacionados al producto
        $productTypes = $product->productTypes()->with('type')->get();

        // Obtener el tipo por defecto
        $defaultProductType = $productTypes->where('default', true)->first();

        return view('product.show', compact('product', 'productTypes', 'defaultProductType'));
    }

}
