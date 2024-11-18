<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DataCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Clasicas',
            'description' => 'Pizzas Clasicas'
        ]);

        Category::create([
            'name' => 'Especiales',
            'description' => 'Pizzas Especiales'
        ]);

        Category::create([
            'name' => 'Burritos',
            'description' => 'Burritos'
        ]);

        Category::create([
            'name' => 'Fries',
            'description' => 'Fries'
        ]);
    }
}
