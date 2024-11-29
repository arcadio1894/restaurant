<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'description',
        'quantity',
        'type',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function selections()
    {
        return $this->hasMany(Selection::class);
    }
}
