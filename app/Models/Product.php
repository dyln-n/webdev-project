<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'seller_id',
    ];

    protected $appends = ['main_image_path'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getMainImagePathAttribute()
    {
        $image = $this->images()->where('is_main', 1)->first();
        return $image
            ? asset('storage/' . $image->image_path)
            : asset('images/placeholder.png');
    }
}
