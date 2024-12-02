<?php

namespace App\Http\Controllers;

use App\Models\DataGeneral;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function getBusinessHours()
    {
        /*//Logica para verificar si esta abierto o no el local
        // Obtener la hora actual en el formato adecuado
        $current_time = now();
        $current_day = $current_time->format('l'); // Obtiene el día de la semana como texto

        // Determinar los horarios según el día de la semana
        if (in_array($current_day, ['Saturday', 'Sunday'])) {
            // Horarios de fin de semana
            $open_time_data = DataGeneral::where('name', 'open_time_weekend')->first();
            $close_time_data = DataGeneral::where('name', 'close_time_weekend')->first();
        } else {
            // Horarios de lunes a viernes
            $open_time_data = DataGeneral::where('name', 'open_time')->first();
            $close_time_data = DataGeneral::where('name', 'close_time')->first();
        }

        if (!$open_time_data || !$close_time_data) {
            return response()->json(['message' => 'Horario no configurado.'], 404);
        }

        $open_time = $open_time_data->valueText;
        $close_time = $close_time_data->valueText;*/

        // Verificar si la hora actual está dentro del rango de atención
        $status = DataGeneral::where('name', 'status_store')->first();
        $is_open = $status->valueNumber;

        return response()->json([
            'is_open' => $is_open,
            'message' => ($is_open == 1) ? 'Estamos atendiendo. ¡Bienvenido!' : 'Estamos fuera de horario. Te esperamos en nuestro próximo turno.',
            /*'open_time' => $open_time,
            'close_time' => $close_time,*/
        ]);
    }

    public function toggleStoreStatus(Request $request)
    {
        $newStatus = $request->input('status_store'); // 1 para abrir, 0 para cerrar

        if (!in_array($newStatus, [0, 1])) {
            return response()->json(['error' => 'Estado inválido.'], 400);
        }

        DataGeneral::setValue('status_store', $newStatus);

        return response()->json([
            'message' => $newStatus == 1 ? 'La tienda se ha abierto manualmente.' : 'La tienda se ha cerrado manualmente.',
            'statusStore' => DataGeneral::getValue('status_store'),
        ]);
    }
}
