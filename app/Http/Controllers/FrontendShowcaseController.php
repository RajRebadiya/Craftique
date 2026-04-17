<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FrontendShowcaseController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderFeed($request);
    }

    public function brand(Request $request, $slug)
    {
        if (get_setting('vendor_system_activation') != 1) {
            return redirect()->route('home');
        }

        $shop = Shop::where('slug', $slug)->firstOrFail();

        return $this->renderFeed($request, $shop);
    }

    public function history(Request $request)
    {
        $request->merge(['type' => 'history']);
        return $this->renderFeed($request);
    }

    public function collection(Request $request)
    {
        $request->merge(['type' => 'collection']);
        return $this->renderFeed($request);
    }

    public function vitrin(Request $request)
    {
        $request->merge(['type' => 'vitrin']);
        return $this->renderFeed($request);
    }

    public function launch(Request $request)
    {
        $request->merge(['type' => 'launch']);
        return $this->renderFeed($request);
    }

    public function show($id, $slug = null)
    {
        $item = DB::table('showcases')
            ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
            ->where('showcases.id', $id)
            ->where('showcases.status', 'published')
            ->select(
                'showcases.*',
                'shops.name as seller_name',
                'shops.slug as seller_slug'
            )
            ->first();

        abort_unless($item, 404);

        $item = $this->applyLocaleFields($item);

        $products = $this->getShowcaseProducts($item->id);
        $collectionItems = $item->type === 'collection'
            ? $this->getCollectionItems($item->id)
            : collect();

        $prevItem = $this->getAdjacentShowcaseItem($item, 'prev');
        $nextItem = $this->getAdjacentShowcaseItem($item, 'next');

        return view('frontend.showcase.post', [
            'item'            => $item,
            'products'        => $products,
            'collectionItems' => $collectionItems,
            'previousPost'    => $prevItem,
            'nextPost'        => $nextItem,
            'prevItem'        => $prevItem,
            'nextItem'        => $nextItem,
        ]);
    }

    private function renderFeed(Request $request, ?Shop $shop = null)
    {
        $filters = $this->resolveFeedFilters($request);

        $query = DB::table('showcases')
            ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
            ->where('showcases.status', 'published');

        if ($shop) {
            $query->where('showcases.seller_id', $shop->id);
        }

        $query = $this->applyFeedFilters($query, $filters);

        $showcaseItems = $query
            ->select(
                'showcases.*',
                'shops.name as seller_name',
                'shops.slug as seller_slug'
            )
            ->paginate(12)
            ->appends($request->query());

        $showcaseItems->getCollection()->transform(function ($item) {
            return $this->applyLocaleFields($item);
        });

        $locale = app()->getLocale();

        return view('frontend.showcase.index', [
            'shop'          => $shop,
            'showcaseItems' => $showcaseItems,
            'pageTitle'     => $shop ? $shop->name : 'Showcase',
            'pageSubtitle'  => $shop
                ? ($locale === 'en'
                    ? 'Published showcase posts from this creator'
                    : 'Δημοσιευμένες αναρτήσεις showcase από αυτόν τον δημιουργό')
                : ($locale === 'en'
                    ? 'Stories, collections, storefronts and launches'
                    : 'Ιστορίες, συλλογές και βιτρίνες δημιουργών'),
            'isBrandPage'   => (bool) $shop,
            'filters'       => $filters,
        ]);
    }

    private function getCollectionItems(int $showcaseId): Collection
    {
        $raw = DB::table('showcases')
            ->where('id', $showcaseId)
            ->value('collection_items_json');

        if (empty($raw)) {
            return collect();
        }

        $decoded = is_array($raw) ? $raw : json_decode($raw, true);

        if (!is_array($decoded) || empty($decoded)) {
            return collect();
        }

        $productIds = collect($decoded)
            ->pluck('product_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();

        $products = collect();

        if (!empty($productIds)) {
            $products = DB::table('products')
                ->whereIn('id', $productIds)
                ->select(
                    'id',
                    'name',
                    'slug',
                    'thumbnail_img',
                    'unit_price',
                    'discount',
                    'discount_type'
                )
                ->get()
                ->keyBy('id');
        }

        $locale = app()->getLocale();

        return collect($decoded)
            ->sortBy('sort_order')
            ->values()
            ->map(function ($row, $index) use ($products, $locale) {
                $row = is_array($row) ? $row : [];

                $productId = !empty($row['product_id']) ? (int) $row['product_id'] : null;
                $product = $productId && $products->has($productId) ? $products->get($productId) : null;

                $title = $locale === 'en'
                    ? (($row['title_en'] ?? null) ?: ($row['title_gr'] ?? null) ?: ($row['title'] ?? null))
                    : (($row['title_gr'] ?? null) ?: ($row['title_en'] ?? null) ?: ($row['title'] ?? null));

                $description = $locale === 'en'
                    ? (($row['description_en'] ?? null) ?: ($row['description_gr'] ?? null) ?: ($row['description'] ?? null))
                    : (($row['description_gr'] ?? null) ?: ($row['description_en'] ?? null) ?: ($row['description'] ?? null));

                return (object) [
                    'row_id'      => $index,
                    'title'       => $title,
                    'description' => $description,
                    'cover_image' => $row['cover_image'] ?? null,
                    'product_id'  => $productId,
                    'product'     => $product,
                    'sort_order'  => (int) ($row['sort_order'] ?? $index),
                ];
            })
            ->filter(function ($row) {
                return !empty($row->title)
                    || !empty($row->description)
                    || !empty($row->cover_image)
                    || !empty($row->product);
            })
            ->values();
    }

    private function resolveFeedFilters(Request $request): array
    {
        $allowedTypes = ['all', 'history', 'collection', 'vitrin', 'launch'];
        $allowedSorts = ['newest', 'oldest'];

        $type = $request->get('type', 'all');
        $sort = $request->get('sort', 'newest');

        if (!in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'newest';
        }

        return [
            'type' => $type,
            'sort' => $sort,
        ];
    }

    private function applyFeedFilters($query, array $filters)
    {
        if (($filters['type'] ?? 'all') !== 'all') {
            $query->where('showcases.type', $filters['type']);
        }

        if (($filters['sort'] ?? 'newest') === 'oldest') {
            $query->orderBy('showcases.created_at', 'asc');
        } else {
            $query->orderBy('showcases.created_at', 'desc');
        }

        return $query;
    }

    private function getAdjacentShowcaseItem($item, string $direction = 'next')
    {
        $query = DB::table('showcases')
            ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
            ->where('showcases.status', 'published')
            ->where('showcases.type', $item->type)
            ->where('showcases.id', '!=', $item->id);

        if ($direction === 'prev') {
            $query->where(function ($q) use ($item) {
                $q->where('showcases.created_at', '<', $item->created_at)
                    ->orWhere(function ($q2) use ($item) {
                        $q2->where('showcases.created_at', $item->created_at)
                            ->where('showcases.id', '<', $item->id);
                    });
            })
            ->orderBy('showcases.created_at', 'desc')
            ->orderBy('showcases.id', 'desc');
        } else {
            $query->where(function ($q) use ($item) {
                $q->where('showcases.created_at', '>', $item->created_at)
                    ->orWhere(function ($q2) use ($item) {
                        $q2->where('showcases.created_at', $item->created_at)
                            ->where('showcases.id', '>', $item->id);
                    });
            })
            ->orderBy('showcases.created_at', 'asc')
            ->orderBy('showcases.id', 'asc');
        }

        $adjacentItem = $query
            ->select(
                'showcases.*',
                'shops.name as seller_name',
                'shops.slug as seller_slug'
            )
            ->first();

        return $adjacentItem ? $this->applyLocaleFields($adjacentItem) : null;
    }

    private function applyLocaleFields($item)
    {
        $locale = app()->getLocale();

        $item->title = $locale === 'en'
            ? ($item->title_en ?: $item->title_gr ?: $item->title)
            : ($item->title_gr ?: $item->title_en ?: $item->title);

        $item->subtitle = $locale === 'en'
            ? (($item->subtitle_en ?? null) ?: ($item->subtitle_gr ?? null) ?: ($item->subtitle ?? null))
            : (($item->subtitle_gr ?? null) ?: ($item->subtitle_en ?? null) ?: ($item->subtitle ?? null));

        $item->intro = $locale === 'en'
            ? (($item->intro_en ?? null) ?: ($item->intro_gr ?? null) ?: ($item->intro ?? null))
            : (($item->intro_gr ?? null) ?: ($item->intro_en ?? null) ?: ($item->intro ?? null));

        $item->description = $locale === 'en'
            ? ($item->description_en ?: $item->description_gr ?: $item->description)
            : ($item->description_gr ?: $item->description_en ?: $item->description);

        return $item;
    }

    private function getShowcaseProducts($showcaseId): Collection
    {
        return DB::table('showcase_products')
            ->join('products', 'showcase_products.product_id', '=', 'products.id')
            ->where('showcase_products.showcase_id', $showcaseId)
            ->orderBy('showcase_products.sort_order')
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'products.thumbnail_img',
                'products.unit_price',
                'products.discount',
                'products.discount_type'
            )
            ->get();
    }
}
