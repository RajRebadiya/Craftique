<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Seller\Concerns\EnforcesShowcasePackageLimits;
use App\Models\Showcase;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ShowcaseLaunchController extends Controller
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
            ->where('type', 'launch')
            ->latest()
            ->paginate(15);

        return view('seller.showcase.launch.index', [
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

        $limitRedirect = $this->ensureShowcaseCreationAllowed();
        if ($limitRedirect) {
            return $limitRedirect;
        }

        return $this->renderForm('Create Launch');
    }

    public function edit($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerLaunchItem($id);

        return $this->renderForm('Edit Launch', $item);
    }

    public function store(Request $request)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $limitRedirect = $this->ensureShowcaseCreationAllowed();
        if ($limitRedirect) {
            return $limitRedirect;
        }

        $shop = $this->shop();

        $titleGr = trim((string) $request->input('title_gr', $request->input('title', '')));
        $titleEn = trim((string) $request->input('title_en', ''));

        $subtitleGr = trim((string) $request->input('subtitle_gr', $request->input('subtitle', '')));
        $subtitleEn = trim((string) $request->input('subtitle_en', ''));

        $descriptionGr = trim((string) $request->input('description_gr', $request->input('description', '')));
        $descriptionEn = trim((string) $request->input('description_en', ''));
        $hashtags = trim((string) $request->input('hashtags', ''));
        $posterData = trim((string) $request->input('poster_image_data', ''));

        $productRule = Rule::exists('products', 'id');
        if (!app()->environment('local')) {
            $productRule = $productRule->where(function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        $validated = $request->validate([
            'main_visual' => ['required'],
            'cover_image' => ['nullable'],
            'status'      => ['required', 'in:draft,published'],
            'product_id'  => [
                'required',
                $productRule,
            ],
        ]);

        if ($titleGr === '' && $titleEn === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'title_gr' => translate('Please enter at least one title in Greek or English.'),
                ]);
        }

        if ($launchMediaError = $this->validateLaunchMediaRequirements($request, $validated['main_visual'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'main_visual' => $launchMediaError,
                ]);
        }

        $saveData = [
            'seller_id'      => $shop->id,
            'type'           => 'launch',
            'title'          => $titleGr ?: $titleEn,
            'subtitle'       => $subtitleGr ?: $subtitleEn,
            'description'    => $descriptionGr ?: $descriptionEn,
            'title_gr'       => $titleGr ?: null,
            'title_en'       => $titleEn ?: null,
            'subtitle_gr'    => $subtitleGr ?: null,
            'subtitle_en'    => $subtitleEn ?: null,
            'description_gr' => $descriptionGr ?: null,
            'description_en' => $descriptionEn ?: null,
            'main_visual'    => $request->input('main_visual') ?: null,
            'cover_image'    => $posterData !== '' ? $posterData : ($request->input('cover_image') ?: null),
            'hashtags'       => $hashtags ?: null,
            'status'         => $validated['status'],
        ];

        $item = Showcase::create($saveData);

        $this->syncProduct($item->id, (int) $validated['product_id']);

        flash(translate('Launch saved successfully'))->success();
        return redirect()->route('seller.showcase.launch.index');
    }

    public function update(Request $request, $id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerLaunchItem($id);

        $titleGr = trim((string) $request->input('title_gr', $request->input('title', '')));
        $titleEn = trim((string) $request->input('title_en', ''));

        $subtitleGr = trim((string) $request->input('subtitle_gr', $request->input('subtitle', '')));
        $subtitleEn = trim((string) $request->input('subtitle_en', ''));

        $descriptionGr = trim((string) $request->input('description_gr', $request->input('description', '')));
        $descriptionEn = trim((string) $request->input('description_en', ''));
        $hashtags = trim((string) $request->input('hashtags', ''));
        $posterData = trim((string) $request->input('poster_image_data', ''));

        $productRule = Rule::exists('products', 'id');
        if (!app()->environment('local')) {
            $productRule = $productRule->where(function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        $validated = $request->validate([
            'main_visual' => ['nullable'],
            'cover_image' => ['nullable'],
            'status'      => ['required', 'in:draft,published'],
            'product_id'  => [
                'required',
                $productRule,
            ],
        ]);

        if ($titleGr === '' && $titleEn === '') {
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
                    'main_visual' => translate('Please upload the main Launch media.'),
                ]);
        }

        if ($launchMediaError = $this->validateLaunchMediaRequirements($request, $mainVisual)) {
            return back()
                ->withInput()
                ->withErrors([
                    'main_visual' => $launchMediaError,
                ]);
        }

        $item->update([
            'title'          => $titleGr ?: $titleEn,
            'subtitle'       => $subtitleGr ?: $subtitleEn,
            'description'    => $descriptionGr ?: $descriptionEn,
            'title_gr'       => $titleGr ?: null,
            'title_en'       => $titleEn ?: null,
            'subtitle_gr'    => $subtitleGr ?: null,
            'subtitle_en'    => $subtitleEn ?: null,
            'description_gr' => $descriptionGr ?: null,
            'description_en' => $descriptionEn ?: null,
            'main_visual'    => $mainVisual,
            'cover_image'    => $posterData !== '' ? $posterData : ($request->input('cover_image') ?: null),
            'hashtags'       => $hashtags ?: null,
            'status'         => $validated['status'],
        ]);

        $this->syncProduct($item->id, (int) $validated['product_id']);

        flash(translate('Launch updated successfully'))->success();
        return redirect()->route('seller.showcase.launch.index');
    }

    public function toggleStatus($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerLaunchItem($id);

        $item->status = $item->status === 'published' ? 'draft' : 'published';
        $item->save();

        flash(translate('Launch status updated successfully'))->success();
        return redirect()->route('seller.showcase.launch.index');
    }

    public function destroy($id)
    {
        $redirect = $this->ensureActivePackage();
        if ($redirect) {
            return $redirect;
        }

        $item = $this->sellerLaunchItem($id);

        DB::table('showcase_products')->where('showcase_id', $item->id)->delete();
        $item->delete();

        flash(translate('Launch deleted successfully'))->success();
        return redirect()->route('seller.showcase.launch.index');
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

        return view('seller.showcase.launch.form', [
            'shop'               => $shop,
            'page_title'         => $pageTitle,
            'item'               => $item,
            'products'           => $products,
            'selectedProductId'  => $selectedProductId,
        ]);
    }

    private function sellerLaunchItem($id): Showcase
    {
        return Showcase::where('id', $id)
            ->where('type', 'launch')
            ->where('seller_id', $this->shop()->id)
            ->firstOrFail();
    }

    private function syncProduct($showcaseId, int $productId): void
    {
        $allowedQuery = DB::table('products')->where('id', $productId);
        if (!app()->environment('local')) {
            $allowedQuery->where('user_id', auth()->id());
        }
        $allowedProductId = $allowedQuery->value('id');

        DB::table('showcase_products')->where('showcase_id', $showcaseId)->delete();

        if (empty($allowedProductId)) {
            return;
        }

        DB::table('showcase_products')->insert([
            [
                'showcase_id' => $showcaseId,
                'product_id'  => (int) $allowedProductId,
                'sort_order'  => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    private function validateLaunchMediaRequirements(Request $request, $mediaValue): ?string
    {
        [$width, $height] = $this->resolveLaunchMediaDimensions($request, $mediaValue);

        if (empty($width) || empty($height)) {
            return translate('Launch main visual must use a 16:9 HD landscape ratio such as 1280x720 or 1920x1080.');
        }

        if ((int) $width < 1280 || (int) $height < 720) {
            return translate('Launch main visual must be at least 1280x720 in a 16:9 landscape ratio.');
        }

        $ratio = (float) $width / max(1, (float) $height);
        if (abs($ratio - (16 / 9)) > 0.02) {
            return translate('Launch main visual must use a 16:9 HD landscape ratio such as 1280x720 or 1920x1080.');
        }

        return null;
    }

    private function resolveLaunchMediaDimensions(Request $request, $mediaValue): array
    {
        $width = (int) $request->input('launch_media_width');
        $height = (int) $request->input('launch_media_height');

        if ($width > 0 && $height > 0) {
            return [$width, $height];
        }

        return $this->getMediaImageDimensions($mediaValue);
    }

    private function getMediaImageDimensions($mediaValue): array
    {
        $filePath = $this->resolveMediaFilePath($mediaValue);
        if (empty($filePath) || !is_file($filePath)) {
            return [null, null];
        }

        $imageSize = @getimagesize($filePath);
        if (!$imageSize || empty($imageSize[0]) || empty($imageSize[1])) {
            return [null, null];
        }

        return [(int) $imageSize[0], (int) $imageSize[1]];
    }

    private function resolveMediaFilePath($mediaValue): ?string
    {
        $value = trim((string) $mediaValue);
        if ($value === '') {
            return null;
        }

        $parts = explode(',', $value);
        $first = trim((string) ($parts[0] ?? ''));
        if ($first === '') {
            return null;
        }

        if (is_numeric($first)) {
            $upload = Upload::find((int) $first);
            $first = $upload?->file_name ?: '';
        }

        if ($first === '' || filter_var($first, FILTER_VALIDATE_URL)) {
            return null;
        }

        return public_path(ltrim(str_replace('\\', '/', $first), '/'));
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
