<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Resources\V2\Seller\SellerPackageResource;
use Illuminate\Http\Request;
use App\Models\SellerPackage;
use App\Models\SellerPackagePayment;

class SellerPackageController extends Controller
{
    public function seller_packages_list()
    {
        if (addon_is_activated('seller_subscription')) {
            $seller_packages = SellerPackage::all();
            return SellerPackageResource::collection($seller_packages);
        }

        return $this->failed(translate('Package is not available'));
    }

    public function purchase_free_package(Request $request)
    {
        $data['seller_package_id'] = $request->package_id;
        $data['payment_method'] = $request->payment_option;


        $seller_package = SellerPackage::findOrFail($request->seller_package_id);

        if ($seller_package->amount == 0) {
            seller_purchase_payment_done(auth()->user()->id, $request->package_id, 'Free Package', null);
            return $this->success(translate('Package purchasing successful'));
        }

        return $this->failed(translate('Please use a payment method to purchase this package.'));
    }

    public function purchase_package_offline(Request $request)
    {
        $seller_package = SellerPackage::findOrFail($request->package_id);

        $seller_package = new SellerPackagePayment;
        $seller_package->user_id = auth()->user()->id;
        $seller_package->seller_package_id = $request->package_id;
        $seller_package->amount = $seller_package->amount;
        $seller_package->payment_method = $request->payment_option;
        $seller_package->payment_details = $request->trx_id;
        $seller_package->approval = 0;
        $seller_package->offline_payment = 1;
        $seller_package->reciept = $request->photo;

        $seller_package->save();

        return $this->success(translate('Offline payment has been done. Please wait for response.'));
    }
}
