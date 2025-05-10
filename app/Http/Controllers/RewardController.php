<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $milestones = Milestone::orderBy('flames')->get();

        $user = Auth::user();

        $flames = $user->flames;

        return view('reward.index', compact('milestones', 'flames'));
    }

    public function show($slug, $id)
    {
        $milestone = Milestone::find($id);
        $product = Product::where('slug', $slug)->firstOrFail();

        // Obtener los tipos relacionados al producto
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
        $adicionales = [];
        //return view('product.show', compact('product', 'productTypes', 'defaultProductType', 'options', 'adicionales'));
        return view('reward.show', compact('product', 'productTypes', 'defaultProductType', 'options', 'adicionales', 'milestone'));
    }
}
