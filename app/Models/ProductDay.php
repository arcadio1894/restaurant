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
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
