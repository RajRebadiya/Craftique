<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShowcaseCenterController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $shop = $user->shop;

        abort_unless($shop, 404);

        $hasActivePackage = !empty($shop->seller_package_id);

        if ($hasActivePackage && !empty($shop->package_invalid_at)) {
            $hasActivePackage = Carbon::parse($shop->package_invalid_at)->endOfDay()->gte(now());
        }

        $packages = collect();

        if (!$hasActivePackage) {
            $packages = SellerPackage::orderBy('amount', 'asc')->get();
        }

        $stats = [
            'history_total'     => 0,
            'collection_total'  => 0,
            'vitrin_total'      => 0,
            'launch_total'      => 0,
            'published_total'   => 0,
            'draft_total'       => 0,
            'all_total'         => 0,
        ];

        $recentItems = collect();
        $daysRemaining = null;

        $showcaseLimit = null;
        $showcaseUsed = 0;
        $showcaseRemaining = null;
        $limitReached = false;

        if ($hasActivePackage) {
            $stats['history_total'] = DB::table('showcases')
                ->where('seller_id', $shop->id)
                ->where('type', 'history')
                ->count();

            $stats['collection_total'] = DB::table('showcases')
                ->where('seller_id', $shop->id)
                ->where('type', 'collection')
                ->count();

            $stats['vitrin_total'] = DB::table('showcases')
                ->where('seller_id', $shop->id)
                ->where('type', 'vitrin')
                ->count();

            $stats['launch_total'] = DB::table('showcases')
                ->where('seller_id', $shop->id)
                ->where('type', 'launch')
                ->count();

            $stats['published_total'] = DB::table('showcases')
                ->where('seller_id', $shop->id)
                ->where('status', 'published')
                ->count();

            $stats['draft_total'] = DB::table('showcases')
                ->where('seller_id', $shop->id)
                ->where('status', 'draft')
                ->count();

            $stats['all_total'] = DB::table('showcases')
                ->where('seller_id', $shop->id)
                ->count();

            $showcaseUsed = $stats['all_total'];
            $showcaseLimit = optional($shop->seller_package)->showcase_post_limit;

            if ($showcaseLimit !== null && $showcaseLimit !== '') {
                $showcaseLimit = (int) $showcaseLimit;
                $showcaseRemaining = max(0, $showcaseLimit - $showcaseUsed);
                $limitReached = $showcaseUsed >= $showcaseLimit;
            }

            $recentItems = DB::table('showcases')
                ->where('seller_id', $shop->id)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();

            if (!empty($shop->package_invalid_at)) {
                $daysRemaining = max(
                    0,
                    now()->startOfDay()->diffInDays(Carbon::parse($shop->package_invalid_at)->startOfDay(), false)
                );
            }
        }

        return view('seller.showcase.index', [
            'shop'              => $shop,
            'hasActivePackage'  => $hasActivePackage,
            'packages'          => $packages,
            'stats'             => $stats,
            'recentItems'       => $recentItems,
            'daysRemaining'     => $daysRemaining,
            'showcaseLimit'     => $showcaseLimit,
            'showcaseUsed'      => $showcaseUsed,
            'showcaseRemaining' => $showcaseRemaining,
            'limitReached'      => $limitReached,
        ]);
    }
}
