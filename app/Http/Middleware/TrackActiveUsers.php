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
        $sessionId = session()->getId(); // ✅ Usamos el ID de sesión en lugar de la IP
        $cacheKey = "active_users";

        Log::info("Middleware ejecutado para la IP: " . $sessionId); // ✅ Log para ver si se ejecuta

        // Obtener los usuarios activos
        $activeUsers = Cache::get($cacheKey, []);

        if (!in_array($sessionId, $activeUsers)) {
            $activeUsers[] = $sessionId;
            Cache::put($cacheKey, $activeUsers, now()->addMinutes(5));

            Log::info("Usuarios activos actualizados: " . count($activeUsers));

            // Emitir el evento con la cantidad actualizada de usuarios
            broadcast(new UserActive(count($activeUsers)));
        }

        return $next($request);
    }
}
