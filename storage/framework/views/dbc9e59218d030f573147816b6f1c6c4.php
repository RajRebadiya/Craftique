<?php
    $homepageShowcaseItems = \Illuminate\Support\Facades\DB::table('showcases')
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
        ->limit(8)
        ->get();

    $locale = app()->getLocale();
    $followedSellerIds = auth()->check() ? get_followed_sellers() : [];

    $homepageShowcaseItems = $homepageShowcaseItems->map(function ($item) use ($locale) {
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
    });

    $showcaseProductMap = collect();

    if ($homepageShowcaseItems->isNotEmpty()) {
        $showcaseProductMap = \Illuminate\Support\Facades\DB::table('showcase_products')
            ->join('products', 'showcase_products.product_id', '=', 'products.id')
            ->whereIn('showcase_products.showcase_id', $homepageShowcaseItems->pluck('id'))
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

    $homepageLaunchItems = \Illuminate\Support\Facades\DB::table('showcases')
        ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
        ->where('showcases.status', 'published')
        ->where('showcases.type', 'launch')
        ->orderByDesc('showcases.created_at')
        ->select(
            'showcases.*',
            'shops.id as seller_shop_id',
            'shops.name as seller_name',
            'shops.slug as seller_slug',
            'shops.logo as seller_logo'
        )
        ->limit(8)
        ->get();

    $homepageLaunchItems = $homepageLaunchItems->map(function ($item) use ($locale) {
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
    });

    $launchPayload = collect();

    if ($homepageLaunchItems->isNotEmpty()) {
        $launchProductMap = \Illuminate\Support\Facades\DB::table('showcase_products')
            ->join('products', 'showcase_products.product_id', '=', 'products.id')
            ->whereIn('showcase_products.showcase_id', $homepageLaunchItems->pluck('id'))
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

        $resolveLaunchAssetUrl = function ($value) {
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
        };

        $launchPayload = $homepageLaunchItems->map(function ($item) use ($launchProductMap, $resolveLaunchAssetUrl) {
            $mainVisualUrl = $resolveLaunchAssetUrl(($item->main_visual ?? null) ?: ($item->cover_image ?? null));
            $sideVisualUrl = $resolveLaunchAssetUrl(($item->cover_image ?? null) ?: ($item->main_visual ?? null));
            $hashtags = !empty($item->hashtags)
                ? collect(explode(',', $item->hashtags))->map(fn ($tag) => trim($tag))->filter()->values()->all()
                : [];

            $product = optional($launchProductMap->get($item->id))->first();
            $productPayload = null;

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

                $productPayload = [
                    'id' => (int) $product->id,
                    'name' => $product->name,
                    'thumbnail_url' => !empty($product->thumbnail_img) ? $resolveLaunchAssetUrl($product->thumbnail_img) : null,
                    'price_html' => single_price($finalPrice),
                    'product_url' => !empty($product->slug) ? route('product', $product->slug) : null,
                ];
            }

            return [
                'id' => (int) $item->id,
                'title' => $item->title ?: translate('Launch'),
                'subtitle' => $item->subtitle ?: '',
                'description' => $item->description ?: ($item->intro ?: ''),
                'seller_name' => $item->seller_name ?: translate('The Shop'),
                'seller_shop_id' => $item->seller_shop_id ?: null,
                'seller_slug' => $item->seller_slug ?: null,
                'seller_logo_url' => !empty($item->seller_logo) ? uploaded_asset($item->seller_logo) : null,
                'main_visual_url' => $mainVisualUrl,
                'side_visual_url' => $sideVisualUrl,
                'hashtags' => $hashtags,
                'product' => $productPayload,
            ];
        })->values();
    }

    $homepageStorefrontItems = \Illuminate\Support\Facades\DB::table('showcases')
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
        ->limit(8)
        ->get();

    $homepageStorefrontItems = $homepageStorefrontItems->map(function ($item) use ($locale) {
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
    });

    $storefrontPayload = collect();

    if ($homepageStorefrontItems->isNotEmpty()) {
        $storefrontProductMap = \Illuminate\Support\Facades\DB::table('showcase_products')
            ->join('products', 'showcase_products.product_id', '=', 'products.id')
            ->whereIn('showcase_products.showcase_id', $homepageStorefrontItems->pluck('id'))
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

        $resolveAssetUrl = function ($value) {
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
        };

        $storefrontPayload = $homepageStorefrontItems->map(function ($item) use ($storefrontProductMap, $resolveAssetUrl) {
            $mainVisualUrl = $resolveAssetUrl(($item->main_visual ?? null) ?: ($item->cover_image ?? null));
            $products = collect($storefrontProductMap->get($item->id, collect()))
                ->map(function ($product) use ($resolveAssetUrl) {
                    $thumb = !empty($product->thumbnail_img) ? $resolveAssetUrl($product->thumbnail_img) : null;
                    $finalPrice = $product->unit_price;

                    if (!empty($product->discount) && !empty($product->discount_type)) {
                        if ($product->discount_type === 'percent') {
                            $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                        } elseif ($product->discount_type === 'amount') {
                            $finalPrice = $product->unit_price - $product->discount;
                        }
                    }

                    $finalPrice = max(0, $finalPrice);

                    return [
                        'id' => (int) $product->id,
                        'name' => $product->name,
                        'thumbnail_url' => $thumb,
                        'product_url' => !empty($product->slug) ? route('product', $product->slug) : null,
                        'price_html' => single_price($finalPrice),
                    ];
                })
                ->values();

            return [
                'id' => (int) $item->id,
                'title' => $item->title ?: translate('Storefront'),
                'description' => $item->description ?: ($item->intro ?: ($item->subtitle ?: '')),
                'seller_name' => $item->seller_name ?: translate('The Shop'),
                'seller_shop_id' => $item->seller_shop_id ?: null,
                'seller_slug' => $item->seller_slug ?: null,
                'seller_logo_url' => !empty($item->seller_logo) ? uploaded_asset($item->seller_logo) : null,
                'main_visual_url' => $mainVisualUrl,
                'products' => $products->all(),
            ];
        })->filter(fn ($item) => !empty($item['main_visual_url']) || !empty($item['products']))->values();
    }

    $homepageCollectionItems = \Illuminate\Support\Facades\DB::table('showcases')
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
        ->limit(8)
        ->get();

    $homepageCollectionItems = $homepageCollectionItems->map(function ($item) use ($locale) {
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
    });

    $collectionPayload = collect();

    if ($homepageCollectionItems->isNotEmpty()) {
        $allCollectionRows = collect();
        $allCollectionProductIds = collect();

        foreach ($homepageCollectionItems as $collectionItem) {
            $collectionItemsJson = data_get($collectionItem, 'collection_items_json');

            $rows = collect(is_array($collectionItemsJson)
                ? $collectionItemsJson
                : json_decode($collectionItemsJson ?? '[]', true));

            $rows = $rows
                ->filter(fn ($row) => is_array($row))
                ->sortBy(fn ($row) => (int) ($row['sort_order'] ?? 0))
                ->values();

            $allCollectionRows->put($collectionItem->id, $rows);
            $allCollectionProductIds = $allCollectionProductIds->merge(
                $rows->pluck('product_id')->filter()->map(fn ($id) => (int) $id)->values()
            );
        }

        $collectionProducts = collect();
        $productIds = $allCollectionProductIds->unique()->values()->all();

        if (!empty($productIds)) {
            $collectionProducts = \Illuminate\Support\Facades\DB::table('products')
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

        $resolveAssetUrl = function ($value) {
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
        };

        $collectionPayload = $homepageCollectionItems->map(function ($item) use ($allCollectionRows, $collectionProducts, $resolveAssetUrl, $locale) {
            $hashtags = !empty($item->hashtags)
                ? collect(explode(',', $item->hashtags))->map(fn ($tag) => trim($tag))->filter()->values()
                : collect();

            $rows = collect($allCollectionRows->get($item->id, collect()))->map(function ($row, $index) use ($collectionProducts, $resolveAssetUrl, $locale) {
                $product = null;
                if (!empty($row['product_id']) && $collectionProducts->has((int) $row['product_id'])) {
                    $product = $collectionProducts->get((int) $row['product_id']);
                }

                $rowTitle = $locale === 'en'
                    ? (($row['title_en'] ?? null) ?: ($row['title_gr'] ?? null) ?: ($row['title'] ?? null))
                    : (($row['title_gr'] ?? null) ?: ($row['title_en'] ?? null) ?: ($row['title'] ?? null));

                $rowDescription = $locale === 'en'
                    ? (($row['description_en'] ?? null) ?: ($row['description_gr'] ?? null) ?: ($row['description'] ?? null))
                    : (($row['description_gr'] ?? null) ?: ($row['description_en'] ?? null) ?: ($row['description'] ?? null));

                $productThumb = $product && !empty($product->thumbnail_img) ? $resolveAssetUrl($product->thumbnail_img) : null;
                $productUrl = $product && !empty($product->slug) ? route('product', $product->slug) : null;

                $finalPrice = null;
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
                }

                return [
                    'row_id' => $index,
                    'title' => $rowTitle ?: translate('Collection Item'),
                    'description' => $rowDescription ?: '',
                    'cover_image_url' => $resolveAssetUrl($row['cover_image'] ?? null) ?: $productThumb,
                    'product' => $product ? [
                        'id' => (int) $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'thumbnail_url' => $productThumb,
                        'product_url' => $productUrl,
                        'price_html' => $finalPrice !== null ? single_price($finalPrice) : '',
                    ] : null,
                ];
            })->values();

            $postUrl = route('frontend.showcase.post', [
                'id' => $item->id,
                'slug' => \Illuminate\Support\Str::slug($item->title ?: $item->id),
            ]);

            return [
                'id' => (int) $item->id,
                'title' => $item->title ?: translate('Collection'),
                'subtitle' => $item->subtitle ?: '',
                'description' => $item->description ?: ($item->intro ?: ''),
                'seller_name' => $item->seller_name ?: translate('The Shop'),
                'seller_shop_id' => $item->seller_shop_id ?: null,
                'seller_slug' => $item->seller_slug ?: null,
                'seller_logo_url' => !empty($item->seller_logo) ? uploaded_asset($item->seller_logo) : null,
                'hashtags' => $hashtags->all(),
                'post_url' => $postUrl,
                'items' => $rows->all(),
            ];
        })->filter(fn ($item) => !empty($item['items']))->values();
    }
?>

