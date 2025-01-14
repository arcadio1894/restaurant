<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductDay;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActivateProducts extends Command
{
    protected $signature = 'products:activate';
    protected $description = 'Activates or deactivates products based on the current day';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentDay = Carbon::now()->dayOfWeek;
        $today = Carbon::now()->toDateString();

        // Obtener productos activos para el día actual y dentro del rango de fecha
        $activeProductIds = ProductDay::where('day', $currentDay)
            ->where(function ($query) use ($today) {
                $query->whereNull('date_finish') // Verificar sin límite de fecha
                ->orWhere('date_finish', '>=', $today); // Dentro del rango
            })
            ->pluck('product_id')
            ->toArray();

        // Activar los productos correspondientes
        Product::whereIn('id', $activeProductIds)
            ->where('enable_status', '<>', 2) // Excluir los descontinuados
            ->update(['enable_status' => 1]);

        // Desactivar los productos que no corresponden o han pasado su rango de fecha
        Product::whereNotIn('id', $activeProductIds)
            ->where('enable_status', '<>', 2) // Excluir los descontinuados
            ->update(['enable_status' => 0]);

        $this->info('Product activation process completed.');
    }
}
