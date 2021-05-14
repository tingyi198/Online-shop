<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];
    private $rate = 1;

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkout()
    {
        DB::beginTransaction();
        try {
            $order = $this->order()->create([
                'user_id' => $this->user_id
            ]);

            if ($this->user->level == 2) {
                $this->rate = 0.8;
            }

            foreach ($this->cartItems as $cartItem) {
                $inventoryQuantity = $cartItem->product->getQuantity($cartItem->product_id);
                if ($cartItem->quantity > $inventoryQuantity) {
                    return $cartItem->product->title . '庫存不足';
                } else {
                    // 更新庫存數量
                    $cartItem->product->updateQuantity($cartItem->quantity);
                }

                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'price' => $cartItem->product->price * $this->rate
                ]);
            }

            $this->update(['checkouted' => true]);

            // 一併取得 order_items 資料
            $order->orderItems;
            DB::commit();
            return $order;
        } catch (\Throwable $th) {
            DB::rollback();
            return 'checkout error';
        }
    }
}
