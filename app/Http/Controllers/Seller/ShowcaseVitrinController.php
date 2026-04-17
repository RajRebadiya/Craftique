<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Seller\Concerns\EnforcesShowcasePackageLimits;
use App\Models\Showcase;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowcaseVitrinController extends Controller
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
            ->where('type', 'vitrin')
            ->latest()
            ->paginate(15);

        return view('seller.showcase.vitrin.index', [
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

        $typeRedirect = $this->ensureShowcaseTypeAllowed('vitrin');
        if ($typeRedirect) {
            return $typeRedirect;
        }

        $limitRedirect = $this->ensureShowcaseCreationAllowed();
        if ($limitRedirect) {
            return $limitRedirect;
        }

        return $this->renderForm('Create Storefront');
    }

    public function edit($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerVitrinItem($id);

        return $this->renderForm('Edit Storefront', $item);
    }

    public function store(Request $request)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $typeRedirect = $this->ensureShowcaseTypeAllowed('vitrin');
        if ($typeRedirect) {
            return $typeRedirect;
        }

        $limitRedirect = $this->ensureShowcaseCreationAllowed();
        if ($limitRedirect) {
            return $limitRedirect;
        }

        $shop = $this->shop();

        $titleGr = $request->input('title_gr', $request->input('title'));
        $titleEn = $request->input('title_en');

        $descriptionGr = $request->input('description_gr', $request->input('description'));
        $descriptionEn = $request->input('description_en');
        $hashtags = trim((string) $request->input('hashtags', ''));

        $validated = $request->validate([
            'main_visual'   => 'nullable',
            'cover_image'   => 'nullable',
            'status'        => 'required|in:draft,published',
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'integer',
        ]);

        if (!$titleGr && !$titleEn) {
            return back()
                ->withInput()
                ->withErrors([
                    'title_gr' => translate('Please enter at least one title in Greek or English.'),
                ]);
        }

        if (empty($validated['main_visual'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'main_visual' => translate('Please upload the main Storefront media.'),
                ]);
        }

        $saveData = [
            'seller_id'      => $shop->id,
            'type'           => 'vitrin',
            'title'          => $titleGr ?: $titleEn,
            'description'    => $descriptionGr ?: $descriptionEn,
            'title_gr'       => $titleGr,
            'title_en'       => $titleEn,
            'description_gr' => $descriptionGr,
            'description_en' => $descriptionEn,
            'main_visual'    => $validated['main_visual'],
            'cover_image'    => $request->input('cover_image'),
            'hashtags'       => $hashtags ?: null,
            'status'         => $validated['status'],
        ];

        $item = Showcase::create($saveData);

        $this->syncProducts($item->id, $request->input('product_ids', []));

        flash(translate('Storefront saved successfully'))->success();
        return redirect()->route('seller.showcase.vitrin.index');
    }

    public function update(Request $request, $id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerVitrinItem($id);

        $titleGr = $request->input('title_gr', $request->input('title'));
        $titleEn = $request->input('title_en');

        $descriptionGr = $request->input('description_gr', $request->input('description'));
        $descriptionEn = $request->input('description_en');
        $hashtags = trim((string) $request->input('hashtags', ''));

        $validated = $request->validate([
            'main_visual'   => 'nullable',
            'cover_image'   => 'nullable',
            'status'        => 'required|in:draft,published',
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'integer',
        ]);

        if (!$titleGr && !$titleEn) {
            return back()
                ->withInput()
                ->withErrors([
                    'title_gr' => translate('Please enter at least one title in Greek or English.'),
                ]);
        }

        $mainVisual = $validated['main_visual'] ?? $item->main_visual;

        if (empty($mainVisual)) {
            return back()
                ->withInput()
                ->withErrors([
                    'main_visual' => translate('Please upload the main Storefront media.'),
                ]);
        }

        $saveData = [
            'title'          => $titleGr ?: $titleEn,
            'description'    => $descriptionGr ?: $descriptionEn,
            'title_gr'       => $titleGr,
            'title_en'       => $titleEn,
            'description_gr' => $descriptionGr,
            'description_en' => $descriptionEn,
            'main_visual'    => $mainVisual,
            'cover_image'    => $request->input('cover_image'),
            'hashtags'       => $hashtags ?: null,
            'status'         => $validated['status'],
        ];

        $item->update($saveData);

        $this->syncProducts($item->id, $request->input('product_ids', []));

        flash(translate('Storefront updated successfully'))->success();
        return redirect()->route('seller.showcase.vitrin.index');
    }

    public function toggleStatus($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerVitrinItem($id);

        $item->status = $item->status === 'published' ? 'draft' : 'published';
        $item->save();

        flash(translate('Storefront status updated successfully'))->success();
        return redirect()->route('seller.showcase.vitrin.index');
    }

    public function destroy($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerVitrinItem($id);

        DB::table('showcase_products')->where('showcase_id', $item->id)->delete();
        $item->delete();

        flash(translate('Storefront deleted successfully'))->success();
        return redirect()->route('seller.showcase.vitrin.index');
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

        $productIds = $products->pluck('id')->toArray();
        $categoryRows = DB::table('product_categories as pc')
            ->join('categories as c', 'pc.category_id', '=', 'c.id')
            ->whereIn('pc.product_id', $productIds)
            ->select('pc.product_id', 'c.id as category_id', 'c.parent_id')
            ->get();

        $productCategoryMap = [];
        foreach ($categoryRows as $row) {
            if (!isset($productCategoryMap[$row->product_id])) {
                $productCategoryMap[$row->product_id] = [
                    'category_id' => $row->parent_id ?: $row->category_id,
                    'subcategory_id' => $row->parent_id ? $row->category_id : null,
                ];
            }
        }

        $showcaseCategories = Category::orderBy('name')->get();

        $selectedProducts = [];
        if ($item) {
            $selectedProducts = DB::table('showcase_products')
                ->where('showcase_id', $item->id)
                ->orderBy('sort_order')
                ->pluck('product_id')
                ->toArray();
        }

        return view('seller.showcase.vitrin.form', [
            'shop'             => $shop,
            'page_title'       => $pageTitle,
            'item'             => $item,
            'products'         => $products,
            'selectedProducts' => $selectedProducts,
            'showcaseCategories' => $showcaseCategories,
            'productCategoryMap' => $productCategoryMap,
        ]);
    }

    private function sellerVitrinItem($id): Showcase
    {
        return Showcase::where('id', $id)
            ->where('type', 'vitrin')
            ->where('seller_id', $this->shop()->id)
            ->firstOrFail();
    }

    private function syncProducts($showcaseId, array $productIds = [])
    {
        $allowedProductIds = DB::table('products')
            ->where('user_id', auth()->id())
            ->whereIn('id', $productIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        DB::table('showcase_products')->where('showcase_id', $showcaseId)->delete();

        $rows = [];
        foreach ($allowedProductIds as $index => $productId) {
            $rows[] = [
                'showcase_id' => $showcaseId,
                'product_id'  => $productId,
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
