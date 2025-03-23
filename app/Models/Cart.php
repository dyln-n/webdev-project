<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function scopeWithUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    public function scopeWithProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
