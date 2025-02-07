<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSubmotivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('submotivos')->insert([
            // Submotivos para 'Tiempo'
            ['nombre' => 'Demora en la atención en tienda', 'motivo_id' => 1],
            ['nombre' => 'Demora en la entrega del pedido', 'motivo_id' => 1],

            // Submotivos para 'Servicio'
            ['nombre' => 'Problemas con la atención del personal en tienda', 'motivo_id' => 2],
            ['nombre' => 'Problemas con la atención del personal en call center', 'motivo_id' => 2],
            ['nombre' => 'Pedido cancelado', 'motivo_id' => 2],
            ['nombre' => 'No llegó el pedido', 'motivo_id' => 2],
            ['nombre' => 'Problemas con la web/app/sistema', 'motivo_id' => 2],
            ['nombre' => 'Problemas con información de productos o promociones', 'motivo_id' => 2],
            ['nombre' => 'Distrito fuera de zona', 'motivo_id' => 2],
            ['nombre' => 'Pedido errado', 'motivo_id' => 2],
            ['nombre' => 'Tienda no cumple horario de atención', 'motivo_id' => 2],
            ['nombre' => 'Problemas para contactar al canal telefónico', 'motivo_id' => 2],
            ['nombre' => 'Tienda asignada incorrecta', 'motivo_id' => 2],

            // Submotivos para 'Pedido incompleto'
            ['nombre' => 'Producto principal', 'motivo_id' => 3],
            ['nombre' => 'Bebidas', 'motivo_id' => 3],
            ['nombre' => 'Salsas', 'motivo_id' => 3],
            ['nombre' => 'Complementos', 'motivo_id' => 3],
            ['nombre' => 'Otros', 'motivo_id' => 3],

            // Submotivos para 'Producto'
            ['nombre' => 'Sabor', 'motivo_id' => 4],
            ['nombre' => 'Temperatura', 'motivo_id' => 4],
            ['nombre' => 'Presentación', 'motivo_id' => 4],
            ['nombre' => 'Disponibilidad del producto', 'motivo_id' => 4],
            ['nombre' => 'Otros', 'motivo_id' => 4],

            // Submotivos para 'Promociones'
            ['nombre' => 'Problemas con promoción vigente', 'motivo_id' => 5],
            ['nombre' => 'Publicidad engañosa', 'motivo_id' => 5],

            // Submotivos para 'Problemas con el cobro'
            ['nombre' => 'Doble cobro', 'motivo_id' => 6],
            ['nombre' => 'Problemas con el POS', 'motivo_id' => 6],
            ['nombre' => 'Problemas con la factura/boleta', 'motivo_id' => 6],
            ['nombre' => 'Cobro incorrecto', 'motivo_id' => 6],
            ['nombre' => 'Medio de pago no autorizado', 'motivo_id' => 6],
            ['nombre' => 'Otros', 'motivo_id' => 6],

            // Submotivos para 'Mantenimiento/Limpieza'
            ['nombre' => 'Local', 'motivo_id' => 7],
            ['nombre' => 'Servicios higiénicos', 'motivo_id' => 7],
            ['nombre' => 'Menajes', 'motivo_id' => 7],
            ['nombre' => 'Personal', 'motivo_id' => 7],
            ['nombre' => 'Otros', 'motivo_id' => 7],

            // Submotivos para 'Seguridad'
            ['nombre' => 'Privacidad/Datos personales', 'motivo_id' => 8],
            ['nombre' => 'Robo', 'motivo_id' => 8],
            ['nombre' => 'Extravío', 'motivo_id' => 8],
            ['nombre' => 'Falta de señalización', 'motivo_id' => 8],
            ['nombre' => 'Accidente en tienda', 'motivo_id' => 8],
        ]);
    }
}
