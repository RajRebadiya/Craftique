<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellerPackage;
use App\Models\SellerPackageTranslation;
use App\Models\SellerPackagePayment;
use App\Models\Shop;
use Artisan;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SellerPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_all_seller_packages'])->only('index');
        $this->middleware(['permission:add_seller_package'])->only('create');
        $this->middleware(['permission:edit_seller_package'])->only('edit');
        $this->middleware(['permission:delete_seller_package'])->only('destroy');
    }

    public function index()
    {
        $seller_packages = SellerPackage::all();
        return view('seller_packages.index', compact('seller_packages'));
    }

    public function create()
    {
        return view('seller_packages.create');
    }

    public function store(Request $request)
    {
        $seller_package = new SellerPackage;
        $seller_package->name = $request->name;
        $seller_package->amount = $request->amount;
        $seller_package->showcase_post_limit = $this->normalizeShowcaseLimit($request->showcase_post_limit);
        $seller_package->allow_showcase_history = $this->packageToggle($request, 'allow_showcase_history', true);
        $seller_package->allow_showcase_collection = $this->packageToggle($request, 'allow_showcase_collection', true);
        $seller_package->allow_showcase_vitrin = $this->packageToggle($request, 'allow_showcase_vitrin', true);

        $seller_package->duration = $request->duration;
        $seller_package->logo = $request->logo;

        if ($seller_package->save()) {
            $seller_package_translation = SellerPackageTranslation::firstOrNew([
                'lang' => env('DEFAULT_LANGUAGE'),
                'seller_package_id' => $seller_package->id,
            ]);
            $seller_package_translation->name = $request->name;
            $seller_package_translation->save();

            flash(translate('Package has been inserted successfully'))->success();
            return redirect()->route('seller_packages.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $seller_package = SellerPackage::findOrFail($id);
        return view('seller_packages.edit', compact('seller_package', 'lang'));
    }

    public function update(Request $request, $id)
    {
        $seller_package = SellerPackage::findOrFail($id);
        $seller_package->allow_showcase_history = $this->packageToggle($request, 'allow_showcase_history', true);
        $seller_package->allow_showcase_collection = $this->packageToggle($request, 'allow_showcase_collection', true);
        $seller_package->allow_showcase_vitrin = $this->packageToggle($request, 'allow_showcase_vitrin', true);

        if ($request->lang == env('DEFAULT_LANGUAGE')) {
            $seller_package->name = $request->name;
        }

        $seller_package->amount = $request->amount;
        $seller_package->showcase_post_limit = $this->normalizeShowcaseLimit($request->showcase_post_limit);

        $seller_package->duration = $request->duration;
        $seller_package->logo = $request->logo;

        if ($seller_package->save()) {
            $seller_package_translation = SellerPackageTranslation::firstOrNew([
                'lang' => $request->lang,
                'seller_package_id' => $seller_package->id,
            ]);
            $seller_package_translation->name = $request->name;
            $seller_package_translation->save();

            flash(translate('Package has been inserted successfully'))->success();
            return redirect()->route('seller_packages.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function destroy($id)
    {
        $seller_package = SellerPackage::findOrFail($id);

        foreach ($seller_package->seller_package_translations as $seller_package_translation) {
            $seller_package_translation->delete();
        }

        SellerPackage::destroy($id);

        flash(translate('Package has been deleted successfully'))->success();
        return redirect()->route('seller_packages.index');
    }

    public function packages_payment_list()
    {
        $seller_packages_payment = SellerPackagePayment::with('seller_package')
            ->where('user_id', Auth::user()->id)
            ->paginate(15);

        return view('seller_packages.seller.packages_payment_list', compact('seller_packages_payment'));
    }

    public function seller_packages_list()
    {
        $seller_packages = SellerPackage::all();
        return view('seller_packages.seller.seller_packages_list', compact('seller_packages'));
    }

    public function purchase_package(Request $request)
    {
        $seller_purchased_package = auth()->user()->shop->seller_package;
        $shop = auth()->user()->shop;

        $data['seller_package_id'] = $request->seller_package_id;
        $data['payment_method'] = $request->payment_option;

        $request->session()->put('payment_type', 'seller_package_payment');
        $request->session()->put('payment_data', $data);

        $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
        $currentShowcaseCount = DB::table('showcases')->where('seller_id', $shop->id)->count();

        if (!$this->isShowcaseUsageWithinLimit($seller_package->showcase_post_limit ?? null, $currentShowcaseCount)) {
            flash(translate('This package showcase limit is lower than your current showcase posts.'))->warning();
            return back();
        }

        if ($seller_package->amount == 0) {
            return $this->purchase_payment_done(Session::get('payment_data'), null);
        } elseif ($seller_purchased_package != null) {
            $can_purchase = $this->canPurchaseShowcaseLimit(
                $seller_package->showcase_post_limit ?? null,
                $seller_purchased_package->showcase_post_limit ?? null,
                $currentShowcaseCount
            );

            if (!$can_purchase) {
                flash(translate('You can not downgrade the package.'))->warning();
                return back();
            }
        }

        $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . 'Controller';

        if (class_exists($decorator)) {
            return (new $decorator)->pay($request);
        }
    }

    public function purchase_payment_done($payment_data, $payment)
    {
        $user = auth()->user();
        $seller = $user->shop;

        $seller->seller_package_id = Session::get('payment_data')['seller_package_id'];

        $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
        $baseDate = !empty($seller->package_invalid_at) && Carbon::parse($seller->package_invalid_at)->greaterThan(now())
            ? Carbon::parse($seller->package_invalid_at)
            : now();
        $seller->package_invalid_at = $baseDate->copy()->addDays(max(0, (int) $seller_package->duration))->toDateString();
        $seller->save();

        $seller_package_payment = new SellerPackagePayment;
        $seller_package_payment->user_id = $user->id;
        $seller_package_payment->seller_package_id = $seller_package->id;
        $seller_package_payment->amount = $seller_package->amount;
        $seller_package_payment->payment_method = Session::get('payment_data')['payment_method'];
        $seller_package_payment->payment_details = $payment;
        $seller_package_payment->approval = 1;
        $seller_package_payment->offline_payment = 2;
        $seller_package_payment->save();

        flash(translate('Package purchasing successful'))->success();
        return redirect()->route('seller.dashboard');
    }

    public function unpublish_products(Request $request)
    {
        foreach (Shop::all() as $shop) {
            if ($shop->package_invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) <= 0) {
                foreach ($shop->user->products as $product) {
                    $product->published = 0;
                    $product->save();
                }

                foreach ($shop->user->preorderProducts as $preorderProduct) {
                    $preorderProduct->is_published = 0;
                    $preorderProduct->save();
                }

                DB::table('showcases')
                    ->where('seller_id', $shop->id)
                    ->where('status', 'published')
                    ->update([
                        'status' => 'draft',
                        'updated_at' => now(),
                    ]);

                $shop->seller_package_id = null;
                $shop->package_invalid_at = null;
                $shop->save();
            }
        }

        Artisan::call('cache:clear');
    }

    public function purchase_package_offline(Request $request)
    {
        $seller_package = SellerPackage::findOrFail($request->package_id);
        $user = auth()->user();
        $shop = $user->shop;
        $seller_purchased_package = $shop->seller_package;
        $currentShowcaseCount = DB::table('showcases')->where('seller_id', $shop->id)->count();

        if (!$this->isShowcaseUsageWithinLimit($seller_package->showcase_post_limit ?? null, $currentShowcaseCount)) {
            flash(translate('This package showcase limit is lower than your current showcase posts.'))->warning();
            return back();
        }

        if ($user->shop->seller_package != null) {
            $can_purchase = $this->canPurchaseShowcaseLimit(
                $seller_package->showcase_post_limit ?? null,
                $seller_purchased_package->showcase_post_limit ?? null,
                $currentShowcaseCount
            );

            if (!$can_purchase) {
                flash(translate('You can not downgrade the package.'))->warning();
                return back();
            }
        }

        $seller_package_payment = new SellerPackagePayment;
        $seller_package_payment->user_id = $user->id;
        $seller_package_payment->seller_package_id = $request->package_id;
        $seller_package_payment->amount = $seller_package->amount;
        $seller_package_payment->payment_method = $request->payment_option;
        $seller_package_payment->payment_details = $request->trx_id;
        $seller_package_payment->approval = 0;
        $seller_package_payment->offline_payment = 1;
        $seller_package_payment->reciept = $request->photo;
        $seller_package_payment->save();

        flash(translate('Offline payment has been done. Please wait for response.'))->success();
        return redirect()->route('seller.products');
    }

    private function normalizeShowcaseLimit($value): ?int
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return max(0, (int) $value);
    }

    private function isShowcaseUsageWithinLimit($limit, int $currentCount): bool
    {
        $limit = $this->normalizeShowcaseLimit($limit);

        if ($limit === null) {
            return true;
        }

        return $currentCount <= $limit;
    }

    private function canPurchaseShowcaseLimit($newLimit, $currentLimit, int $currentCount): bool
    {
        $newLimit = $this->normalizeShowcaseLimit($newLimit);
        $currentLimit = $this->normalizeShowcaseLimit($currentLimit);

        if (!$this->isShowcaseUsageWithinLimit($newLimit, $currentCount)) {
            return false;
        }

        if ($currentLimit === null) {
            return $newLimit === null;
        }

        if ($newLimit === null) {
            return true;
        }

        return $newLimit >= $currentLimit;
    }

    private function packageToggle(Request $request, string $field, bool $legacyDefault = true): int
    {
        $hasAnyShowcaseToggle = $request->has('allow_showcase_history')
            || $request->has('allow_showcase_collection')
            || $request->has('allow_showcase_vitrin');

        if (!$hasAnyShowcaseToggle) {
            return $legacyDefault ? 1 : 0;
        }

        return $request->boolean($field) ? 1 : 0;
    }
}
