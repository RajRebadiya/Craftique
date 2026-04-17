<?php

namespace App\Http\Controllers\Seller\Concerns;

use Illuminate\Support\Facades\DB;

trait EnforcesShowcasePackageLimits
{
    protected function ensureShowcaseCreationAllowed()
    {
        $shop = $this->shop();
        $limit = optional($shop->seller_package)->showcase_post_limit;

        if ($limit === '' || $limit === null) {
            return null;
        }

        $limit = (int) $limit;

        $currentCount = DB::table('showcases')
            ->where('seller_id', $shop->id)
            ->count();

        if ($currentCount >= $limit) {
            flash(translate('Your package showcase post limit has been reached.'))->warning();
            return redirect()->route('seller.showcase.index');
        }

        return null;
    }

    protected function ensureShowcaseTypeAllowed(string $type)
    {
        $shop = $this->shop();
        $package = optional($shop)->seller_package;

        if (!$package) {
            return null;
        }

        $map = [
            'history'    => 'allow_showcase_history',
            'collection' => 'allow_showcase_collection',
            'vitrin'     => 'allow_showcase_vitrin',
        ];

        $labels = [
            'history'    => translate('Story'),
            'collection' => translate('Collection'),
            'vitrin'     => translate('Storefront'),
        ];

        $column = $map[$type] ?? null;

        if (!$column) {
            return null;
        }

        $allowed = (int) ($package->{$column} ?? 1) === 1;

        if (!$allowed) {
            flash(
                translate('Your current seller package does not allow this Showcase type: ') . ($labels[$type] ?? $type)
            )->warning();

            return redirect()->route('seller.showcase.index');
        }

        return null;
    }

    protected function showcaseTypeAllowedForPackage($package, string $type): bool
    {
        if (!$package) {
            return false;
        }

        $map = [
            'history'    => 'allow_showcase_history',
            'collection' => 'allow_showcase_collection',
            'vitrin'     => 'allow_showcase_vitrin',
        ];

        $column = $map[$type] ?? null;

        if (!$column) {
            return false;
        }

        return (int) ($package->{$column} ?? 1) === 1;
    }
}
