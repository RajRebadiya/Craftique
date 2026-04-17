<?php

namespace App\Http\Controllers\AbandonedCart;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart\EmailNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $emailNotifications = EmailNotification::paginate(10);

        return view('backend.abandoned_cart.notifications.index', compact('emailNotifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.abandoned_cart.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'minutes_after_trigger' => ['required', 'integer'],
            'is_active' => ['required', 'boolean'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string']
        ]);

        EmailNotification::create($validated);

        flash(translate('Email notifications has been inserted successfully'))->success();

        return to_route('ac.email-notifications.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EmailNotification  $emailNotification
     * @return Application|Factory|View
     */
    public function edit(EmailNotification $emailNotification)
    {
        return view('backend.abandoned_cart.notifications.edit', compact('emailNotification'));
    }

    public function update(Request $request, EmailNotification $emailNotification)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'minutes_after_trigger' => ['required', 'integer'],
            'is_active' => ['required', 'boolean'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string']
        ]);

        $emailNotification->update($validated);

        flash(translate('Email notification has been updated successfully'))->success();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  EmailNotification  $emailNotification
     * @return RedirectResponse
     */
    public function destroy(EmailNotification $emailNotification)
    {
        $emailNotification->delete();

        flash(translate('Email notification has been deleted successfully'))->success();

        return to_route('ac.email-notifications.index');
    }
}
