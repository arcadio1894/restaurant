<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AntiBotMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip(); // Obtener la IP del usuario
        $cacheKey = "bot_protection:$ip";

        // Obtener la cantidad de solicitudes en los últimos 10 segundos
        $requests = Cache::get($cacheKey, 0);

        if ($requests > 20) { // Límite de 20 solicitudes en 10 segundos
            Log::warning("Posible bot detectado: $ip");
            abort(429, "Demasiadas solicitudes. Espere un momento.");
        }

        // Incrementar el contador de solicitudes
        Cache::put($cacheKey, $requests + 1, now()->addSeconds(10));

        return $next($request);
    }
}
