<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataGeneral;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $categories = Category::with('products')
            ->where('visible', 1)
            ->where('enable_status', 1)
            ->get();
        $products = Product::where('enable_status', 1)->get(); // Solo productos habilitados

        $slidersSmalls = Slider::where('size', 's')->orderBy('order', 'asc')->get();
        $slidersLarges = Slider::where('size', 'l')->orderBy('order', 'asc')->get();

        return view('welcome', compact('categories', 'products', 'slidersSmalls', 'slidersLarges'));
    }

    public function isAuthenticated()
    {
        return response()->json([
            'authenticated' => auth()->check(),
            'user_id' => auth()->check() ? auth()->id() : null,
            ]
        );
    }

    public function menu()
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

        return view('welcome.menu', compact('categories', 'products'));
    }

    public function about()
    {
        return view('welcome.about');
    }

    public function goToDashboard()
    {
        $status = DataGeneral::getValue('status_store');
        //dd($status);
        return view('welcome.dashboard', compact('status'));
    }
}
