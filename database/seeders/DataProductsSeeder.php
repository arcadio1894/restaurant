<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DataProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'code' => 'P-00001',
            'full_name' => 'Margarita del campo',
            'description' => 'Disfruta de una pizza ligera llena de frescura. Margarita del Campo realza la esencia de la albahaca fresca sobre una capa de queso derretido y salsa de tomate, en un bocado que celebra lo natural.',
            'stock_current' => 100,
            'unit_price' => 35,
            'image' => '1.png',
            'category_id' => 2,
            'enable_status' => 1
        ]);

        Product::create([
            'code' => 'P-00002',
            'full_name' => 'Chorizo Clasico',
            'description' => 'La intensidad del chorizo ahumado se fusiona con aceitunas verdes, queso y salsa de tomate. Chorizo Clásico es una explosión de sabor, frescura y equilibrio en cada bocado, ideal para amantes de lo intenso.',
            'stock_current' => 100,
            'unit_price' => 35,
            'image' => '2.webp',
            'category_id' => 2,
            'enable_status' => 1
        ]);

        Product::create([
            'code' => 'P-00003',
            'full_name' => 'Doble Fuego Carnica',
            'description' => 'Doble Fuego Cárnica es para los amantes de lo robusto: carne jugosa y tocino ahumado en salsa de tomate y queso. Una pizza que llena de sabor y carácter, ideal para quienes buscan lo intenso.',
            'stock_current' => 100,
            'unit_price' => 35,
            'image' => '3.png',
            'category_id' => 2,
            'enable_status' => 1
        ]);

        Product::create([
            'code' => 'P-00004',
            'full_name' => 'Jamón Clásico',
            'description' => 'Jamón Clásico rinde homenaje a la simplicidad, con jamón suave sobre una base de queso y salsa de tomate. Un sabor que no pasa de moda y que cautiva a quienes buscan lo esencial en una pizza clásica.',
            'stock_current' => 100,
            'unit_price' => 35,
            'image' => '4.png',
            'category_id' => 1,
            'enable_status' => 1
        ]);

        Product::create([
            'code' => 'P-00005',
            'full_name' => 'Vegetariana Clásica',
            'description' => 'Vegetariana Clásica trae la frescura en su máxima expresión: champiñones tiernos, albahaca aromática, aceitunas y pimiento verde. Un sabor equilibrado y natural para los que buscan lo esencial.',
            'stock_current' => 100,
            'unit_price' => 35,
            'image' => '5.png',
            'category_id' => 1,
            'enable_status' => 1
        ]);

        Product::create([
            'code' => 'P-00006',
            'full_name' => 'Selva Tropical',
            'description' => 'Selva Tropical es una explosión vegana de frescura y color, con champiñones, albahaca, pimientos, piña, durazno y aceitunas. Una combinación exótica y deliciosa para quienes buscan nuevos sabores.',
            'stock_current' => 100,
            'unit_price' => 35,
            'image' => '6.png',
            'category_id' => 1,
            'enable_status' => 1
        ]);

        Product::create([
            'code' => 'P-00007',
            'full_name' => 'Hawayana Tropical',
            'description' => 'Con jamón jugoso y piña dorada, Hawayana Tropical te transporta a un paraíso de sabor en cada bocado. La combinación dulce y salada en una base artesanal es el equilibrio perfecto para el paladar.',
            'stock_current' => 100,
            'unit_price' => 35,
            'image' => '7.png',
            'category_id' => 3,
            'enable_status' => 1
        ]);

        Product::create([
            'code' => 'P-00008',
            'full_name' => 'Delicious Pasta',
            'description' => 'Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque',
            'stock_current' => 100,
            'unit_price' => 10,
            'image' => '8.png',
            'category_id' => 3,
            'enable_status' => 1
        ]);

        Product::create([
            'code' => 'P-00009',
            'full_name' => 'Dúo Primaveral',
            'description' => 'Jamón y champiñones se encuentran en Dúo Primaveral, una combinación simple pero perfecta. Esta pizza resalta lo fresco y natural, ideal para quienes disfrutan los sabores esenciales y equilibrados.',
            'stock_current' => 100,
            'unit_price' => 35,
            'image' => '9.png',
            'category_id' => 4,
            'enable_status' => 1
        ]);
    }
}
