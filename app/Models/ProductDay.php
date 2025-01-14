<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'day',
        'date_finish', // Fecha hasta la cual es válido
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
