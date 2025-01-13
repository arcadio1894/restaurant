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

        // Obtener productos activos para el día actual
        $activeProductIds = ProductDay::where('day', $currentDay)
            ->pluck('product_id')
            ->toArray();

        // Activar los productos correspondientes
        Product::whereIn('id', $activeProductIds)
            ->update(['enable_status' => 1]);

        // Desactivar los productos que no corresponden
        Product::whereNotIn('id', $activeProductIds)
            ->update(['enable_status' => 0]);

        $this->info('Product activation process completed.');
    }
}
