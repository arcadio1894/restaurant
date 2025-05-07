<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'flames',
        'title',
        'description',
        'image'
    ];

    public function products()
    {
        $this->hasMany(MilestoneReward::class);
    }
}
