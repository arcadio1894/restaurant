<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestoneReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_id',
        'product_id'
    ];

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
