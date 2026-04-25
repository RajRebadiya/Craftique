@extends('frontend.layouts.app')

@section('content')
    @php
        $storyJson = $stories
            ->map(function ($story) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'subtitle' => $story->subtitle,
                    'description' => $story->description,
                    'hashtags' => $story->hashtags,
                    'seller_name' => $story->seller_name,
                    'seller_shop_id' => $story->seller_shop_id,
                    'seller_slug' => $story->seller_slug,
                    'shop_url' => !empty($story->seller_slug) ? route('shop.visit', $story->seller_slug) : null,
                    'brand_url' => !empty($story->seller_slug) ? route('frontend.showcase.brand', $story->seller_slug) : null,
                    'seller_logo_url' => $story->seller_logo_url,
                    'media_url' => $story->media_url,
                    'media_is_video' => $story->media_is_video,
                    'post_url' => $story->post_url,
                    'products' => collect($story->products)
                        ->map(function ($product) {
                            return [
                                'id' => $product->id,
                                'name' => $product->name,
                                'thumbnail_url' => $product->thumbnail_url,
                                'price_html' => $product->price_html,
                                'product_url' => $product->product_url,
                            ];
                        })
                        ->values()
                        ->all(),
                    'primary_product' => $story->primary_product
                        ? [
                            'id' => $story->primary_product->id,
                            'name' => $story->primary_product->name,
                            'thumbnail_url' => $story->primary_product->thumbnail_url,
                            'price_html' => $story->primary_product->price_html,
                            'product_url' => $story->primary_product->product_url,
                        ]
                        : null,
                ];
            })
            ->values();
        $followedSellerIds = auth()->check() ? get_followed_sellers() : [];
    @endphp

    <div class="container">

        <section class="story-reel-page">
            <div class="container-fluid px-0 px-xl-3">
                <div class="row no-gutters align-items-start">
                    <div class="col">
                        <div class="story-reel-page__shell">
                            <div class="story-reel-page__topbar">
                                <div>
                                    <h1 class="story-reel-page__heading">{{ translate('Story Page') }}</h1>
                                    <p class="story-reel-page__subheading">
                                        {{ translate('Scroll through published stories like a reel feed.') }}
                                    </p>
                                </div>

                                <div class="story-reel-page__top-actions">
                                    <a href="{{ route('home') }}"
                                        class="btn btn-soft-secondary btn-sm">
                                        {{ translate('Back to Stories') }}
                                    </a>
                                </div>
                            </div>

                            @if ($stories->count())
                                <div class="story-reel-page__layout">
                                    {{-- Feed Section --}}
                                    <div class="story-reel-page__feed-wrap">
                                        <div class="story-reel-page__feed" id="storyReelFeed">
                                            @foreach ($stories as $story)
                                                @php
                                                    $brandUrl = !empty($story->seller_slug)
                                                        ? route('frontend.showcase.brand', $story->seller_slug)
                                                        : null;
                                                    $shopUrl = !empty($story->seller_slug)
                                                        ? route('shop.visit', $story->seller_slug)
                                                        : null;
                                                    $isFollowingSeller = !empty($story->seller_shop_id) && in_array((int) $story->seller_shop_id, $followedSellerIds);
                                                    $followUrl = !empty($story->seller_shop_id)
                                                        ? ($isFollowingSeller
                                                            ? route('followed_seller.remove', ['id' => $story->seller_shop_id])
                                                            : route('followed_seller.store', ['id' => $story->seller_shop_id]))
                                                        : (auth()->check() ? '#' : route('user.login'));
                                                    $sellerInitial = \Illuminate\Support\Str::upper(
                                                        \Illuminate\Support\Str::substr(
                                                            $story->seller_name ?: 'S',
                                                            0,
                                                            1,
                                                        ),
                                                    );
                                                @endphp
                                                <article class="story-reel-page__slide {{ ((int) $story->id === (int) $initialStoryId) || (!$initialStoryId && $loop->first) ? 'is-active' : '' }}" data-story-slide
                                                    data-story-id="{{ $story->id }}">
                                                    <div class="story-reel-page__card">
                                                        <div class="story-reel-page__main">
                                                            <div class="story-reel-page__story-frame">
                                                                <div class="story-reel-page__story-toolbar">
                                                                    <button type="button" class="story-reel-page__tool"
                                                                        data-story-audio>
                                                                        <i class="las la-volume-mute"></i>
                                                                    </button>
                                                                    <a href="{{ $story->post_url }}"
                                                                        class="story-reel-page__tool">
                                                                        <i class="las la-expand"></i>
                                                                    </a>
                                                                </div>

                                                                <div class="story-reel-page__story-media">
                                                                    @if ($story->media_url && $story->media_is_video)
                                                                        <video class="story-reel-page__media-video" muted
                                                                            playsinline loop preload="metadata">
                                                                            <source src="{{ $story->media_url }}">
                                                                        </video>
                                                                    @elseif($story->media_url)
                                                                        <img src="{{ $story->media_url }}"
                                                                            alt="{{ $story->title }}">
                                                                    @else
                                                                        <div class="story-reel-page__media-empty">
                                                                            {{ translate('Story Post') }}</div>
                                                                    @endif
                                                                    <div class="story-reel-page__story-overlay"></div>
                                                                </div>

                                                                <div class="story-reel-page__story-content">
                                                                    <div class="story-reel-page__story-title">
                                                                        {{ \Illuminate\Support\Str::limit($story->title, 42) }}
                                                                    </div>
                                                                    @if (!empty($story->hashtags))
                                                                        <div class="story-reel-page__hashtags">
                                                                            @foreach (array_slice($story->hashtags, 0, 4) as $tag)
                                                                                <span>#{{ $tag }}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            {{-- Logo and Shop Name - Moved outside video, bottom left --}}
                                                            <div class="story-reel-page__card-header-bottom">
                                                                <div class="story-reel-page__bottom-info">
                                                                    <div class="story-reel-page__story-shop">
                                                                        <a href="{{ $shopUrl ?: '#' }}" class="story-reel-page__logo">
                                                                            @if ($story->seller_logo_url)
                                                                                <img src="{{ $story->seller_logo_url }}"
                                                                                    alt="{{ $story->seller_name }}">
                                                                            @else
                                                                                <span>{{ $sellerInitial }}</span>
                                                                            @endif
                                                                        </a>
                                                                        <div>
                                                                            @if ($shopUrl)
                                                                                <a href="{{ $shopUrl }}"
                                                                                    class="story-reel-page__shop-name">
                                                                                    {{ $story->seller_name }}
                                                                                </a>
                                                                            @else
                                                                                <div class="story-reel-page__shop-name">
                                                                                    {{ $story->seller_name }}</div>
                                                                            @endif
                                                                            @if($brandUrl)
                                                                                <a href="{{ $brandUrl }}" class="story-reel-page__shop-meta">
                                                                                    {{ translate('Brand') }}
                                                                                </a>
                                                                            @else
                                                                                <div class="story-reel-page__shop-meta">{{ translate('Brand') }}</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @if (!empty($story->hashtags))
                                                                        <div class="story-reel-page__bottom-tags">
                                                                            @foreach (array_slice($story->hashtags, 0, 3) as $tag)
                                                                                <span>#{{ $tag }}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                @if($isFollowingSeller)
                                                                    <div class="showcase-follow-menu">
                                                                        <button type="button" class="story-reel-page__follow showcase-follow-menu__toggle">
                                                                            {{ translate('Following') }} <i class="las la-angle-down"></i>
                                                                        </button>
                                                                        <div class="showcase-follow-menu__dropdown">
                                                                            <a href="{{ $followUrl }}">{{ translate('Unfollow') }}</a>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <a href="{{ $followUrl }}" class="story-reel-page__follow">
                                                                        {{ translate('Follow') }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="story-reel-page__actions-rail">
                                                            <button type="button" class="story-reel-page__action-btn">
                                                                <i class="lar la-thumbs-up"></i>
                                                                <span>97 {{ translate('k') }}</span>
                                                            </button>
                                                            <button type="button" class="story-reel-page__action-btn">
                                                                <i class="las la-thumbs-down"></i>
                                                                <span>{{ translate('Nope') }}</span>
                                                            </button>
                                                            <button type="button" class="story-reel-page__action-btn">
                                                                <i class="lar la-comment"></i>
                                                                <span>400</span>
                                                            </button>
                                                            <button type="button" class="story-reel-page__action-btn">
                                                                <i class="las la-share"></i>
                                                                <span>{{ translate('Share') }}</span>
                                                            </button>
                                                            <button type="button" class="story-reel-page__action-btn">
                                                                <i class="las la-sync"></i>
                                                                <span>Remix</span>
                                                            </button>
                                                            <button type="button" class="story-reel-page__action-btn">
                                                                <i class="las la-user-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Product Panel Section --}}
                                    <aside class="story-reel-page__product-panel">
                                        <div class="story-reel-page__product-sticky">
                                            <div class="story-reel-page__product-viewer" id="storyProductViewer"></div>
                                            <div class="story-reel-page__story-list" id="storyThumbRail"></div>
                                        </div>
                                    </aside>

                                    {{-- Scroll Arrows - Positioned to the right of product panel --}}
                                    <div class="story-reel-page__scroll-arrows">
                                        <button type="button" class="story-reel-page__scroll-btn" id="storyScrollUp"
                                            aria-label="{{ translate('Previous story') }}">
                                            <i class="las la-angle-up"></i>
                                        </button>
                                        <button type="button" class="story-reel-page__scroll-btn" id="storyScrollDown"
                                            aria-label="{{ translate('Next story') }}">
                                            <i class="las la-angle-down"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="bg-white border rounded shadow-sm text-center py-5 px-3">
                                    <h4 class="fw-700 mb-2">{{ translate('No Story found') }}</h4>
                                    <p class="text-muted mb-3">
                                        {{ translate('There is no published story available right now.') }}</p>
                                    <a href="{{ route('frontend.showcase.history') }}" class="btn btn-soft-primary">
                                        {{ translate('Open Story Feed') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="storyProductPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content story-product-modal">
                <button type="button" class="story-product-modal__close" data-dismiss="modal" aria-label="{{ translate('Close') }}">
                    <i class="las la-times"></i>
                </button>
                <div class="modal-body p-0" id="storyProductPreviewModalBody"></div>
            </div>
        </div>
    </div>


    <style>
        .story-reel-page {
            background: linear-gradient(180deg, #fcfaf8 0%, #f4f0ec 100%);
            padding: 20px 0 40px;
        }

        .story-reel-page__shell {
            padding: 0 12px;
        }

        .story-reel-page__topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .story-reel-page__heading {
            font-size: 34px;
            line-height: 1.05;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .story-reel-page__subheading {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        /* Layout: Feed takes more space, Product panel smaller */
        .story-reel-page__layout {
            display: grid;
            grid-template-columns: minmax(0, 560px) 252px 48px;
            justify-content: center;
            gap: 18px;
            align-items: start;
            position: relative;
        }

        .story-reel-page__feed-wrap {
            min-width: 0;
            width: 100%;
        }

        .story-reel-page__feed {
            height: calc(100vh - 170px);
            overflow-y: auto;
            scroll-snap-type: y mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 4px 0 0 0;
        }

        .story-reel-page__feed::-webkit-scrollbar {
            display: none;
        }

        .story-reel-page__slide {
            min-height: calc(100vh - 170px);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            scroll-snap-align: start;
            padding: 6px 0 18px;
        }

        /* Card now uses full width of the feed column */
        .story-reel-page__card {
            width: 100%;
            max-width: 560px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 56px;
            gap: 16px;
            align-items: start;
        }

        .story-reel-page__main {
            min-width: 0;
        }

        /* Story frame (video) now takes more relative space */
        .story-reel-page__story-frame {
            position: relative;
            aspect-ratio: 9 / 16;
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid rgba(207, 166, 143, 0.7);
            background: #e8e8e8;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.08);
            margin-bottom: 10px;
        }

        .story-reel-page__story-toolbar {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .story-reel-page__tool {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: rgba(98, 115, 138, 0.8);
            color: #fff;
            border: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            text-decoration: none;
        }

        .story-reel-page__story-media {
            position: absolute;
            inset: 0;
        }

        .story-reel-page__story-media img,
        .story-reel-page__story-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .story-reel-page__media-empty {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c99778;
            font-size: 40px;
            font-weight: 800;
            text-align: center;
            padding: 24px;
        }

        .story-reel-page__story-overlay {
            position: absolute;
            inset: auto 0 0 0;
            height: 38%;
            background: linear-gradient(180deg, rgba(17, 24, 39, 0) 0%, rgba(17, 24, 39, 0.78) 100%);
        }

        .story-reel-page__story-content {
            position: absolute;
            left: 16px;
            right: 16px;
            bottom: 16px;
            z-index: 2;
            color: #fff;
        }

        .story-reel-page__story-title {
            font-size: 16px;
            font-weight: 800;
            line-height: 1.15;
        }

        .story-reel-page__hashtags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
            font-size: 11px;
            font-weight: 700;
        }

        /* New header bottom for logo and shop name - outside video, left aligned */
        .story-reel-page__card-header-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 2px;
            padding-left: 6px;
        }

        .story-reel-page__story-shop {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .story-reel-page__bottom-info {
            min-width: 0;
        }

        .story-reel-page__logo {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            overflow: hidden;
            background: #edf2f7;
            color: #7a5a4b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex: 0 0 36px;
        }

        .story-reel-page__logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .story-reel-page__shop-name {
            color: #111827;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            line-height: 1.2;
        }

        .story-reel-page__shop-meta {
            display: inline-block;
            color: #7a8798;
            font-size: 11px;
            margin-top: 2px;
            text-decoration: none;
        }

        .story-reel-page__bottom-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 16px;
            color: #000;
            font-size: 15px;
            font-weight: 800;
        }

        .story-reel-page__follow {
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

        /* Action buttons styling */
        .story-reel-page__actions-rail {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding-top: 72px;
        }

        .story-reel-page__action-btn {
            width: 44px;
            min-height: 44px;
            border: 0;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.96);
            color: #4b5563;
            box-shadow: 0 12px 22px rgba(15, 23, 42, 0.08);
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            font-size: 9px;
            font-weight: 700;
            line-height: 1.1;
            padding: 6px 3px;
        }

        .story-reel-page__action-btn i {
            font-size: 15px;
            line-height: 1;
        }

        /* Scroll arrows - positioned to the right of product panel */
        .story-reel-page__scroll-arrows {
            position: sticky;
            top: 50%;
            transform: translateY(-50%);
            z-index: 4;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-left: auto;
            align-self: start;
        }

        .story-reel-page__scroll-btn {
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.86);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.18);
            cursor: pointer;
        }

        /* Product panel - reduced width */
        .story-reel-page__product-panel {
            min-width: 0;
        }

        .story-reel-page__product-sticky {
            position: sticky;
            top: 96px;
        }

        .story-reel-page__product-viewer {
            min-height: auto;
            max-width: 252px;
            margin-left: auto;
        }

        .story-reel-page__product-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.08);
            border: 1px solid #ebe6e0;
        }

        .story-reel-page__product-media {
            aspect-ratio: 4 / 4.4;
            background: #f3f4f6;
        }

        .story-reel-page__product-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .story-reel-page__product-body {
            padding: 12px;
        }

        .story-reel-page__product-links {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            color: #8b919a;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .story-reel-page__product-title {
            color: #111827;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 5px;
        }

        .story-reel-page__product-brand,
        .story-reel-page__product-copy,
        .story-reel-page__product-meta {
            color: #6b7280;
            font-size: 11px;
            line-height: 1.45;
        }

        .story-reel-page__product-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #8b919a;
            font-size: 11px;
            margin-top: 5px;
        }

        .story-reel-page__product-rating i {
            color: #cfd4dc;
            font-size: 11px;
        }

        .story-reel-page__product-ask {
            margin-left: auto;
            color: #3b82f6;
            font-weight: 600;
            white-space: nowrap;
        }

        .story-reel-page__deal-bar {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: center;
            margin-top: 12px;
            border-radius: 10px;
            background: #252632;
            color: #fff;
            padding: 9px 12px;
            font-size: 11px;
            font-weight: 700;
        }

        .story-reel-page__deal-bar span:last-child {
            color: #ff8b1f;
        }

        .story-reel-page__pricing-label {
            margin-top: 12px;
            color: #8b919a;
            font-size: 11px;
            font-weight: 600;
        }

        .story-reel-page__pricing-box {
            margin-top: 8px;
            border-radius: 12px;
            background: #f8f8fb;
            padding: 12px;
        }

        .story-reel-page__pricing-main {
            font-size: 14px;
            font-weight: 800;
            color: #111827;
        }

        .story-reel-page__pricing-old {
            margin-top: 4px;
            color: #9ca3af;
            font-size: 11px;
            text-decoration: line-through;
        }

        .story-reel-page__pricing-badges {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .story-reel-page__pricing-badge {
            border-radius: 8px;
            padding: 6px 8px;
            font-size: 10px;
            font-weight: 700;
        }

        .story-reel-page__pricing-badge--accent {
            background: #ff8b1f;
            color: #fff;
        }

        .story-reel-page__pricing-badge--soft {
            background: #efe9eb;
            color: #d97706;
        }

        .story-reel-page__product-price {
            margin-top: 0;
            font-size: 14px;
            font-weight: 800;
            color: #111827;
        }

        .story-reel-page__buy-box {
            margin-top: 12px;
            border: 1px dashed #d7dbe2;
            border-radius: 12px;
            padding: 12px;
        }

        .story-reel-page__buy-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .story-reel-page__buy-meta {
            color: #60a5fa;
            font-size: 11px;
            font-weight: 700;
            margin-top: 5px;
        }

        .story-reel-page__buy-submeta {
            color: #8b919a;
            font-size: 11px;
            margin-top: 2px;
        }

        .story-reel-page__qty {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .story-reel-page__qty-label {
            color: #8b919a;
            font-size: 10px;
            font-weight: 700;
        }

        .story-reel-page__qty-box {
            display: flex;
            align-items: center;
            border: 1px solid #d8dde6;
            border-radius: 8px;
            overflow: hidden;
        }

        .story-reel-page__qty-btn,
        .story-reel-page__qty-count {
            width: 30px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #4b5563;
            font-size: 12px;
            border: 0;
        }

        .story-reel-page__buy-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 12px;
        }

        .story-reel-page__product-btn {
            border-radius: 10px;
            padding: 11px 8px;
            font-size: 12px;
            font-weight: 700;
            border: 0;
        }

        .story-reel-page__product-btn--dark {
            background: #111827;
            color: #fff;
        }

        .story-reel-page__product-btn--soft {
            background: #cfe3ff;
            color: #3b82f6;
        }

        .story-reel-page__info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 12px;
        }

        .story-reel-page__info-card {
            display: grid;
            grid-template-columns: 34px 1fr;
            gap: 8px;
            align-items: center;
            border-radius: 12px;
            background: #f7f8fb;
            padding: 10px;
        }

        .story-reel-page__info-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 14px;
        }

        .story-reel-page__info-label {
            color: #8b919a;
            font-size: 10px;
        }

        .story-reel-page__info-value {
            color: #111827;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 3px;
        }

        .story-reel-page__feature-list {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            color: #111827;
            font-size: 12px;
        }

        .story-reel-page__feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .story-reel-page__feature-item i {
            color: #1e3a8a;
        }

        .story-reel-page__story-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 16px;
            max-height: 180px;
            overflow-y: auto;
            scrollbar-width: none;
            display: none;
        }

        .story-reel-page__story-list::-webkit-scrollbar {
            display: none;
        }

        .story-reel-page__story-thumb {
            display: grid;
            grid-template-columns: 56px 1fr;
            gap: 10px;
            align-items: center;
            border: 1px solid #e7e5e4;
            background: #fff;
            border-radius: 14px;
            padding: 8px;
            cursor: pointer;
        }

        .story-reel-page__story-thumb.is-active {
            border-color: #cfa68f;
            box-shadow: 0 8px 18px rgba(207, 166, 143, 0.18);
        }

        .story-reel-page__story-thumb-media {
            width: 56px;
            height: 72px;
            border-radius: 10px;
            overflow: hidden;
            background: #f3f4f6;
        }

        .story-reel-page__story-thumb-media img,
        .story-reel-page__story-thumb-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .story-reel-page__story-thumb-title {
            color: #111827;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.25;
        }

        .story-reel-page__story-thumb-meta {
            color: #7a8798;
            font-size: 12px;
            margin-top: 3px;
        }

        .story-product-modal {
            border: 0;
            border-radius: 0;
            overflow: hidden;
            background: #ead5cc;
        }

        .story-product-modal__close {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 5;
            width: 28px;
            height: 28px;
            border: 0;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.34);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
        }

        .story-product-modal__layout {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            min-height: 380px;
        }

        .story-product-modal__details {
            padding: 28px 26px;
            color: #4a2f2a;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .story-product-modal__top {
            display: grid;
            grid-template-columns: 86px 1fr;
            gap: 14px;
            align-items: start;
        }

        .story-product-modal__thumb {
            width: 86px;
            height: 86px;
            border-radius: 4px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.45);
            border: 1px solid rgba(122, 90, 75, 0.16);
        }

        .story-product-modal__thumb img,
        .story-product-modal__media img,
        .story-product-modal__media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .story-product-modal__brand {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #a68a7c;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .story-product-modal__brand a {
            color: inherit;
            text-decoration: none;
        }

        .story-product-modal__brand-dot {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.7);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #7a5a4b;
        }

        .story-product-modal__brand-dot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .story-product-modal__title {
            color: #34405b;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.08;
            margin-bottom: 8px;
        }

        .story-product-modal__stock {
            color: #d94f3f;
            font-size: 12px;
            font-weight: 800;
            margin-top: 12px;
        }

        .story-product-modal__meta {
            margin-top: 12px;
            font-size: 12px;
            line-height: 1.45;
            color: #4a2f2a;
            font-weight: 700;
        }

        .story-product-modal__price-row {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin: 8px 0 16px 100px;
        }

        .story-product-modal__old-price {
            color: #86726a;
            font-size: 13px;
            text-decoration: line-through;
        }

        .story-product-modal__price {
            color: #34405b;
            font-size: 25px;
            font-weight: 900;
        }

        .story-product-modal__actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: 100px;
        }

        .story-product-modal__cart {
            border: 1px solid #7f6a62;
            background: transparent;
            color: #4a2f2a;
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            padding: 12px 22px;
            min-width: 174px;
        }

        .story-product-modal__tools {
            display: grid;
            gap: 4px;
        }

        .story-product-modal__tool {
            width: 26px;
            height: 22px;
            border: 0;
            background: transparent;
            color: #4a2f2a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .story-product-modal__media {
            position: relative;
            min-height: 380px;
            background: #111827;
        }

        .story-product-modal__media-empty {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
        }

        #click-category-menu,
        .story-reel-page #category-sidebar {
            display: none !important;
        }

        .story-reel-page {
            padding: 0 0 28px;
            background: #fffaf7;
        }

        .story-reel-page__topbar {
            display: none;
        }

        .story-reel-page__layout {
            grid-template-columns: minmax(160px, 220px) minmax(340px, 390px) 56px minmax(270px, 320px) 46px;
            gap: 14px;
            justify-content: center;
            align-items: start;
            min-height: calc(100vh - 132px);
        }

        .story-reel-page__feed-wrap {
            grid-column: 2 / 4;
        }

        .story-reel-page__product-panel {
            grid-column: 4;
        }

        .story-reel-page__scroll-arrows {
            grid-column: 5;
        }

        .story-reel-page__feed {
            height: calc(100vh - 132px);
            padding-top: 28px;
        }

        .story-reel-page__slide {
            min-height: calc(100vh - 132px);
            padding: 0 0 18px;
            justify-content: flex-start;
        }

        .story-reel-page__card {
            max-width: 448px;
            grid-template-columns: minmax(0, 390px) 46px;
            gap: 12px;
            margin: 0;
        }

        .story-reel-page__story-frame {
            max-height: calc(100vh - 142px);
            min-height: 580px;
            border-radius: 7px;
            box-shadow: none;
            background: #d7d7d7;
            border-color: #c99778;
        }

        .story-reel-page__media-empty {
            color: #c99778;
            font-size: 54px;
            letter-spacing: 0.02em;
        }

        .story-reel-page__story-toolbar {
            justify-content: space-between;
        }

        .story-reel-page__tool {
            width: 32px;
            height: 32px;
            background: rgba(55, 65, 81, 0.62);
        }

        .story-reel-page__story-overlay,
        .story-reel-page__story-content {
            display: none;
        }

        .story-reel-page__card-header-bottom {
            position: fixed;
            left: max(24px, calc(50% - 520px));
            bottom: 44px;
            width: 280px;
            z-index: 6;
            padding: 0;
            display: block;
            align-items: initial;
        }

        .story-reel-page__slide:not(.is-active) .story-reel-page__card-header-bottom {
            display: none !important;
        }

        .story-reel-page__slide.is-active .story-reel-page__card-header-bottom {
            display: block !important;
        }

        .story-reel-page__bottom-info {
            display: block;
            min-width: 0;
        }

        .story-reel-page__story-shop {
            align-items: center;
            gap: 12px;
        }

        .story-reel-page__logo {
            width: 54px;
            height: 28px;
            flex: 0 0 54px;
            border-radius: 0;
            background: transparent;
        }

        .story-reel-page__shop-name {
            color: #c99778;
            font-size: 18px;
            line-height: 1.1;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            white-space: nowrap;
        }

        .story-reel-page__shop-meta {
            display: block;
            color: #c99778;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 2px;
        }

        .story-reel-page__bottom-tags {
            gap: 8px;
            margin-top: 12px;
            font-size: 13px;
            line-height: 1.2;
        }

        .story-reel-page__card-header-bottom .story-reel-page__follow,
        .story-reel-page__card-header-bottom .showcase-follow-menu {
            display: none;
        }

        .story-reel-page__actions-rail {
            padding-top: 250px;
            gap: 8px;
        }

        .story-reel-page__action-btn {
            width: 44px;
            min-height: 44px;
            border-radius: 999px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        }

        .story-reel-page__product-sticky {
            top: 28px;
        }

        .story-reel-page__product-viewer {
            max-width: 320px;
            margin: 0;
        }

        .story-reel-page__product-card {
            border: 0;
            border-radius: 0;
            box-shadow: none;
            background: #fff;
        }

        .story-reel-page__product-media {
            aspect-ratio: 4 / 3.05;
            border-radius: 3px;
            overflow: hidden;
        }

        .story-reel-page__product-body {
            padding: 14px 0 0;
        }

        .story-reel-page__product-title {
            font-size: 13px;
            margin-top: 6px;
        }

        .story-reel-page__product-links {
            justify-content: flex-end;
            font-size: 10px;
            margin-bottom: 8px;
        }

        .story-reel-page__deal-bar {
            border-radius: 3px;
            margin-top: 10px;
        }

        .story-reel-page__pricing-box,
        .story-reel-page__buy-box,
        .story-reel-page__info-card {
            border-radius: 3px;
        }

        .story-reel-page__buy-actions {
            grid-template-columns: 1fr 1fr;
        }

        .story-reel-page__product-btn {
            border-radius: 3px;
            padding: 12px 10px;
        }

        .story-reel-page__scroll-arrows {
            top: 50%;
        }

        .story-reel-page__scroll-btn {
            background: rgba(17, 24, 39, 0.08);
            color: #111827;
            box-shadow: none;
        }

        @media (max-width: 1199.98px) {
            .story-reel-page__layout {
                grid-template-columns: minmax(0, 448px) minmax(260px, 310px) auto;
            }
            
            .story-reel-page__product-viewer {
                max-width: 310px;
            }

            .story-reel-page__feed-wrap {
                grid-column: 1;
            }

            .story-reel-page__product-panel {
                grid-column: 2;
            }

            .story-reel-page__scroll-arrows {
                grid-column: 3;
            }

            .story-reel-page__card-header-bottom {
                display: none;
            }
        }

        @media (max-width: 991.98px) {
            .story-reel-page__layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .story-reel-page__scroll-arrows {
                display: none;
            }
            
            .story-reel-page__product-panel {
                order: 2;
            }
            
            .story-reel-page__product-viewer {
                max-width: 100%;
                margin-left: 0;
            }
            
            .story-reel-page__card {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
            
            .story-reel-page__actions-rail {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                padding-top: 16px;
                order: 2;
            }
        }

        @media (max-width: 767.98px) {
            .story-product-modal__layout {
                grid-template-columns: 1fr;
            }

            .story-product-modal__media {
                min-height: 320px;
                order: -1;
            }

            .story-product-modal__price-row,
            .story-product-modal__actions {
                margin-left: 0;
            }

            .story-product-modal__top {
                grid-template-columns: 72px 1fr;
            }

            .story-product-modal__thumb {
                width: 72px;
                height: 72px;
            }

            .story-reel-page {
                padding-top: 12px;
            }

            .story-reel-page__shell {
                padding: 0 10px;
            }

            .story-reel-page__topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .story-reel-page__heading {
                font-size: 28px;
            }

            .story-reel-page__feed {
                height: auto;
                overflow: visible;
                padding-right: 0;
            }

            .story-reel-page__slide {
                min-height: 0;
                padding: 0 0 18px;
            }

            .story-reel-page__story-frame {
                max-width: 420px;
                margin: 0 auto;
            }
            
            .story-reel-page__info-grid,
            .story-reel-page__buy-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        (function() {
            var stories = @json($storyJson);
            var initialStoryId = Number(@json($initialStoryId));
            var storyMap = {};
            stories.forEach(function(story) {
                storyMap[String(story.id)] = story;
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

            window.shareStoryProduct = function(url, title) {
                if (!url) return;
                if (navigator.share) {
                    navigator.share({ title: title || document.title, url: url }).catch(function() {});
                    return;
                }
                window.copyStoryProduct(url);
            };

            window.copyStoryProduct = function(url) {
                if (!url) return;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).catch(function() {});
                    return;
                }
                window.prompt('{{ translate('Copy product link') }}', url);
            };

            function setActiveStory(storyId) {
                document.querySelectorAll('[data-story-slide]').forEach(function(slide) {
                    slide.classList.toggle(
                        'is-active',
                        Number(slide.getAttribute('data-story-id')) === Number(storyId)
                    );
                });
            }

            window.openStoryProductPreview = function(storyId) {
                if (!window.jQuery) return;
                var story = storyMap[String(storyId)] || stories[0];
                var product = story ? story.primary_product : null;
                var modalBody = document.getElementById('storyProductPreviewModalBody');
                if (!story || !product || !modalBody) return;

                var sellerLogo = story.seller_logo_url
                    ? '<span class="story-product-modal__brand-dot"><img src="' + story.seller_logo_url + '" alt=""></span>'
                    : '<span class="story-product-modal__brand-dot">' + escapeHtml((story.seller_name || 'S').charAt(0).toUpperCase()) + '</span>';
                if (story.shop_url) {
                    sellerLogo = '<a href="' + story.shop_url + '" class="story-product-modal__brand-dot-wrap">' + sellerLogo + '</a>';
                }
                var sellerName = story.shop_url
                    ? '<a href="' + story.shop_url + '">' + escapeHtml(story.seller_name || '') + '</a>'
                    : '<span>' + escapeHtml(story.seller_name || '') + '</span>';
                var media = story.media_url
                    ? (story.media_is_video
                        ? '<video controls autoplay muted playsinline loop><source src="' + story.media_url + '"></video>'
                        : '<img src="' + story.media_url + '" alt="">')
                    : '<div class="story-product-modal__media-empty">{{ translate('Story Post') }}</div>';

                modalBody.innerHTML =
                    '<div class="story-product-modal__layout">' +
                        '<div class="story-product-modal__details">' +
                            '<div class="story-product-modal__top">' +
                                '<div class="story-product-modal__thumb">' +
                                    (product.thumbnail_url ? '<img src="' + product.thumbnail_url + '" alt="">' : '') +
                                '</div>' +
                                '<div>' +
                                    '<div class="story-product-modal__brand">' + sellerLogo + sellerName + '</div>' +
                                    '<div class="story-product-modal__title">' + escapeHtml(product.name || '') + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="story-product-modal__stock">{{ translate('In Stock') }}</div>' +
                            '<div class="story-product-modal__meta">' +
                                '<div><strong>SKU:</strong></div>' +
                                '<div><strong>{{ translate('Categories') }}:</strong> ' + escapeHtml((story.hashtags || []).slice(0, 3).join(', ')) + '</div>' +
                            '</div>' +
                            '<div class="story-product-modal__price-row">' +
                                '<span class="story-product-modal__old-price">' + (product.price_html || '') + '</span>' +
                                '<span class="story-product-modal__price">' + (product.price_html || '') + '</span>' +
                            '</div>' +
                            '<div class="story-product-modal__actions">' +
                                '<button type="button" class="story-product-modal__cart" onclick="showAddToCartModal(' + product.id + ')">{{ translate('Add to cart') }}</button>' +
                                '<div class="story-product-modal__tools">' +
                                    '<button type="button" class="story-product-modal__tool" onclick="shareStoryProduct(' + JSON.stringify(product.product_url || '') + ', ' + JSON.stringify(product.name || '') + ')" aria-label="{{ translate('Share product') }}"><i class="las la-share-alt"></i></button>' +
                                    '<button type="button" class="story-product-modal__tool" onclick="copyStoryProduct(' + JSON.stringify(product.product_url || '') + ')" aria-label="{{ translate('Copy product link') }}"><i class="las la-code"></i></button>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="story-product-modal__media">' + media + '</div>' +
                    '</div>';

                $('#storyProductPreviewModal').modal('show');
            };

            function renderProductPanel(storyId) {
                var viewer = document.getElementById('storyProductViewer');
                var rail = document.getElementById('storyThumbRail');
                var story = storyMap[String(storyId)] || stories[0];
                if (!viewer || !story) return;

                setActiveStory(story.id);

                var primary = story.primary_product;
                viewer.innerHTML = primary ?
                    '' +
                    '<div class="story-reel-page__product-card">' +
                    '<div class="story-reel-page__product-media">' +
                    (primary.thumbnail_url ? '<img src="' + primary.thumbnail_url + '" alt="">' : '') +
                    '</div>' +
                    '<div class="story-reel-page__product-body">' +
                    '<div class="story-reel-page__product-links">' +
                    '<span>{{ translate('Compare') }}</span>' +
                    '<span>{{ translate('Wishlist') }}</span>' +
                    '<span>{{ translate('Share') }}</span>' +
                    '</div>' +
                    '<div class="story-reel-page__product-title">' + (primary.name || '') + '</div>' +
                    '<div class="story-reel-page__product-brand">{{ translate('Brand') }} <a href="' + (story.brand_url || '#') + '" style="color:#d4a28b;font-weight:700;margin-left:4px;text-decoration:none;">' + (story.seller_name || '') + '</a></div>' +
                    '<div class="story-reel-page__product-rating">' +
                    '<span><i class="las la-star"></i> <i class="las la-star"></i> <i class="las la-star"></i> <i class="las la-star"></i> <i class="las la-star"></i></span>' +
                    '<span>0/5.0 (0 reviews)</span>' +
                    '<span class="story-reel-page__product-ask">{{ translate('Ask about this product') }}</span>' +
                    '</div>' +
                    '<div class="story-reel-page__deal-bar">' +
                    '<span>{{ translate('Todays Deal') }}</span>' +
                    '<span>{{ translate('Exclusive for today only') }}</span>' +
                    '</div>' +
                    '<div class="story-reel-page__pricing-label">{{ translate('Pricing') }}</div>' +
                    '<div class="story-reel-page__pricing-box">' +
                    '<div class="story-reel-page__pricing-main">' + (primary.price_html || '') + ' <span style="font-size:13px;font-weight:600;color:#6b7280;">/{{ translate('piece') }}</span></div>' +
                    '<div class="story-reel-page__pricing-old">{{ translate('€43.40') }}</div>' +
                    '<div class="story-reel-page__pricing-badges">' +
                    '<span class="story-reel-page__pricing-badge story-reel-page__pricing-badge--accent">-5%</span>' +
                    '<span class="story-reel-page__pricing-badge story-reel-page__pricing-badge--soft">CLUB POINT: 26.25</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="story-reel-page__buy-box">' +
                    '<div class="story-reel-page__buy-head">' +
                    '<div>' +
                    '<div class="story-reel-page__product-price">' + (primary.price_html || '') + '</div>' +
                    '<div class="story-reel-page__buy-meta">100 available</div>' +
                    '<div class="story-reel-page__buy-submeta">Minimum order qty 1</div>' +
                    '</div>' +
                    '<div class="story-reel-page__qty">' +
                    '<span class="story-reel-page__qty-label">QTY</span>' +
                    '<div class="story-reel-page__qty-box">' +
                    '<button type="button" class="story-reel-page__qty-btn">-</button>' +
                    '<span class="story-reel-page__qty-count">1</span>' +
                    '<button type="button" class="story-reel-page__qty-btn">+</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="story-reel-page__buy-actions">' +
                    '<button type="button" class="story-reel-page__product-btn story-reel-page__product-btn--dark" onclick="openStoryProductPreview(' +
                    story.id + ')">{{ translate('View Product') }}</button>' +
                    '<button type="button" class="story-reel-page__product-btn story-reel-page__product-btn--soft" onclick="openStoryProductPreview(' +
                    story.id + ')">{{ translate('Add to cart') }}</button>' +
                    '</div>' +
                    '</div>' +
                    '<div class="story-reel-page__info-grid">' +
                    '<div class="story-reel-page__info-card">' +
                    '<div class="story-reel-page__info-icon"><i class="las la-store"></i></div>' +
                    '<div><div class="story-reel-page__info-label">Inhouse product</div><div class="story-reel-page__info-value">{{ translate('Message Seller') }}</div></div>' +
                    '</div>' +
                    '<div class="story-reel-page__info-card">' +
                    '<div class="story-reel-page__info-icon"><i class="las la-tag"></i></div>' +
                    '<div><div class="story-reel-page__info-label">{{ translate('Brand') }}</div><div class="story-reel-page__info-value">' + (story.seller_name || '') + '</div></div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="story-reel-page__feature-list">' +
                    '<div class="story-reel-page__feature-item"><i class="las la-check-circle"></i><span>{{ translate('Cash on delivery available') }}</span></div>' +
                    '<div class="story-reel-page__feature-item"><i class="las la-check-circle"></i><span>{{ translate('Free Shipping') }}</span></div>' +
                    '</div>' +
                    '<div class="story-reel-page__product-copy">' + (story.subtitle || story.description || '') +
                    '</div>' +
                    '<div class="story-reel-page__product-meta" style="margin-top:10px;">' + (story.hashtags || []).map(function(tag) {
                        return '#' + tag;
                    }).join(' ') + '</div>' +
                    '</div>' +
                    '</div>' :
                    '' +
                    '<div class="story-reel-page__product-card">' +
                    '<div class="story-reel-page__product-body text-center py-5">' +
                    '<div class="story-reel-page__product-title mb-2">{{ translate('No product found') }}</div>' +
                    '<div class="story-reel-page__product-copy">{{ translate('This story does not have a linked product yet.') }}</div>' +
                    '</div>' +
                    '</div>';

                if (rail) {
                    rail.innerHTML = stories.map(function(item) {
                        var activeClass = Number(item.id) === Number(story.id) ? ' is-active' : '';
                        return '' +
                            '<button type="button" class="story-reel-page__story-thumb' + activeClass +
                            '" data-story-thumb="' + item.id + '">' +
                            '<span class="story-reel-page__story-thumb-media">' +
                            (item.media_is_video ?
                                '<video muted playsinline preload="metadata"><source src="' + (item.media_url ||
                                    '') + '"></video>' :
                                (item.media_url ? '<img src="' + item.media_url + '" alt="">' : '')) +
                            '</span>' +
                            '<span>' +
                            '<span class="story-reel-page__story-thumb-title">' + (item.title || '') +
                            '</span>' +
                            '<span class="story-reel-page__story-thumb-meta">' + (item.seller_name || '') +
                            '</span>' +
                            '</span>' +
                            '</button>';
                    }).join('');

                    rail.querySelectorAll('[data-story-thumb]').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            var targetId = Number(btn.getAttribute('data-story-thumb'));
                            var target = document.querySelector('[data-story-id="' + targetId + '"]');
                            var feed = document.getElementById('storyReelFeed');
                            if (target && feed) {
                                feed.scrollTo({
                                    top: target.offsetTop,
                                    behavior: 'smooth'
                                });
                            }
                        });
                    });
                }
            }

            function syncVisibleStory() {
                var slides = Array.from(document.querySelectorAll('[data-story-slide]'));
                if (!slides.length) return;
                var feed = document.getElementById('storyReelFeed');
                if (!feed) return;

                var observer = new IntersectionObserver(function(entries) {
                    var visible = entries
                        .filter(function(entry) {
                            return entry.isIntersecting;
                        })
                        .sort(function(a, b) {
                            return b.intersectionRatio - a.intersectionRatio;
                        })[0];

                    if (visible) {
                        var storyId = Number(visible.target.getAttribute('data-story-id'));
                        renderProductPanel(storyId);
                    }
                }, {
                    root: feed,
                    threshold: [0.45, 0.6, 0.8]
                });

                slides.forEach(function(slide) {
                    observer.observe(slide);
                });
            }

            function initVideos() {
                var slides = document.querySelectorAll('[data-story-slide]');
                slides.forEach(function(slide) {
                    var video = slide.querySelector('video.story-reel-page__media-video');
                    var audioBtn = slide.querySelector('[data-story-audio]');
                    if (!video) return;

                    video.muted = true;
                    video.play().catch(function() {});

                    if (audioBtn) {
                        audioBtn.addEventListener('click', function() {
                            video.muted = !video.muted;
                            audioBtn.innerHTML = video.muted ?
                                '<i class="las la-volume-mute"></i>' :
                                '<i class="las la-volume-up"></i>';
                            video.play().catch(function() {});
                        });
                    }
                });
            }

            function jumpToInitialStory() {
                var feed = document.getElementById('storyReelFeed');
                if (!initialStoryId) {
                    renderProductPanel(stories.length ? stories[0].id : 0);
                    return;
                }

                var target = document.querySelector('[data-story-id="' + initialStoryId + '"]');
                if (target && feed) {
                    setTimeout(function() {
                        window.scrollTo(0, 0);
                        feed.scrollTo({
                            top: target.offsetTop,
                            behavior: 'auto'
                        });
                        renderProductPanel(initialStoryId);
                    }, 120);
                } else if (stories.length) {
                    renderProductPanel(stories[0].id);
                }
            }

            function bindScrollButtons() {
                var feed = document.getElementById('storyReelFeed');
                var upBtn = document.getElementById('storyScrollUp');
                var downBtn = document.getElementById('storyScrollDown');
                var slides = Array.from(document.querySelectorAll('[data-story-slide]'));
                if (!feed || !upBtn || !downBtn || !slides.length) return;

                function currentIndex() {
                    var closestIndex = 0;
                    var closestDistance = Infinity;
                    slides.forEach(function(slide, index) {
                        var distance = Math.abs(slide.offsetTop - feed.scrollTop);
                        if (distance < closestDistance) {
                            closestDistance = distance;
                            closestIndex = index;
                        }
                    });
                    return closestIndex;
                }

                function goToIndex(index) {
                    var bounded = Math.max(0, Math.min(index, slides.length - 1));
                    feed.scrollTo({
                        top: slides[bounded].offsetTop,
                        behavior: 'smooth'
                    });
                }

                upBtn.addEventListener('click', function() {
                    goToIndex(currentIndex() - 1);
                });

                downBtn.addEventListener('click', function() {
                    goToIndex(currentIndex() + 1);
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initVideos();
                    syncVisibleStory();
                    bindScrollButtons();
                    jumpToInitialStory();
                });
            } else {
                initVideos();
                syncVisibleStory();
                bindScrollButtons();
                jumpToInitialStory();
            }
        })();
    </script>
@endsection
