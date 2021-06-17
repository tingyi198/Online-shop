<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cart_id',
        'quantity',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function addCartItem($data)
    {
        $cartItem = CartItem::create($data);
        return $cartItem;
    }

    public function updateCartItem($data, $id)
    {
        $cartItem = CartItem::where('id', $id)
                    ->update($data);

        return $cartItem;
    }

    public function deleteCartItem($id)
    {
        CartItem::find($id)->delete();
        // $test = CartItem::withTrashed()->where('quantity', 2)->get();
        // print_r($test);
        // CartItem::onlyTrashed()->where('id', $id)->restore();
        // return CartItem::find($id)->delete();
    }
}
