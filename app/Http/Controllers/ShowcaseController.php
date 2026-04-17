<?php

namespace App\Http\Controllers;

use App\Models\Showcase;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowcaseController extends Controller
{
    public function history()
    {
        return $this->renderForm('history', 'Showcase Story', 'backend.showcase.history', null);
    }

    public function collection()
    {
        return $this->renderForm('collection', 'Showcase Collection', 'backend.showcase.collection', null);
    }
    public function vitrin()
    {
        return $this->renderForm('vitrin', 'Showcase Storefront', 'backend.showcase.vitrin', null);
    }

    public function launch()
    {
        return $this->renderForm('launch', 'Showcase Launch', 'backend.showcase.launch', null);
    }

    public function oru()
    {
        return redirect()->route('showcase.vitrin');
    }

    public function historyList()
    {
        return $this->renderList('history', 'Showcase Story List');
    }

    public function collectionList()
    {
        return $this->renderList('collection', 'Showcase Collection List');
    }

    public function vitrinList()
    {
        return $this->renderList('vitrin', 'Showcase Storefront List');
    }

    public function launchList()
    {
        return $this->renderList('launch', 'Showcase Launch List');
    }

    public function historyEdit($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'history')->firstOrFail();
        return $this->renderForm('history', 'Edit Showcase Story', 'backend.showcase.history', $item);
    }

    public function collectionEdit($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'collection')->firstOrFail();
        return $this->renderForm('collection', 'Edit Showcase Collection', 'backend.showcase.collection', $item);
    }

    public function vitrinEdit($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'vitrin')->firstOrFail();
        return $this->renderForm('vitrin', 'Edit Showcase Storefront', 'backend.showcase.vitrin', $item);
    }

    public function launchEdit($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'launch')->firstOrFail();
        return $this->renderForm('launch', 'Edit Showcase Launch', 'backend.showcase.launch', $item);
    }

    public function historyDelete($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'history')->firstOrFail();
        DB::table('showcase_products')->where('showcase_id', $item->id)->delete();
        $item->delete();

        return redirect()->route('showcase.history.list')->with('success', 'Showcase Story deleted successfully.');
    }

    public function historyToggleStatus($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'history')->firstOrFail();
        $item->status = $item->status === 'published' ? 'draft' : 'published';
        $item->save();

        return redirect()->route('showcase.history.list')->with('success', 'Story status updated successfully.');
    }

    public function collectionDelete($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'collection')->firstOrFail();
        DB::table('showcase_products')->where('showcase_id', $item->id)->delete();
        $item->delete();

        return redirect()->route('showcase.collection.list')->with('success', 'Showcase Collection deleted successfully.');
    }

    public function collectionToggleStatus($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'collection')->firstOrFail();
        $item->status = $item->status === 'published' ? 'draft' : 'published';
        $item->save();

        return redirect()->route('showcase.collection.list')->with('success', 'Collection status updated successfully.');
    }

    public function vitrinDelete($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'vitrin')->firstOrFail();
        DB::table('showcase_products')->where('showcase_id', $item->id)->delete();
        $item->delete();

        return redirect()->route('showcase.vitrin.list')->with('success', 'Showcase Storefront deleted successfully.');
    }

    public function vitrinToggleStatus($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'vitrin')->firstOrFail();
        $item->status = $item->status === 'published' ? 'draft' : 'published';
        $item->save();

        return redirect()->route('showcase.vitrin.list')->with('success', 'Storefront status updated successfully.');
    }

    public function launchDelete($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'launch')->firstOrFail();
        DB::table('showcase_products')->where('showcase_id', $item->id)->delete();
        $item->delete();

        return redirect()->route('showcase.launch.list')->with('success', 'Showcase Launch deleted successfully.');
    }

    public function launchToggleStatus($id)
    {
        $item = Showcase::where('id', $id)->where('type', 'launch')->firstOrFail();
        $item->status = $item->status === 'published' ? 'draft' : 'published';
        $item->save();

        return redirect()->route('showcase.launch.list')->with('success', 'Launch status updated successfully.');
    }

    public function storeHistory(Request $request)
    {
        $mainVisual = $request->input('story_video', $request->input('main_visual'));
        $posterData = trim((string) $request->input('poster_image_data', ''));
        $coverImage = $posterData !== '' ? $posterData : $request->input('cover_image');
        $hashtags = trim((string) $request->input('hashtags', ''));

        $validated = $request->validate([
            'seller_id'     => 'nullable|integer',
            'status'        => 'required|in:draft,published',
            'product_ids'   => 'required|array',
            'product_ids.*' => 'integer',
            'story_video'   => 'required',
            'main_visual'   => 'nullable',
            'cover_image'   => 'nullable',
        ]);

        $selectedProductId = (int) ($validated['product_ids'][0] ?? 0);
        $productName = $selectedProductId
            ? DB::table('products')->where('id', $selectedProductId)->value('name')
            : null;
        $fallbackTitle = $productName ?: translate('Story');

        $saveData = [
            'seller_id'      => $validated['seller_id'] ?? null,
            'type'           => 'history',

            'title'          => $fallbackTitle,
            'subtitle'       => null,
            'description'    => null,

            'title_gr'       => null,
            'title_en'       => null,
            'subtitle_gr'    => null,
            'subtitle_en'    => null,
            'description_gr' => null,
            'description_en' => null,

            'main_visual'    => $mainVisual ?: null,
            'cover_image'    => $coverImage ?: null,
            'hashtags'       => $hashtags ?: null,
            'status'         => $validated['status'],
        ];

        if ($request->filled('id')) {
            $item = Showcase::where('id', $request->id)
                ->where('type', 'history')
                ->firstOrFail();

            $item->update($saveData);
        } else {
            $item = Showcase::create($saveData);
        }

        $this->syncProducts($item->id, [$selectedProductId]);

        return redirect()
            ->route('showcase.history.list')
            ->with('success', 'Showcase Story saved successfully.');
    }

    public function storeCollection(Request $request)
    {
        $titleGr = trim((string) $request->input('title_gr', $request->input('title', '')));
        $titleEn = trim((string) $request->input('title_en', ''));

        $introGr = trim((string) $request->input('intro_gr', $request->input('intro', '')));
        $introEn = trim((string) $request->input('intro_en', ''));

        $descriptionGr = trim((string) $request->input('description_gr', $request->input('description', '')));
        $descriptionEn = trim((string) $request->input('description_en', ''));
        $hashtags = trim((string) $request->input('hashtags', ''));
        $hashtags = trim((string) $request->input('hashtags', ''));

        $validated = $request->validate([
            'seller_id'         => 'nullable|integer',
            'status'            => 'required|in:draft,published',
            'billing_period'    => 'nullable|in:monthly,yearly',
            'cover_image'       => 'nullable',
            'product_ids'       => 'nullable|array',
            'product_ids.*'     => 'integer',
            'collection_items'  => 'nullable|array',
        ]);

        if ($titleGr === '' && $titleEn === '') {
            return back()
                ->withInput()
                ->withErrors(['title_gr' => 'Βάλε τουλάχιστον έναν τίτλο.']);
        }

        $collectionItems = $this->normalizeCollectionItems($request->input('collection_items', []));

        if (empty($collectionItems) && is_array($request->input('product_ids'))) {
            $collectionItems = $this->buildLegacyCollectionItemsFromProducts($request->input('product_ids', []));
        }

        $saveData = [
            'seller_id'             => $validated['seller_id'] ?? null,
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
            'hashtags'              => $hashtags ?: null,
            'collection_items_json' => !empty($collectionItems)
                ? json_encode($collectionItems, JSON_UNESCAPED_UNICODE)
                : null,
            'billing_period'        => $validated['billing_period'] ?? null,
            'status'                => $validated['status'],
        ];

        if ($request->filled('id')) {
            $item = Showcase::where('id', $request->id)
                ->where('type', 'collection')
                ->firstOrFail();

            $item->update($saveData);
        } else {
            $item = Showcase::create($saveData);
        }

        $this->syncProducts(
            $item->id,
            $this->extractProductIdsFromCollectionItems($collectionItems)
        );

        return redirect()
            ->route('showcase.collection.list')
            ->with('success', 'Showcase Collection saved successfully.');
    }

    public function storeVitrin(Request $request)
    {
        $titleGr = trim((string) $request->input('title_gr', $request->input('title', '')));
        $titleEn = trim((string) $request->input('title_en', ''));

        $descriptionGr = trim((string) $request->input('description_gr', $request->input('description', '')));
        $descriptionEn = trim((string) $request->input('description_en', ''));
        $hashtags = trim((string) $request->input('hashtags', ''));

        $validated = $request->validate([
            'seller_id'     => 'nullable|integer',
            'status'        => 'required|in:draft,published',
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'integer',
            'main_visual'   => 'nullable',
            'cover_image'   => 'nullable',
        ]);

        if ($titleGr === '' && $titleEn === '') {
            return back()
                ->withInput()
                ->withErrors(['title_gr' => 'Βάλε τουλάχιστον έναν τίτλο.']);
        }

        $saveData = [
            'seller_id'       => $validated['seller_id'] ?? null,
            'type'            => 'vitrin',

            'title'           => $titleGr ?: $titleEn,
            'description'     => $descriptionGr ?: $descriptionEn,

            'title_gr'        => $titleGr ?: null,
            'title_en'        => $titleEn ?: null,
            'description_gr'  => $descriptionGr ?: null,
            'description_en'  => $descriptionEn ?: null,

            'main_visual'     => $request->input('main_visual') ?: null,
            'cover_image'     => $request->input('cover_image') ?: null,
            'hashtags'        => $hashtags ?: null,
            'status'          => $validated['status'],
        ];

        if ($request->filled('id')) {
            $item = Showcase::where('id', $request->id)
                ->where('type', 'vitrin')
                ->firstOrFail();

            $item->update($saveData);
        } else {
            $item = Showcase::create($saveData);
        }

        $this->syncProducts($item->id, $request->input('product_ids', []));

        return redirect()
            ->route('showcase.vitrin.list')
            ->with('success', 'Showcase Storefront saved successfully.');
    }

    public function storeLaunch(Request $request)
    {
        $titleGr = trim((string) $request->input('title_gr', $request->input('title', '')));
        $titleEn = trim((string) $request->input('title_en', ''));

        $subtitleGr = trim((string) $request->input('subtitle_gr', $request->input('subtitle', '')));
        $subtitleEn = trim((string) $request->input('subtitle_en', ''));

        $descriptionGr = trim((string) $request->input('description_gr', $request->input('description', '')));
        $descriptionEn = trim((string) $request->input('description_en', ''));

        $hashtags = trim((string) $request->input('hashtags', ''));

        $validated = $request->validate([
            'seller_id'     => 'nullable|integer',
            'status'        => 'required|in:draft,published',
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'integer',
            'main_visual'   => 'nullable',
            'cover_image'   => 'nullable',
        ]);

        if ($titleGr === '' && $titleEn === '') {
            return back()
                ->withInput()
                ->withErrors(['title_gr' => 'Î’Î¬Î»Îµ Ï„Î¿Ï…Î»Î¬Ï‡Î¹ÏƒÏ„Î¿Î½ Î­Î½Î±Î½ Ï„Î¯Ï„Î»Î¿.']);
        }

        if (empty($validated['main_visual'])) {
            return back()
                ->withInput()
                ->withErrors(['main_visual' => 'Î Î±ÏÎ±ÎºÎ±Î»ÏŽ Î±Î½ÎµÎ²Î¬ÏƒÏ„Îµ Ï„Î¿ Î²Î±ÏƒÎ¹ÎºÏŒ Ï€Î¿Î»Ï…Î¼Î­ÏƒÎ¿.']);
        }

        $selectedProductIds = $request->input('product_ids', []);
        $selectedProductIds = is_array($selectedProductIds) ? $selectedProductIds : [];
        $selectedProductId = !empty($selectedProductIds) ? (int) $selectedProductIds[0] : null;

        if (empty($selectedProductId)) {
            return back()
                ->withInput()
                ->withErrors(['product_ids' => 'Î Î±ÏÎ±ÎºÎ±Î»ÏŽ ÎµÏ€Î¹Î»Î­Î¾Ï„Îµ Î­Î½Î± Ï€ÏÎ¿ÏŠÏŒÎ½.']);
        }

        $saveData = [
            'seller_id'      => $validated['seller_id'] ?? null,
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
            'cover_image'    => $request->input('cover_image') ?: null,
            'hashtags'       => $hashtags ?: null,
            'status'         => $validated['status'],
        ];

        if ($request->filled('id')) {
            $item = Showcase::where('id', $request->id)
                ->where('type', 'launch')
                ->firstOrFail();

            $item->update($saveData);
        } else {
            $item = Showcase::create($saveData);
        }

        $this->syncProducts($item->id, [$selectedProductId]);

        return redirect()
            ->route('showcase.launch.list')
            ->with('success', 'Showcase Launch saved successfully.');
    }

    public function storeOru(Request $request)
    {
        return $this->storeVitrin($request);
    }

    private function renderForm($section, $pageTitle, $view, $item = null)
    {
        $products = DB::table('products')
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

        $collectionItems = $this->resolveCollectionItemsForForm($item, $selectedProducts);

        return view($view, [
            'page_title'       => $pageTitle,
            'section'          => $section,
            'item'             => $item,
            'products'         => $products,
            'selectedProducts' => $selectedProducts,
            'showcaseCategories' => $showcaseCategories,
            'productCategoryMap' => $productCategoryMap,
            'collectionItems'  => $collectionItems,
        ]);
    }

    private function renderList($type, $pageTitle)
    {
        $items = Showcase::where('type', $type)->latest()->get();

        return view('backend.showcase.list', [
            'page_title' => $pageTitle,
            'section'    => $type,
            'items'      => $items,
        ]);
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

        $allowedProductIds = DB::table('products')
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
                $titleGr === '' &&
                $titleEn === '' &&
                $descriptionGr === '' &&
                $descriptionEn === '' &&
                $coverImage === '' &&
                empty($productId)
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
        $allowedProductIds = DB::table('products')
            ->whereIn('id', $productIds)
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
}
