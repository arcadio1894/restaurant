<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Type;
use Illuminate\Database\Seeder;

class ProductAndProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener todos los productos existentes
        $products = Product::all();

        // Obtener todos los tipos existentes
        $types = Type::all();

        // Asociar cada producto con todos los tipos
        foreach ($products as $product) {
            foreach ($types as $type) {
                ProductType::create([
                    'product_id' => $product->id,
                    'type_id' => $type->id,
                    'price' => $type->price, // Utilizamos el precio del tipo
                    'default' => $type->id == 1 ? true : false, // El primer tipo será el por defecto (puedes cambiar la lógica)
                ]);
            }
        }

    }
}
