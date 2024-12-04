<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'visible',
        'enable_status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected $dates = ['deleted_at'];
}
