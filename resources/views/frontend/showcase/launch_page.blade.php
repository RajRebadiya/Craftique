@extends('frontend.layouts.app')

@section('content')
    @php
        $launchJson = $launches->map(function ($launch) {
            return [
                'id' => $launch->id,
                'title' => $launch->title,
                'subtitle' => $launch->subtitle,
                'description' => $launch->description,
                'seller_name' => $launch->seller_name,
                'seller_shop_id' => $launch->seller_shop_id,
                'seller_slug' => $launch->seller_slug,
                'shop_url' => !empty($launch->seller_slug) ? route('shop.visit', $launch->seller_slug) : null,
                'brand_url' => !empty($launch->seller_slug) ? route('frontend.showcase.brand', $launch->seller_slug) : null,
                'seller_logo_url' => $launch->seller_logo_url,
                'media_url' => $launch->media_url,
                'media_is_video' => $launch->media_is_video,
                'hashtags' => $launch->hashtags,
                'primary_product' => $launch->primary_product ? [
                    'id' => $launch->primary_product->id,
                    'name' => $launch->primary_product->name,
                    'thumbnail_url' => $launch->primary_product->thumbnail_url,
                    'price_html' => $launch->primary_product->price_html,
                    'product_url' => $launch->primary_product->product_url,
                    'gallery' => $launch->primary_product->gallery,
                ] : null,
                'related_products' => collect($launch->related_products)->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'thumbnail_url' => $product->thumbnail_url,
                        'price_html' => $product->price_html,
                        'product_url' => $product->product_url,
                        'gallery' => $product->gallery,
                    ];
                })->values()->all(),
            ];
        })->values();
        $followedSellerIds = auth()->check() ? get_followed_sellers() : [];
    @endphp

    <div class="container">
        <section class="launch-page">
            <div class="container-fluid px-0 px-xl-3">
                <div class="row no-gutters align-items-start">
                    <div class="col">
                        <div class="launch-page__shell">
                            <a href="{{ route('home') }}" class="launch-page__back-btn btn btn-soft-secondary btn-sm">
                                {{ translate('Back to Launches') }}
                            </a>

                            @if($launches->count())
                                <div class="launch-page__sections-wrap">
                                    <div class="launch-page__scroll-arrows">
                                        <button type="button" class="launch-page__scroll-btn" id="launchScrollUp"
                                            aria-label="{{ translate('Previous launch') }}">
                                            <i class="las la-angle-up"></i>
                                        </button>
                                        <button type="button" class="launch-page__scroll-btn" id="launchScrollDown"
                                            aria-label="{{ translate('Next launch') }}">
                                            <i class="las la-angle-down"></i>
                                        </button>
                                    </div>

                                    <div class="launch-page__sections" id="launchPageFeed">
                                        @foreach($launches as $launch)
                                            @php
                                                $sellerInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($launch->seller_name ?: 'S', 0, 1));
                                                $shopUrl = !empty($launch->seller_slug) ? route('shop.visit', $launch->seller_slug) : '#';
                                                $brandUrl = !empty($launch->seller_slug) ? route('frontend.showcase.brand', $launch->seller_slug) : '#';
                                                $isFollowingSeller = !empty($launch->seller_shop_id) && in_array((int) $launch->seller_shop_id, $followedSellerIds);
                                                $followUrl = !empty($launch->seller_shop_id)
                                                    ? ($isFollowingSeller
                                                        ? route('followed_seller.remove', ['id' => $launch->seller_shop_id])
                                                        : route('followed_seller.store', ['id' => $launch->seller_shop_id]))
                                                    : (auth()->check() ? '#' : route('user.login'));
                                            @endphp
                                            <section class="launch-page__section" data-launch-section data-launch-id="{{ $launch->id }}">
                                                <div class="launch-page__hero">
                                                    <div class="launch-page__hero-media">
                                                        @if($launch->media_url && $launch->media_is_video)
                                                            <video class="launch-page__video" muted playsinline loop preload="metadata">
                                                                <source src="{{ $launch->media_url }}">
                                                            </video>
                                                        @elseif($launch->media_url)
                                                            <img src="{{ $launch->media_url }}" alt="{{ $launch->title }}">
                                                        @else
                                                            <div class="launch-page__hero-empty">{{ translate('Video HD Landscape') }}</div>
                                                        @endif
                                                    </div>

                                                    <aside class="launch-page__product-panel" data-launch-panel>
                                                        <div class="launch-page__thumbs" data-launch-thumbs>
                                                            @foreach($launch->related_products->take(2) as $product)
                                                                <button type="button" class="launch-page__thumb{{ $loop->first ? ' is-active' : '' }}"
                                                                    data-launch-product-id="{{ $product->id }}">
                                                                    @if($product->thumbnail_url)
                                                                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
                                                                    @else
                                                                        <span>{{ translate('Product Images') }}</span>
                                                                    @endif
                                                                </button>
                                                            @endforeach
                                                        </div>

                                                        <div class="launch-page__product-viewer" data-launch-product-viewer></div>
                                                        <div class="launch-page__related-list" data-launch-related-list></div>
                                                    </aside>
                                                </div>

                                                <div class="launch-page__meta-row">
                                                    <div class="launch-page__shop-block">
                                                        <a href="{{ $shopUrl }}" class="launch-page__shop-logo">
                                                            @if($launch->seller_logo_url)
                                                                <img src="{{ $launch->seller_logo_url }}" alt="{{ $launch->seller_name }}">
                                                            @else
                                                                <span>{{ $sellerInitial }}</span>
                                                            @endif
                                                        </a>
                                                        <div>
                                                            <a href="{{ $shopUrl }}" class="launch-page__shop-name">{{ $launch->seller_name }}</a>
                                                            <a href="{{ $brandUrl }}" class="launch-page__shop-brand">{{ translate('Brand') }}</a>
                                                        </div>
                                                        @if($isFollowingSeller)
                                                            <div class="showcase-follow-menu">
                                                                <button type="button" class="launch-page__follow showcase-follow-menu__toggle">
                                                                    {{ translate('Following') }} <i class="las la-angle-down"></i>
                                                                </button>
                                                                <div class="showcase-follow-menu__dropdown">
                                                                    <a href="{{ $followUrl }}">{{ translate('Unfollow') }}</a>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <a href="{{ $followUrl }}" class="launch-page__follow">{{ translate('Follow') }}</a>
                                                        @endif
                                                    </div>

                                                    <div class="launch-page__dummy-actions">
                                                        <button type="button" class="launch-page__dummy-btn">
                                                            <i class="lar la-thumbs-up"></i><span>6,4 k.</span>
                                                        </button>
                                                        <button type="button" class="launch-page__dummy-btn">
                                                            <i class="las la-share"></i><span>{{ translate('Share') }}</span>
                                                        </button>
                                                        <button type="button" class="launch-page__dummy-btn">
                                                            <i class="lar la-bookmark"></i><span>{{ translate('Save') }}</span>
                                                        </button>
                                                        <button type="button" class="launch-page__dummy-btn">
                                                            <i class="las la-ellipsis-h"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="launch-page__description-box">
                                                    <div class="launch-page__description-title">{{ translate('Description') }}</div>
                                                    <div class="launch-page__description-text">
                                                        {{ $launch->description ?: $launch->subtitle ?: translate('Launch description will appear here.') }}
                                                    </div>
                                                </div>
                                            </section>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="bg-white border rounded shadow-sm text-center py-5 px-3">
                                    <h4 class="fw-700 mb-2">{{ translate('No Launch found') }}</h4>
                                    <p class="text-muted mb-3">{{ translate('There is no published launch available right now.') }}</p>
                                    <a href="{{ route('frontend.showcase.launch') }}" class="btn btn-soft-primary">
                                        {{ translate('Open Launch Feed') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .launch-page {
            background: linear-gradient(180deg, #fdfaf8 0%, #f4eeea 100%);
            padding: 18px 0 40px;
        }

        .launch-page__shell {
            padding: 0 8px;
            position: relative;
        }

        .launch-page__shop-name {
            color: #cb9b83;
            display: block;
            text-decoration: none;
            font-weight: 800;
        }

        .launch-page__shop-brand {
            display: block;
            color: #8b7281;
            font-size: 12px;
            text-decoration: none;
            margin-top: 2px;
        }

        .launch-page__back-btn {
            position: absolute;
            top: 0;
            right: 8px;
            z-index: 5;
        }

        .launch-page__sections-wrap {
            position: relative;
        }

        .launch-page__sections {
            display: flex;
            flex-direction: column;
            gap: 0;
            height: calc(100vh - 135px);
            overflow-y: auto;
            scroll-snap-type: y mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-right: 66px;
            padding-bottom: 28px;
        }

        .launch-page__sections::-webkit-scrollbar {
            display: none;
        }

        .launch-page__scroll-arrows {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            z-index: 4;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .launch-page__scroll-btn {
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.86);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.18);
        }

        .launch-page__section {
            min-height: auto;
            scroll-snap-align: start;
            padding: 4px 0 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .launch-page__hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 8px;
            align-items: start;
        }

        .launch-page__hero-media {
            aspect-ratio: 16 / 9;
            max-height: min(56vh, 520px);
            border: 2px solid #6f7b97;
            background: #d9d9d9;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .launch-page__hero-media img,
        .launch-page__video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            background: #d9d9d9;
        }

        .launch-page__hero-empty {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            font-size: clamp(24px, 4vw, 58px);
            font-weight: 800;
            color: #cb9b83;
            text-align: center;
            padding: 20px;
        }

        .launch-page__product-panel {
            min-width: 0;
        }

        .launch-page__thumbs {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 10px;
        }

        .launch-page__thumb {
            width: 132px;
            height: 120px;
            border: 2px solid #6f7b97;
            border-radius: 24px;
            background: #d9d9d9;
            overflow: hidden;
            color: #cb9b83;
            font-size: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10px;
        }

        .launch-page__thumb.is-active {
            box-shadow: 0 0 0 3px rgba(203, 155, 131, 0.28);
        }

        .launch-page__thumb img,
        .launch-page__related-thumb img,
        .launch-page__product-main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .launch-page__product-viewer {
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 0 0 10px;
        }

        .launch-page__product-title {
            font-size: 14px;
            font-weight: 800;
            color: #1f2937;
            line-height: 1.35;
            margin: 0 0 8px;
            padding: 0 10px;
        }

        .launch-page__product-brand,
        .launch-page__product-rating,
        .launch-page__compare-row {
            padding: 0 10px;
            color: #8b8f98;
            font-size: 12px;
        }

        .launch-page__product-brand a {
            color: inherit;
            text-decoration: none;
        }

        .launch-page__compare-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 10px 0 8px;
        }

        .launch-page__price-box {
            background: #f3f4f6;
            margin: 8px 0;
            padding: 10px;
        }

        .launch-page__price-main {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
        }

        .launch-page__price-sub {
            color: #6b7280;
            font-size: 12px;
        }

        .launch-page__qty-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: center;
            padding: 8px 10px 0;
        }

        .launch-page__cta-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 10px;
        }

        .launch-page__cta,
        .launch-page__list-cta {
            border: 0;
            border-radius: 2px;
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 800;
        }

        .launch-page__cta--buy {
            background: #111827;
            color: #fff;
        }

        .launch-page__cta--cart,
        .launch-page__list-cta {
            background: #dbeafe;
            color: #3b82f6;
        }

        .launch-page__product-main-image {
            height: 126px;
            background: #ececec;
            margin-bottom: 10px;
        }

        .launch-page__related-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .launch-page__related-item {
            display: grid;
            grid-template-columns: 116px 1fr;
            gap: 8px;
            align-items: center;
        }

        .launch-page__related-thumb {
            height: 66px;
            border: 2px solid #6f7b97;
            border-radius: 16px;
            background: #d9d9d9;
            overflow: hidden;
        }

        .launch-page__related-title {
            font-size: 13px;
            font-weight: 800;
            line-height: 1.2;
            color: #111827;
        }

        .launch-page__related-shop {
            font-size: 12px;
            font-weight: 700;
            color: #1f2937;
            margin-top: 4px;
            text-decoration: none;
            display: inline-block;
        }

        .launch-page__meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .launch-page__shop-block {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .launch-page__shop-logo {
            width: 46px;
            height: 24px;
            color: #cb9b83;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .launch-page__shop-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .launch-page__shop-name {
            font-size: 30px;
            font-weight: 800;
            text-transform: uppercase;
            color: #cb9b83;
            text-decoration: none;
        }

        .launch-page__follow {
            border: 0;
            background: #cfa68f;
            color: #fff;
            border-radius: 6px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .showcase-follow-menu {
            position: relative;
            display: inline-flex;
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

        .launch-page__dummy-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .launch-page__dummy-btn {
            border: 1px solid #ece7e2;
            background: #fff;
            color: #3f3f46;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .launch-page__description-box {
            border: 2px solid #6f7b97;
            border-radius: 0 0 24px 24px;
            background: #d9d9d9;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px 12px;
        }

        .launch-page__description-text {
            color: #5b5b5b;
            font-size: 12px;
            line-height: 1.2;
            font-weight: 600;
            max-width: 860px;
            text-align: center;
        }

        @media (max-width: 1199.98px) {
            .launch-page__hero {
                grid-template-columns: 1fr;
            }

            .launch-page__hero-media {
                max-height: none;
            }
        }

        @media (max-width: 767.98px) {
            .launch-page {
                padding-top: 12px;
            }

            .launch-page__back-btn {
                position: static;
                margin-bottom: 12px;
            }

            .launch-page__sections {
                height: auto;
                overflow: visible;
                padding-right: 0;
            }

            .launch-page__scroll-arrows {
                display: none;
            }

            .launch-page__thumb {
                width: 104px;
                height: 92px;
            }

            .launch-page__meta-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .launch-page__shop-name {
                font-size: 22px;
            }
        }
    </style>

    <script>
        (function() {
            var launchData = @json($launchJson);
            var noProductText = @json(translate('No product found'));
            var reviewsText = @json(translate('reviews'));
            var qtyText = @json(translate('Qty'));
            var buyNowText = @json(translate('Buy now'));
            var addToCartText = @json(translate('Add to cart'));
            var launchFallbackTitle = @json(translate('Title of the product Launch'));
            var viewProductText = @json(translate('View Product'));
            var launchMap = {};
            launchData.forEach(function(item) {
                launchMap[String(item.id)] = item;
            });

            function buildProductViewer(product, launch) {
                if (!product) {
                    return '<div class="p-3 text-muted">' + noProductText + '</div>';
                }

                var mainImage = product.gallery && product.gallery.length ? product.gallery[0] : product.thumbnail_url;

                return '' +
                    '<div class="launch-page__product-main-image">' +
                        (mainImage ? '<img src="' + mainImage + '" alt="' + (product.name || '') + '">' : '') +
                    '</div>' +
                    '<div class="launch-page__product-title">' + (product.name || '') + '</div>' +
                    '<div class="launch-page__product-brand">Brand <a href="' + (launch.brand_url || '#') + '"><strong>lucky Brand</strong></a></div>' +
                    '<div class="launch-page__product-rating">&#9733; &#9733; &#9733; &#9733; &#9734; 0/5.0 (0 ' + reviewsText + ')</div>' +
                    '<div class="launch-page__compare-row">' +
                        '<span><i class="las la-sync"></i> Compare</span>' +
                        '<span><i class="lar la-heart"></i> Wishlist</span>' +
                        '<span><i class="las la-share"></i> Share</span>' +
                    '</div>' +
                    '<div class="launch-page__price-box">' +
                        '<div class="launch-page__price-main">' + (product.price_html || '') + '</div>' +
                        '<div class="launch-page__price-sub">Minimum order qty 1</div>' +
                    '</div>' +
                    '<div class="launch-page__qty-row">' +
                        '<div class="text-muted small">' + qtyText + '</div>' +
                        '<div class="border rounded px-3 py-1 bg-white">1</div>' +
                    '</div>' +
                    '<div class="launch-page__cta-row">' +
                        '<button type="button" class="launch-page__cta launch-page__cta--buy" onclick="showAddToCartModal(' + product.id + ')">' + buyNowText + '</button>' +
                        '<button type="button" class="launch-page__cta launch-page__cta--cart" onclick="showAddToCartModal(' + product.id + ')">' + addToCartText + '</button>' +
                    '</div>';
            }

            function buildRelatedList(products, launch) {
                return (products || []).map(function(product) {
                    return '' +
                        '<div class="launch-page__related-item">' +
                            '<div class="launch-page__related-thumb">' +
                                (product.thumbnail_url ? '<img src="' + product.thumbnail_url + '" alt="' + (product.name || '') + '">' : '') +
                            '</div>' +
                            '<div>' +
                                '<div class="launch-page__related-title">' + (launch.title || launchFallbackTitle) + '</div>' +
                                '<a class="launch-page__related-shop" href="' + (launch.shop_url || '#') + '">' + (launch.seller_name || 'SHOP NAME') + '</a>' +
                                '<button type="button" class="launch-page__list-cta mt-2" onclick="showAddToCartModal(' + product.id + ')">' + viewProductText + '</button>' +
                            '</div>' +
                        '</div>';
                }).join('');
            }

            function renderLaunchPanel(section) {
                if (!section) return;
                var launchId = section.getAttribute('data-launch-id');
                var launch = launchMap[String(launchId)];
                if (!launch) return;

                var viewer = section.querySelector('[data-launch-product-viewer]');
                var thumbs = section.querySelector('[data-launch-thumbs]');
                var relatedList = section.querySelector('[data-launch-related-list]');
                var products = (launch.related_products || []);
                var activeId = section.getAttribute('data-active-product-id') || (products[0] ? String(products[0].id) : '');
                var activeProduct = products.find(function(product) {
                    return String(product.id) === String(activeId);
                }) || products[0] || launch.primary_product;

                section.setAttribute('data-active-product-id', activeProduct ? activeProduct.id : '');

                if (viewer) {
                    viewer.innerHTML = buildProductViewer(activeProduct, launch);
                }

                if (thumbs) {
                    thumbs.querySelectorAll('[data-launch-product-id]').forEach(function(button) {
                        button.classList.toggle('is-active', String(button.getAttribute('data-launch-product-id')) === String(activeProduct ? activeProduct.id : ''));
                    });
                }

                if (relatedList) {
                    relatedList.innerHTML = buildRelatedList(products, launch);
                }
            }

            function bindLaunchPageThumbs() {
                document.querySelectorAll('[data-launch-thumbs]').forEach(function(thumbs) {
                    if (thumbs.dataset.bound === '1') return;
                    thumbs.dataset.bound = '1';

                    thumbs.addEventListener('click', function(event) {
                        var button = event.target.closest('[data-launch-product-id]');
                        if (!button) return;
                        var section = thumbs.closest('[data-launch-section]');
                        if (!section) return;
                        section.setAttribute('data-active-product-id', button.getAttribute('data-launch-product-id'));
                        renderLaunchPanel(section);
                    });
                });
            }

            function bindLaunchPageVerticalNavigation() {
                var feed = document.getElementById('launchPageFeed');
                var upBtn = document.getElementById('launchScrollUp');
                var downBtn = document.getElementById('launchScrollDown');
                var sections = Array.from(document.querySelectorAll('[data-launch-section]'));

                if (!feed || !upBtn || !downBtn || !sections.length) return;

                function currentIndex() {
                    var closestIndex = 0;
                    var closestDistance = Infinity;
                    sections.forEach(function(section, index) {
                        var distance = Math.abs(section.offsetTop - feed.scrollTop);
                        if (distance < closestDistance) {
                            closestDistance = distance;
                            closestIndex = index;
                        }
                    });
                    return closestIndex;
                }

                function goToIndex(index) {
                    var bounded = Math.max(0, Math.min(index, sections.length - 1));
                    feed.scrollTo({ top: sections[bounded].offsetTop, behavior: 'smooth' });
                }

                function updateButtons() {
                    var index = currentIndex();
                    upBtn.style.display = index <= 0 ? 'none' : 'inline-flex';
                    downBtn.style.display = index >= sections.length - 1 ? 'none' : 'inline-flex';
                }

                upBtn.addEventListener('click', function() {
                    goToIndex(currentIndex() - 1);
                });

                downBtn.addEventListener('click', function() {
                    goToIndex(currentIndex() + 1);
                });

                feed.addEventListener('scroll', updateButtons, { passive: true });
                window.addEventListener('resize', updateButtons);
                updateButtons();
            }

            function bindLaunchVideos() {
                document.querySelectorAll('.launch-page__video').forEach(function(video) {
                    video.addEventListener('mouseenter', function() {
                        video.play().catch(function() {});
                    });
                    video.addEventListener('loadedmetadata', function() {
                        video.play().catch(function() {});
                    });
                });
            }

            function initLaunchPage() {
                document.querySelectorAll('[data-launch-section]').forEach(renderLaunchPanel);
                bindLaunchPageThumbs();
                bindLaunchPageVerticalNavigation();
                bindLaunchVideos();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initLaunchPage);
            } else {
                initLaunchPage();
            }
        })();
    </script>
@endsection
