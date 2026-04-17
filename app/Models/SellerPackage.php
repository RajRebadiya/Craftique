<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App;

class SellerPackage extends Model
{
    use PreventDemoModeChanges;

    protected $guarded = [];

    protected $casts = [
        'amount'                       => 'float',
        'product_upload_limit'         => 'integer',
        'preorder_product_upload_limit'=> 'integer',
        'showcase_post_limit'          => 'integer',
        'allow_showcase_history'       => 'integer',
        'allow_showcase_collection'    => 'integer',
        'allow_showcase_vitrin'        => 'integer',
        'duration'                     => 'integer',
        'logo'                         => 'integer',
    ];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $seller_package_translation = $this->hasMany(SellerPackageTranslation::class)
            ->where('lang', $lang)
            ->first();

        return $seller_package_translation != null
            ? $seller_package_translation->$field
            : $this->$field;
    }

    public function seller_package_translations()
    {
        return $this->hasMany(SellerPackageTranslation::class);
    }

    public function seller_package_payments()
    {
        return $this->hasMany(SellerPackagePayment::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }
}