<?php

namespace App\Http\Controllers;

use App\Models\DataGeneral;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function getBusinessHours()
    {
        $open_time_data = DataGeneral::where('name', 'open_time')->first();
        $close_time_data = DataGeneral::where('name', 'close_time')->first();

        if (!$open_time_data || !$close_time_data) {
            return response()->json(['message' => 'Horario no configurado.'], 404);
        }

        $open_time = $open_time_data->valueText;
        $close_time = $close_time_data->valueText;

        //Logica para verificar si esta abierto o no el local
        // Obtener la hora actual en el formato adecuado
        $current_time = now();

        // Verificar si la hora actual está dentro del rango de atención
        $is_open = $current_time->between($open_time, $close_time);

        return response()->json([
            'is_open' => $is_open,
            'message' => $is_open ? 'Estamos atendiendo. ¡Bienvenido!' : 'En estos momentos no estamos atendiendo.',
            'open_time' => $open_time,
            'close_time' => $close_time,
        ]);
    }
}
