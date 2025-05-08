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

    public function rewards()
    {
        return $this->hasMany(MilestoneReward::class);
    }

    public function getSlugRewardAttribute()
    {
        $slug = "";

        $reward = MilestoneReward::with('product')->where('milestone_id', $this->id)->first();

        if ( $reward ) {
            $slug = $reward->product->slug;
        }

        return $slug;

    }
}
