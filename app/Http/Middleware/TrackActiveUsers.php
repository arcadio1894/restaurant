<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Broadcast;
use App\Events\UserActive;
use Illuminate\Support\Facades\Log;

class TrackActiveUsers
{
    public function handle(Request $request, Closure $next)
    {
        $sessionId = session()->getId();
        $cacheKey = "active_users";

        // Obtener la lista actual de usuarios activos
        $activeUsers = Cache::get($cacheKey, []);

        // Guardamos el tiempo actual para saber cuándo se conectó este usuario
        $activeUsers[$sessionId] = now()->timestamp;

        // Eliminar sesiones inactivas (más de 5 minutos sin actividad)
        $activeUsers = array_filter($activeUsers, function ($timestamp) {
            return (now()->timestamp - (int) $timestamp) <= 300; // Convertimos a entero

        });

        // Guardar la lista de usuarios activos
        Cache::put($cacheKey, $activeUsers, now()->addMinutes(5));

        // Log para depuración
        Log::info("Usuarios activos actualizados: " . count($activeUsers));

        // Emitir evento con la cantidad de usuarios activos
        broadcast(new UserActive(count($activeUsers)));

        return $next($request);
    }
}
