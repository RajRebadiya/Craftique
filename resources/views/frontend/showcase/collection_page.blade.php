@extends('frontend.layouts.app')

@section('content')
    @php
        $followedSellerIds = auth()->check() ? get_followed_sellers() : [];
    @endphp
    <div class="container">
        <section class="collection-page">
            <div class="container-fluid px-0 px-xl-3">
                <div class="row no-gutters align-items-start">
                    <div class="col-auto d-none d-xl-block">
                        <div class="collection-page__sidebar">
                            @include('frontend.' . get_setting('homepage_select') . '.partials.category_menu')
                        </div>
                    </div>

                    <div class="col">
                        <div class="collection-page__shell">
                            <div class="collection-page__topbar">
                                <div>
                                    <h1 class="collection-page__heading">{{ translate('Collection Page') }}</h1>
                                    <p class="collection-page__subheading">
                                        {{ translate('Browse collection cards side by side just like the preview.') }}
                                    </p>
                                </div>

                                <div class="collection-page__top-actions">
                                    <a href="{{ route('home') }}" class="btn btn-soft-secondary btn-sm">
                                        {{ translate('Back to Collections') }}
                                    </a>
                                </div>
                            </div>

                            @if($collections->count())
                                <div class="collection-page__sections-wrap">
                                    <div class="collection-page__scroll-arrows">
                                        <button type="button" class="collection-page__scroll-btn" id="collectionScrollUp"
                                            aria-label="{{ translate('Previous collection') }}">
                                            <i class="las la-angle-up"></i>
                                        </button>
                                        <button type="button" class="collection-page__scroll-btn" id="collectionScrollDown"
                                            aria-label="{{ translate('Next collection') }}">
                                            <i class="las la-angle-down"></i>
                                        </button>
                                    </div>

                                <div class="collection-page__sections" id="collectionPageFeed">
                                    @foreach($collections as $collection)
                                        @php
                                            $sellerInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($collection->seller_name ?: 'S', 0, 1));
                                            $shopUrl = !empty($collection->seller_slug) ? route('shop.visit', $collection->seller_slug) : '#';
                                            $brandUrl = !empty($collection->seller_slug) ? route('frontend.showcase.brand', $collection->seller_slug) : '#';
                                            $isFollowingSeller = !empty($collection->seller_shop_id) && in_array((int) $collection->seller_shop_id, $followedSellerIds);
                                            $followUrl = !empty($collection->seller_shop_id)
                                                ? ($isFollowingSeller
                                                    ? route('followed_seller.remove', ['id' => $collection->seller_shop_id])
                                                    : route('followed_seller.store', ['id' => $collection->seller_shop_id]))
                                                : (auth()->check() ? '#' : route('user.login'));
                                        @endphp
                                        <section class="collection-page__section" data-collection-section>
                                            <div class="collection-page__section-head">
                                                <div class="collection-page__title-wrap">
                                                    <h2 class="collection-page__section-title">
                                                        {{ \Illuminate\Support\Str::upper($collection->title) }}
                                                    </h2>
                                                    <div class="collection-page__meta-row">
                                                        <a href="{{ $brandUrl }}" class="collection-page__mini-brand">Craftique</a>
                                                        <a href="{{ $shopUrl }}" class="collection-page__shop-name">{{ $collection->seller_name }}</a>
                                                        @if($isFollowingSeller)
                                                            <div class="showcase-follow-menu">
                                                                <button type="button" class="collection-page__follow showcase-follow-menu__toggle">
                                                                    {{ translate('Following') }} <i class="las la-angle-down"></i>
                                                                </button>
                                                                <div class="showcase-follow-menu__dropdown">
                                                                    <a href="{{ $followUrl }}">{{ translate('Unfollow') }}</a>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <a href="{{ $followUrl }}" class="collection-page__follow">{{ translate('Follow') }}</a>
                                                        @endif
                                                    </div>
                                                    <div class="collection-page__intro">
                                                        {{ $collection->description ?: translate('Create your own collection and showcase products on Craftique.') }}
                                                    </div>
                                                </div>

                                                <div class="collection-page__dummy-actions">
                                                    <button type="button" class="collection-page__dummy-btn">
                                                        <i class="lar la-thumbs-up"></i><span>6,4 k.</span>
                                                    </button>
                                                    <button type="button" class="collection-page__dummy-btn">
                                                        <i class="las la-share"></i><span>{{ translate('Share') }}</span>
                                                    </button>
                                                    <button type="button" class="collection-page__dummy-btn">
                                                        <i class="lar la-bookmark"></i><span>{{ translate('Save') }}</span>
                                                    </button>
                                                    <button type="button" class="collection-page__dummy-btn">
                                                        <i class="las la-ellipsis-h"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="collection-page__rail-wrap">
                                                <button type="button"
                                                    class="collection-page__arrow collection-page__arrow--left"
                                                    data-collection-page-scroll="left"
                                                    aria-label="{{ translate('Scroll left') }}">
                                                    <span>&lsaquo;</span>
                                                </button>

                                                <div class="collection-page__rail" data-collection-page-rail>
                                                    @foreach($collection->items as $item)
                                                        <article class="collection-page__card">
                                                            <div class="collection-page__card-media">
                                                                @if($item->cover_image_url)
                                                                    <img src="{{ $item->cover_image_url }}" alt="{{ $item->title }}">
                                                                @endif
                                                                <div class="collection-page__card-overlay">
                                                                    <div class="collection-page__card-title">
                                                                        {{ \Illuminate\Support\Str::limit($item->title, 48) }}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="collection-page__product-strip">
                                                                <div class="collection-page__product-strip-thumb">
                                                                    @if($item->product_thumb_url)
                                                                        <img src="{{ $item->product_thumb_url }}" alt="{{ $item->product_name }}">
                                                                    @endif
                                                                </div>
                                                                <div class="collection-page__product-strip-body">
                                                                    <div class="collection-page__product-strip-name">
                                                                        {{ \Illuminate\Support\Str::words($item->product_name ?: $item->title, 3, '...') }}
                                                                    </div>
                                                                    <div class="collection-page__product-strip-price">
                                                                        {{ $item->price_html ?: single_price(350) }}
                                                                    </div>
                                                                </div>
                                                                <div class="collection-page__product-strip-icons">
                                                                    <button type="button"
                                                                        class="collection-page__product-icon-btn"
                                                                        onclick='shareCollectionPageProduct(@json($item->product_url ?? ''), @json($item->product_name ?: $item->title))'
                                                                        aria-label="{{ translate('Share product') }}">
                                                                        <i class="las la-share-alt"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="collection-page__product-icon-btn"
                                                                        onclick='copyCollectionPageProduct(@json($item->product_url ?? ''))'
                                                                        aria-label="{{ translate('Copy product link') }}">
                                                                        <i class="las la-code"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <button type="button"
                                                                class="collection-page__add-btn"
                                                                @if($item->product_id)
                                                                    onclick="showAddToCartModal({{ $item->product_id }})"
                                                                @else
                                                                    onclick="showShowcaseEmptyProductModal()"
                                                                @endif>
                                                                {{ translate('Add to cart') }}
                                                            </button>
                                                        </article>
                                                    @endforeach
                                                </div>

                                                <button type="button"
                                                    class="collection-page__arrow collection-page__arrow--right"
                                                    data-collection-page-scroll="right"
                                                    aria-label="{{ translate('Scroll right') }}">
                                                    <span>&rsaquo;</span>
                                                </button>
                                            </div>
                                        </section>
                                    @endforeach
                                </div>
                                </div>
                            @else
                                <div class="bg-white border rounded shadow-sm text-center py-5 px-3">
                                    <h4 class="fw-700 mb-2">{{ translate('No Collection found') }}</h4>
                                    <p class="text-muted mb-3">{{ translate('There is no published collection available right now.') }}</p>
                                    <a href="{{ route('frontend.showcase.collection') }}" class="btn btn-soft-primary">
                                        {{ translate('Open Collection Feed') }}
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
        .collection-page {
            background: linear-gradient(180deg, #fcfaf8 0%, #f4f0ec 100%);
            padding: 20px 0 40px;
        }

        .collection-page__sidebar {
            width: 280px;
            margin-top: 64px;
            margin-right: 20px;
        }

        .collection-page__shell {
            padding: 0 16px;
        }

        .collection-page__topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .collection-page__heading {
            font-size: 34px;
            line-height: 1.05;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .collection-page__subheading {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .collection-page__sections-wrap {
            position: relative;
        }

        .collection-page__sections {
            display: flex;
            flex-direction: column;
            gap: 0;
            height: calc(100vh - 170px);
            overflow-y: auto;
            scroll-snap-type: y mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-right: 62px;
        }

        .collection-page__sections::-webkit-scrollbar {
            display: none;
        }

        .collection-page__scroll-arrows {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            z-index: 4;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .collection-page__scroll-btn {
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

        .collection-page__section {
            border-top: 1px solid #e5d3c7;
            padding-top: 18px;
            min-height: calc(100vh - 170px);
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-bottom: 22px;
        }

        .collection-page__section-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 16px;
        }

        .collection-page__section-title {
            color: #c99778;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 10px;
        }

        .collection-page__meta-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            color: #c99778;
            font-weight: 700;
        }

        .collection-page__mini-brand {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.75;
            color: inherit;
            text-decoration: none;
        }

        .collection-page__shop-name {
            font-size: 14px;
            color: inherit;
            text-decoration: none;
        }

        .collection-page__follow {
            border: 0;
            background: #cfa68f;
            color: #fff;
            border-radius: 8px;
            padding: 8px 16px;
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

        .collection-page__intro {
            margin-top: 10px;
            max-width: 760px;
            color: #c99778;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.35;
        }

        .collection-page__dummy-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .collection-page__dummy-btn {
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

        .collection-page__rail-wrap {
            position: relative;
        }

        .collection-page__rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(240px, 290px);
            gap: 14px;
            overflow-x: auto;
            padding: 6px 40px 10px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: x proximity;
        }

        .collection-page__rail::-webkit-scrollbar {
            display: none;
        }

        .collection-page__arrow {
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

        .collection-page__arrow--left {
            left: 0;
        }

        .collection-page__arrow--right {
            right: 0;
        }

        .collection-page__card {
            scroll-snap-align: start;
            background: #d2a589;
            border-radius: 0 0 10px 10px;
            overflow: hidden;
            box-shadow: 0 14px 28px rgba(121, 83, 61, 0.18);
        }

        .collection-page__card-media {
            position: relative;
            aspect-ratio: 4 / 4.6;
            overflow: hidden;
            background: #ead3c4;
        }

        .collection-page__card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .collection-page__card-overlay {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 12px;
            background: linear-gradient(180deg, rgba(17, 24, 39, 0) 0%, rgba(210, 165, 137, 0.92) 100%);
            color: #fff;
        }

        .collection-page__card-title {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
        }

        .collection-page__product-strip {
            display: grid;
            grid-template-columns: 42px 1fr auto;
            gap: 10px;
            align-items: center;
            background: #fff;
            margin: 0 0 8px;
            padding: 8px 10px;
        }

        .collection-page__product-strip-thumb {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            overflow: hidden;
            background: #f3f4f6;
        }

        .collection-page__product-strip-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .collection-page__product-strip-name {
            color: #22304d;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
        }

        .collection-page__product-strip-price {
            color: #3b4c7a;
            font-size: 13px;
            font-weight: 800;
            margin-top: 3px;
        }

        .collection-page__product-strip-icons {
            display: grid;
            gap: 3px;
            background: #f4eddc;
            border-radius: 8px;
            padding: 4px 0;
        }

        .collection-page__product-icon-btn {
            width: 26px;
            height: 22px;
            border: 0;
            background: transparent;
            color: #737373;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
        }

        .collection-page__add-btn {
            width: 100%;
            border: 0;
            background: #b78f78;
            color: #fff;
            padding: 12px 14px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        @media (max-width: 991.98px) {
            .collection-page__section-head {
                flex-direction: column;
            }
        }

        @media (max-width: 767.98px) {
            .collection-page {
                padding-top: 12px;
            }

            .collection-page__shell {
                padding: 0 10px;
            }

            .collection-page__topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .collection-page__heading {
                font-size: 28px;
            }

            .collection-page__sections {
                height: auto;
                overflow: visible;
                padding-right: 0;
                gap: 24px;
            }

            .collection-page__rail {
                grid-auto-columns: minmax(240px, 270px);
                padding-left: 8px;
                padding-right: 8px;
            }

            .collection-page__arrow {
                display: none;
            }

            .collection-page__scroll-arrows {
                display: none;
            }
        }
    </style>

    <script>
        (function() {
            window.shareCollectionPageProduct = function(url, title) {
                if (!url) return;
                if (navigator.share) {
                    navigator.share({ title: title || document.title, url: url }).catch(function() {});
                    return;
                }
                window.copyCollectionPageProduct(url);
            };

            window.copyCollectionPageProduct = function(url) {
                if (!url) return;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).catch(function() {});
                    return;
                }
                window.prompt('{{ translate('Copy product link') }}', url);
            };

            function bindCollectionPageRails() {
                document.querySelectorAll('[data-collection-page-rail]').forEach(function(rail) {
                    if (rail.dataset.bound === '1') return;
                    rail.dataset.bound = '1';

                    var wrap = rail.closest('.collection-page__rail-wrap');
                    var leftBtn = wrap ? wrap.querySelector('[data-collection-page-scroll="left"]') : null;
                    var rightBtn = wrap ? wrap.querySelector('[data-collection-page-scroll="right"]') : null;

                    function getStep() {
                        return Math.max(280, Math.floor(rail.clientWidth * 0.9));
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
                });
            }

            function bindCollectionPageVerticalNavigation() {
                var feed = document.getElementById('collectionPageFeed');
                var upBtn = document.getElementById('collectionScrollUp');
                var downBtn = document.getElementById('collectionScrollDown');
                var sections = Array.from(document.querySelectorAll('[data-collection-section]'));
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
                    feed.scrollTo({
                        top: sections[bounded].offsetTop,
                        behavior: 'smooth'
                    });
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

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    bindCollectionPageRails();
                    bindCollectionPageVerticalNavigation();
                });
            } else {
                bindCollectionPageRails();
                bindCollectionPageVerticalNavigation();
            }
        })();
    </script>
@endsection
