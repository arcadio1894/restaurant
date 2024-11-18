<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class DataPaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create([
            'name' => 'POS',
            'code' => 'pos',
            'description' => 'Metodo POS',
            'is_active' => true
        ]);

        PaymentMethod::create([
            'name' => 'EFECTIVO',
            'code' => 'efectivo',
            'description' => 'Metodo EFECTIVO',
            'is_active' => true
        ]);

        PaymentMethod::create([
            'name' => 'YAPE/PLIN',
            'code' => 'yape_plin',
            'description' => 'Metodo YAPE/PLIN',
            'is_active' => true
        ]);
    }
}
