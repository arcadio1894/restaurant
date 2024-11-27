<?php

namespace Database\Seeders;

use App\Models\ShippingDistrict;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts = [
            ['name' => 'Trujillo', 'shipping_cost' => 0.00, 'ubigeo' => '130101'],
            ['name' => 'Víctor Larco Herrera', 'shipping_cost' => 5.50, 'ubigeo' => '130102'],
            ['name' => 'La Esperanza', 'shipping_cost' => 6.00, 'ubigeo' => '130103'],
            ['name' => 'El Porvenir', 'shipping_cost' => 6.50, 'ubigeo' => '130104'],
            ['name' => 'Huanchaco', 'shipping_cost' => 8.00, 'ubigeo' => '130105'],
            ['name' => 'Moche', 'shipping_cost' => 8.50, 'ubigeo' => '130106'],
            ['name' => 'Salaverry', 'shipping_cost' => 10.00, 'ubigeo' => '130107'],
            ['name' => 'Laredo', 'shipping_cost' => 12.00, 'ubigeo' => '130108'],
        ];

        DB::table('shipping_districts')->insert($districts);
    }
}
