<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Seller\Concerns\EnforcesShowcasePackageLimits;
use App\Models\Showcase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowcaseCollectionController extends Controller
{
    use EnforcesShowcasePackageLimits;

    public function index()
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $shop = $this->shop();

        $items = Showcase::where('seller_id', $shop->id)
            ->where('type', 'collection')
            ->latest()
            ->paginate(15);

        return view('seller.showcase.collection.index', [
            'shop'  => $shop,
            'items' => $items,
        ]);
    }

    public function create()
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $typeRedirect = $this->ensureShowcaseTypeAllowed('collection');
        if ($typeRedirect) {
            return $typeRedirect;
        }

        $limitRedirect = $this->ensureShowcaseCreationAllowed();
        if ($limitRedirect) {
            return $limitRedirect;
        }

        return $this->renderForm('Create Collection');
    }

    public function edit($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerCollectionItem($id);

        return $this->renderForm('Edit Collection', $item);
    }

    public function store(Request $request)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $typeRedirect = $this->ensureShowcaseTypeAllowed('collection');
        if ($typeRedirect) {
            return $typeRedirect;
        }

        $limitRedirect = $this->ensureShowcaseCreationAllowed();
        if ($limitRedirect) {
            return $limitRedirect;
        }

        $shop = $this->shop();

        $titleGr = trim((string) $request->input('title_gr', $request->input('title', '')));
        $titleEn = trim((string) $request->input('title_en', ''));

        $introGr = trim((string) $request->input('intro_gr', $request->input('intro', '')));
        $introEn = trim((string) $request->input('intro_en', ''));

        $descriptionGr = trim((string) $request->input('description_gr', $request->input('description', '')));
        $descriptionEn = trim((string) $request->input('description_en', ''));
        $hashtags = trim((string) $request->input('hashtags', ''));

        $validated = $request->validate([
            'cover_image'    => 'nullable',
            'status'         => 'required|in:draft,published',
        ]);

        if ($titleGr === '' && $titleEn === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'title_gr' => translate('Please enter at least one title in Greek or English.'),
                ]);
        }

        $collectionItems = $this->normalizeCollectionItems($request->input('collection_items', []));

        if (empty($collectionItems) && is_array($request->input('product_ids'))) {
            $collectionItems = $this->buildLegacyCollectionItemsFromProducts($request->input('product_ids', []));
        }

        $saveData = [
            'seller_id'             => $shop->id,
            'type'                  => 'collection',
            'title'                 => $titleGr ?: $titleEn,
            'intro'                 => $introGr ?: $introEn,
            'description'           => $descriptionGr ?: $descriptionEn,
            'title_gr'              => $titleGr ?: null,
            'title_en'              => $titleEn ?: null,
            'intro_gr'              => $introGr ?: null,
            'intro_en'              => $introEn ?: null,
            'description_gr'        => $descriptionGr ?: null,
            'description_en'        => $descriptionEn ?: null,
            'cover_image'           => $request->input('cover_image') ?: null,
            'collection_items_json' => !empty($collectionItems) ? $collectionItems : null,
            'billing_period'        => null,
            'hashtags'              => $hashtags ?: null,
            'status'                => $validated['status'],
        ];

        $item = Showcase::create($saveData);

        $this->syncProducts(
            $item->id,
            $this->extractProductIdsFromCollectionItems($collectionItems)
        );

        flash(translate('Collection saved successfully'))->success();
        return redirect()->route('seller.showcase.collection.index');
    }

    public function update(Request $request, $id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerCollectionItem($id);

        $titleGr = trim((string) $request->input('title_gr', $request->input('title', '')));
        $titleEn = trim((string) $request->input('title_en', ''));

        $introGr = trim((string) $request->input('intro_gr', $request->input('intro', '')));
        $introEn = trim((string) $request->input('intro_en', ''));

        $descriptionGr = trim((string) $request->input('description_gr', $request->input('description', '')));
        $descriptionEn = trim((string) $request->input('description_en', ''));
        $hashtags = trim((string) $request->input('hashtags', ''));

        $validated = $request->validate([
            'cover_image'    => 'nullable',
            'status'         => 'required|in:draft,published',
        ]);

        if ($titleGr === '' && $titleEn === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'title_gr' => translate('Please enter at least one title in Greek or English.'),
                ]);
        }

        $collectionItems = $this->normalizeCollectionItems($request->input('collection_items', []));

        if (empty($collectionItems) && is_array($request->input('product_ids'))) {
            $collectionItems = $this->buildLegacyCollectionItemsFromProducts($request->input('product_ids', []));
        }

        $item->update([
            'title'                 => $titleGr ?: $titleEn,
            'intro'                 => $introGr ?: $introEn,
            'description'           => $descriptionGr ?: $descriptionEn,
            'title_gr'              => $titleGr ?: null,
            'title_en'              => $titleEn ?: null,
            'intro_gr'              => $introGr ?: null,
            'intro_en'              => $introEn ?: null,
            'description_gr'        => $descriptionGr ?: null,
            'description_en'        => $descriptionEn ?: null,
            'cover_image'           => $request->input('cover_image') ?: null,
            'collection_items_json' => !empty($collectionItems) ? $collectionItems : null,
            'billing_period'        => null,
            'hashtags'              => $hashtags ?: null,
            'status'                => $validated['status'],
        ]);

        $this->syncProducts(
            $item->id,
            $this->extractProductIdsFromCollectionItems($collectionItems)
        );

        flash(translate('Collection updated successfully'))->success();
        return redirect()->route('seller.showcase.collection.index');
    }

    public function toggleStatus($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerCollectionItem($id);

        $item->status = $item->status === 'published' ? 'draft' : 'published';
        $item->save();

        flash(translate('Collection status updated successfully'))->success();
        return redirect()->route('seller.showcase.collection.index');
    }

    public function destroy($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerCollectionItem($id);

        DB::table('showcase_products')->where('showcase_id', $item->id)->delete();
        $item->delete();

        flash(translate('Collection deleted successfully'))->success();
        return redirect()->route('seller.showcase.collection.index');
    }

    private function renderForm($pageTitle, $item = null)
    {
        $shop = $this->shop();

        $productsQuery = DB::table('products');
        if (!app()->environment('local')) {
            $productsQuery->where('user_id', auth()->id());
        }

        $products = $productsQuery
            ->select('id', 'name')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $selectedProducts = [];
        if ($item) {
            $selectedProducts = DB::table('showcase_products')
                ->where('showcase_id', $item->id)
                ->orderBy('sort_order')
                ->pluck('product_id')
                ->toArray();
        }

        $collectionItems = $this->resolveCollectionItemsForForm($item, $selectedProducts);

        return view('seller.showcase.collection.form', [
            'shop'             => $shop,
            'page_title'       => $pageTitle,
            'item'             => $item,
            'products'         => $products,
            'selectedProducts' => $selectedProducts,
            'collectionItems'  => $collectionItems,
        ]);
    }

    private function sellerCollectionItem($id): Showcase
    {
        return Showcase::where('id', $id)
            ->where('type', 'collection')
            ->where('seller_id', $this->shop()->id)
            ->firstOrFail();
    }

    private function resolveCollectionItemsForForm($item, array $selectedProducts = []): array
    {
        if ($item && !empty($item->collection_items_json)) {
            $decoded = is_array($item->collection_items_json)
                ? $item->collection_items_json
                : json_decode($item->collection_items_json, true);

            if (is_array($decoded) && !empty($decoded)) {
                return array_values(array_map(function ($row) {
                    return [
                        'title_gr'       => $row['title_gr'] ?? '',
                        'title_en'       => $row['title_en'] ?? '',
                        'description_gr' => $row['description_gr'] ?? '',
                        'description_en' => $row['description_en'] ?? '',
                        'cover_image'    => $row['cover_image'] ?? '',
                        'product_id'     => $row['product_id'] ?? '',
                    ];
                }, $decoded));
            }
        }

        $legacyRows = [];
        foreach ($selectedProducts as $productId) {
            $legacyRows[] = [
                'title_gr'       => '',
                'title_en'       => '',
                'description_gr' => '',
                'description_en' => '',
                'cover_image'    => '',
                'product_id'     => (int) $productId,
            ];
        }

        return $legacyRows;
    }

    private function normalizeCollectionItems($rows): array
    {
        if (!is_array($rows)) {
            return [];
        }

        $allowedQuery = DB::table('products');
        if (!app()->environment('local')) {
            $allowedQuery->where('user_id', auth()->id());
        }

        $allowedProductIds = $allowedQuery
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $allowedLookup = array_flip($allowedProductIds);
        $normalized = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $titleGr = trim((string) ($row['title_gr'] ?? ''));
            $titleEn = trim((string) ($row['title_en'] ?? ''));
            $descriptionGr = trim((string) ($row['description_gr'] ?? ''));
            $descriptionEn = trim((string) ($row['description_en'] ?? ''));
            $coverImage = trim((string) ($row['cover_image'] ?? ''));
            $productId = !empty($row['product_id']) ? (int) $row['product_id'] : null;

            if ($productId && !isset($allowedLookup[$productId])) {
                $productId = null;
            }

            if (
                $titleGr === ''
                && $titleEn === ''
                && $descriptionGr === ''
                && $descriptionEn === ''
                && $coverImage === ''
                && empty($productId)
            ) {
                continue;
            }

            $normalized[] = [
                'title'          => $titleGr ?: ($titleEn ?: null),
                'title_gr'       => $titleGr ?: null,
                'title_en'       => $titleEn ?: null,
                'description'    => $descriptionGr ?: ($descriptionEn ?: null),
                'description_gr' => $descriptionGr ?: null,
                'description_en' => $descriptionEn ?: null,
                'cover_image'    => $coverImage ?: null,
                'product_id'     => $productId,
                'sort_order'     => count($normalized),
            ];
        }

        return $normalized;
    }

    private function buildLegacyCollectionItemsFromProducts(array $productIds): array
    {
        $allowedQuery = DB::table('products')->whereIn('id', $productIds);
        if (!app()->environment('local')) {
            $allowedQuery->where('user_id', auth()->id());
        }

        $allowedProductIds = $allowedQuery
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->toArray();

        $rows = [];

        foreach ($allowedProductIds as $index => $productId) {
            $rows[] = [
                'title'          => null,
                'title_gr'       => null,
                'title_en'       => null,
                'description'    => null,
                'description_gr' => null,
                'description_en' => null,
                'cover_image'    => null,
                'product_id'     => $productId,
                'sort_order'     => $index,
            ];
        }

        return $rows;
    }

    private function extractProductIdsFromCollectionItems(array $collectionItems): array
    {
        return collect($collectionItems)
            ->pluck('product_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();
    }

    private function syncProducts($showcaseId, array $productIds = [])
    {
        DB::table('showcase_products')->where('showcase_id', $showcaseId)->delete();

        $rows = [];
        foreach ($productIds as $index => $productId) {
            $rows[] = [
                'showcase_id' => $showcaseId,
                'product_id'  => (int) $productId,
                'sort_order'  => $index,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        if (!empty($rows)) {
            DB::table('showcase_products')->insert($rows);
        }
    }

    private function ensureActivePackage()
    {
        if (app()->environment('local')) {
            return null;
        }

        $shop = $this->shop();

        $hasActivePackage = !empty($shop->seller_package_id);

        if ($hasActivePackage && !empty($shop->package_invalid_at)) {
            $hasActivePackage = Carbon::parse($shop->package_invalid_at)->endOfDay()->gte(now());
        }

        if (!$hasActivePackage) {
            flash(translate('You need an active seller package to use Showcase.'))->warning();
            return redirect()->route('seller.showcase.index');
        }

        return null;
    }

    private function shop()
    {
        $shop = auth()->user()->shop;
        abort_unless($shop, 404);

        return $shop;
    }
}
