<?php

namespace App\Http\Controllers\AbandonedCart;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart\AbandonedCart;
use App\Models\AbandonedCart\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the date range from the request
        $date = $request->get('date');

        // Initialize date filters
        $startDate = null;
        $endDate = null;

        if ($date != null) {
            $dates = explode(" to ", $date);
            if (count($dates) == 2) {
                try {
                    // Parse dates using Carbon with format 'd-m-Y'
                    $startDate = Carbon::createFromFormat('d-m-Y', $dates[0])->startOfDay();
                    $endDate = Carbon::createFromFormat('d-m-Y', $dates[1])->endOfDay();
                } catch (\Exception $e) {
                    return back()->with('error', 'Invalid date format');
                }
            } else {
                return back()->with('error', 'Invalid date range');
            }
        }
        // Apply date filtering to recoverable orders
        $recoverableOrdersQuery = AbandonedCart::query();
        if ($startDate && $endDate) {
            $recoverableOrdersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $data['recoverable_orders'] = $recoverableOrdersQuery->count();

        // Apply date filtering to recoverable revenue
        $recoverableRevenueQuery = CartItem::query();
        if ($startDate && $endDate) {
            $recoverableRevenueQuery->whereHas('cart.abandonedCart', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });
        }
        $data['recoverable_revenue'] = $recoverableRevenueQuery->sum('calculated_price');

        // Apply date filtering to recovered orders
        $recoveredOrdersQuery = AbandonedCart::where('status', AbandonedCart::STATUS_RECOVERED);
        if ($startDate && $endDate) {
            $recoveredOrdersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $data['recovered_orders'] = $recoveredOrdersQuery->count();

        // Apply date filtering to recovered revenue
        $recoveredRevenueQuery = CartItem::query();
        if ($startDate && $endDate) {
            $recoveredRevenueQuery->whereHas('cart.abandonedCart', function ($query) use ($startDate, $endDate) {
                $query->where('status', AbandonedCart::STATUS_RECOVERED)
                    ->whereBetween('created_at', [$startDate, $endDate]);
            });
        } else {
            $recoveredRevenueQuery->whereHas('cart.abandonedCart', function ($query) {
                $query->where('status', AbandonedCart::STATUS_RECOVERED);
            });
        }
        $data['recovered_revenue'] = $recoveredRevenueQuery->sum('calculated_price');

        // Apply date filtering to lost orders
        $lostOrdersQuery = AbandonedCart::where('status', AbandonedCart::STATUS_LOST);
        if ($startDate && $endDate) {
            $lostOrdersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $data['lost_orders'] = $lostOrdersQuery->count();

        // Calculate recovery rate
        $data['recovery_rate'] = $data['recoverable_orders'] > 0
            ? number_format(($data['recovered_orders'] / $data['recoverable_orders']) * 100, 2).'%'
            : '0.00%';

        return view('backend.abandoned_cart.dashboard', compact('data', 'date'));
    }

    public function redirect()
    {
        return redirect()->route('ac.dashboard');
    }
}
