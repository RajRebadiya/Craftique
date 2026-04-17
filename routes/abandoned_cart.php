<?php

use App\Http\Controllers\AbandonedCart\CartController;
use App\Http\Controllers\AbandonedCart\DashboardController;
use App\Http\Controllers\AbandonedCart\EmailNotificationController;
use App\Http\Controllers\AbandonedCart\InstallController;
use App\Http\Controllers\AbandonedCart\SettingController;
use App\Http\Controllers\AbandonedCart\TrackUnsubscribeController;

/*
  |--------------------------------------------------------------------------
  | Abandoned Cart Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register abandoned cart routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::group([
    'prefix' => 'admin/ac',
    'as' => 'ac.',
    'middleware' => ['auth', 'admin', 'prevent-back-history']
], function () {
    Route::get('/', [DashboardController::class, 'redirect']);

    Route::get('/install', InstallController::class);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/abandoned-carts', [CartController::class, 'index'])->name('abandoned-carts.index');

    Route::resource('/email-notifications', EmailNotificationController::class)
        ->except('show');

    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');

    Route::post('/settings', [SettingController::class, 'update'])
        ->name('settings.update');
});

Route::group(['prefix' => 'ac', 'as' => 'ac.'], function () {
    Route::get('/track-email/{uuid}', [TrackUnsubscribeController::class, 'track'])
        ->name('track.email');

    Route::get('/unsubscribe/{uuid}', [TrackUnsubscribeController::class, 'unsubscribe'])
        ->name('unsubscribe');
});
