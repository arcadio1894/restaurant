<?php

namespace App\Console\Commands;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReactivarProductos extends Command
{
    /**
     * El nombre y la firma del comando Artisan.
     *
     * @var string
     */
    protected $signature = 'products:reactivar';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Reactiva los productos cuya fecha de reactivación ha llegado.';

    /**
     * Ejecuta el comando.
     */
    public function handle()
    {
        $now = Carbon::now(); // Fecha y hora actual

        // Obtener productos que deben ser reactivados
        $productos = Product::whereNotNull('date_reactivate')
            ->where('date_reactivate', '<=', $now)
            ->get();

        if ($productos->isEmpty()) {
            $this->info('No hay productos para reactivar.');
            return;
        }

        // Reactivar los productos
        foreach ($productos as $producto) {
            $producto->enable_status = 1; // Activar producto
            $producto->date_reactivate = null; // Limpiar fecha de reactivación
            $producto->save();

            Log::info("Producto ID {$producto->id} reactivado.");
            $this->info("Producto ID {$producto->id} reactivado.");
        }
    }
}