<?php if($homepageShowcaseItems->count()): ?>
    <?php
        $homeStoryPreviewPayload = $homepageShowcaseItems->map(function ($item) use ($showcaseProductMap) {
            $resolveStoryAssetUrl = function ($value) {
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
            };

            $visualUrl = $resolveStoryAssetUrl(($item->main_visual ?? null) ?: ($item->cover_image ?? null));
            $extension = strtolower(pathinfo(parse_url($visualUrl ?? '', PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
            $visualIsVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);
            $hashtags = !empty($item->hashtags)
                ? collect(explode(',', $item->hashtags))->map(fn ($tag) => trim($tag))->filter()->values()->all()
                : [];

            $productsPayload = collect($showcaseProductMap->get($item->id, collect()))->map(function ($product) use ($resolveStoryAssetUrl) {
                $finalPrice = $product->unit_price;
                if (!empty($product->discount) && !empty($product->discount_type)) {
                    if ($product->discount_type === 'percent') {
                        $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                    } elseif ($product->discount_type === 'amount') {
                        $finalPrice = $product->unit_price - $product->discount;
                    }
                }
                $finalPrice = max(0, $finalPrice);

                return [
                    'id' => (int) $product->id,
                    'name' => $product->name,
                    'thumbnail_url' => $resolveStoryAssetUrl($product->thumbnail_img ?? null),
                    'price_html' => single_price($finalPrice),
                    'product_url' => !empty($product->slug) ? route('product', $product->slug) : null,
                ];
            })->values();

            return [
                'id' => (int) $item->id,
                'title' => $item->title ?: translate('Story'),
                'seller_name' => $item->seller_name ?: translate('The Shop'),
                'seller_logo_url' => !empty($item->seller_logo) ? uploaded_asset($item->seller_logo) : null,
                'seller_slug' => $item->seller_slug ?: null,
                'shop_url' => !empty($item->seller_slug) ? route('shop.visit', $item->seller_slug) : null,
                'brand_url' => !empty($item->seller_slug) ? route('frontend.showcase.brand', $item->seller_slug) : null,
                'media_url' => $visualUrl,
                'media_is_video' => $visualIsVideo,
                'hashtags' => $hashtags,
                'product' => $productsPayload->first(),
                'products' => $productsPayload->all(),
            ];
        })->values();
    ?>

    <section class="showcase-story-home mb-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                <div>
                    <h3 class="h4 fw-700 mb-1"><?php echo e(translate('Showcase')); ?></h3>
                    <p class="text-muted mb-0">
                        <?php echo e(translate('Latest stories from creators, shown just like the live preview.')); ?>

                    </p>
                </div>

                <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                    <a href="<?php echo e(\Illuminate\Support\Facades\Route::has('frontend.showcase.story_page') ? route('frontend.showcase.story_page') : url('/showcase/story')); ?>"
                       class="btn btn-outline-secondary btn-sm">
                        <?php echo e(translate('View Page')); ?>

                    </a>
                    <a href="<?php echo e(route('frontend.showcase.index', ['type' => 'history'])); ?>"
                       class="btn btn-soft-primary btn-sm">
                        <?php echo e(translate('View All')); ?>

                    </a>
                </div>
            </div>

            <div class="showcase-story-home__rail-wrap">
                <button type="button"
                        class="showcase-story-home__arrow showcase-story-home__arrow--left"
                        data-showcase-scroll="left"
                        aria-label="<?php echo e(translate('Scroll left')); ?>">
                    <span>&lsaquo;</span>
                </button>

                <div class="showcase-story-home__rail" data-showcase-rail>
                <?php $__currentLoopData = $homepageShowcaseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $itemTitle = $item->title ?: translate('Story');
                        $itemHashtags = !empty($item->hashtags)
                            ? collect(explode(',', $item->hashtags))
                                ->map(fn ($tag) => trim($tag))
                                ->filter()
                                ->take(2)
                                ->values()
                            : collect();

                        $rawVisual = ($item->main_visual ?? null) ?: ($item->cover_image ?? null);
                        $visualUrl = null;
                        $visualIsVideo = false;

                        if (!empty($rawVisual)) {
                            if (is_numeric($rawVisual)) {
                                $visualUrl = uploaded_asset($rawVisual);
                            } elseif (filter_var($rawVisual, FILTER_VALIDATE_URL)) {
                                $visualUrl = $rawVisual;
                            } else {
                                $visualUrl = asset($rawVisual);
                            }

                            $extension = strtolower(pathinfo(parse_url($visualUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                            $visualIsVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);
                        }

                        $postUrl = route('frontend.showcase.post', [
                            'id' => $item->id,
                            'slug' => \Illuminate\Support\Str::slug($itemTitle ?: $item->id),
                        ]);

                        $shopUrl = !empty($item->seller_slug)
                            ? route('shop.visit', $item->seller_slug)
                            : null;
                        $sellerBrandUrl = !empty($item->seller_slug)
                            ? route('frontend.showcase.brand', $item->seller_slug)
                            : null;
                        $isFollowingSeller = !empty($item->seller_shop_id) && in_array((int) $item->seller_shop_id, $followedSellerIds);
                        $followUrl = !empty($item->seller_shop_id)
                            ? ($isFollowingSeller
                                ? route('followed_seller.remove', ['id' => $item->seller_shop_id])
                                : route('followed_seller.store', ['id' => $item->seller_shop_id]))
                            : (auth()->check() ? '#' : route('user.login'));

                        $sellerLogoUrl = !empty($item->seller_logo) ? uploaded_asset($item->seller_logo) : null;
                        $sellerInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($item->seller_name ?: 'S', 0, 1));

                        $linkedProduct = optional($showcaseProductMap->get($item->id))->first();
                        $productThumb = null;
                        $productUrl = null;
                        $finalPrice = null;

                        if ($linkedProduct) {
                            if (!empty($linkedProduct->thumbnail_img)) {
                                if (is_numeric($linkedProduct->thumbnail_img)) {
                                    $productThumb = uploaded_asset($linkedProduct->thumbnail_img);
                                } elseif (filter_var($linkedProduct->thumbnail_img, FILTER_VALIDATE_URL)) {
                                    $productThumb = $linkedProduct->thumbnail_img;
                                } else {
                                    $productThumb = asset($linkedProduct->thumbnail_img);
                                }
                            }

                            $productUrl = !empty($linkedProduct->slug) ? route('product', $linkedProduct->slug) : null;

                            $finalPrice = $linkedProduct->unit_price;
                            if (!empty($linkedProduct->discount) && !empty($linkedProduct->discount_type)) {
                                if ($linkedProduct->discount_type === 'percent') {
                                    $finalPrice = $linkedProduct->unit_price - (($linkedProduct->unit_price * $linkedProduct->discount) / 100);
                                } elseif ($linkedProduct->discount_type === 'amount') {
                                    $finalPrice = $linkedProduct->unit_price - $linkedProduct->discount;
                                }
                            }

                            $finalPrice = max(0, $finalPrice);
                        }

                        $storyModalAction = !empty($linkedProduct->id)
                            ? "openHomeStoryProductPreview(" . (int) $item->id . ")"
                            : "showShowcaseEmptyProductModal()";
                    ?>

                    <article class="showcase-story-home__card">
                        <div class="showcase-story-home__card-inner">
                            <div class="showcase-story-home__head">
                                <div class="showcase-story-home__shop">
                                    <?php if($shopUrl): ?>
                                        <a href="<?php echo e($shopUrl); ?>" class="showcase-story-home__logo">
                                            <?php if($sellerLogoUrl): ?>
                                                <img src="<?php echo e($sellerLogoUrl); ?>" alt="<?php echo e($item->seller_name ?: $itemTitle); ?>">
                                            <?php else: ?>
                                                <span><?php echo e($sellerInitial); ?></span>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <div class="showcase-story-home__logo">
                                            <?php if($sellerLogoUrl): ?>
                                                <img src="<?php echo e($sellerLogoUrl); ?>" alt="<?php echo e($item->seller_name ?: $itemTitle); ?>">
                                            <?php else: ?>
                                                <span><?php echo e($sellerInitial); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="showcase-story-home__shop-meta">
                                        <?php if(!empty($item->seller_name)): ?>
                                            <?php if($shopUrl): ?>
                                                <a href="<?php echo e($shopUrl); ?>" class="showcase-story-home__shop-name">
                                                    <?php echo e(\Illuminate\Support\Str::limit($item->seller_name, 22)); ?>

                                                </a>
                                            <?php else: ?>
                                                <div class="showcase-story-home__shop-name">
                                                    <?php echo e(\Illuminate\Support\Str::limit($item->seller_name, 22)); ?>

                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if($sellerBrandUrl): ?>
                                            <a href="<?php echo e($sellerBrandUrl); ?>" class="showcase-story-home__shop-type showcase-story-home__shop-type--link">
                                                <?php echo e(translate('Brand')); ?>

                                            </a>
                                        <?php else: ?>
                                            <div class="showcase-story-home__shop-type"><?php echo e(translate('Story')); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if($isFollowingSeller): ?>
                                    <div class="showcase-follow-menu">
                                        <button type="button" class="showcase-story-home__follow showcase-follow-menu__toggle">
                                            <?php echo e(translate('Following')); ?> <i class="las la-angle-down"></i>
                                        </button>
                                        <div class="showcase-follow-menu__dropdown">
                                            <a href="<?php echo e($followUrl); ?>"><?php echo e(translate('Unfollow')); ?></a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <a href="<?php echo e($followUrl); ?>" class="showcase-story-home__follow">
                                        <?php echo e(translate('Follow')); ?>

                                    </a>
                                <?php endif; ?>
                            </div>

                            <button type="button"
                                    class="showcase-story-home__media"
                                    onclick="<?php echo e($storyModalAction); ?>">
                                <?php if($visualUrl && $visualIsVideo): ?>
                                    <video muted playsinline preload="metadata" poster="">
                                        <source src="<?php echo e($visualUrl); ?>">
                                    </video>
                                    <span class="showcase-story-home__video-badge"><?php echo e(translate('Video')); ?></span>
                                <?php elseif($visualUrl): ?>
                                    <img src="<?php echo e($visualUrl); ?>" alt="<?php echo e($itemTitle); ?>">
                                <?php else: ?>
                                    <span class="showcase-story-home__placeholder">
                                        <?php echo e(translate('No preview')); ?>

                                    </span>
                                <?php endif; ?>

                                <span class="showcase-story-home__gradient"></span>

                                <span class="showcase-story-home__media-meta">
                                    <span class="showcase-story-home__title">
                                        <?php echo e(\Illuminate\Support\Str::limit($itemTitle, 42)); ?>

                                    </span>

                                    <?php if($itemHashtags->isNotEmpty()): ?>
                                        <span class="showcase-story-home__tags">
                                            <?php $__currentLoopData = $itemHashtags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span>#<?php echo e($tag); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </span>
                                    <?php endif; ?>
                                </span>
                            </button>

                            <div class="showcase-story-home__product">
                                <div class="showcase-story-home__product-link"
                                     onclick="<?php echo e($storyModalAction); ?>">
                                    <div class="showcase-story-home__product-summary">
                                        <div class="showcase-story-home__product-thumb">
                                            <?php if($productThumb): ?>
                                                <img src="<?php echo e($productThumb); ?>" alt="<?php echo e($linkedProduct->name ?? $itemTitle); ?>">
                                            <?php endif; ?>
                                        </div>

                                        <div class="showcase-story-home__product-meta">
                                            <div class="showcase-story-home__product-name">
                                                <?php echo e($linkedProduct ? \Illuminate\Support\Str::limit($linkedProduct->name, 24) : translate('No product found')); ?>

                                            </div>
                                            <div class="showcase-story-home__product-price">
                                                <?php echo $linkedProduct ? single_price($finalPrice) : '&nbsp;'; ?>

                                            </div>
                                        </div>
                                    </div>

                                    <button type="button"
                                            class="showcase-story-home__product-btn"
                                            onclick="event.stopPropagation(); <?php echo e($storyModalAction); ?>">
                                        <?php echo e(translate('View Product')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <button type="button"
                        class="showcase-story-home__arrow showcase-story-home__arrow--right"
                        data-showcase-scroll="right"
                        aria-label="<?php echo e(translate('Scroll right')); ?>">
                    <span>&rsaquo;</span>
                </button>
            </div>
        </div>
    </section>

    <div class="modal fade" id="homeStoryProductPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <button type="button"
                class="story-product-modal__nav story-product-modal__nav--prev"
                onclick="navigateHomeStoryProductPreview(-1)"
                aria-label="<?php echo e(translate('Previous story')); ?>">
            <i class="las la-angle-left"></i>
        </button>
        <button type="button"
                class="story-product-modal__nav story-product-modal__nav--next"
                onclick="navigateHomeStoryProductPreview(1)"
                aria-label="<?php echo e(translate('Next story')); ?>">
            <i class="las la-angle-right"></i>
        </button>
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content story-product-modal">
                <button type="button" class="story-product-modal__close" data-dismiss="modal" aria-label="<?php echo e(translate('Close')); ?>">
                    <i class="las la-times"></i>
                </button>
                <div class="modal-body p-0" id="homeStoryProductPreviewModalBody"></div>
            </div>
        </div>
    </div>

    <style>
        .showcase-story-home__rail-wrap {
            position: relative;
        }

        .showcase-story-home__rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(240px, 280px);
            gap: 16px;
            overflow-x: auto;
            padding: 6px 42px 10px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: x proximity;
        }

        .showcase-story-home__rail::-webkit-scrollbar {
            display: none;
        }

        .showcase-story-home__arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.78);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.22);
            cursor: pointer;
        }

        .showcase-story-home__arrow span {
            font-size: 24px;
            line-height: 1;
            margin-top: -2px;
        }

        .showcase-story-home__arrow--left {
            left: 2px;
        }

        .showcase-story-home__arrow--right {
            right: 2px;
        }

        .showcase-story-home__card {
            scroll-snap-align: start;
        }

        .showcase-story-home__card-inner {
            height: 100%;
            background: #ffffff;
            border: 1px solid #dde2ea;
            border-radius: 18px;
            padding: 12px;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        }

        .showcase-story-home__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .showcase-story-home__shop {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .showcase-story-home__logo {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            overflow: hidden;
            background: #eef2f7;
            color: #7a5a4b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex: 0 0 36px;
        }

        .showcase-story-home__logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-story-home__shop-meta {
            min-width: 0;
        }

        .showcase-story-home__shop-name {
            display: block;
            color: #111827;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .showcase-story-home__shop-type {
            color: #64748b;
            font-size: 11px;
            line-height: 1.2;
            margin-top: 2px;
        }

        .showcase-story-home__shop-type--link {
            display: inline-block;
            text-decoration: none;
        }

        .showcase-story-home__follow {
            border: 0;
            background: #f4ecff;
            color: #6f52d9;
            text-decoration: none;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
        }

        .showcase-follow-menu {
            position: relative;
            display: inline-flex;
        }

        .showcase-follow-menu__toggle {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .showcase-follow-menu__dropdown {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            z-index: 8;
            min-width: 118px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.14);
            padding: 6px;
            display: none;
        }

        .showcase-follow-menu:hover .showcase-follow-menu__dropdown,
        .showcase-follow-menu:focus-within .showcase-follow-menu__dropdown {
            display: block;
        }

        .showcase-follow-menu__dropdown a {
            display: block;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            padding: 7px 8px;
            border-radius: 6px;
        }

        .showcase-follow-menu__dropdown a:hover {
            background: #f3f4f6;
        }

        .showcase-story-home__media {
            position: relative;
            display: block;
            aspect-ratio: 9 / 16;
            border-radius: 14px;
            overflow: hidden;
            background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
            text-decoration: none;
            width: 100%;
            padding: 0;
            border: 0;
            text-align: left;
            cursor: pointer;
        }

        .showcase-story-home__media img,
        .showcase-story-home__media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-story-home__placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
        }

        .showcase-story-home__gradient {
            position: absolute;
            inset: auto 0 0 0;
            height: 42%;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.82) 100%);
        }

        .showcase-story-home__media-meta {
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 14px;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
            color: #fff;
        }

        .showcase-story-home__title {
            font-size: 15px;
            font-weight: 800;
            line-height: 1.25;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .showcase-story-home__tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            opacity: 0.95;
        }

        .showcase-story-home__video-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 1;
            background: rgba(15, 23, 42, 0.75);
            color: #fff;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
        }

        .showcase-story-home__product {
            margin-top: 12px;
        }

        .showcase-story-home__product-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 8px 10px;
            background: #fff;
            color: inherit;
            cursor: pointer;
        }

        .showcase-story-home__product-summary {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1 1 auto;
        }

        .showcase-story-home__product-thumb {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            overflow: hidden;
            background: #f3f4f6;
            flex: 0 0 40px;
        }

        .showcase-story-home__product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-story-home__product-meta {
            min-width: 0;
        }

        .showcase-story-home__product-name,
        .showcase-story-home__product-price {
            color: #111827;
            line-height: 1.2;
        }

        .showcase-story-home__product-name {
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .showcase-story-home__product-price {
            margin-top: 4px;
            font-size: 12px;
            font-weight: 700;
        }

        .showcase-story-home__product-btn {
            border: 0;
            background: #111827;
            color: #fff;
            border-radius: 999px;
            padding: 7px 14px;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.1;
        }

        .story-product-modal {
            border: 0;
            border-radius: 32px;
            overflow: hidden;
            background: transparent;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.38);
        }

        .story-product-modal__close {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 5;
            width: 24px;
            height: 24px;
            border: 0;
            background: rgba(17, 24, 39, 0.42);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .story-product-modal__nav {
            position: fixed;
            top: 50%;
            z-index: 1065;
            transform: translateY(-50%);
            width: 56px;
            height: 84px;
            border: 0;
            background: transparent;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
            line-height: 1;
            text-shadow: 0 8px 22px rgba(0, 0, 0, 0.45);
            cursor: pointer;
        }

        .story-product-modal__nav--prev {
            left: 4.5vw;
        }

        .story-product-modal__nav--next {
            right: 4.5vw;
        }

        .story-product-modal__layout {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(320px, 0.95fr);
            min-height: 520px;
            background: #fff;
            border-radius: 32px;
            overflow: hidden;
        }

        .story-product-modal__details {
            padding: 34px 34px 34px 42px;
            color: #1f2937;
            background: #fff;
        }

        .story-product-modal__shop-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 14px;
        }

        .story-product-modal__shop-logo {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            overflow: hidden;
            background: #e9f4f0;
            color: #24715e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 900;
            flex: 0 0 48px;
        }

        .story-product-modal__shop-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .story-product-modal__shop-name {
            color: #8a5d45;
            background: #faf4f1;
            padding: 7px 12px;
            font-size: 18px;
            font-weight: 800;
            text-decoration: none;
        }

        .story-product-modal__follow {
            margin-left: auto;
            border: 0;
            border-radius: 6px;
            background: #d2a589;
            color: #fff;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 800;
        }

        .story-product-modal__breadcrumb {
            margin-bottom: 12px;
            color: #9ca3af;
            font-size: 10px;
        }

        .story-product-modal__product-area {
            display: grid;
            grid-template-columns: 74px minmax(0, 1fr);
            gap: 18px;
        }

        .story-product-modal__thumbs {
            display: grid;
            align-content: start;
            gap: 12px;
            max-height: 330px;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .story-product-modal__thumbs::-webkit-scrollbar {
            display: none;
        }

        .story-product-modal__thumb {
            width: 62px;
            height: 62px;
            border-radius: 5px;
            overflow: hidden;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 0;
            cursor: pointer;
        }

        .story-product-modal__thumb--active {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.18);
        }

        .story-product-modal__thumb--empty {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 20px;
        }

        .story-product-modal__thumb img,
        .story-product-modal__gallery-image img,
        .story-product-modal__media img,
        .story-product-modal__media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .story-product-modal__gallery {
            display: grid;
            grid-template-columns: minmax(240px, 1fr) minmax(260px, 1.16fr);
            gap: 26px;
            align-items: center;
        }

        .story-product-modal__gallery-image {
            position: relative;
            aspect-ratio: 1 / 1;
            border-radius: 6px;
            overflow: hidden;
            background: #f3f4f6;
        }

        .story-product-modal__gallery-btn,
        .story-product-modal__expand {
            position: absolute;
            border-radius: 999px;
            border: 0;
            background: #fff;
            color: #6b7280;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.14);
        }

        .story-product-modal__gallery-btn {
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            font-size: 20px;
        }

        .story-product-modal__expand {
            left: 14px;
            bottom: 14px;
            width: 36px;
            height: 36px;
            font-size: 18px;
        }

        .story-product-modal__summary {
            min-width: 0;
        }

        .story-product-modal__title {
            color: #1f2937;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .story-product-modal__brand-line,
        .story-product-modal__rating,
        .story-product-modal__ask,
        .story-product-modal__availability,
        .story-product-modal__min-order {
            color: #6b7280;
            font-size: 11px;
        }

        .story-product-modal__brand-line strong {
            color: #8a5d45;
        }

        .story-product-modal__rating {
            margin-top: 6px;
            color: #9ca3af;
        }

        .story-product-modal__ask {
            margin-top: 8px;
            color: #3b82f6;
            font-weight: 700;
        }

        .story-product-modal__deal {
            margin-top: 26px;
            background: #252530;
            color: #ff8b2d;
            border-radius: 3px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 10px;
            font-weight: 900;
        }

        .story-product-modal__pricing {
            background: #f8fafc;
            padding: 14px 16px;
            border-radius: 4px;
        }

        .story-product-modal__old-price {
            color: #9ca3af;
            font-size: 12px;
            text-decoration: line-through;
        }

        .story-product-modal__price {
            color: #1f2937;
            font-size: 20px;
            font-weight: 900;
        }

        .story-product-modal__club {
            display: inline-flex;
            margin-top: 8px;
            background: #fff0e8;
            color: #ff7a1a;
            border-radius: 3px;
            padding: 5px 8px;
            font-size: 10px;
            font-weight: 900;
        }

        .story-product-modal__buy {
            margin-top: 22px;
            border-top: 1px solid #eef2f7;
            padding-top: 18px;
        }

        .story-product-modal__buy-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 12px;
        }

        .story-product-modal__qty {
            display: inline-flex;
            align-items: center;
            gap: 18px;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 800;
        }

        .story-product-modal__stepper {
            display: inline-flex;
            align-items: center;
            border: 1px solid #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            color: #6b7280;
        }

        .story-product-modal__stepper span {
            min-width: 34px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .story-product-modal__stepper span:nth-child(2) {
            color: #111827;
            font-weight: 900;
        }

        .story-product-modal__actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .story-product-modal__buy-now,
        .story-product-modal__cart {
            border: 0;
            border-radius: 3px;
            padding: 13px 14px;
            font-size: 11px;
            font-weight: 900;
        }

        .story-product-modal__buy-now {
            background: #11111d;
            color: #fff;
        }

        .story-product-modal__cart {
            background: #dbeafe;
            color: #3b82f6;
        }

        .story-product-modal__media {
            height: 520px;
            min-height: 520px;
            background: #111827;
            overflow: hidden;
        }

        .story-product-modal__media-empty {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
        }

        @media (max-width: 767.98px) {
            .story-product-modal {
                border-radius: 18px;
            }

            .story-product-modal__layout {
                grid-template-columns: 1fr;
                border-radius: 18px;
            }

            .story-product-modal__media {
                height: 320px;
                min-height: 320px;
                order: -1;
            }

            .story-product-modal__details {
                padding: 20px;
            }

            .story-product-modal__product-area,
            .story-product-modal__gallery {
                grid-template-columns: 1fr;
            }

            .story-product-modal__thumbs {
                display: flex;
            }

            .story-product-modal__nav {
                display: none;
            }

            .showcase-story-home__rail {
                grid-auto-columns: minmax(220px, 250px);
                gap: 12px;
                padding-left: 8px;
                padding-right: 8px;
            }

            .showcase-story-home__card-inner {
                padding: 10px;
                border-radius: 16px;
            }

            .showcase-story-home__product-link {
                align-items: flex-start;
            }

            .showcase-story-home__product-btn {
                padding-left: 12px;
                padding-right: 12px;
            }

            .showcase-story-home__arrow {
                display: none;
            }
        }
    </style>

    <script>
        (function() {
            var homeStoryPreviewData = <?php echo json_encode($homeStoryPreviewPayload, 15, 512) ?>;
            var homeStoryPreviewMap = {};
            var currentHomeStoryPreviewId = null;
            var currentHomeStoryProductIndex = 0;

            homeStoryPreviewData.forEach(function(story) {
                homeStoryPreviewMap[String(story.id)] = story;
            });

            function getHomeStoryProductStories() {
                return homeStoryPreviewData.filter(function(story) {
                    return story && story.product;
                });
            }

            function getHomeStoryProducts(story) {
                if (!story) return [];
                if (Array.isArray(story.products) && story.products.length) {
                    return story.products;
                }
                return story.product ? [story.product] : [];
            }

            function escapeHomeStoryHtml(value) {
                return String(value || '').replace(/[&<>"']/g, function(char) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    }[char];
                });
            }

            window.shareHomeStoryProduct = function(url, title) {
                if (!url) return;
                if (navigator.share) {
                    navigator.share({ title: title || document.title, url: url }).catch(function() {});
                    return;
                }
                window.copyHomeStoryProduct(url);
            };

            window.copyHomeStoryProduct = function(url) {
                if (!url) return;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).catch(function() {});
                    return;
                }
                window.prompt('<?php echo e(translate('Copy product link')); ?>', url);
            };

            window.openHomeStoryProductPreview = function(storyId, productIndex) {
                if (!window.jQuery) return;
                var story = homeStoryPreviewMap[String(storyId)];
                var products = getHomeStoryProducts(story);
                var safeProductIndex = products.length
                    ? ((Number(productIndex || 0) % products.length) + products.length) % products.length
                    : 0;
                var product = products[safeProductIndex] || null;
                var modalBody = document.getElementById('homeStoryProductPreviewModalBody');
                if (!story || !product || !modalBody) return;
                currentHomeStoryPreviewId = story.id;
                currentHomeStoryProductIndex = safeProductIndex;

                var sellerLogo = story.seller_logo_url
                    ? '<img src="' + story.seller_logo_url + '" alt="">'
                    : escapeHomeStoryHtml((story.seller_name || 'S').charAt(0).toUpperCase());
                var shopName = story.shop_url
                    ? '<a href="' + story.shop_url + '" class="story-product-modal__shop-name">' + escapeHomeStoryHtml(story.seller_name || '') + '</a>'
                    : '<span class="story-product-modal__shop-name">' + escapeHomeStoryHtml(story.seller_name || '') + '</span>';
                var media = story.media_url
                    ? (story.media_is_video
                        ? '<video controls autoplay muted playsinline loop><source src="' + story.media_url + '"></video>'
                        : '<img src="' + story.media_url + '" alt="">')
                    : '<div class="story-product-modal__media-empty"><?php echo e(translate('Story Post')); ?></div>';
                var productImage = product.thumbnail_url ? '<img src="' + product.thumbnail_url + '" alt="">' : '';
                var categories = (story.hashtags || []).slice(0, 3).join(', ');
                var thumbs = products.map(function(item, index) {
                    var image = item.thumbnail_url ? '<img src="' + item.thumbnail_url + '" alt="">' : '<i class="las la-image"></i>';
                    return '<button type="button" class="story-product-modal__thumb' + (index === safeProductIndex ? ' story-product-modal__thumb--active' : '') + '" onclick="openHomeStoryProductPreview(' + story.id + ', ' + index + ')">' + image + '</button>';
                }).join('');
                if (products.length < 3) {
                    thumbs += '<div class="story-product-modal__thumb story-product-modal__thumb--empty"><i class="las la-image"></i></div>';
                }

                modalBody.innerHTML =
                    '<div class="story-product-modal__layout">' +
                        '<div class="story-product-modal__details">' +
                            '<div class="story-product-modal__shop-row">' +
                                '<span class="story-product-modal__shop-logo">' + sellerLogo + '</span>' +
                                shopName +
                                '<button type="button" class="story-product-modal__follow"><?php echo e(translate('Follow')); ?></button>' +
                            '</div>' +
                            '<div class="story-product-modal__breadcrumb">home / story / ' + escapeHomeStoryHtml(product.name || story.title || '') + '</div>' +
                            '<div class="story-product-modal__product-area">' +
                                '<div class="story-product-modal__thumbs">' +
                                    thumbs +
                                '</div>' +
                                '<div class="story-product-modal__gallery">' +
                                    '<div class="story-product-modal__gallery-image">' +
                                        productImage +
                                        '<button type="button" class="story-product-modal__gallery-btn" onclick="navigateHomeStoryProductPreviewProduct(1)" aria-label="<?php echo e(translate('Next product')); ?>"' + (products.length > 1 ? '' : ' style="display:none;"') + '><i class="las la-angle-right"></i></button>' +
                                        '<button type="button" class="story-product-modal__expand" aria-label="<?php echo e(translate('Preview')); ?>"><i class="las la-expand"></i></button>' +
                                    '</div>' +
                                    '<div class="story-product-modal__summary">' +
                                        '<div class="story-product-modal__title">' + escapeHomeStoryHtml(product.name || '') + '</div>' +
                                        '<div class="story-product-modal__brand-line"><?php echo e(translate('Brand')); ?> <strong>Lucky Brand</strong></div>' +
                                        '<div class="story-product-modal__rating"><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i> 0/5.0 (0 reviews)</div>' +
                                        '<div class="story-product-modal__ask"><i class="las la-question-circle"></i> <?php echo e(translate('Ask about this product')); ?></div>' +
                                        '<div class="story-product-modal__deal"><span><?php echo e(translate('Todays Deal')); ?></span><span><?php echo e(translate('Exclusive for today only')); ?></span></div>' +
                                        '<div class="story-product-modal__pricing">' +
                                            '<div class="story-product-modal__price">' + (product.price_html || '') + ' <span class="story-product-modal__brand-line"><?php echo e(translate('Piece')); ?></span></div>' +
                                            '<div class="story-product-modal__old-price">' + (product.price_html || '') + '</div>' +
                                            '<span class="story-product-modal__club">-5% CLUB POINT: 26.25</span>' +
                                        '</div>' +
                                        '<div class="story-product-modal__buy">' +
                                            '<div class="story-product-modal__buy-top">' +
                                                '<div>' +
                                                    '<div class="story-product-modal__price">' + (product.price_html || '') + '</div>' +
                                                    '<div class="story-product-modal__availability">100 available</div>' +
                                                    '<div class="story-product-modal__min-order">Minimum order qty 1</div>' +
                                                '</div>' +
                                                '<div class="story-product-modal__qty">' +
                                                    '<span>QTY</span>' +
                                                    '<span class="story-product-modal__stepper"><span>-</span><span>1</span><span>+</span></span>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="story-product-modal__actions">' +
                                                '<button type="button" class="story-product-modal__buy-now" onclick="showAddToCartModal(' + product.id + ')"><?php echo e(translate('Buy Now')); ?></button>' +
                                                '<button type="button" class="story-product-modal__cart" onclick="showAddToCartModal(' + product.id + ')"><?php echo e(translate('Add to cart')); ?> (01)</button>' +
                                            '</div>' +
                                        '</div>' +
                                        (categories ? '<div class="story-product-modal__brand-line mt-2"><?php echo e(translate('Categories')); ?>: ' + escapeHomeStoryHtml(categories) + '</div>' : '') +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="story-product-modal__media">' + media + '</div>' +
                    '</div>';

                $('#homeStoryProductPreviewModal').modal('show');
            };

            window.navigateHomeStoryProductPreview = function(direction) {
                var stories = getHomeStoryProductStories();
                if (!stories.length) return;

                var currentIndex = stories.findIndex(function(story) {
                    return String(story.id) === String(currentHomeStoryPreviewId);
                });
                if (currentIndex < 0) {
                    currentIndex = 0;
                }

                var nextIndex = (currentIndex + direction + stories.length) % stories.length;
                window.openHomeStoryProductPreview(stories[nextIndex].id, 0);
            };

            window.navigateHomeStoryProductPreviewProduct = function(direction) {
                var story = homeStoryPreviewMap[String(currentHomeStoryPreviewId)];
                var products = getHomeStoryProducts(story);
                if (!story || products.length < 2) return;

                var nextProductIndex = (currentHomeStoryProductIndex + direction + products.length) % products.length;
                window.openHomeStoryProductPreview(story.id, nextProductIndex);
            };

            function initShowcaseRailControls() {
                var section = document.querySelector('.showcase-story-home');
                if (!section) return;

                var rail = section.querySelector('[data-showcase-rail]');
                if (!rail || rail.dataset.controlsBound === '1') return;

                rail.dataset.controlsBound = '1';

                var leftBtn = section.querySelector('[data-showcase-scroll="left"]');
                var rightBtn = section.querySelector('[data-showcase-scroll="right"]');

                function getStep() {
                    return Math.max(260, Math.floor(rail.clientWidth * 0.72));
                }

                function updateArrowState() {
                    if (!leftBtn || !rightBtn) return;

                    var maxScrollLeft = rail.scrollWidth - rail.clientWidth - 4;
                    leftBtn.style.display = rail.scrollLeft <= 4 ? 'none' : 'inline-flex';
                    rightBtn.style.display = rail.scrollLeft >= maxScrollLeft ? 'none' : 'inline-flex';
                }

                if (leftBtn) {
                    leftBtn.addEventListener('click', function() {
                        rail.scrollBy({ left: -getStep(), behavior: 'smooth' });
                    });
                }

                if (rightBtn) {
                    rightBtn.addEventListener('click', function() {
                        rail.scrollBy({ left: getStep(), behavior: 'smooth' });
                    });
                }

                rail.addEventListener('scroll', updateArrowState, { passive: true });
                window.addEventListener('resize', updateArrowState);
                updateArrowState();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initShowcaseRailControls);
            } else {
                initShowcaseRailControls();
            }
        })();

        function showShowcaseEmptyProductModal() {
            if (!window.jQuery) return;
            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            $('#addToCart-modal-body').html(
                '<div class="p-4 p-md-5 text-center">' +
                    '<h4 class="fw-700 mb-2"><?php echo e(translate('No product found')); ?></h4>' +
                    '<p class="text-muted mb-0"><?php echo e(translate('There is no linked product available for this story right now.')); ?></p>' +
                '</div>'
            );
            $('.c-preloader').hide();
            $('#addToCart').modal();
        }
    </script>
