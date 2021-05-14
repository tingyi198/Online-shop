<?php

namespace App\Observers;

use App\Models\product;
use App\Notifications\ProductReplenish;

class ProductObserver
{
    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\product  $product
     * @return void
     */
    public function created(product $product)
    {
        //
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Models\product  $product
     * @return void
     */
    public function updated(product $product)
    {
        $original = $product->getOriginal();
        $changes = $product->getChanges();

        // 當原本數量為0，補貨後通知有加入favorites 的 user
        if ($original['quantity'] == 0 && isset($changes['quantity']) && $changes['quantity'] > 0) {
            $users = $product->favorites_users;
            foreach($users as $user) {
                $user->notify(new ProductReplenish($product));
            }
        }
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\product  $product
     * @return void
     */
    public function deleted(product $product)
    {
        //
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Models\product  $product
     * @return void
     */
    public function restored(product $product)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Models\product  $product
     * @return void
     */
    public function forceDeleted(product $product)
    {
        //
    }
}
