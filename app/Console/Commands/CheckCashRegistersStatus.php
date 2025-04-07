<?php

namespace App\Console\Commands;

use App\Http\Controllers\TelegramController;
use App\Models\CashRegister;
use App\Models\DataGeneral;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckCashRegistersStatus extends Command
{
    protected $signature = 'check:cash-registers';
    protected $description = 'Verifica si las cajas están abiertas cuando la tienda está en funcionamiento';

    public function handle()
    {
        $statusStore = DataGeneral::getValue('status_store');

        if ($statusStore != 1) {
            $this->info('La tienda está cerrada. No se realiza verificación de cajas.');
            return 0;
        }

        $today = Carbon::now('America/Lima')->format('Y-m-d');

        // Verificar si existe una caja abierta del tipo "efectivo"
        $cashOpen = CashRegister::whereDate('opening_time', $today)
            ->where('type', 'efectivo')
            ->where('status', 1)
            ->exists();

        // Verificar si existe una caja abierta del tipo "bancario"
        $bankOpen = CashRegister::whereDate('opening_time', $today)
            ->where('type', 'bancario')
            ->where('status', 1)
            ->exists();

        if (!$cashOpen || !$bankOpen) {
            $missing = [];

            if (!$cashOpen) {
                $missing[] = 'Caja de efectivo';
            }

            if (!$bankOpen) {
                $missing[] = 'Caja bancaria';
            }

            $mensaje = '⚠️ Atención: Faltan las siguientes cajas abiertas para hoy (' . now()->format('d/m/Y H:i') . '): ' . implode(' y ', $missing);

            // Enviamos mensaje al Telegram
            $telegramController = new TelegramController();
            $telegramController->sendNotification('cash_register_warning', ['mensaje' => $mensaje]);

            $this->warn($mensaje);
        } else {
            $this->info('Las cajas están correctamente abiertas.');
        }

        return 0;
    }
}
