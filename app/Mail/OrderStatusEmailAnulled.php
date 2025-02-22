<?php

namespace App\Mail;

use App\Models\Address;
use App\Models\Order;
use App\Models\ShippingDistrict;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusEmailAnulled extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $direccion = Address::find($this->order->shipping_address_id);
        $distrito = ShippingDistrict::find($this->order->shipping_district_id);

        return $this->subject('Actualización del estado de tu orden')
            ->view('emails.order_status_anulled')
            ->with([
                'orderId' => $this->order->id,
                'orderStatus' => $this->order->status_name,
                'orderCreatedAt' => $this->order->formatted_created_date,
                'shippingAddress' => $direccion->address_line. " - ".( (!isset($distrito)) ? 'N/A':$distrito->name),
                "orderDateDelivery" => ($this->order->created_at != null) ? $this->order->formatted_date : "",
                "phone" => $direccion->phone,
                "address" => $direccion->address_line. " - ".( (!isset($distrito)) ? 'N/A':$distrito->name),
                "total" => $this->order->amount_pay,
                "method" => ($this->order->payment_method_id == null) ? 'Sin método de pago':$this->order->payment_method->name ,
                "data_payment" => $this->order->data_payment,
                "active_step" => $this->order->active_step,
                "name" => ($this->order->shipping_address_id == null) ? 'Incognito':$this->order->shipping_address->first_name." ".$this->order->shipping_address->last_name,
                "reference" => $direccion->reference,
            ]);
    }
}

