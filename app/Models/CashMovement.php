<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_register_id',
        'order_id',
        'type',
        'amount',
        'description',
        'subtype',
        'regularize'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function order()
    {
        return$this->belongsTo(Order::class);
    }
}
