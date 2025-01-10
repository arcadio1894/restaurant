<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailTopping extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_detail_id',
        'topping_id',
        'topping_name',
        'type',
        'extra'
    ];

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function topping()
    {
        return $this->belongsTo(Topping::class);
    }
}