<?php endif; ?>

<?php if($launchPayload->count()): ?>
    <section class="showcase-launch-home mb-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                <div>
                    <h3 class="h4 fw-700 mb-1"><?php echo e(translate('Launches')); ?></h3>
                    <p class="text-muted mb-0">
                        <?php echo e(translate('Launch previews styled like the backend preview.')); ?>

                    </p>
                </div>

                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <a href="<?php echo e(\Illuminate\Support\Facades\Route::has('frontend.showcase.launch_page') ? route('frontend.showcase.launch_page') : url('/showcase/launch-page')); ?>"
                       class="btn btn-outline-primary btn-sm">
                        <?php echo e(translate('View Page')); ?>

                    </a>
                    <a href="<?php echo e(route('frontend.showcase.index', ['type' => 'launch'])); ?>"
                       class="btn btn-soft-primary btn-sm">
                        <?php echo e(translate('View All')); ?>

                    </a>
                </div>
            </div>

            <div class="showcase-launch-home__rail-wrap">
                <button type="button"
                        class="showcase-launch-home__arrow showcase-launch-home__arrow--left"
                        data-launch-scroll="left"
                        aria-label="<?php echo e(translate('Scroll left')); ?>">
                    <span>&lsaquo;</span>
                </button>

                <div class="showcase-launch-home__rail" data-launch-rail>
                    <?php $__currentLoopData = $launchPayload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $launch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $sellerInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($launch['seller_name'] ?: 'S', 0, 1));
                            $launchShopUrl = !empty($launch['seller_slug']) ? route('shop.visit', $launch['seller_slug']) : '#';
                            $launchBrandUrl = !empty($launch['seller_slug']) ? route('frontend.showcase.brand', $launch['seller_slug']) : '#';
                            $launchIsFollowing = !empty($launch['seller_shop_id']) && in_array((int) $launch['seller_shop_id'], $followedSellerIds);
                            $launchFollowUrl = !empty($launch['seller_shop_id'])
                                ? ($launchIsFollowing
                                    ? route('followed_seller.remove', ['id' => $launch['seller_shop_id']])
                                    : route('followed_seller.store', ['id' => $launch['seller_shop_id']]))
                                : (auth()->check() ? '#' : route('user.login'));
                        ?>
                        <article class="showcase-launch-home__card">
                            <div class="showcase-launch-home__card-inner">
                                <div class="showcase-launch-home__head">
                                    <div class="showcase-launch-home__shop">
                                        <a href="<?php echo e($launchShopUrl); ?>" class="showcase-launch-home__logo">
                                            <?php if(!empty($launch['seller_logo_url'])): ?>
                                                <img src="<?php echo e($launch['seller_logo_url']); ?>" alt="<?php echo e($launch['seller_name']); ?>">
                                            <?php else: ?>
                                                <span><?php echo e($sellerInitial); ?></span>
                                            <?php endif; ?>
                                        </a>
                                        <a href="<?php echo e($launchShopUrl); ?>" class="showcase-launch-home__shop-name"><?php echo e($launch['seller_name']); ?></a>
                                    </div>
                                    <?php if($launchIsFollowing): ?>
                                        <div class="showcase-follow-menu">
                                            <button type="button" class="showcase-launch-home__follow showcase-follow-menu__toggle">
                                                <?php echo e(translate('Following')); ?> <i class="las la-angle-down"></i>
                                            </button>
                                            <div class="showcase-follow-menu__dropdown">
                                                <a href="<?php echo e($launchFollowUrl); ?>"><?php echo e(translate('Unfollow')); ?></a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?php echo e($launchFollowUrl); ?>" class="showcase-launch-home__follow">
                                            <?php echo e(translate('Follow')); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="showcase-launch-home__visuals">
                                    <div class="showcase-launch-home__main-visual">
                                        <?php if(!empty($launch['main_visual_url'])): ?>
                                            <img src="<?php echo e($launch['main_visual_url']); ?>" alt="<?php echo e($launch['title']); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="showcase-launch-home__side-visual">
                                        <?php if(!empty($launch['side_visual_url'])): ?>
                                            <img src="<?php echo e($launch['side_visual_url']); ?>" alt="<?php echo e($launch['title']); ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <a href="<?php echo e($launchBrandUrl); ?>" class="showcase-launch-home__brand"><?php echo e(translate('LUCKY BRAND')); ?></a>
                                <div class="showcase-launch-home__title"><?php echo e(\Illuminate\Support\Str::limit($launch['title'], 28)); ?></div>
                                <div class="showcase-launch-home__subtitle"><?php echo e(\Illuminate\Support\Str::limit($launch['subtitle'], 40)); ?></div>
                                <div class="showcase-launch-home__desc"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($launch['description']), 64)); ?></div>

                                <div class="showcase-launch-home__tags">
                                    <?php $__currentLoopData = array_slice($launch['hashtags'], 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span>#<?php echo e($tag); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div class="showcase-launch-home__product">
                                    <?php if(!empty($launch['product'])): ?>
                                        <div class="showcase-launch-home__product-card">
                                            <div class="showcase-launch-home__product-thumb">
                                                <?php if(!empty($launch['product']['thumbnail_url'])): ?>
                                                    <img src="<?php echo e($launch['product']['thumbnail_url']); ?>" alt="<?php echo e($launch['product']['name']); ?>">
                                                <?php endif; ?>
                                            </div>
                                            <div class="showcase-launch-home__product-name">
                                                <?php echo e(\Illuminate\Support\Str::limit($launch['product']['name'], 24)); ?>

                                            </div>
                                            <div class="showcase-launch-home__product-price">
                                                <?php echo e($launch['product']['price_html']); ?>

                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="showcase-launch-home__product-card showcase-launch-home__product-card--empty"></div>
                                    <?php endif; ?>
                                </div>

                                <button type="button"
                                        class="showcase-launch-home__cta"
                                        <?php if(!empty($launch['product'])): ?>
                                            onclick="showAddToCartModal(<?php echo e($launch['product']['id']); ?>)"
                                        <?php else: ?>
                                            onclick="showShowcaseEmptyProductModal()"
                                        <?php endif; ?>>
                                    <?php echo e(translate('Add to cart')); ?>

                                </button>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <button type="button"
                        class="showcase-launch-home__arrow showcase-launch-home__arrow--right"
                        data-launch-scroll="right"
                        aria-label="<?php echo e(translate('Scroll right')); ?>">
                    <span>&rsaquo;</span>
                </button>
            </div>
        </div>
    </section>

    <style>
        .showcase-launch-home__rail-wrap {
            position: relative;
        }

        .showcase-launch-home__rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(320px, 360px);
            gap: 18px;
            overflow-x: auto;
            padding: 6px 42px 10px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: x proximity;
        }

        .showcase-launch-home__rail::-webkit-scrollbar {
            display: none;
        }

        .showcase-launch-home__arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.78);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.22);
            cursor: pointer;
        }

        .showcase-launch-home__arrow--left {
            left: 2px;
        }

        .showcase-launch-home__arrow--right {
            right: 2px;
        }

        .showcase-launch-home__card {
            scroll-snap-align: start;
        }

        .showcase-launch-home__card-inner {
            height: 100%;
            display: flex;
            flex-direction: column;
            background: #cfa68f;
            color: #fff;
            border-radius: 18px;
            padding: 14px;
            box-shadow: 0 16px 30px rgba(121, 83, 61, 0.18);
        }

        .showcase-launch-home__head,
        .showcase-launch-home__shop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .showcase-launch-home__shop {
            justify-content: flex-start;
        }

        .showcase-launch-home__logo {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.7);
            color: #7a5a4b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex: 0 0 28px;
        }

        .showcase-launch-home__logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-launch-home__shop-name,
        .showcase-launch-home__title {
            font-size: 13px;
            font-weight: 800;
            line-height: 1.2;
            color: #fff;
            text-decoration: none;
        }

        .showcase-launch-home__follow {
            border: 0;
            background: rgba(255, 255, 255, 0.92);
            color: #7a5a4b;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .showcase-launch-home .showcase-follow-menu,
        .showcase-storefront-home .showcase-follow-menu {
            position: relative;
            display: inline-flex;
        }

        .showcase-launch-home .showcase-follow-menu__dropdown,
        .showcase-storefront-home .showcase-follow-menu__dropdown {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            z-index: 8;
            min-width: 118px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.14);
            padding: 6px;
            display: none;
        }

        .showcase-launch-home .showcase-follow-menu:hover .showcase-follow-menu__dropdown,
        .showcase-launch-home .showcase-follow-menu:focus-within .showcase-follow-menu__dropdown,
        .showcase-storefront-home .showcase-follow-menu:hover .showcase-follow-menu__dropdown,
        .showcase-storefront-home .showcase-follow-menu:focus-within .showcase-follow-menu__dropdown {
            display: block;
        }

        .showcase-launch-home .showcase-follow-menu__dropdown a,
        .showcase-storefront-home .showcase-follow-menu__dropdown a {
            display: block;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            padding: 7px 8px;
            border-radius: 6px;
        }

        .showcase-launch-home__visuals {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 64px;
            gap: 10px;
            margin-top: 12px;
            align-items: start;
        }

        .showcase-launch-home__main-visual,
        .showcase-launch-home__side-visual {
            border-radius: 14px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.2);
        }

        .showcase-launch-home__main-visual {
            min-height: 280px;
        }

        .showcase-launch-home__side-visual {
            height: 64px;
        }

        .showcase-launch-home__main-visual img,
        .showcase-launch-home__side-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-launch-home__brand,
        .showcase-launch-home__subtitle,
        .showcase-launch-home__desc,
        .showcase-launch-home__tags {
            color: rgba(255, 255, 255, 0.92);
        }

        .showcase-launch-home__brand {
            display: block;
            margin-top: 12px;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
        }

        .showcase-launch-home__title {
            margin-top: 6px;
            font-size: 26px;
            min-height: 32px;
        }

        .showcase-launch-home__subtitle,
        .showcase-launch-home__desc,
        .showcase-launch-home__tags {
            margin-top: 6px;
            font-size: 12px;
            line-height: 1.35;
            min-height: 16px;
        }

        .showcase-launch-home__product {
            margin-top: 14px;
        }

        .showcase-launch-home__product-card {
            min-height: 58px;
            display: grid;
            grid-template-columns: 42px 1fr auto;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.96);
            color: #7a5a4b;
            border-radius: 12px;
            padding: 8px 10px;
        }

        .showcase-launch-home__product-card--empty {
            background: rgba(255, 255, 255, 0.25);
        }

        .showcase-launch-home__product-thumb {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            overflow: hidden;
            background: #f3f4f6;
        }

        .showcase-launch-home__product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-launch-home__product-name,
        .showcase-launch-home__product-price {
            font-size: 13px;
            font-weight: 800;
            line-height: 1.2;
        }

        .showcase-launch-home__cta {
            width: calc(100% + 28px);
            margin: auto -14px -14px;
            margin-top: 14px;
            border: 0;
            background: #b78f78;
            color: #fff;
            padding: 12px 14px;
            border-radius: 0 0 18px 18px;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        @media (max-width: 767.98px) {
            .showcase-launch-home__rail {
                grid-auto-columns: minmax(280px, 320px);
                gap: 12px;
                padding-left: 8px;
                padding-right: 8px;
            }

            .showcase-launch-home__arrow {
                display: none;
            }
        }
    </style>

    <script>
        (function() {
            function bindLaunchRail() {
                var section = document.querySelector('.showcase-launch-home');
                if (!section) return;

                var rail = section.querySelector('[data-launch-rail]');
                if (!rail || rail.dataset.controlsBound === '1') return;
                rail.dataset.controlsBound = '1';

                var leftBtn = section.querySelector('[data-launch-scroll="left"]');
                var rightBtn = section.querySelector('[data-launch-scroll="right"]');

                function getStep() {
                    return Math.max(300, Math.floor(rail.clientWidth * 0.72));
                }

                function updateArrowState() {
                    if (!leftBtn || !rightBtn) return;
                    var maxScrollLeft = rail.scrollWidth - rail.clientWidth - 4;
                    leftBtn.style.display = rail.scrollLeft <= 4 ? 'none' : 'inline-flex';
                    rightBtn.style.display = rail.scrollLeft >= maxScrollLeft ? 'none' : 'inline-flex';
                }

                leftBtn && leftBtn.addEventListener('click', function() {
                    rail.scrollBy({ left: -getStep(), behavior: 'smooth' });
                });
                rightBtn && rightBtn.addEventListener('click', function() {
                    rail.scrollBy({ left: getStep(), behavior: 'smooth' });
                });
                rail.addEventListener('scroll', updateArrowState, { passive: true });
                window.addEventListener('resize', updateArrowState);
                updateArrowState();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', bindLaunchRail);
            } else {
                bindLaunchRail();
            }
        })();
    </script>
<?php endif; ?>

<?php if($storefrontPayload->count()): ?>
    <section class="showcase-storefront-home mb-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                <div>
                    <h3 class="h4 fw-700 mb-1"><?php echo e(translate('Storefronts')); ?></h3>
                    <p class="text-muted mb-0">
                        <?php echo e(translate('Storefront previews styled like the backend preview.')); ?>

                    </p>
                </div>

                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <a href="<?php echo e(\Illuminate\Support\Facades\Route::has('frontend.showcase.storefront_page') ? route('frontend.showcase.storefront_page') : url('/showcase/storefront-page')); ?>"
                       class="btn btn-outline-primary btn-sm">
                        <?php echo e(translate('View Page')); ?>

                    </a>
                    <a href="<?php echo e(route('frontend.showcase.index', ['type' => 'vitrin'])); ?>"
                       class="btn btn-soft-primary btn-sm">
                        <?php echo e(translate('View All')); ?>

                    </a>
                </div>
            </div>

            <div class="showcase-storefront-home__rail-wrap">
                <button type="button"
                        class="showcase-storefront-home__arrow showcase-storefront-home__arrow--left"
                        data-storefront-scroll="left"
                        aria-label="<?php echo e(translate('Scroll left')); ?>">
                    <span>&lsaquo;</span>
                </button>

                <div class="showcase-storefront-home__rail" data-storefront-rail>
                    <?php $__currentLoopData = $storefrontPayload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storefront): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $sellerInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($storefront['seller_name'] ?: 'S', 0, 1));
                            $storefrontShopUrl = !empty($storefront['seller_slug']) ? route('shop.visit', $storefront['seller_slug']) : '#';
                            $storefrontBrandUrl = !empty($storefront['seller_slug']) ? route('frontend.showcase.brand', $storefront['seller_slug']) : '#';
                            $storefrontIsFollowing = !empty($storefront['seller_shop_id']) && in_array((int) $storefront['seller_shop_id'], $followedSellerIds);
                            $storefrontFollowUrl = !empty($storefront['seller_shop_id'])
                                ? ($storefrontIsFollowing
                                    ? route('followed_seller.remove', ['id' => $storefront['seller_shop_id']])
                                    : route('followed_seller.store', ['id' => $storefront['seller_shop_id']]))
                                : (auth()->check() ? '#' : route('user.login'));
                        ?>
                        <article class="showcase-storefront-home__card">
                            <div class="showcase-storefront-home__card-inner">
                                <div class="showcase-storefront-home__head">
                                    <div class="showcase-storefront-home__shop">
                                        <a href="<?php echo e($storefrontShopUrl); ?>" class="showcase-storefront-home__logo">
                                            <?php if(!empty($storefront['seller_logo_url'])): ?>
                                                <img src="<?php echo e($storefront['seller_logo_url']); ?>" alt="<?php echo e($storefront['seller_name']); ?>">
                                            <?php else: ?>
                                                <span><?php echo e($sellerInitial); ?></span>
                                            <?php endif; ?>
                                        </a>

                                        <div class="showcase-storefront-home__shop-meta">
                                            <a href="<?php echo e($storefrontShopUrl); ?>" class="showcase-storefront-home__shop-name">
                                                <?php echo e(\Illuminate\Support\Str::limit($storefront['seller_name'], 22)); ?>

                                            </a>
                                            <a href="<?php echo e($storefrontBrandUrl); ?>" class="showcase-storefront-home__shop-brand"><?php echo e(translate('lucky Brand')); ?></a>
                                        </div>
                                    </div>

                                    <?php if($storefrontIsFollowing): ?>
                                        <div class="showcase-follow-menu">
                                            <button type="button" class="showcase-storefront-home__follow showcase-follow-menu__toggle">
                                                <?php echo e(translate('Following')); ?> <i class="las la-angle-down"></i>
                                            </button>
                                            <div class="showcase-follow-menu__dropdown">
                                                <a href="<?php echo e($storefrontFollowUrl); ?>"><?php echo e(translate('Unfollow')); ?></a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?php echo e($storefrontFollowUrl); ?>" class="showcase-storefront-home__follow">
                                            <?php echo e(translate('Follow')); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="showcase-storefront-home__title">
                                    <?php echo e(\Illuminate\Support\Str::limit($storefront['title'], 32)); ?>

                                </div>

                                <?php if(!empty($storefront['description'])): ?>
                                    <div class="showcase-storefront-home__desc">
                                        <?php echo e(\Illuminate\Support\Str::limit(strip_tags($storefront['description']), 48)); ?>

                                    </div>
                                <?php endif; ?>

                                <div class="showcase-storefront-home__media">
                                    <?php if(!empty($storefront['main_visual_url'])): ?>
                                        <img src="<?php echo e($storefront['main_visual_url']); ?>" alt="<?php echo e($storefront['title']); ?>">
                                    <?php endif; ?>
                                </div>

                                <div class="showcase-storefront-home__thumb-slider<?php echo e(count($storefront['products'] ?? []) > 3 ? ' has-arrows' : ''); ?>">
                                    <button type="button"
                                            class="showcase-storefront-home__thumb-arrow showcase-storefront-home__thumb-arrow--left"
                                            data-storefront-thumb-scroll="left"
                                            aria-label="<?php echo e(translate('Previous products')); ?>">
                                        <i class="las la-angle-left"></i>
                                    </button>

                                    <div class="showcase-storefront-home__thumbs" data-storefront-thumb-rail>
                                        <?php $__empty_1 = true; $__currentLoopData = $storefront['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thumbProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="showcase-storefront-home__thumb">
                                                <?php if(!empty($thumbProduct['thumbnail_url'])): ?>
                                                    <img src="<?php echo e($thumbProduct['thumbnail_url']); ?>" alt="<?php echo e($thumbProduct['name']); ?>">
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <?php $__currentLoopData = range(0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emptyThumbIndex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="showcase-storefront-home__thumb showcase-storefront-home__thumb--empty"></div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>

                                    <button type="button"
                                            class="showcase-storefront-home__thumb-arrow showcase-storefront-home__thumb-arrow--right"
                                            data-storefront-thumb-scroll="right"
                                            aria-label="<?php echo e(translate('Next products')); ?>">
                                        <i class="las la-angle-right"></i>
                                    </button>
                                </div>

                                <div class="showcase-storefront-home__label">
                                    <?php echo e(translate('Shop our selection')); ?>

                                </div>

                                <div class="showcase-storefront-home__cta-wrap">
                                    <button type="button"
                                            class="showcase-storefront-home__cta"
                                            onclick="openShowcaseStorefrontModal(<?php echo e($storefront['id']); ?>)">
                                        <?php echo e(translate('View Product')); ?>

                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <button type="button"
                        class="showcase-storefront-home__arrow showcase-storefront-home__arrow--right"
                        data-storefront-scroll="right"
                        aria-label="<?php echo e(translate('Scroll right')); ?>">
                    <span>&rsaquo;</span>
                </button>
            </div>
        </div>
    </section>

    <div class="modal fade" id="showcaseStorefrontModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content showcase-storefront-modal">
                <button type="button" class="close absolute-top-right btn-icon close z-1 btn-circle hov-text-blue bg-light hov-bg-gray has-transition mr-3 mt-3 d-flex justify-content-center align-items-center" data-dismiss="modal" aria-label="Close" style="background: #ededf2; width: calc(2rem + 2px); height: calc(2rem + 2px);">
                    <i class="la la-close fs-20 text-gray hov-text-blue has-transition"></i>
                </button>
                <div class="modal-body p-3 p-lg-4" id="showcaseStorefrontModalBody"></div>
            </div>
        </div>
    </div>

    <style>
        .showcase-storefront-home__rail-wrap {
            position: relative;
        }

        .showcase-storefront-home__rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(280px, 320px);
            gap: 18px;
            overflow-x: auto;
            padding: 6px 42px 10px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: x proximity;
        }

        .showcase-storefront-home__rail::-webkit-scrollbar {
            display: none;
        }

        .showcase-storefront-home__arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.78);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.22);
            cursor: pointer;
        }

        .showcase-storefront-home__arrow--left {
            left: 2px;
        }

        .showcase-storefront-home__arrow--right {
            right: 2px;
        }

        .showcase-storefront-home__card {
            scroll-snap-align: start;
        }

        .showcase-storefront-home__card-inner,
        .showcase-storefront-modal__wrap {
            background: #fff;
            border: 1px solid #dfe4ec;
            border-radius: 18px;
            padding: 14px;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        }

        .showcase-storefront-home__card-inner {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .showcase-storefront-home__head,
        .showcase-storefront-home__shop,
        .showcase-storefront-modal__head,
        .showcase-storefront-modal__shop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .showcase-storefront-home__shop,
        .showcase-storefront-modal__shop {
            justify-content: flex-start;
        }

        .showcase-storefront-home__logo,
        .showcase-storefront-modal__logo {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            overflow: hidden;
            background: #eef2f7;
            color: #7a5a4b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex: 0 0 40px;
        }

        .showcase-storefront-home__logo img,
        .showcase-storefront-modal__logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-storefront-home__shop-name,
        .showcase-storefront-modal__shop-name {
            display: block;
            font-size: 13px;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            text-decoration: none;
        }

        .showcase-storefront-home__shop-brand,
        .showcase-storefront-modal__shop-brand,
        .showcase-storefront-home__desc,
        .showcase-storefront-modal__desc {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.35;
            text-decoration: none;
        }

        .showcase-storefront-home__follow,
        .showcase-storefront-modal__follow {
            border: 0;
            background: #f4f1fb;
            color: #6f52d9;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .showcase-storefront-home__title,
        .showcase-storefront-modal__title {
            margin-top: 12px;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.15;
            color: #111827;
            min-height: 50px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .showcase-storefront-home__media,
        .showcase-storefront-modal__media {
            margin-top: 12px;
            border-radius: 14px;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .showcase-storefront-home__media {
            height: 220px;
        }

        .showcase-storefront-home__media img,
        .showcase-storefront-modal__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-storefront-home__thumb-slider {
            position: relative;
            margin-top: 12px;
        }

        .showcase-storefront-home__thumbs {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: calc((100% - 20px) / 3);
            gap: 10px;
            min-height: 86px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            align-items: start;
        }

        .showcase-storefront-home__thumb-slider.has-arrows .showcase-storefront-home__thumbs {
            padding: 0 22px;
        }

        .showcase-storefront-home__thumbs::-webkit-scrollbar {
            display: none;
        }

        .showcase-storefront-home__thumb {
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            overflow: hidden;
            background: #f3f4f6;
            scroll-snap-align: start;
        }

        .showcase-storefront-home__thumb--empty {
            border: 1px dashed #d8dee8;
            background: #f8fafc;
        }

        .showcase-storefront-home__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-storefront-home__thumb-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.82);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.18);
        }

        .showcase-storefront-home__thumb-slider.has-arrows .showcase-storefront-home__thumb-arrow {
            display: inline-flex;
        }

        .showcase-storefront-home__thumb-arrow--left {
            left: 0;
        }

        .showcase-storefront-home__thumb-arrow--right {
            right: 0;
        }

        .showcase-storefront-home__label,
        .showcase-storefront-modal__label {
            margin-top: 10px;
            font-size: 13px;
            font-weight: 800;
            color: #111827;
        }

        .showcase-storefront-home__cta-wrap {
            margin-top: auto;
            padding-top: 14px;
        }

        .showcase-storefront-home__cta {
            width: 100%;
            border: 0;
            background: #111827;
            color: #fff;
            border-radius: 999px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 800;
        }

        .showcase-storefront-modal {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
        }

        .showcase-storefront-modal__media {
            height: 320px;
            margin-top: 16px;
        }

        .showcase-storefront-modal__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 14px;
        }

        .showcase-storefront-modal__product {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
        }

        .showcase-storefront-modal__product-thumb {
            aspect-ratio: 16 / 10;
            background: #f3f4f6;
        }

        .showcase-storefront-modal__product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-storefront-modal__product-body {
            padding: 10px 12px 12px;
        }

        .showcase-storefront-modal__product-name {
            font-size: 13px;
            font-weight: 800;
            color: #111827;
            line-height: 1.25;
        }

        .showcase-storefront-modal__product-price {
            margin-top: 6px;
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .showcase-storefront-modal__product-btn {
            width: 100%;
            margin-top: 10px;
            border: 0;
            background: #d2a589;
            color: #fff;
            border-radius: 999px;
            padding: 9px 12px;
            font-size: 12px;
            font-weight: 700;
        }

        @media (max-width: 767.98px) {
            .showcase-storefront-home__rail {
                grid-auto-columns: minmax(260px, 300px);
                gap: 12px;
                padding-left: 8px;
                padding-right: 8px;
            }

            .showcase-storefront-home__arrow {
                display: none;
            }

            .showcase-storefront-modal__grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        (function() {
            var storefrontData = <?php echo json_encode($storefrontPayload, 15, 512) ?>;
            var storefrontMap = {};

            storefrontData.forEach(function(item) {
                storefrontMap[String(item.id)] = item;
            });

            window.openShowcaseStorefrontModal = function(storefrontId) {
                if (!window.jQuery) return;
                var storefront = storefrontMap[String(storefrontId)];
                var modalBody = document.getElementById('showcaseStorefrontModalBody');
                if (!storefront || !modalBody) return;

                modalBody.innerHTML =
                    '<div class="showcase-storefront-modal__wrap">' +
                        '<div class="showcase-storefront-modal__head">' +
                            '<div class="showcase-storefront-modal__shop">' +
                                '<div class="showcase-storefront-modal__logo">' +
                                    (storefront.seller_slug ? '<a href="<?php echo e(url('/shop')); ?>/' + storefront.seller_slug + '">' : '') +
                                    (storefront.seller_logo_url ? '<img src="' + storefront.seller_logo_url + '" alt="">' : '<span>' + ((storefront.seller_name || 'S').charAt(0).toUpperCase()) + '</span>') +
                                    (storefront.seller_slug ? '</a>' : '') +
                                '</div>' +
                                '<div>' +
                                    '<a class="showcase-storefront-modal__shop-name" href="<?php echo e(url('/shop')); ?>/' + (storefront.seller_slug || '') + '">' + (storefront.seller_name || '') + '</a>' +
                                    '<a class="showcase-storefront-modal__shop-brand" href="<?php echo e(url('/showcase/brand')); ?>/' + (storefront.seller_slug || '') + '">lucky Brand</a>' +
                                '</div>' +
                            '</div>' +
                            '<button type="button" class="showcase-storefront-modal__follow"><?php echo e(translate('Follow')); ?></button>' +
                        '</div>' +
                        '<div class="showcase-storefront-modal__title">' + (storefront.title || '') + '</div>' +
                        '<div class="showcase-storefront-modal__desc">' + (storefront.description || '') + '</div>' +
                        '<div class="showcase-storefront-modal__media">' +
                            (storefront.main_visual_url ? '<img src="' + storefront.main_visual_url + '" alt="">' : '') +
                        '</div>' +
                        '<div class="showcase-storefront-modal__label"><?php echo e(translate('Shop our selection')); ?></div>' +
                        '<div class="showcase-storefront-modal__grid">' +
                            (storefront.products || []).map(function(product) {
                                return '' +
                                    '<div class="showcase-storefront-modal__product">' +
                                        '<div class="showcase-storefront-modal__product-thumb">' +
                                            (product.thumbnail_url ? '<img src="' + product.thumbnail_url + '" alt="">' : '') +
                                        '</div>' +
                                        '<div class="showcase-storefront-modal__product-body">' +
                                            '<div class="showcase-storefront-modal__product-name">' + (product.name || '') + '</div>' +
                                            '<div class="showcase-storefront-modal__product-price">' + (product.price_html || '') + '</div>' +
                                            '<button type="button" class="showcase-storefront-modal__product-btn" onclick="showAddToCartModal(' + product.id + ')"><?php echo e(translate('Add to cart')); ?></button>' +
                                        '</div>' +
                                    '</div>';
                            }).join('') +
                        '</div>' +
                    '</div>';

                $('#showcaseStorefrontModal').modal('show');
            };

            function bindStorefrontRail() {
                var section = document.querySelector('.showcase-storefront-home');
                if (!section) return;

                var rail = section.querySelector('[data-storefront-rail]');
                if (!rail || rail.dataset.controlsBound === '1') return;
                rail.dataset.controlsBound = '1';

                var leftBtn = section.querySelector('[data-storefront-scroll="left"]');
                var rightBtn = section.querySelector('[data-storefront-scroll="right"]');

                function getStep() {
                    return Math.max(280, Math.floor(rail.clientWidth * 0.7));
                }

                function updateArrowState() {
                    if (!leftBtn || !rightBtn) return;
                    var maxScrollLeft = rail.scrollWidth - rail.clientWidth - 4;
                    leftBtn.style.display = rail.scrollLeft <= 4 ? 'none' : 'inline-flex';
                    rightBtn.style.display = rail.scrollLeft >= maxScrollLeft ? 'none' : 'inline-flex';
                }

                leftBtn && leftBtn.addEventListener('click', function() {
                    rail.scrollBy({ left: -getStep(), behavior: 'smooth' });
                });
                rightBtn && rightBtn.addEventListener('click', function() {
                    rail.scrollBy({ left: getStep(), behavior: 'smooth' });
                });
                rail.addEventListener('scroll', updateArrowState, { passive: true });
                window.addEventListener('resize', updateArrowState);
                updateArrowState();
            }

            function bindStorefrontThumbRails() {
                document.querySelectorAll('[data-storefront-thumb-rail]').forEach(function(rail) {
                    if (rail.dataset.controlsBound === '1') return;
                    rail.dataset.controlsBound = '1';

                    var slider = rail.closest('.showcase-storefront-home__thumb-slider');
                    var leftBtn = slider ? slider.querySelector('[data-storefront-thumb-scroll="left"]') : null;
                    var rightBtn = slider ? slider.querySelector('[data-storefront-thumb-scroll="right"]') : null;

                    function getStep() {
                        var firstThumb = rail.querySelector('.showcase-storefront-home__thumb');
                        return firstThumb ? firstThumb.offsetWidth + 10 : Math.floor(rail.clientWidth / 3);
                    }

                    function updateThumbArrowState() {
                        if (!leftBtn || !rightBtn || !slider) return;

                        var hasOverflow = rail.scrollWidth > rail.clientWidth + 4;
                        slider.classList.toggle('has-arrows', hasOverflow);

                        if (!hasOverflow) {
                            leftBtn.style.display = 'none';
                            rightBtn.style.display = 'none';
                            return;
                        }

                        var maxScrollLeft = rail.scrollWidth - rail.clientWidth - 4;
                        leftBtn.style.display = rail.scrollLeft <= 4 ? 'none' : 'inline-flex';
                        rightBtn.style.display = rail.scrollLeft >= maxScrollLeft ? 'none' : 'inline-flex';
                    }

                    leftBtn && leftBtn.addEventListener('click', function() {
                        rail.scrollBy({ left: -getStep(), behavior: 'smooth' });
                    });

                    rightBtn && rightBtn.addEventListener('click', function() {
                        rail.scrollBy({ left: getStep(), behavior: 'smooth' });
                    });

                    rail.addEventListener('scroll', updateThumbArrowState, { passive: true });
                    window.addEventListener('resize', updateThumbArrowState);
                    updateThumbArrowState();
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    bindStorefrontRail();
                    bindStorefrontThumbRails();
                });
            } else {
                bindStorefrontRail();
                bindStorefrontThumbRails();
            }
        })();
    </script>
<?php endif; ?>

<?php if($collectionPayload->count()): ?>
    <section class="showcase-collection-home mb-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                <div>
                    <h3 class="h4 fw-700 mb-1"><?php echo e(translate('Collections')); ?></h3>
                    <p class="text-muted mb-0">
                        <?php echo e(translate('Featured collection previews styled like the backend preview.')); ?>

                    </p>
                </div>

                <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                    <a href="<?php echo e(\Illuminate\Support\Facades\Route::has('frontend.showcase.collection_page') ? route('frontend.showcase.collection_page') : url('/showcase/collection-page')); ?>"
                       class="btn btn-outline-secondary btn-sm">
                        <?php echo e(translate('View Page')); ?>

                    </a>
                    <a href="<?php echo e(route('frontend.showcase.index', ['type' => 'collection'])); ?>"
                       class="btn btn-soft-primary btn-sm">
                        <?php echo e(translate('View All')); ?>

                    </a>
                </div>
            </div>

            <div class="showcase-collection-home__rail-wrap">
                <button type="button"
                        class="showcase-collection-home__arrow showcase-collection-home__arrow--left"
                        data-collection-scroll="left"
                        aria-label="<?php echo e(translate('Scroll left')); ?>">
                    <span>&lsaquo;</span>
                </button>

                <div class="showcase-collection-home__rail" data-collection-rail>
                    <?php $__currentLoopData = $collectionPayload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $sellerInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($collection['seller_name'] ?: 'S', 0, 1));
                            $firstCollectionItem = $collection['items'][0] ?? null;
                            $collectionShopUrl = !empty($collection['seller_slug']) ? route('shop.visit', $collection['seller_slug']) : '#';
                            $collectionBrandUrl = !empty($collection['seller_slug']) ? route('frontend.showcase.brand', $collection['seller_slug']) : '#';
                        ?>
                        <article class="showcase-collection-home__card"
                                 data-collection-card
                                 data-collection='<?php echo json_encode($collection, 15, 512) ?>'
                                 data-collection-index="0">
                            <div class="showcase-collection-home__card-inner">
                                <div class="showcase-collection-home__head">
                                    <div class="showcase-collection-home__shop">
                                        <a href="<?php echo e($collectionShopUrl); ?>" class="showcase-collection-home__logo">
                                            <?php if(!empty($collection['seller_logo_url'])): ?>
                                                <img src="<?php echo e($collection['seller_logo_url']); ?>" alt="<?php echo e($collection['seller_name']); ?>">
                                            <?php else: ?>
                                                <span><?php echo e($sellerInitial); ?></span>
                                            <?php endif; ?>
                                        </a>
                                        <div class="showcase-collection-home__shop-meta">
                                            <a href="<?php echo e($collectionBrandUrl); ?>" class="showcase-collection-home__shop-name">
                                                <?php echo e(\Illuminate\Support\Str::limit($collection['title'], 24)); ?>

                                            </a>
                                            <div class="showcase-collection-home__shop-type">
                                                <?php echo e(translate('By')); ?> - <a href="<?php echo e($collectionShopUrl); ?>"><?php echo e($collection['seller_name']); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="showcase-collection-home__preview">
                                    <?php if(!empty($collection['description'])): ?>
                                        <div class="showcase-collection-home__desc">
                                            <?php echo e(\Illuminate\Support\Str::limit(strip_tags($collection['description']), 50)); ?>

                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty($collection['hashtags'])): ?>
                                        <div class="showcase-collection-home__hashtags">
                                            <?php $__currentLoopData = array_slice($collection['hashtags'], 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span>#<?php echo e($tag); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="showcase-collection-home__slider">
                                        <button type="button"
                                                class="showcase-collection-home__slide-nav showcase-collection-home__slide-nav--left"
                                                data-collection-slide="prev"
                                                aria-label="<?php echo e(translate('Previous item')); ?>">
                                            &lsaquo;
                                        </button>

                                        <div class="showcase-collection-home__media" data-collection-media>
                                            <?php if(!empty($firstCollectionItem['cover_image_url'])): ?>
                                                <img src="<?php echo e($firstCollectionItem['cover_image_url']); ?>" alt="<?php echo e($firstCollectionItem['title']); ?>">
                                            <?php endif; ?>
                                        </div>

                                        <button type="button"
                                                class="showcase-collection-home__slide-nav showcase-collection-home__slide-nav--right"
                                                data-collection-slide="next"
                                                aria-label="<?php echo e(translate('Next item')); ?>">
                                            &rsaquo;
                                        </button>
                                    </div>

                                    <div class="showcase-collection-home__active-title" data-collection-active-title>
                                        <?php echo e($firstCollectionItem['title'] ?? translate('Collection Item')); ?>

                                    </div>
                                    <div class="showcase-collection-home__active-desc" data-collection-active-desc>
                                        <?php echo e(!empty($firstCollectionItem['description']) ? \Illuminate\Support\Str::limit(strip_tags($firstCollectionItem['description']), 58) : ''); ?>

                                    </div>

                                    <div class="showcase-collection-home__linked" data-collection-product>
                                        <?php if(!empty($firstCollectionItem['product'])): ?>
                                            <div class="showcase-collection-home__linked-card">
                                                <div class="showcase-collection-home__linked-tools">
                                                    <button type="button"
                                                            class="showcase-collection-home__tool-btn"
                                                            onclick='shareShowcaseCollectionProduct(<?php echo json_encode($firstCollectionItem['product']['product_url'] ?? '', 15, 512) ?>, <?php echo json_encode($firstCollectionItem['product']['name'] ?? '', 15, 512) ?>)'
                                                            aria-label="<?php echo e(translate('Share product')); ?>">
                                                        <i class="las la-share-alt"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="showcase-collection-home__tool-btn"
                                                            onclick='copyShowcaseCollectionProduct(<?php echo json_encode($firstCollectionItem['product']['product_url'] ?? '', 15, 512) ?>)'
                                                            aria-label="<?php echo e(translate('Copy product link')); ?>">
                                                        <i class="las la-code"></i>
                                                    </button>
                                                </div>
                                                <div class="showcase-collection-home__linked-thumb">
                                                    <?php if(!empty($firstCollectionItem['product']['thumbnail_url'])): ?>
                                                        <img src="<?php echo e($firstCollectionItem['product']['thumbnail_url']); ?>" alt="<?php echo e($firstCollectionItem['product']['name']); ?>">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="showcase-collection-home__linked-body">
                                                    <div class="showcase-collection-home__linked-name">
                                                        <?php echo e(\Illuminate\Support\Str::words($firstCollectionItem['product']['name'], 3, '...')); ?>

                                                    </div>
                                                    <div class="showcase-collection-home__linked-price">
                                                        <?php echo e($firstCollectionItem['product']['price_html']); ?>

                                                    </div>
                                                </div>
                                                <button type="button"
                                                        class="showcase-collection-home__cart-btn"
                                                        onclick="showAddToCartModal(<?php echo e($firstCollectionItem['product']['id']); ?>)"
                                                        aria-label="<?php echo e(translate('Add to cart')); ?>">
                                                    <i class="las la-shopping-cart"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <button type="button"
                                            class="showcase-collection-home__btn"
                                            onclick="openShowcaseCollectionModal(<?php echo e($collection['id']); ?>)">
                                        <?php echo e(translate('View Collection')); ?>

                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <button type="button"
                        class="showcase-collection-home__arrow showcase-collection-home__arrow--right"
                        data-collection-scroll="right"
                        aria-label="<?php echo e(translate('Scroll right')); ?>">
                    <span>&rsaquo;</span>
                </button>
            </div>
        </div>
    </section>

    <div class="modal fade" id="showcaseCollectionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content showcase-collection-modal">
                <button type="button" class="close absolute-top-right btn-icon close z-1 btn-circle hov-text-blue bg-light hov-bg-gray has-transition mr-3 mt-3 d-flex justify-content-center align-items-center" data-dismiss="modal" aria-label="Close" style="background: #ededf2; width: calc(2rem + 2px); height: calc(2rem + 2px);">
                    <i class="la la-close fs-20 text-gray hov-text-blue has-transition"></i>
                </button>
                <div class="modal-body p-3 p-lg-4" id="showcaseCollectionModalBody"></div>
            </div>
        </div>
    </div>

    <style>
        .showcase-collection-home__rail-wrap {
            position: relative;
        }

        .showcase-collection-home__rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(260px, 290px);
            gap: 18px;
            overflow-x: auto;
            padding: 6px 42px 10px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: x proximity;
        }

        .showcase-collection-home__rail::-webkit-scrollbar {
            display: none;
        }

        .showcase-collection-home__arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.78);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.22);
            cursor: pointer;
        }

        .showcase-collection-home__arrow--left {
            left: 2px;
        }

        .showcase-collection-home__arrow--right {
            right: 2px;
        }

        .showcase-collection-home__card {
            scroll-snap-align: start;
        }

        .showcase-collection-home__card-inner {
            border-radius: 22px;
            background: #d2a589;
            padding: 12px;
            color: #fff;
            box-shadow: 0 16px 32px rgba(121, 83, 61, 0.18);
        }

        .showcase-collection-home__head,
        .showcase-collection-home__shop {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .showcase-collection-home__logo {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.75);
            color: #7a5a4b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex: 0 0 36px;
            text-decoration: none;
        }

        .showcase-collection-home__logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-collection-home__shop-name {
            display: block;
            font-weight: 700;
            font-size: 13px;
            line-height: 1.2;
            color: #fff;
            text-decoration: none;
        }

        .showcase-collection-home__shop-type,
        .showcase-collection-home__by,
        .showcase-collection-home__desc,
        .showcase-collection-home__active-desc {
            font-size: 11px;
            line-height: 1.35;
            color: rgba(255, 255, 255, 0.92);
        }

        .showcase-collection-home__shop-type a {
            color: inherit;
            font-weight: 800;
            text-decoration: none;
        }

        .showcase-collection-home__hashtags {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
            margin: 8px 0 0;
            font-size: 11px;
            font-weight: 600;
        }

        .showcase-collection-home__preview {
            border-radius: 18px;
            padding: 12px;
            background: transparent;
        }

        .showcase-collection-home__title,
        .showcase-collection-home__active-title {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.15;
            text-align: center;
            margin-bottom: 6px;
        }

        .showcase-collection-home__active-title {
            font-size: 15px;
            margin-top: 12px;
            margin-bottom: 4px;
        }

        .showcase-collection-home__by,
        .showcase-collection-home__desc {
            text-align: center;
        }

        .showcase-collection-home__slider {
            position: relative;
            margin-top: 12px;
        }

        .showcase-collection-home__media {
            aspect-ratio: 1 / 1;
            border-radius: 16px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.2);
        }

        .showcase-collection-home__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-collection-home__slide-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            width: 28px;
            height: 28px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.7);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .showcase-collection-home__slide-nav--left {
            left: 10px;
        }

        .showcase-collection-home__slide-nav--right {
            right: 10px;
        }

        .showcase-collection-home__linked {
            margin-top: 10px;
            min-height: 70px;
        }

        .showcase-collection-home__linked-card {
            display: grid;
            grid-template-columns: 26px 44px minmax(0, 1fr) 40px;
            align-items: center;
            gap: 10px;
            background: #fff;
            color: #7a5a4b;
            border-radius: 14px;
            padding: 8px;
        }

        .showcase-collection-home__linked-tools {
            display: grid;
            gap: 4px;
            align-self: stretch;
            background: #f4eddc;
            border-radius: 8px;
            padding: 5px 0;
        }

        .showcase-collection-home__tool-btn {
            width: 26px;
            height: 22px;
            border: 0;
            background: transparent;
            color: #7a5a4b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
        }

        .showcase-collection-home__linked-thumb {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            overflow: hidden;
            background: #f3f4f6;
            flex: 0 0 44px;
        }

        .showcase-collection-home__linked-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-collection-home__linked-name {
            font-size: 12px;
            font-weight: 800;
            line-height: 1.25;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .showcase-collection-home__linked-price {
            margin-top: 3px;
            font-size: 12px;
            font-weight: 700;
        }

        .showcase-collection-home__cart-btn {
            width: 40px;
            min-height: 64px;
            border: 0;
            border-radius: 10px;
            background: #d2a589;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(121, 83, 61, 0.16);
        }

        .showcase-collection-home__btn {
            width: 100%;
            margin-top: 12px;
            border: 2px solid rgba(255, 255, 255, 0.85);
            background: transparent;
            color: #fff;
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 800;
        }

        .showcase-collection-modal {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
            background: #fff;
        }

        .showcase-collection-modal__wrap {
            background: #fff;
            border-radius: 18px;
            padding: 8px;
        }

        .showcase-collection-modal__head {
            margin-bottom: 18px;
            padding-right: 40px;
        }

        .showcase-collection-modal__title {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            color: #111827;
        }

        .showcase-collection-modal__meta,
        .showcase-collection-modal__tags {
            margin-top: 8px;
            color: #6b7280;
            font-size: 13px;
        }

        .showcase-collection-modal__meta a {
            color: inherit;
            font-weight: 800;
            text-decoration: none;
        }

        .showcase-collection-modal__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .showcase-collection-modal__item {
            background: transparent;
            color: #111827;
            border-radius: 18px;
            padding: 12px;
        }

        .showcase-collection-modal__image {
            width: 100%;
            aspect-ratio: 16 / 10;
            border-radius: 14px;
            overflow: hidden;
            background: #f3f4f6;
            margin-bottom: 10px;
        }

        .showcase-collection-modal__image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-collection-modal__item-title {
            font-size: 18px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 4px;
        }

        .showcase-collection-modal__item-desc {
            font-size: 12px;
            color: #6b7280;
            min-height: 30px;
        }

        .showcase-collection-modal__product {
            margin-top: 12px;
            background: #fff;
            border: 1px solid #f0e7e0;
            color: #7a5a4b;
            border-radius: 14px;
            padding: 10px;
            box-shadow: 0 8px 18px rgba(121, 83, 61, 0.06);
        }

        .showcase-collection-modal__product-top {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .showcase-collection-modal__product-thumb {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            overflow: hidden;
            background: #f3f4f6;
            flex: 0 0 48px;
        }

        .showcase-collection-modal__product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-collection-modal__product-name {
            font-size: 13px;
            font-weight: 800;
            line-height: 1.2;
        }

        .showcase-collection-modal__product-price {
            margin-left: auto;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .showcase-collection-modal__actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .showcase-collection-modal__cart,
        .showcase-collection-modal__view {
            flex: 1;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            border: 0;
        }

        .showcase-collection-modal__cart {
            background: #d2a589;
            color: #fff;
        }

        .showcase-collection-modal__view {
            background: transparent;
            border: 1px solid #d2a589;
            color: #8a5d45;
            margin-top: 10px;
            width: 100%;
        }

        @media (max-width: 991.98px) {
            .showcase-collection-modal__grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .showcase-collection-home__rail {
                grid-auto-columns: minmax(250px, 280px);
                gap: 12px;
                padding-left: 8px;
                padding-right: 8px;
            }

            .showcase-collection-home__arrow {
                display: none;
            }
        }
    </style>

    <script>
        (function() {
            var collectionData = <?php echo json_encode($collectionPayload, 15, 512) ?>;
            var collectionMap = {};

            collectionData.forEach(function(item) {
                collectionMap[String(item.id)] = item;
            });

            function escapeHtml(value) {
                return String(value || '').replace(/[&<>"']/g, function(char) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    }[char];
                });
            }

            function truncateWords(value, limit) {
                var words = String(value || '').trim().split(/\s+/).filter(Boolean);
                if (words.length <= limit) return words.join(' ');
                return words.slice(0, limit).join(' ') + '...';
            }

            window.shareShowcaseCollectionProduct = function(url, title) {
                if (!url) return;
                if (navigator.share) {
                    navigator.share({ title: title || document.title, url: url }).catch(function() {});
                    return;
                }
                window.copyShowcaseCollectionProduct(url);
            };

            window.copyShowcaseCollectionProduct = function(url) {
                if (!url) return;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).catch(function() {});
                    return;
                }
                window.prompt('<?php echo e(translate('Copy product link')); ?>', url);
            };

            window.openShowcaseCollectionModal = function(collectionId) {
                if (!window.jQuery) return;
                var collection = collectionMap[String(collectionId)];
                var modalBody = document.getElementById('showcaseCollectionModalBody');
                if (!collection || !modalBody) return;

                modalBody.innerHTML =
                    '<div class="showcase-collection-modal__wrap">' +
                        '<div class="showcase-collection-modal__head">' +
                            '<div class="showcase-collection-modal__title">' + (collection.title || '') + '</div>' +
                            '<div class="showcase-collection-modal__meta"><?php echo e(translate('By')); ?> <a href="<?php echo e(url('/shop')); ?>/' + (collection.seller_slug || '') + '">' + (collection.seller_name || '') + '</a></div>' +
                            (collection.hashtags && collection.hashtags.length
                                ? '<div class="showcase-collection-modal__tags">' + collection.hashtags.map(function(tag) {
                                    return '#' + tag;
                                }).join(' ') + '</div>'
                                : '') +
                        '</div>' +
                        '<div class="showcase-collection-modal__grid">' +
                            collection.items.map(function(item) {
                                var product = item.product || null;
                                return '' +
                                    '<div class="showcase-collection-modal__item">' +
                                        '<div class="showcase-collection-modal__image">' +
                                            (item.cover_image_url ? '<img src="' + item.cover_image_url + '" alt="">' : '') +
                                        '</div>' +
                                        '<div class="showcase-collection-modal__item-title">' + (item.title || '') + '</div>' +
                                        '<div class="showcase-collection-modal__item-desc">' + (item.description || '') + '</div>' +
                                        (product ? '' +
                                            '<div class="showcase-collection-modal__product">' +
                                                '<div class="showcase-collection-modal__product-top">' +
                                                    '<div class="showcase-collection-modal__product-thumb">' +
                                                        (product.thumbnail_url ? '<img src="' + product.thumbnail_url + '" alt="">' : '') +
                                                    '</div>' +
                                                    '<div class="showcase-collection-modal__product-name">' + escapeHtml(truncateWords(product.name || '', 3)) + '</div>' +
                                                    '<div class="showcase-collection-modal__product-price">' + (product.price_html || '') + '</div>' +
                                                '</div>' +
                                                '<div class="showcase-collection-modal__actions">' +
                                                    '<button type="button" class="showcase-collection-modal__cart" onclick="showAddToCartModal(' + product.id + ')"><?php echo e(translate('Add to cart')); ?></button>' +
                                                '</div>' +
                                            '</div>' +
                                            '<button type="button" class="showcase-collection-modal__view" onclick="showAddToCartModal(' + product.id + ')"><?php echo e(translate('View Product')); ?></button>'
                                            : '<button type="button" class="showcase-collection-modal__view" onclick="showShowcaseEmptyProductModal()"><?php echo e(translate('View Product')); ?></button>') +
                                    '</div>';
                            }).join('') +
                        '</div>' +
                    '</div>';

                $('#showcaseCollectionModal').modal('show');
            };

            function bindCollectionRail() {
                var section = document.querySelector('.showcase-collection-home');
                if (!section) return;

                var rail = section.querySelector('[data-collection-rail]');
                if (!rail || rail.dataset.controlsBound === '1') return;
                rail.dataset.controlsBound = '1';

                var leftBtn = section.querySelector('[data-collection-scroll="left"]');
                var rightBtn = section.querySelector('[data-collection-scroll="right"]');

                function getStep() {
                    return Math.max(280, Math.floor(rail.clientWidth * 0.7));
                }

                function updateArrowState() {
                    if (!leftBtn || !rightBtn) return;
                    var maxScrollLeft = rail.scrollWidth - rail.clientWidth - 4;
                    leftBtn.style.display = rail.scrollLeft <= 4 ? 'none' : 'inline-flex';
                    rightBtn.style.display = rail.scrollLeft >= maxScrollLeft ? 'none' : 'inline-flex';
                }

                leftBtn && leftBtn.addEventListener('click', function() {
                    rail.scrollBy({ left: -getStep(), behavior: 'smooth' });
                });
                rightBtn && rightBtn.addEventListener('click', function() {
                    rail.scrollBy({ left: getStep(), behavior: 'smooth' });
                });
                rail.addEventListener('scroll', updateArrowState, { passive: true });
                window.addEventListener('resize', updateArrowState);
                updateArrowState();
            }

            function bindCollectionCards() {
                document.querySelectorAll('[data-collection-card]').forEach(function(card) {
                    if (card.dataset.bound === '1') return;
                    card.dataset.bound = '1';

                    var payload = {};
                    try {
                        payload = JSON.parse(card.getAttribute('data-collection') || '{}');
                    } catch (e) {
                        payload = {};
                    }

                    var media = card.querySelector('[data-collection-media]');
                    var title = card.querySelector('[data-collection-active-title]');
                    var desc = card.querySelector('[data-collection-active-desc]');
                    var productWrap = card.querySelector('[data-collection-product]');
                    var navButtons = card.querySelectorAll('[data-collection-slide]');

                    function render(index) {
                        var items = payload.items || [];
                        if (!items.length) return;
                        var safeIndex = ((index % items.length) + items.length) % items.length;
                        card.dataset.collectionIndex = String(safeIndex);
                        var item = items[safeIndex];

                        if (media) {
                            media.innerHTML = item.cover_image_url ? '<img src="' + item.cover_image_url + '" alt="">' : '';
                        }
                        if (title) {
                            title.textContent = item.title || '';
                        }
                        if (desc) {
                            desc.textContent = item.description || '';
                        }
                        if (productWrap) {
                            if (item.product) {
                                var productUrl = item.product.product_url || '';
                                var productName = item.product.name || '';
                                productWrap.innerHTML =
                                    '<div class="showcase-collection-home__linked-card">' +
                                        '<div class="showcase-collection-home__linked-tools">' +
                                            '<button type="button" class="showcase-collection-home__tool-btn" onclick="shareShowcaseCollectionProduct(' + JSON.stringify(productUrl) + ', ' + JSON.stringify(productName) + ')" aria-label="<?php echo e(translate('Share product')); ?>">' +
                                                '<i class="las la-share-alt"></i>' +
                                            '</button>' +
                                            '<button type="button" class="showcase-collection-home__tool-btn" onclick="copyShowcaseCollectionProduct(' + JSON.stringify(productUrl) + ')" aria-label="<?php echo e(translate('Copy product link')); ?>">' +
                                                '<i class="las la-code"></i>' +
                                            '</button>' +
                                        '</div>' +
                                        '<div class="showcase-collection-home__linked-thumb">' +
                                            (item.product.thumbnail_url ? '<img src="' + item.product.thumbnail_url + '" alt="">' : '') +
                                        '</div>' +
                                        '<div class="showcase-collection-home__linked-body">' +
                                            '<div class="showcase-collection-home__linked-name">' + escapeHtml(truncateWords(productName, 3)) + '</div>' +
                                            '<div class="showcase-collection-home__linked-price">' + (item.product.price_html || '') + '</div>' +
                                        '</div>' +
                                        '<button type="button" class="showcase-collection-home__cart-btn" onclick="showAddToCartModal(' + item.product.id + ')" aria-label="<?php echo e(translate('Add to cart')); ?>">' +
                                            '<i class="las la-shopping-cart"></i>' +
                                        '</button>' +
                                    '</div>';
                            } else {
                                productWrap.innerHTML = '';
                            }
                        }
                    }

                    navButtons.forEach(function(btn) {
                        btn.style.display = (payload.items || []).length > 1 ? 'inline-flex' : 'none';
                        btn.addEventListener('click', function() {
                            var current = Number(card.dataset.collectionIndex || 0);
                            render(btn.getAttribute('data-collection-slide') === 'prev' ? current - 1 : current + 1);
                        });
                    });

                    render(Number(card.dataset.collectionIndex || 0));
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    bindCollectionRail();
                    bindCollectionCards();
                });
            } else {
                bindCollectionRail();
                bindCollectionCards();
            }
        })();
    </script>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/reclassic/partials/showcase_home_section.blade.php ENDPATH**/ ?>