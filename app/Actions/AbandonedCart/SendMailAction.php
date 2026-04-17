<?php

namespace App\Actions\AbandonedCart;

use App\Jobs\AbandonedCart\SendMailJob;
use App\Models\AbandonedCart\AbandonedCart;
use App\Models\AbandonedCart\EmailNotification;
use Illuminate\Console\Command;

class SendMailAction
{
    public function __construct(protected Command $command) {}

    public function handle()
    {
        // get the email notifications
        $emailNotifications = EmailNotification::isActive()
            ->orderBy('minutes_after_trigger')
            ->get();

        // check if there are no email notifications
        if ($emailNotifications->isEmpty()) {
            $this->command->info('No email notifications found.');
            return;
        }

        $this->command->info('Found ' . $emailNotifications->count() . ' email notifications');

        // get the abandoned carts
        $abandonedCartQuery = AbandonedCart::query()
            ->where('status', AbandonedCart::STATUS_PENDING)
            ->whereNot('is_unsubscribed')
            ->with([
                'user',
                'cart' => [
                    'items' => [
                        'product'
                    ]
                ]
            ]);

        $processedCount = 0;
        $totalCount = $abandonedCartQuery->count();

        $this->command->info('Found ' . $totalCount . ' abandoned carts');

        // Process abandoned carts in chunks to avoid memory issues
        $abandonedCartQuery->chunk(100, function ($abandonedCarts) use ($emailNotifications, &$processedCount) {
            foreach ($abandonedCarts as $abandonedCart) {
                try {
                    foreach ($emailNotifications as $emailNotification) {
                        SendMailJob::dispatch($abandonedCart, $emailNotification)
                            ->delay(now()->addMinutes($emailNotification->minutes_after_trigger));
                    }

                    // update the abandoned cart status
                    $abandonedCart->update([
                        'status' => AbandonedCart::STATUS_DISPATCHED,
                    ]);
                    $processedCount++;

                    $this->command->info("Processed abandoned cart - ID: {$abandonedCart->id}, User: {$abandonedCart->user_id}, Email: {$abandonedCart->user->email}, Items Count: {$abandonedCart->cart->items->count()}");
                } catch (\Exception $e) {
                    $this->command->error("Failed to process abandoned cart - ID: {$abandonedCart->id}, User: {$abandonedCart->user_id}, Error: {$e->getMessage()}");
                }
            }
        });

        $this->command->info("Abandoned cart email notifications processing completed. Processed $processedCount out of $totalCount carts");
    }
}
