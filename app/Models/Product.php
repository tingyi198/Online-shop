<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites_users()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function getQuantity($productId)
    {
        $product = $this::find($productId);
        return $product->quantity;
    }

    public function updateQuantity($quantity)
    {
        $this->update(['quantity' => $this->quantity - $quantity]);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'attachable');
    }

    // 使用 attribute: $product->image_url
    public function getImageUrlAttribute()
    {
        $images = $this->images;
        if ($images->isNotEmpty()) {
            return Storage::url($images->last()->path);
        }
    }
}
