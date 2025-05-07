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
        $this->belongsTo(Milestone::class);
    }

    public function product()
    {
        $this->belongsTo(Product::class);
    }
}
