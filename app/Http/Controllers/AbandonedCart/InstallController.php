<?php

namespace App\Http\Controllers\AbandonedCart;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart\EmailNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class InstallController extends Controller
{
    public function __invoke()
    {
        if (Schema::hasTable('ac_abandoned_carts')) {
            return 'Abandoned Cart is already installed.';
        }

        Artisan::call('migrate --path=database/migrations/abandoned_cart');

        $emailNotifications = [
            [
                'name' => 'Abandoned Cart Reminder',
                'minutes_after_trigger' => 60,
                'is_active' => true,
                'subject' => 'Reminder: You have items in your cart',
                'body' => 'Hello, you have items in your cart. Please complete your purchase.'
            ],
            [
                'name' => 'Abandoned Cart Reminder 2',
                'minutes_after_trigger' => 120,
                'is_active' => true,
                'subject' => 'Reminder: You have items in your cart',
                'body' => 'Hello, you have items in your cart. Please complete your purchase.'
            ],
            [
                'name' => 'Abandoned Cart Reminder 3',
                'minutes_after_trigger' => 180,
                'is_active' => true,
                'subject' => 'Reminder: You have items in your cart',
                'body' => 'Hello, you have items in your cart. Please complete your purchase.'
            ],
        ];

        foreach ($emailNotifications as $emailNotification) {
            EmailNotification::firstOrCreate(
                ['name' => $emailNotification['name']],
                Arr::except($emailNotification, 'name'),
            );
        }

        return 'Abandoned Cart installed successfully.';
    }
}
