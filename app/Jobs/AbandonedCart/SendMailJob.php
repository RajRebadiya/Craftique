<?php

namespace App\Jobs\AbandonedCart;

use App\Mail\AbandonedCart\RecoveryMail;
use App\Models\AbandonedCart\AbandonedCart;
use App\Models\AbandonedCart\AbandonEmail;
use App\Models\AbandonedCart\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public AbandonedCart $abandonedCart,
        public EmailNotification $emailNotification,
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // check if the user is unsubscribed
        if ($this->abandonedCart->is_unsubscribed) {
            return;
        }

        $abandonMail = AbandonEmail::firstOrCreate([
            'ac_abandoned_cart_id' => $this->abandonedCart->id,
            'ac_email_notification_id' => $this->emailNotification->id,
        ], [
            'sent_at' => now(),
        ]);

        if ($abandonMail->wasRecentlyCreated) {
            // send email to the user
            Mail::to($this->abandonedCart->email)
                ->queue(new RecoveryMail($this->abandonedCart, $abandonMail, $this->emailNotification));
        }

        $this->abandonedCart->update([
            'status' => AbandonedCart::STATUS_PROGRESS,
        ]);
    }
}
