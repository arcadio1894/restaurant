<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataGeneral;

class DataCustomPizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DataGeneral::create([
            'name' => 'base_price_familiar',
            'valueText' => '',
            'valueNumber' => 35,
            'description' => 'Precio base de pizza familiar'
        ]);

        DataGeneral::create([
            'name' => 'base_price_large',
            'valueText' => '',
            'valueNumber' => 30,
            'description' => 'Precio base de pizza grande'
        ]);

        DataGeneral::create([
            'name' => 'base_price_personal',
            'valueText' => '',
            'valueNumber' => 30,
            'description' => 'Precio base de pizza personal'
        ]);

        DataGeneral::create([
            'name' => 'price_adicional',
            'valueText' => '',
            'valueNumber' => 3,
            'description' => 'Precio de topping adicional'
        ]);
    }
}
