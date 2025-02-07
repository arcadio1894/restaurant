<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataGeneral;
use App\Models\Department;
use App\Models\District;
use App\Models\Motivo;
use App\Models\Product;
use App\Models\Province;
use App\Models\Slider;
use App\Models\Submotivo;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome()
    {
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
            ->get();

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

    public function reclamaciones()
    {
        $departments = Department::all();
        $motivos = Motivo::all();
        return view('welcome.reclamaciones', compact('departments', 'motivos'));
    }

    public function getProvinces($departmentId)
    {
        $provinces = Province::where('department_id', $departmentId)->get();
        return response()->json($provinces);
    }

    public function getDistricts($provinceId)
    {
        $districts = District::where('province_id', $provinceId)->get();
        return response()->json($districts);
    }

    public function getSubmotivos($motivoId)
    {
        $submotivos = Submotivo::where('motivo_id', $motivoId)->get();
        return response()->json($submotivos);
    }
}
