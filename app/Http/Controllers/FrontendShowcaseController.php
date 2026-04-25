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

    public function storyPage(Request $request)
    {
        $stories = DB::table('showcases')
            ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
            ->where('showcases.status', 'published')
            ->where('showcases.type', 'history')
            ->orderByDesc('showcases.created_at')
            ->select(
                'showcases.*',
                'shops.id as seller_shop_id',
                'shops.name as seller_name',
                'shops.slug as seller_slug',
                'shops.logo as seller_logo'
            )
            ->limit(30)
            ->get()
            ->map(function ($item) {
                return $this->applyLocaleFields($item);
            });

        $storyIds = $stories->pluck('id')->filter()->values()->all();
        $groupedProducts = collect();

        if (!empty($storyIds)) {
            $groupedProducts = DB::table('showcase_products')
                ->join('products', 'showcase_products.product_id', '=', 'products.id')
                ->whereIn('showcase_products.showcase_id', $storyIds)
                ->orderBy('showcase_products.sort_order')
                ->select(
                    'showcase_products.showcase_id',
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.thumbnail_img',
                    'products.unit_price',
                    'products.discount',
                    'products.discount_type'
                )
                ->get()
                ->groupBy('showcase_id');
        }

        $storyFeed = $stories->map(function ($item) use ($groupedProducts) {
            $rawVisual = ($item->main_visual ?? null) ?: ($item->cover_image ?? null);
            $mediaUrl = $this->resolveAssetUrl($rawVisual);
            $extension = strtolower(pathinfo(parse_url($mediaUrl ?? '', PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
            $isVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);

            $hashtags = !empty($item->hashtags)
                ? collect(explode(',', $item->hashtags))->map(fn ($tag) => trim($tag))->filter()->values()
                : collect();

            $products = collect($groupedProducts->get($item->id, collect()))
                ->map(function ($product) {
                    $finalPrice = $product->unit_price;
                    if (!empty($product->discount) && !empty($product->discount_type)) {
                        if ($product->discount_type === 'percent') {
                            $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                        } elseif ($product->discount_type === 'amount') {
                            $finalPrice = $product->unit_price - $product->discount;
                        }
                    }

                    $finalPrice = max(0, $finalPrice);

                    return (object) [
                        'id' => (int) $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'thumbnail_url' => $this->resolveAssetUrl($product->thumbnail_img ?? null),
                        'price_html' => single_price($finalPrice),
                        'product_url' => !empty($product->slug) ? route('product', $product->slug) : null,
                    ];
                })
                ->values();

            return (object) [
                'id' => (int) $item->id,
                'title' => $item->title ?: translate('Story'),
                'subtitle' => $item->subtitle ?: '',
                'description' => $item->description ?: ($item->intro ?: ''),
                'hashtags' => $hashtags->all(),
                'seller_name' => $item->seller_name ?: translate('The Shop'),
                'seller_shop_id' => $item->seller_shop_id ?: null,
                'seller_slug' => $item->seller_slug ?: null,
                'seller_logo_url' => $this->resolveAssetUrl($item->seller_logo ?? null),
                'media_url' => $mediaUrl,
                'media_is_video' => $isVideo,
                'created_at' => $item->created_at,
                'post_url' => route('frontend.showcase.post', [
                    'id' => $item->id,
                    'slug' => \Illuminate\Support\Str::slug($item->title ?: $item->id),
                ]),
                'products' => $products,
                'primary_product' => $products->first(),
            ];
        })->values();

        return view('frontend.showcase.story_page', [
            'stories' => $storyFeed,
            'initialStoryId' => (int) ($request->get('story') ?: optional($storyFeed->first())->id),
        ]);
    }

    public function collection(Request $request)
    {
        $request->merge(['type' => 'collection']);
        return $this->renderFeed($request);
    }

    public function collectionPage(Request $request)
    {
        $collections = DB::table('showcases')
            ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
            ->where('showcases.status', 'published')
            ->where('showcases.type', 'collection')
            ->orderByDesc('showcases.created_at')
            ->select(
                'showcases.*',
                'shops.id as seller_shop_id',
                'shops.name as seller_name',
                'shops.slug as seller_slug',
                'shops.logo as seller_logo'
            )
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return $this->applyLocaleFields($item);
            });

        $collectionFeed = $collections->map(function ($item) {
            $hashtags = !empty($item->hashtags)
                ? collect(explode(',', $item->hashtags))->map(fn ($tag) => trim($tag))->filter()->values()->all()
                : [];

            $items = $this->getCollectionItems($item->id)->map(function ($row) {
                $coverUrl = $this->resolveAssetUrl($row->cover_image ?? null);
                $product = $row->product;
                $priceHtml = '';
                $thumbUrl = null;
                $productId = null;
                $productUrl = null;
                $productName = '';

                if ($product) {
                    $finalPrice = $product->unit_price;
                    if (!empty($product->discount) && !empty($product->discount_type)) {
                        if ($product->discount_type === 'percent') {
                            $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                        } elseif ($product->discount_type === 'amount') {
                            $finalPrice = $product->unit_price - $product->discount;
                        }
                    }
                    $finalPrice = max(0, $finalPrice);
                    $priceHtml = single_price($finalPrice);
                    $thumbUrl = $this->resolveAssetUrl($product->thumbnail_img ?? null);
                    $productId = (int) $product->id;
                    $productUrl = !empty($product->slug) ? route('product', $product->slug) : null;
                    $productName = $product->name ?? '';
                }

                return (object) [
                    'title' => $row->title ?: translate('Collection Item'),
                    'description' => $row->description ?: '',
                    'cover_image_url' => $coverUrl ?: $thumbUrl,
                    'product_id' => $productId,
                    'product_url' => $productUrl,
                    'product_name' => $productName,
                    'product_thumb_url' => $thumbUrl,
                    'price_html' => $priceHtml,
                ];
            })->values();

            return (object) [
                'id' => (int) $item->id,
                'title' => $item->title ?: translate('Collection'),
                'subtitle' => $item->subtitle ?: '',
                'description' => $item->description ?: ($item->intro ?: ''),
                'seller_name' => $item->seller_name ?: translate('Shop Name'),
                'seller_shop_id' => $item->seller_shop_id ?: null,
                'seller_slug' => $item->seller_slug ?: null,
                'seller_logo_url' => $this->resolveAssetUrl($item->seller_logo ?? null),
                'hashtags' => $hashtags,
                'items' => $items,
            ];
        })->filter(function ($item) {
            return $item->items->isNotEmpty();
        })->values();

        return view('frontend.showcase.collection_page', [
            'collections' => $collectionFeed,
        ]);
    }

    public function vitrin(Request $request)
    {
        $request->merge(['type' => 'vitrin']);
        return $this->renderFeed($request);
    }

    public function storefrontPage(Request $request)
    {
        $storefronts = DB::table('showcases')
            ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
            ->where('showcases.status', 'published')
            ->where('showcases.type', 'vitrin')
            ->orderByDesc('showcases.created_at')
            ->select(
                'showcases.*',
                'shops.id as seller_shop_id',
                'shops.name as seller_name',
                'shops.slug as seller_slug',
                'shops.logo as seller_logo'
            )
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return $this->applyLocaleFields($item);
            });

        $showcaseIds = $storefronts->pluck('id')->filter()->values()->all();
        $groupedProducts = collect();

        if (!empty($showcaseIds)) {
            $groupedProducts = DB::table('showcase_products')
                ->join('products', 'showcase_products.product_id', '=', 'products.id')
                ->whereIn('showcase_products.showcase_id', $showcaseIds)
                ->orderBy('showcase_products.sort_order')
                ->select(
                    'showcase_products.showcase_id',
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.thumbnail_img',
                    'products.unit_price',
                    'products.discount',
                    'products.discount_type'
                )
                ->get()
                ->groupBy('showcase_id');
        }

        $storefrontFeed = $storefronts->map(function ($item) use ($groupedProducts) {
            $products = collect($groupedProducts->get($item->id, collect()))
                ->map(function ($product) {
                    $finalPrice = $product->unit_price;
                    if (!empty($product->discount) && !empty($product->discount_type)) {
                        if ($product->discount_type === 'percent') {
                            $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                        } elseif ($product->discount_type === 'amount') {
                            $finalPrice = $product->unit_price - $product->discount;
                        }
                    }
                    $finalPrice = max(0, $finalPrice);

                    return (object) [
                        'id' => (int) $product->id,
                        'name' => $product->name,
                        'thumbnail_url' => $this->resolveAssetUrl($product->thumbnail_img ?? null),
                        'price_html' => single_price($finalPrice),
                        'product_url' => !empty($product->slug) ? route('product', $product->slug) : null,
                    ];
                })
                ->values();

            return (object) [
                'id' => (int) $item->id,
                'title' => $item->title ?: translate('Storefront'),
                'description' => $item->description ?: ($item->intro ?: ''),
                'seller_name' => $item->seller_name ?: translate('Shop Name'),
                'seller_shop_id' => $item->seller_shop_id ?: null,
                'seller_slug' => $item->seller_slug ?: null,
                'seller_logo_url' => $this->resolveAssetUrl($item->seller_logo ?? null),
                'main_visual_url' => $this->resolveAssetUrl(($item->main_visual ?? null) ?: ($item->cover_image ?? null)),
                'products' => $products,
            ];
        })->values();

        return view('frontend.showcase.storefront_page', [
            'storefronts' => $storefrontFeed,
        ]);
    }

    public function launch(Request $request)
    {
        $request->merge(['type' => 'launch']);
        return $this->renderFeed($request);
    }

    public function launchPage(Request $request)
    {
        $launches = DB::table('showcases')
            ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
            ->where('showcases.status', 'published')
            ->where('showcases.type', 'launch')
            ->orderByDesc('showcases.created_at')
            ->select(
                'showcases.*',
                'shops.id as seller_shop_id',
                'shops.name as seller_name',
                'shops.slug as seller_slug',
                'shops.logo as seller_logo',
                'shops.user_id as seller_user_id'
            )
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return $this->applyLocaleFields($item);
            });

        $launchIds = $launches->pluck('id')->filter()->values()->all();
        $sellerUserIds = $launches->pluck('seller_user_id')->filter()->unique()->values()->all();
        $groupedProducts = collect();
        $sellerProducts = collect();

        if (!empty($launchIds)) {
            $groupedProducts = DB::table('showcase_products')
                ->join('products', 'showcase_products.product_id', '=', 'products.id')
                ->whereIn('showcase_products.showcase_id', $launchIds)
                ->orderBy('showcase_products.sort_order')
                ->select(
                    'showcase_products.showcase_id',
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.thumbnail_img',
                    'products.photos',
                    'products.unit_price',
                    'products.discount',
                    'products.discount_type',
                    'products.user_id'
                )
                ->get()
                ->groupBy('showcase_id');
        }

        if (!empty($sellerUserIds)) {
            $sellerProducts = DB::table('products')
                ->whereIn('user_id', $sellerUserIds)
                ->orderByDesc('id')
                ->select(
                    'id',
                    'user_id',
                    'name',
                    'slug',
                    'thumbnail_img',
                    'photos',
                    'unit_price',
                    'discount',
                    'discount_type'
                )
                ->get()
                ->groupBy('user_id');
        }

        $launchFeed = $launches->map(function ($item) use ($groupedProducts, $sellerProducts) {
            $mediaUrl = $this->resolveAssetUrl(($item->main_visual ?? null) ?: ($item->cover_image ?? null));
            $extension = strtolower(pathinfo(parse_url($mediaUrl ?? '', PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
            $isVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);

            $hashtags = !empty($item->hashtags)
                ? collect(explode(',', $item->hashtags))->map(fn ($tag) => trim($tag))->filter()->values()->all()
                : [];

            $mapProduct = function ($product) {
                $finalPrice = $product->unit_price;
                if (!empty($product->discount) && !empty($product->discount_type)) {
                    if ($product->discount_type === 'percent') {
                        $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                    } elseif ($product->discount_type === 'amount') {
                        $finalPrice = $product->unit_price - $product->discount;
                    }
                }
                $finalPrice = max(0, $finalPrice);

                $gallery = collect(explode(',', (string) ($product->photos ?? '')))
                    ->map(fn ($value) => trim($value))
                    ->filter()
                    ->take(4)
                    ->map(fn ($value) => $this->resolveAssetUrl($value))
                    ->filter()
                    ->values()
                    ->all();

                $thumbUrl = $this->resolveAssetUrl($product->thumbnail_img ?? null);
                if ($thumbUrl && !in_array($thumbUrl, $gallery)) {
                    array_unshift($gallery, $thumbUrl);
                    $gallery = array_values(array_unique(array_filter($gallery)));
                }

                return (object) [
                    'id' => (int) $product->id,
                    'name' => $product->name,
                    'thumbnail_url' => $thumbUrl,
                    'price_html' => single_price($finalPrice),
                    'product_url' => !empty($product->slug) ? route('product', $product->slug) : null,
                    'gallery' => $gallery,
                ];
            };

            $primaryProduct = optional(collect($groupedProducts->get($item->id, collect()))->first());
            $primaryPayload = $primaryProduct && !empty($primaryProduct->id) ? $mapProduct($primaryProduct) : null;

            $relatedProducts = collect($sellerProducts->get($item->seller_user_id, collect()))
                ->filter(function ($product) use ($primaryPayload) {
                    return !$primaryPayload || (int) $product->id !== (int) $primaryPayload->id;
                })
                ->take(4)
                ->map($mapProduct)
                ->values();

            if ($primaryPayload) {
                $relatedProducts = collect([$primaryPayload])->merge($relatedProducts)->take(5)->values();
            }

            return (object) [
                'id' => (int) $item->id,
                'title' => $item->title ?: translate('Launch'),
                'subtitle' => $item->subtitle ?: '',
                'description' => $item->description ?: '',
                'seller_name' => $item->seller_name ?: translate('Shop Name'),
                'seller_shop_id' => $item->seller_shop_id ?: null,
                'seller_slug' => $item->seller_slug ?: null,
                'seller_logo_url' => $this->resolveAssetUrl($item->seller_logo ?? null),
                'media_url' => $mediaUrl,
                'media_is_video' => $isVideo,
                'hashtags' => $hashtags,
                'primary_product' => $primaryPayload,
                'related_products' => $relatedProducts,
            ];
        })->values();

        return view('frontend.showcase.launch_page', [
            'launches' => $launchFeed,
        ]);
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

    private function resolveAssetUrl($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return uploaded_asset($value);
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset($value);
    }
}
