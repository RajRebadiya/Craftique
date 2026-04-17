<?php

namespace App\Console\Commands\AbandonedCart;

use App\Models\AbandonedCart\AbandonedCart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkAsLostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abandoned-cart:mark-as-lost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark abandoned cart as lost';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // mark abandoned cart as lost if it is not updated for 30 days
            AbandonedCart::where('updated_at', '<', now()->subDays(30))
                ->whereNot('status', AbandonedCart::STATUS_LOST)
                ->chunkById(100, function ($abandonedCarts) {
                    $ids = $abandonedCarts->pluck('id');

                    // batch update the abandoned cart status
                    AbandonedCart::whereIn('id', $ids)
                        ->update([
                            'status' => AbandonedCart::STATUS_LOST,
                        ]);
                });
        } catch (\Exception $exception) {
            $this->error('Error marking abandoned cart as lost: ' . $exception->getMessage());

            Log::error('Error marking abandoned cart as lost', [
                'exception' => $exception,
            ]);
        }


        $this->info('Abandoned cart marked as lost.');

        return Command::SUCCESS;
    }
}
