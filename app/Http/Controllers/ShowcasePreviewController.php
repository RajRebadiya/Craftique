<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShowcasePreviewController extends Controller
{
    public function products(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            $ids = explode(',', (string) $ids);
        }
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));

        if (empty($ids)) {
            return response()->json([]);
        }

        $products = Product::with('brand')->whereIn('id', $ids)->get()->keyBy('id');

        $response = [];
        foreach ($ids as $id) {
            $product = $products->get($id);
            if (!$product) {
                continue;
            }

            $productUrl = route('product', $product->slug);
            if ((int) ($product->auction_product ?? 0) === 1) {
                $productUrl = route('auction-product', $product->slug);
            }

            $discountedPrice = function_exists('home_discounted_base_price')
                ? home_discounted_base_price($product)
                : null;
            $basePrice = function_exists('home_base_price')
                ? home_base_price($product)
                : null;

            $photoUrls = [];
            $photosRaw = (string) ($product->photos ?? '');
            if ($photosRaw !== '') {
                foreach (array_slice(array_filter(array_map('trim', explode(',', $photosRaw))), 0, 6) as $photoRef) {
                    $resolvedPhotoUrl = $this->resolveAssetUrl($photoRef);
                    if (!empty($resolvedPhotoUrl)) {
                        $photoUrls[] = $resolvedPhotoUrl;
                    }
                }
            }

            $thumbnailUrl = $this->resolveAssetUrl($product->thumbnail_img ?? null);
            if (empty($thumbnailUrl) && !empty($photoUrls)) {
                $thumbnailUrl = $photoUrls[0];
            }

            $response[$id] = [
                'id' => $product->id,
                'name' => method_exists($product, 'getTranslation') ? $product->getTranslation('name') : ($product->name ?? ''),
                'brand_name' => optional($product->brand)->name,
                'thumbnail_url' => $thumbnailUrl,
                'photo_urls' => $photoUrls,
                'product_url' => $productUrl,
                'price_html' => $discountedPrice ?: ($basePrice ?: ''),
                'base_price_html' => $basePrice ?: '',
            ];
        }

        return response()->json($response);
    }

    private function resolveAssetUrl($value): ?string
    {
        if (empty($value) && $value !== '0') {
            return null;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        if (preg_match('/^(data:|\/)/i', $value)) {
            return $value;
        }

        if (is_numeric($value)) {
            if (function_exists('uploaded_asset')) {
                return uploaded_asset((int) $value);
            }

            if (function_exists('get_image')) {
                return get_image((int) $value);
            }
        }

        return asset(ltrim($value, '/'));
    }
}
