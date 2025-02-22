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

class OrderStatusAnulled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $statusName;
    public $activeStep;

    public function __construct(Order $order)
    {
        // Incluye los accesores y convierte el modelo a un array.
        $this->order = $order;
        $this->statusName = $order->status_name; // Accesor de status_name
        $this->activeStep = $order->active_step; // Accesor de active_step
    }

    public function broadcastOn()
    {
        // Canal al que se transmite
        return new Channel('orders');
    }

    public function broadcastAs()
    {
        // Nombre del evento que recibirá el cliente
        return 'OrderStatusUpdated';
    }

    public function broadcastWith()
    {
        return [
            'order' => $this->order,
            'status_name' => $this->statusName,
            'active_step' => $this->activeStep,
        ];
    }
}
