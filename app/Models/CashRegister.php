<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'opening_balance',
        'closing_balance',
        'current_balance',
        'total_sales',
        'total_incomes',
        'total_expenses',
        'opening_time',
        'closing_time',
        'type',
        'status'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function movements()
    {
        return $this->hasMany(CashMovement::class);
    }

    public function logs()
    {
        return $this->hasMany(CashRegisterLog::class);
    }
}
