<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'order_id',
        'flames',
        'expiration_date',
        'state'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function order()
    {
        $this->belongsTo(Order::class);
    }
}
