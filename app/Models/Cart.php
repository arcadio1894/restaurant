<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'status', 'total'];

    public function getTotalCartAttribute()
    {
        $total = 0;
        $details = CartDetail::where('cart_id', $this->id)->get();
        foreach ( $details as $detail )
        {
            $total += $detail->subtotal;
        }

        return round($total, 2);
    }

    public function getTaxesCartAttribute()
    {
        $total = 0;
        $details = CartDetail::where('cart_id', $this->id)->get();
        foreach ( $details as $detail )
        {
            $total += $detail->subtotal;
        }

        // Calcula los impuestos incluidos en el total (18%)
        $taxes = $total - ($total / 1.18);

        return round($taxes, 2);  // Redondeamos a 2 decimales

    }

    public function getSubtotalCartAttribute()
    {
        $total = 0;
        $details = CartDetail::where('cart_id', $this->id)->get();
        foreach ( $details as $detail )
        {
            $total += $detail->subtotal;
        }

        // Calcula los impuestos incluidos en el total (18%)
        $taxes = $total - ($total / 1.18);

        $subtotal = $total - $taxes;

        return round($subtotal, 2);  // Redondeamos a 2 decimales

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(CartDetail::class);
    }
}
