<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataGeneral;
use App\Models\Product;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $categories = Category::withTrashed()->get(); // Incluye categorías soft-deleted si deseas mostrar todos.
        $products = Product::where('enable_status', 1)->get(); // Solo productos habilitados

        return view('welcome', compact('categories', 'products'));
    }

    public function isAuthenticated()
    {
        return response()->json(['authenticated' => auth()->check()]);
    }

    public function menu()
    {
        $categories = Category::withTrashed()->get(); // Incluye categorías soft-deleted si deseas mostrar todos.
        $products = Product::where('enable_status', 1)->get(); // Solo productos habilitados

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
