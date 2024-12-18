<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
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

        $products = Product::whereIn('category_id', $categoryIds) // Filtrar productos por las categorías seleccionadas
        ->where('enable_status', 1) // Solo productos habilitados
        ->get();

        return view('home', compact('categories', 'products'));
    }
}
