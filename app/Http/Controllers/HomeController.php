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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::withTrashed()->get(); // Incluye categorías soft-deleted si deseas mostrar todos.
        $products = Product::where('enable_status', 1)->get(); // Solo productos habilitados

        return view('home', compact('categories', 'products'));
    }
}
