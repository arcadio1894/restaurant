<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'amount',
        'percentage'
    ];

    // Accesor para verificar si el cupón está activo
    public function isActive()
    {
        return $this->status === 'active';
    }
}
