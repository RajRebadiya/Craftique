<?php

namespace App\Observers;

use App\Models\AbandonedCart\AbandonedCart;
use App\Models\CombinedOrder;

class CombinedOrderObserver
{
    /**
     * Handle the CombinedOrder "created" event.
     *
     * @param  \App\Models\CombinedOrder  $combinedOrder
     * @return void
     */
    public function created(CombinedOrder $combinedOrder)
    {
        // check if cookie has ac_recovery_id
        if (request()->hasCookie('ac_recovery_id')) {
            $abandonedCart = AbandonedCart::with(['cart' => ['items']])
                ->where('uuid', request()->cookie('ac_recovery_id'))
                ->first();

            if ($abandonedCart) {
                $combinedOrder->loadMissing(['orders' => ['orderDetails']]);

                $orderProductIds = $combinedOrder->orders->pluck('orderDetails')->flatten()->pluck('product_id')->toArray();

                $abandonedCartProductIds = $abandonedCart->cart->items->pluck('product_id')->toArray();

                // if order contains products from abandoned cart then update status to recovered
                if (count(array_intersect($orderProductIds, $abandonedCartProductIds)) > 0) {
                    $abandonedCart->update([
                        'status' => AbandonedCart::STATUS_RECOVERED,
                    ]);
                }
            }
        }
    }

    /**
     * Handle the CombinedOrder "updated" event.
     *
     * @param  \App\Models\CombinedOrder  $combinedOrder
     * @return void
     */
    public function updated(CombinedOrder $combinedOrder)
    {
        //
    }

    /**
     * Handle the CombinedOrder "deleted" event.
     *
     * @param  \App\Models\CombinedOrder  $combinedOrder
     * @return void
     */
    public function deleted(CombinedOrder $combinedOrder)
    {
        //
    }

    /**
     * Handle the CombinedOrder "restored" event.
     *
     * @param  \App\Models\CombinedOrder  $combinedOrder
     * @return void
     */
    public function restored(CombinedOrder $combinedOrder)
    {
        //
    }

    /**
     * Handle the CombinedOrder "force deleted" event.
     *
     * @param  \App\Models\CombinedOrder  $combinedOrder
     * @return void
     */
    public function forceDeleted(CombinedOrder $combinedOrder)
    {
        //
    }
}
