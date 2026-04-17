<?php

namespace App\Observers;

use App\Models\Cart;

class CartObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Cart "created" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function created(Cart $cart)
    {
        // get the latest cart data from db
        $cart->refresh();

        // Check if user exists before trying to access user->acCart
        if (!$cart->user) {
            return;
        }

        $acCart = $cart->user->acCart;

        if ($acCart) {
            $acCart->items()->updateOrCreate([
                'product_id' => $cart->product_id,
            ], [
                'quantity' => $cart->quantity,
                'calculated_price' => (($cart->price + $cart->tax) - $cart->discount),
                'meta' => [
                    'price' => $cart->price,
                    'tax' => $cart->tax,
                    'discount' => $cart->discount,
                ],
            ]);
        } else {
            $acCart = $cart->user->acCart()->create([
                'user_id' => $cart->user_id,
            ]);

            $acCart->items()->create([
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'calculated_price' => (($cart->price + $cart->tax) - $cart->discount),
                'meta' => [
                    'price' => $cart->price,
                    'tax' => $cart->tax,
                    'discount' => $cart->discount,
                ],
            ]);
        }

        $acCart->touch();
    }

    /**
     * Handle the Cart "updated" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function updated(Cart $cart)
    {
        // Check if user exists before trying to access user->acCart
        if (!$cart->user) {
            return;
        }

        $acCart = $cart->user->acCart;

        if ($acCart) {
            $acCart->items()->updateOrCreate([
                'product_id' => $cart->product_id,
            ], [
                'quantity' => $cart->quantity,
                'calculated_price' => (($cart->price + $cart->tax) - $cart->discount),
                'meta' => [
                    'price' => $cart->price,
                    'tax' => $cart->tax,
                    'discount' => $cart->discount,
                ],
            ]);
        } else {
            $acCart = $cart->user->acCart()->create([
                'user_id' => $cart->user_id,
            ]);
            $acCart->items()->create([
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'calculated_price' => (($cart->price + $cart->tax) - $cart->discount),
                'meta' => [
                    'price' => $cart->price,
                    'tax' => $cart->tax,
                    'discount' => $cart->discount,
                ],
            ]);
        }

        $acCart->touch();
    }

    /**
     * Handle the Cart "deleted" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function deleted(Cart $cart)
    {
        // Check if user exists before trying to access user->acCart
        if (!$cart->user) {
            return;
        }

        $acCart = $cart->user->acCart;

        if ($acCart) {
            $acCart->items()->where('product_id', $cart->product_id)->delete();
            $acCart->touch();
        }
    }

    /**
     * Handle the Cart "restored" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function restored(Cart $cart)
    {
        //
    }

    /**
     * Handle the Cart "force deleted" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function forceDeleted(Cart $cart)
    {
        //
    }
}
