<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'full_name',
        'description',
        'stock_current',
        'unit_price',
        'image',
        'category_id',
        'enable_status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function productTypes()
    {
        return $this->hasMany(ProductType::class);
    }
}
