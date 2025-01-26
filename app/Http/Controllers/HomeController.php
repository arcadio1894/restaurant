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

        // Obtener los productos habilitados que pertenecen a las categorías seleccionadas
        $categoryIds = $categories->pluck('id'); // Obtener los IDs de las categorías visibles

        $order = [1, 2, 5, 3, 7, 6]; // Orden deseado de las categorías

        $products = Product::with('category:id,name')
            ->where('enable_status', 1)
            ->orderByRaw('FIELD(category_id, ' . implode(',', $order) . ')') // Categorías en orden específico
            ->orderBy('category_id') // Ordenar las categorías no listadas
            ->orderBy('id')->get(); // Ordenar por ID como criterio secundario

        $slidersSmalls = Slider::where('size', 's')->orderBy('order', 'asc')->get();
        $slidersLarges = Slider::where('size', 'l')->orderBy('order', 'asc')->get();

        return view('home', compact('categories', 'products', 'slidersSmalls', 'slidersLarges'));
    }
}
