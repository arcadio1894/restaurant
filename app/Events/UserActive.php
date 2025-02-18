<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels; // ✅ Corrección aquí
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class UserActive implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $activeUsers;

    public function __construct($activeUsers)
    {
        $this->activeUsers = $activeUsers;
        Log::info("Evento UserActive disparado con {$activeUsers} usuarios activos"); // ✅ Log para confirmar evento
    }

    public function broadcastOn()
    {
        return new Channel('active-users');
    }

    public function broadcastAs()
    {
        return 'user.active'; // ✅ Forzar el nombre del evento sin namespace
    }

    public function broadcastWith()
    {
        return [
            'activeUsers' => $this->activeUsers,
        ];
    }

}


