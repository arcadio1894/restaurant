<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener las categorías visibles (incluyendo las soft-deleted si es necesario)
        $categories = Category::with('products')
            ->where('visible', 1)
            ->where('enable_status', 1)
            ->get();

        // Obtener los IDs de las categorías visibles
        $categoryIds = $categories->pluck('id')->toArray();

        // Orden deseado (sin incluir la categoría excluida manualmente)
        $order = [1, 2, 5, 3, 7, 6];

        // Ajustar el orden para considerar solo categorías visibles
        $order = array_filter($order, function ($id) use ($categoryIds) {
            return in_array($id, $categoryIds);
        });

        // Obtener los productos habilitados que pertenecen a las categorías seleccionadas
        $products = Product::with('category:id,name')
            ->where('enable_status', 1)
            ->whereIn('category_id', $categoryIds) // Solo productos en las categorías visibles
            ->orderByRaw('FIELD(category_id, ' . implode(',', $order) . ')') // Categorías en orden específico
            ->orderBy('category_id') // Ordenar categorías no listadas
            ->orderBy('id') // Ordenar por ID como criterio secundario
            ->get()
            ->filter(function ($product) {
                $productTypes = $product->productTypes;

                // Verificar si el producto tiene solo un ProductType y su Type está inactivo
                return !($productTypes->count() === 1 && optional($productTypes->first()->type)->active == 0);
            });

        $slidersSmalls = Slider::where('size', 's')->where('active', 1)->orderBy('order', 'asc')->get();
        $slidersLarges = Slider::where('size', 'l')->where('active', 1)->orderBy('order', 'asc')->get();


        return view('home', compact('categories', 'products', 'slidersSmalls', 'slidersLarges'));
    }
}
