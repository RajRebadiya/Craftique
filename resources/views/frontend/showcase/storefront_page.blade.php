@extends('frontend.layouts.app')

@section('content')
    @php
        $followedSellerIds = auth()->check() ? get_followed_sellers() : [];
    @endphp
    <div class="container">
        <section class="storefront-page">
            <div class="container-fluid px-0 px-xl-3">
                <div class="row no-gutters align-items-start">
                    <div class="col-auto d-none d-xl-block">
                        <div class="storefront-page__sidebar">
                            @include('frontend.' . get_setting('homepage_select') . '.partials.category_menu')
                        </div>
                    </div>

                    <div class="col">
                        <div class="storefront-page__shell">
                            <div class="storefront-page__topbar">
                                <div>
                                    <h1 class="storefront-page__heading">{{ translate('Storefront Page') }}</h1>
                                    <p class="storefront-page__subheading">
                                        {{ translate('Browse storefront previews with linked products in a reel-like feed.') }}
                                    </p>
                                </div>

                                <div class="storefront-page__top-actions">
                                    <a href="{{ route('home') }}" class="btn btn-soft-secondary btn-sm">
                                        {{ translate('Back to Storefronts') }}
                                    </a>
                                </div>
                            </div>

                            @if($storefronts->count())
                                <div class="storefront-page__sections-wrap">
                                    <div class="storefront-page__scroll-arrows">
                                        <button type="button" class="storefront-page__scroll-btn" id="storefrontScrollUp"
                                            aria-label="{{ translate('Previous storefront') }}">
                                            <i class="las la-angle-up"></i>
                                        </button>
                                        <button type="button" class="storefront-page__scroll-btn" id="storefrontScrollDown"
                                            aria-label="{{ translate('Next storefront') }}">
                                            <i class="las la-angle-down"></i>
                                        </button>
                                    </div>

                                    <div class="storefront-page__sections" id="storefrontPageFeed">
                                        @foreach($storefronts as $storefront)
                                            @php
                                                $shopUrl = !empty($storefront->seller_slug) ? route('shop.visit', $storefront->seller_slug) : '#';
                                                $brandUrl = !empty($storefront->seller_slug) ? route('frontend.showcase.brand', $storefront->seller_slug) : '#';
                                                $isFollowingSeller = !empty($storefront->seller_shop_id) && in_array((int) $storefront->seller_shop_id, $followedSellerIds);
                                                $followUrl = !empty($storefront->seller_shop_id)
                                                    ? ($isFollowingSeller
                                                        ? route('followed_seller.remove', ['id' => $storefront->seller_shop_id])
                                                        : route('followed_seller.store', ['id' => $storefront->seller_shop_id]))
                                                    : (auth()->check() ? '#' : route('user.login'));
                                            @endphp
                                            <section class="storefront-page__section" data-storefront-section>
                                                <div class="storefront-page__showcase-row">
                                                    <article class="storefront-page__hero-card">
                                                        <div class="storefront-page__hero-media">
                                                            @if($storefront->main_visual_url)
                                                                <img src="{{ $storefront->main_visual_url }}" alt="{{ $storefront->title }}">
                                                            @else
                                                                <div class="storefront-page__hero-placeholder">
                                                                    {{ translate('Storefront Preview') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </article>

                                                    <div class="storefront-page__content-panel">
                                                        <div class="storefront-page__section-head">
                                                            <div class="storefront-page__title-wrap">
                                                                <h2 class="storefront-page__section-title">
                                                                    {{ \Illuminate\Support\Str::upper($storefront->title) }}
                                                                </h2>

                                                                <div class="storefront-page__meta-row">
                                                                    <a href="{{ $brandUrl }}" class="storefront-page__mini-brand">Craftique</a>
                                                                    <a href="{{ $shopUrl }}" class="storefront-page__shop-name">{{ $storefront->seller_name }}</a>
                                                                    @if($isFollowingSeller)
                                                                        <div class="showcase-follow-menu">
                                                                            <button type="button" class="storefront-page__follow showcase-follow-menu__toggle">
                                                                                {{ translate('Following') }} <i class="las la-angle-down"></i>
                                                                            </button>
                                                                            <div class="showcase-follow-menu__dropdown">
                                                                                <a href="{{ $followUrl }}">{{ translate('Unfollow') }}</a>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <a href="{{ $followUrl }}" class="storefront-page__follow">
                                                                            {{ translate('Follow') }}
                                                                        </a>
                                                                    @endif
                                                                </div>

                                                                <div class="storefront-page__intro">
                                                                    {{ $storefront->description ?: translate('Create your own storefront and present your products on Craftique.') }}
                                                                </div>
                                                            </div>

                                                            <div class="storefront-page__dummy-actions">
                                                                <button type="button" class="storefront-page__dummy-btn">
                                                                    <i class="lar la-thumbs-up"></i><span>6,4 k.</span>
                                                                </button>
                                                                <button type="button" class="storefront-page__dummy-btn">
                                                                    <i class="las la-share"></i><span>{{ translate('Share') }}</span>
                                                                </button>
                                                                <button type="button" class="storefront-page__dummy-btn">
                                                                    <i class="lar la-bookmark"></i><span>{{ translate('Save') }}</span>
                                                                </button>
                                                                <button type="button" class="storefront-page__dummy-btn">
                                                                    <i class="las la-ellipsis-h"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="storefront-page__rail-wrap">
                                                            <button type="button"
                                                                class="storefront-page__arrow storefront-page__arrow--left"
                                                                data-storefront-page-scroll="left"
                                                                aria-label="{{ translate('Scroll left') }}">
                                                                <span>&lsaquo;</span>
                                                            </button>

                                                            <div class="storefront-page__rail" data-storefront-page-rail>
                                                                @foreach($storefront->products as $product)
                                                                    <article class="storefront-page__card">
                                                                        <div class="storefront-page__card-media">
                                                                            @if($product->thumbnail_url)
                                                                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
                                                                            @else
                                                                                <div class="storefront-page__card-fallback">
                                                                                    {{ translate('Product') }}
                                                                                </div>
                                                                            @endif
                                                                        </div>

                                                                        <div class="storefront-page__product-strip">
                                                                            <div class="storefront-page__product-strip-name">
                                                                                {{ \Illuminate\Support\Str::limit($product->name, 34) }}
                                                                            </div>
                                                                            <div class="storefront-page__product-strip-price">
                                                                                {!! $product->price_html !!}
                                                                            </div>
                                                                        </div>
                                                                    </article>
                                                                @endforeach
                                                            </div>

                                                            <button type="button"
                                                                class="storefront-page__arrow storefront-page__arrow--right"
                                                                data-storefront-page-scroll="right"
                                                                aria-label="{{ translate('Scroll right') }}">
                                                                <span>&rsaquo;</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="bg-white border rounded shadow-sm text-center py-5 px-3">
                                    <h4 class="fw-700 mb-2">{{ translate('No Storefront found') }}</h4>
                                    <p class="text-muted mb-3">{{ translate('There is no published storefront available right now.') }}</p>
                                    <a href="{{ route('frontend.showcase.vitrin') }}" class="btn btn-soft-primary">
                                        {{ translate('Open Storefront Feed') }}
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
        .storefront-page {
            background: linear-gradient(180deg, #fcfaf8 0%, #f4f0ec 100%);
            padding: 20px 0 40px;
        }

        .storefront-page__sidebar {
            width: 280px;
            margin-top: 64px;
            margin-right: 20px;
        }

        .storefront-page__shell {
            padding: 0 16px;
        }

        .storefront-page__topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .storefront-page__heading {
            font-size: 34px;
            line-height: 1.05;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .storefront-page__subheading {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .storefront-page__sections-wrap {
            position: relative;
        }

        .storefront-page__sections {
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

        .storefront-page__sections::-webkit-scrollbar {
            display: none;
        }

        .storefront-page__scroll-arrows {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            z-index: 4;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .storefront-page__scroll-btn {
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

        .storefront-page__section {
            border-top: 1px solid #e5d3c7;
            padding-top: 10px;
            min-height: calc((100vh - 170px) / 2);
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-bottom: 12px;
        }

        .storefront-page__showcase-row {
            display: grid;
            grid-template-columns: minmax(190px, 240px) minmax(0, 1fr);
            gap: 14px;
            align-items: start;
        }

        .storefront-page__content-panel {
            min-width: 0;
        }

        .storefront-page__section-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 12px;
        }

        .storefront-page__section-title {
            color: #c99778;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .storefront-page__meta-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            color: #c99778;
            font-weight: 700;
        }

        .storefront-page__mini-brand {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.75;
            color: inherit;
            text-decoration: none;
        }

        .storefront-page__shop-name {
            font-size: 14px;
            color: inherit;
            text-decoration: none;
        }

        .storefront-page__follow {
            border: 0;
            background: #cfa68f;
            color: #fff;
            border-radius: 6px;
            padding: 8px 14px;
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

        .storefront-page__intro {
            margin-top: 12px;
            max-width: 700px;
            color: #c99778;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
        }

        .storefront-page__dummy-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .storefront-page__dummy-btn {
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

        .storefront-page__rail-wrap {
            position: relative;
            min-width: 0;
        }

        .storefront-page__rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(108px, 128px);
            gap: 10px;
            overflow-x: auto;
            padding: 2px 34px 8px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: x proximity;
            align-items: start;
        }

        .storefront-page__rail::-webkit-scrollbar {
            display: none;
        }

        .storefront-page__arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            width: 30px;
            height: 30px;
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

        .storefront-page__arrow--left {
            left: 0;
        }

        .storefront-page__arrow--right {
            right: 0;
        }

        .storefront-page__hero-card,
        .storefront-page__card {
            scroll-snap-align: start;
            background: #fff;
            border: 1px solid #f0e7e0;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 8px 18px rgba(121, 83, 61, 0.08);
        }

        .storefront-page__hero-card {
            position: sticky;
            top: 0;
        }

        .storefront-page__hero-media {
            position: relative;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background: #ead3c4;
        }

        .storefront-page__card-media {
            position: relative;
            aspect-ratio: 1 / 1.12;
            overflow: hidden;
            background: #ead3c4;
        }

        .storefront-page__hero-media img,
        .storefront-page__card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .storefront-page__hero-placeholder,
        .storefront-page__card-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8b6a58;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .storefront-page__product-strip {
            background: #fff;
            padding: 6px 7px 7px;
            min-height: 44px;
        }

        .storefront-page__product-strip-name {
            color: #22304d;
            font-size: 9px;
            font-weight: 700;
            line-height: 1.15;
            min-height: 20px;
        }

        .storefront-page__product-strip-price {
            color: #3b4c7a;
            font-size: 9px;
            font-weight: 800;
            margin-top: 2px;
        }

        @media (max-width: 991.98px) {
            .storefront-page__showcase-row {
                grid-template-columns: minmax(170px, 220px) minmax(0, 1fr);
            }

            .storefront-page__section-head {
                flex-direction: column;
            }

            .storefront-page__rail {
                grid-auto-columns: minmax(104px, 120px);
            }
        }

        @media (max-width: 767.98px) {
            .storefront-page {
                padding-top: 12px;
            }

            .storefront-page__shell {
                padding: 0 10px;
            }

            .storefront-page__topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .storefront-page__heading {
                font-size: 28px;
            }

            .storefront-page__sections {
                height: auto;
                overflow: visible;
                padding-right: 0;
                gap: 24px;
            }

            .storefront-page__showcase-row {
                grid-template-columns: 1fr;
            }

            .storefront-page__hero-card {
                position: static;
            }

            .storefront-page__hero-media {
                aspect-ratio: 16 / 10;
            }

            .storefront-page__rail {
                grid-auto-columns: minmax(118px, 138px);
                padding-left: 8px;
                padding-right: 8px;
            }

            .storefront-page__arrow,
            .storefront-page__scroll-arrows {
                display: none;
            }
        }
    </style>

    <script>
        (function() {
            function bindStorefrontPageRails() {
                document.querySelectorAll('[data-storefront-page-rail]').forEach(function(rail) {
                    if (rail.dataset.bound === '1') return;
                    rail.dataset.bound = '1';

                    var wrap = rail.closest('.storefront-page__rail-wrap');
                    var leftBtn = wrap ? wrap.querySelector('[data-storefront-page-scroll="left"]') : null;
                    var rightBtn = wrap ? wrap.querySelector('[data-storefront-page-scroll="right"]') : null;

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

            function bindStorefrontPageVerticalNavigation() {
                var feed = document.getElementById('storefrontPageFeed');
                var upBtn = document.getElementById('storefrontScrollUp');
                var downBtn = document.getElementById('storefrontScrollDown');
                var sections = Array.from(document.querySelectorAll('[data-storefront-section]'));

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
                    bindStorefrontPageRails();
                    bindStorefrontPageVerticalNavigation();
                });
            } else {
                bindStorefrontPageRails();
                bindStorefrontPageVerticalNavigation();
            }
        })();
    </script>
@endsection
