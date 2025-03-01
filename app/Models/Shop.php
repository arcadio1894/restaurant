<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'phone',
        'email',
        'address',
        'latitude',
        'longitude',
        'department_id',
        'province_id',
        'district_id',
        'status',
        'type'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function province() {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }

    /*public function categories() {
        return $this->belongsToMany(Category::class, 'category_shop');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'product_shop');
    }*/
}
