<?php

namespace App\Console\Commands;

use App\Models\DataGeneral;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateStoreStatus extends Command
{
    /**
     * El nombre y la firma del comando.
     *
     * @var string
     */
    protected $signature = 'store:update-status';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de la tienda según el horario de apertura y cierre';

    /**
     * Ejecutar el comando.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now('America/Lima')->startOfMinute();
        $dayOfWeek = $now->dayOfWeek; // 0 (domingo) a 6 (sábado)

        // Obtener horarios de apertura y cierre según el día
        $openTime = DataGeneral::getValue(($dayOfWeek == 0 || $dayOfWeek == 6) ? 'open_time_weekend' : 'open_time'); // Fin de semana o día laboral
        $closeTime = DataGeneral::getValue(($dayOfWeek == 0 || $dayOfWeek == 6) ? 'close_time_weekend' : 'close_time');

        // Si no hay horarios definidos, salir
        if (!$openTime || !$closeTime) {
            $this->info('Horarios de apertura o cierre no definidos.');
            return;
        }

        // Convertir horarios a objetos Carbon
        $open = Carbon::parse($openTime)->startOfMinute();

        $close = Carbon::parse($closeTime)->startOfMinute();

        // Obtener el estado actual de la tienda
        $currentStatus = DataGeneral::getValue('status_store'); // 1: Abierta, 0: Cerrada

        // Casuística: Apertura
        if ($now->equalTo($open)) {
            if ($currentStatus != 1) {
                DataGeneral::setValue('status_store', 1); // Abrir automáticamente
                $this->info('La tienda se ha abierto automáticamente a la hora programada.'.$now." - ".$open);
            }
            return;
        }

        // Casuística: Cierre
        if ($now->equalTo($close)) {
            if ($currentStatus != 0) {
                DataGeneral::setValue('status_store', 0); // Cerrar automáticamente
                $this->info('La tienda se ha cerrado automáticamente a la hora programada.'.$now." - ".$close);
            }
            return;
        }

        // Caso fuera de horarios automáticos (no hacer nada)
        $this->info('No se requiere ninguna acción en este momento.'.$now." - ".$close." -".$dayOfWeek);
    }
}
