<?php

namespace App\Http\Controllers\AbandonedCart;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart\AbandonedCart;
use App\Models\AbandonedCart\AbandonEmail;
use App\Models\AbandonedCart\EmailNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TrackUnsubscribeController extends Controller
{
    /**
     * @param  string  $uuid
     * @return void
     */
    public function track(string $uuid) {
        $abandonEmail = AbandonEmail::where('uuid', $uuid)
            ->notOpened()
            ->first();

        if (! $abandonEmail) {
            return;
        }

        $abandonEmail->update([
            'opened_at' => now(),
        ]);

        $abandonEmail->abandonedCart()->update([
            'status' => AbandonedCart::STATUS_RECEIVED,
        ]);
    }

    /**
     * @param  string  $uuid
     * @return string
     */
    public function unsubscribe(string $uuid) {
        $abandonEmail = AbandonEmail::where('uuid', $uuid)->first();

        $abandonEmail?->abandonedCart?->update([
            'is_unsubscribed' => true,
        ]);

        return 'You have been unsubscribed from our email list.';
    }
}
