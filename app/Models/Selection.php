<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_id',
        'product_id',
        'additional_price',
    ];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
