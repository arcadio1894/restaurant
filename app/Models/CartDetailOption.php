<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetailOption extends Model
{
    use HasFactory;

    protected $fillable = ['cart_detail_id', 'option_id', 'product_id'];

    public function cartDetail()
    {
        return $this->belongsTo(CartDetail::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
