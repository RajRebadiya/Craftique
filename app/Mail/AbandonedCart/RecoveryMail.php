<?php

namespace App\Mail\AbandonedCart;

use App\Models\AbandonedCart\AbandonedCart;
use App\Models\AbandonedCart\AbandonEmail;
use App\Models\AbandonedCart\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecoveryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public AbandonedCart $abandonedCart,
        public AbandonEmail $abandonEmail,
        public EmailNotification $emailNotification,
    ) {

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->emailNotification->subject . ' - ' . get_setting('site_name'),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $this->abandonedCart->loadMissing([
            'user',
            'cart' => [
                'items' => [
                    'product'
                ]
            ]
        ]);

        return new Content(
            view: 'backend.abandoned_cart.mail.default',
            with: [
                'abandonedCart' => $this->abandonedCart,
                'user' => $this->abandonedCart->user,
                'cart' => $this->abandonedCart->cart,
                'abandonEmail' => $this->abandonEmail,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
