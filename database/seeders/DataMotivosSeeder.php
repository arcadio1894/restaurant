<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataMotivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('motivos')->insert([
            ['nombre' => 'Tiempo'],
            ['nombre' => 'Servicio'],
            ['nombre' => 'Pedido incompleto'],
            ['nombre' => 'Producto'],
            ['nombre' => 'Promociones'],
            ['nombre' => 'Problemas con el cobro'],
            ['nombre' => 'Mantenimiento/Limpieza'],
            ['nombre' => 'Seguridad']
        ]);
    }
}
