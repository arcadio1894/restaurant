<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_detail_id',
        'option_id',
        'product_id',
    ];

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
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
