<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_CREATED = 'created';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';


    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'billing_address_id',
        'total_amount',
        'status',
        'payment_method_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shipping_address()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billing_address()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Accesor para obtener el estado en español
    public function getStatusNameAttribute()
    {
        $statusNames = [
            'created' => 'RECIBIDO',
            'processing' => 'COCINANDO',
            'shipped' => 'EN TRAYECTO',
            'completed' => 'ENTREGADO',
        ];

        return array_key_exists($this->status, $statusNames)
            ? $statusNames[$this->status]
            : 'Desconocido';
    }

    // Método para obtener el número del paso activo según el estado
    public function getActiveStepAttribute()
    {
        switch ($this->status) {
            case 'created':
                return 1;
            case 'processing':
                return 2;
            case 'shipped':
                return 3;
            case 'completed':
                return 4;
            default:
                return 0;
        }
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->isoFormat('DD/MM/YYYY [a las] h:mm A');
    }
}
