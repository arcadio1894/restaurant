<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'code',
        'full_name',
        'description',
        'stock_current',
        'unit_price',
        'image',
        'category_id',
        'enable_status',
        'ingredients',
        'slug'
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function productTypes()
    {
        return $this->hasMany(ProductType::class);
    }

    public function getPriceDefaultAttribute()
    {
        $product = Product::findOrFail($this->id);

        // Obtener los tipos relacionados al producto
        $productTypes = $product->productTypes()->with('type')->get();

        // Obtener el tipo por defecto
        $defaultProductType = $productTypes->where('default', true)->first();

        if ( isset($defaultProductType) )
        {
            return $defaultProductType->price;
        } else {
            return $this->unit_price;
        }

    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $originalSlug = Str::slug($post->title, '-');
                $slug = $originalSlug;
                $count = 1;

                while (Product::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }

                $post->slug = $slug;
            }
        });

        static::updating(function ($post) {
            if (empty($post->slug)) {
                $originalSlug = Str::slug($post->title, '-');
                $slug = $originalSlug;
                $count = 1;

                while (Product::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }

                $post->slug = $slug;
            }
        });
    }
}
