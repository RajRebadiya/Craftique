<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Seller\Concerns\EnforcesShowcasePackageLimits;
use App\Models\Showcase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ShowcaseHistoryController extends Controller
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
            ->where('type', 'history')
            ->latest()
            ->paginate(15);

        return view('seller.showcase.history.index', [
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
		$typeRedirect = $this->ensureShowcaseTypeAllowed('history');
if ($typeRedirect) {
    return $typeRedirect;
}		

        $limitRedirect = $this->ensureShowcaseCreationAllowed();
        if ($limitRedirect) {
            return $limitRedirect;
        }

        return $this->renderForm('Create Story');
    }

    public function edit($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerHistoryItem($id);

        return $this->renderForm('Edit Story', $item);
    }

    public function store(Request $request)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }
		
		$typeRedirect = $this->ensureShowcaseTypeAllowed('history');
if ($typeRedirect) {
    return $typeRedirect;
}

        $limitRedirect = $this->ensureShowcaseCreationAllowed();
        if ($limitRedirect) {
            return $limitRedirect;
        }

        $shop = $this->shop();

        $hashtags = trim((string) $request->input('hashtags', ''));
        $posterData = trim((string) $request->input('poster_image_data', ''));

        $productRule = Rule::exists('products', 'id');
        if (!app()->environment('local')) {
            $productRule = $productRule->where(function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        $validated = $request->validate([
            'story_video'   => 'required',
            'cover_image'   => 'nullable',
            'status'        => 'required|in:draft,published',
            'product_id'    => [
                'required',
                $productRule,
            ],
        ]);

        if (empty($validated['story_video'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'story_video' => translate('Please upload a Story video.')
                ]);
        }

        $productName = DB::table('products')
            ->where('id', (int) $validated['product_id'])
            ->value('name');

        $fallbackTitle = $productName ?: translate('Story');

        $saveData = [
            'seller_id'       => $shop->id,
            'type'            => 'history',
            'title'           => $fallbackTitle,
            'subtitle'        => null,
            'description'     => null,
            'title_gr'        => null,
            'title_en'        => null,
            'subtitle_gr'     => null,
            'subtitle_en'     => null,
            'description_gr'  => null,
            'description_en'  => null,
            'main_visual'     => $validated['story_video'],
            'cover_image'     => $posterData !== '' ? $posterData : $request->input('cover_image'),
            'hashtags'        => $hashtags ?: null,
            'status'          => $validated['status'],
        ];

        $item = Showcase::create($saveData);

        $this->syncProduct($item->id, (int) $validated['product_id']);

        flash(translate('Story saved successfully'))->success();
        return redirect()->route('seller.showcase.history.index');
    }

    public function update(Request $request, $id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerHistoryItem($id);

        $hashtags = trim((string) $request->input('hashtags', ''));
        $posterData = trim((string) $request->input('poster_image_data', ''));

        $productRule = Rule::exists('products', 'id');
        if (!app()->environment('local')) {
            $productRule = $productRule->where(function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        $validated = $request->validate([
            'story_video'   => 'nullable',
            'cover_image'   => 'nullable',
            'status'        => 'required|in:draft,published',
            'product_id'    => [
                'required',
                $productRule,
            ],
        ]);

        $storyVideo = $validated['story_video'] ?? $item->main_visual;

        if (empty($storyVideo)) {
            return back()
                ->withInput()
                ->withErrors([
                    'story_video' => translate('Please upload a Story video.')
                ]);
        }

        $productName = DB::table('products')
            ->where('id', (int) $validated['product_id'])
            ->value('name');

        $fallbackTitle = $productName ?: translate('Story');

        $item->update([
            'title'           => $fallbackTitle,
            'subtitle'        => null,
            'description'     => null,
            'title_gr'        => null,
            'title_en'        => null,
            'subtitle_gr'     => null,
            'subtitle_en'     => null,
            'description_gr'  => null,
            'description_en'  => null,
            'main_visual'     => $storyVideo,
            'cover_image'     => $posterData !== '' ? $posterData : $request->input('cover_image'),
            'hashtags'        => $hashtags ?: null,
            'status'          => $validated['status'],
        ]);

        $this->syncProduct($item->id, (int) $validated['product_id']);

        flash(translate('Story updated successfully'))->success();
        return redirect()->route('seller.showcase.history.index');
    }

    public function toggleStatus($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerHistoryItem($id);

        $item->status = $item->status === 'published' ? 'draft' : 'published';
        $item->save();

        flash(translate('Story status updated successfully'))->success();
        return redirect()->route('seller.showcase.history.index');
    }

    public function destroy($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerHistoryItem($id);

        DB::table('showcase_products')->where('showcase_id', $item->id)->delete();
        $item->delete();

        flash(translate('Story deleted successfully'))->success();
        return redirect()->route('seller.showcase.history.index');
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

        $selectedProductId = null;
        if ($item) {
            $selectedProductId = DB::table('showcase_products')
                ->where('showcase_id', $item->id)
                ->orderBy('sort_order')
                ->value('product_id');
        }

        return view('seller.showcase.history.form', [
            'shop'             => $shop,
            'page_title'       => $pageTitle,
            'item'             => $item,
            'products'         => $products,
            'selectedProductId'=> $selectedProductId,
        ]);
    }

    private function sellerHistoryItem($id): Showcase
    {
        return Showcase::where('id', $id)
            ->where('type', 'history')
            ->where('seller_id', $this->shop()->id)
            ->firstOrFail();
    }

    private function syncProduct($showcaseId, int $productId)
    {
        $allowedQuery = DB::table('products')->where('id', $productId);
        if (!app()->environment('local')) {
            $allowedQuery->where('user_id', auth()->id());
        }
        $allowedProductId = $allowedQuery->value('id');

        DB::table('showcase_products')->where('showcase_id', $showcaseId)->delete();

        if (!empty($allowedProductId)) {
            DB::table('showcase_products')->insert([
                'showcase_id' => $showcaseId,
                'product_id'  => (int) $allowedProductId,
                'sort_order'  => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
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
