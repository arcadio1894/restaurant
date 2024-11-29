<?php

namespace App\Http\Controllers;

use App\Models\DataGeneral;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function getBusinessHours()
    {
        //Logica para verificar si esta abierto o no el local
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
        $close_time = $close_time_data->valueText;

        // Verificar si la hora actual está dentro del rango de atención
        $is_open = $current_time->between($open_time, $close_time);

        return response()->json([
            'is_open' => $is_open,
            'message' => $is_open ? 'Estamos atendiendo. ¡Bienvenido!' : 'Estamos fuera de horario. Te esperamos en nuestro próximo turno.',
            'open_time' => $open_time,
            'close_time' => $close_time,
        ]);
    }
}
