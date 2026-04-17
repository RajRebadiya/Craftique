<?php

namespace App\Actions\AbandonedCart;

use App\Models\AbandonedCart\AbandonedCart;
use App\Models\AbandonedCart\Cart;
use Illuminate\Console\Command;

class ProcessAction
{
    public function __construct(protected Command $command) {}

    public function handle()
    {
        $this->command->info('Starting abandoned cart process');

        // Determine the time threshold for considering carts as abandoned.
        // Any cart not updated since this time will be treated as abandoned.
        $abandonedThresholdTime = now()->subMinutes(config('abandoned-cart.cut_of_time_in_minutes'));
        $this->command->info('Abandoned threshold time: ' . $abandonedThresholdTime);

        // Query carts that are considered abandoned:
        // - Must have at least one item
        // - Must be associated with a user
        // - Must not have been updated since the abandonment threshold
        $cartQuery = Cart::whereHas('items')
            ->whereHas('user')
            ->with(['items' => ['product'], 'user'])
            ->where('updated_at', '<', $abandonedThresholdTime);

        // Process carts in chunks to avoid memory issues
        $processedCount = 0;
        $totalCount = $cartQuery->count();

        $this->command->info('Found ' . $totalCount . ' abandoned carts');

        $cartQuery->chunk(50, function ($carts) use (&$processedCount) {
            foreach ($carts as $cart) {
                try {
                    $cart->user->abandonedCart()->updateOrCreate([
                        'user_id' => $cart->user_id,
                    ], [
                        'ac_cart_id' => $cart->id,
                        'email' => $cart->user->email,
                        'status' => AbandonedCart::STATUS_PENDING,
                        'is_unsubscribed' => false,
                    ]);
                    $processedCount++;

                    $this->command->info("Processed abandoned cart - ID: {$cart->id}, User: {$cart->user_id}, Email: {$cart->user->email}, Items: {$cart->items->count()}");
                } catch (\Exception $e) {
                    $this->command->error("Failed to process abandoned cart - ID: {$cart->id}, User: {$cart->user_id}, Error: {$e->getMessage()}");
                }
            }
        });

        $this->command->info("Abandoned cart process completed. Processed $processedCount out of $totalCount carts");
    }
}
