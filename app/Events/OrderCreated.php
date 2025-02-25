<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $id_kanban;

    /**
     * Crear una nueva instancia del evento.
     */
    public function __construct(Order $order, $id_kanban)
    {
        $this->order = $order;
        $this->id_kanban = $id_kanban;
    }

    /**
     * Determinar en qué canal se emitirá el evento.
     */
    public function broadcastOn()
    {
        return new Channel('ordersCreated');
    }

    /**
     * Especificar el nombre del evento.
     */
    public function broadcastAs()
    {
        return 'order.created';
    }

    public function broadcastWith()
    {
        return [
            'order' => $this->order,
            'id_kanban' => $this->id_kanban,
        ];
    }
}
