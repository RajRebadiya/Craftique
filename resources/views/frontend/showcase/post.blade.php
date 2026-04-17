@extends('frontend.layouts.app')

@section('content')
    @php
        $typeLabelMap = [
            'history' => translate('Story'),
            'collection' => translate('Collection'),
            'vitrin' => translate('Storefront'),
            'launch' => translate('Launch'),
        ];

        $itemTypeLabel = $typeLabelMap[$item->type] ?? ucfirst($item->type);

        $rawVisual = $item->type === 'history'
            ? (($item->main_visual ?? null) ?: ($item->cover_image ?? null))
            : (($item->type === 'vitrin' || $item->type === 'launch')
                ? (($item->main_visual ?? null) ?: ($item->cover_image ?? null))
                : (($item->cover_image ?? null) ?: ($item->main_visual ?? null)));

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

        $itemSubtitle = $item->subtitle ?? null;
        $itemIntro = $item->intro ?? null;
        $itemDescription = $item->description ?? null;
        $itemHashtags = !empty($item->hashtags) ? array_filter(array_map('trim', explode(',', $item->hashtags))) : [];

        $sellerBrandUrl = !empty($item->seller_slug)
            ? route('frontend.showcase.brand', $item->seller_slug)
            : null;

        $hasCollectionItems = $item->type === 'collection'
            && !empty($collectionItems)
            && $collectionItems->count();
    @endphp

    <section class="mb-4">
        <div class="container">
            <div class="bg-white border rounded shadow-sm p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <div class="mb-2">
                            <span class="badge badge-inline badge-soft-primary">{{ $itemTypeLabel }}</span>
                        </div>

                        <h1 class="h3 fw-700 mb-2">{{ $item->title ?: '-' }}</h1>

                        @if(!empty($itemSubtitle))
                            <p class="text-muted mb-2">{{ $itemSubtitle }}</p>
                        @endif

                        <div class="d-flex flex-wrap align-items-center" style="gap:12px;">
                            @if(!empty($item->seller_name))
                                <span class="text-muted">
                                    {{ translate('By') }}:
                                    @if($sellerBrandUrl)
                                        <a href="{{ $sellerBrandUrl }}" class="fw-600 text-reset">
                                            {{ $item->seller_name }}
                                        </a>
                                    @else
                                        <span class="fw-600">{{ $item->seller_name }}</span>
                                    @endif
                                </span>
                            @endif

                            @if(!empty($item->created_at))
                                <span class="text-muted">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4 text-lg-right">
                        <a href="{{ route('frontend.showcase.index') }}" class="btn btn-soft-secondary">
                            {{ translate('Back to Showcase') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="card border-0 shadow-sm overflow-hidden">
                @if($visualUrl)
                    @if($visualIsVideo)
                        <video controls playsinline class="w-100" style="max-height:680px; background:#000;">
                            <source src="{{ $visualUrl }}">
                        </video>
                    @else
                        <img src="{{ $visualUrl }}"
                             alt="{{ $item->title }}"
                             class="w-100"
                             style="max-height:680px; object-fit:cover;">
                    @endif
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                         style="min-height:320px;">
                        <div>
                            <div class="fs-20 fw-700 mb-1">{{ $itemTypeLabel }}</div>
                            <div class="fs-13">{{ translate('No preview media available') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    @if(!empty($itemIntro))
                        <div class="mb-4">
                            <h3 class="h5 fw-700 mb-2">{{ translate('Intro') }}</h3>
                            <div class="text-secondary">
                                {!! nl2br(e($itemIntro)) !!}
                            </div>
                        </div>
                    @endif

                    @if(!empty($itemDescription))
                        <div>
                            <h3 class="h5 fw-700 mb-2">{{ translate('Description') }}</h3>
                            <div class="text-secondary">
                                {!! nl2br(e($itemDescription)) !!}
                            </div>
                        </div>
                    @endif

                    @if(!empty($itemHashtags))
                        <div class="mt-4">
                            <h3 class="h5 fw-700 mb-2">{{ translate('Hashtags') }}</h3>
                            <div class="d-flex flex-wrap" style="gap:8px;">
                                @foreach($itemHashtags as $tag)
                                    <span class="badge badge-inline badge-soft-secondary">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($hasCollectionItems)
        <section class="mb-4">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                    <div>
                        <h3 class="h4 fw-700 mb-1">{{ translate('Linked Products') }}</h3>
                        <p class="text-muted mb-0">{{ translate('Browse the products linked to this post.') }}</p>
                    </div>
                </div>

                <div class="row">
                    @foreach($collectionItems as $collectionItem)
                        @php
                            $cardVisual = $collectionItem->cover_image ?? null;
                            $cardVisualUrl = null;

                            if (!empty($cardVisual)) {
                                if (is_numeric($cardVisual)) {
                                    $cardVisualUrl = uploaded_asset($cardVisual);
                                } elseif (filter_var($cardVisual, FILTER_VALIDATE_URL)) {
                                    $cardVisualUrl = $cardVisual;
                                } else {
                                    $cardVisualUrl = asset($cardVisual);
                                }
                            }

                            $linkedProduct = $collectionItem->product ?? null;
                            $linkedProductUrl = (!empty($linkedProduct) && !empty($linkedProduct->slug))
                                ? url('/product/' . $linkedProduct->slug)
                                : null;

                            $linkedProductThumb = null;
                            if (!empty($linkedProduct) && !empty($linkedProduct->thumbnail_img)) {
                                if (is_numeric($linkedProduct->thumbnail_img)) {
                                    $linkedProductThumb = uploaded_asset($linkedProduct->thumbnail_img);
                                } elseif (filter_var($linkedProduct->thumbnail_img, FILTER_VALIDATE_URL)) {
                                    $linkedProductThumb = $linkedProduct->thumbnail_img;
                                } else {
                                    $linkedProductThumb = asset($linkedProduct->thumbnail_img);
                                }
                            }
                        @endphp

                        <div class="col-md-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                @if($cardVisualUrl)
                                    <img src="{{ $cardVisualUrl }}"
                                         alt="{{ $collectionItem->title ?: translate('Collection Card') }}"
                                         class="w-100"
                                         style="height:240px; object-fit:cover;">
                                @elseif($linkedProductThumb)
                                    <img src="{{ $linkedProductThumb }}"
                                         alt="{{ $linkedProduct->name }}"
                                         class="w-100"
                                         style="height:240px; object-fit:cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                                         style="height:240px;">
                                        <div>{{ translate('No image') }}</div>
                                    </div>
                                @endif

                                <div class="card-body p-3 d-flex flex-column">
                                    @if(!empty($collectionItem->title))
                                        <h4 class="h6 fw-700 mb-2">{{ $collectionItem->title }}</h4>
                                    @endif

                                    @if(!empty($collectionItem->description))
                                        <p class="text-muted mb-3 flex-grow-1">
                                            {!! nl2br(e($collectionItem->description)) !!}
                                        </p>
                                    @else
                                        <div class="flex-grow-1"></div>
                                    @endif

                                    @if(!empty($linkedProduct))
                                        <div class="border-top pt-3 mt-auto">
                                            <div class="fs-13 text-muted mb-1">{{ translate('Linked Product') }}</div>

                                            @if($linkedProductUrl)
                                                <a href="{{ $linkedProductUrl }}" class="fw-600 text-reset d-inline-block mb-2">
                                                    {{ $linkedProduct->name }}
                                                </a>
                                            @else
                                                <div class="fw-600 mb-2">{{ $linkedProduct->name }}</div>
                                            @endif

                                            @php
                                                $finalPrice = $linkedProduct->unit_price;
                                                if (!empty($linkedProduct->discount) && !empty($linkedProduct->discount_type)) {
                                                    if ($linkedProduct->discount_type === 'percent') {
                                                        $finalPrice = $linkedProduct->unit_price - (($linkedProduct->unit_price * $linkedProduct->discount) / 100);
                                                    } elseif ($linkedProduct->discount_type === 'amount') {
                                                        $finalPrice = $linkedProduct->unit_price - $linkedProduct->discount;
                                                    }
                                                }

                                                $finalPrice = max(0, $finalPrice);
                                            @endphp

                                            <div class="fs-15 fw-700 text-primary">
                                                {{ single_price($finalPrice) }}
                                            </div>

                                            @if($linkedProductUrl)
                                                <div class="mt-2">
                                                    <a href="{{ $linkedProductUrl }}" class="btn btn-soft-primary btn-sm">
                                                        {{ translate('View Product') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if((!$hasCollectionItems) && !empty($products) && $products->count())
        <section class="mb-4">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                    <div>
                        <h3 class="h4 fw-700 mb-1">{{ translate('Related Products') }}</h3>
                        <p class="text-muted mb-0">{{ translate('Products linked with this showcase post.') }}</p>
                    </div>
                </div>

                <div class="row">
                    @foreach($products as $product)
                        @php
                            $productThumb = null;

                            if (!empty($product->thumbnail_img)) {
                                if (is_numeric($product->thumbnail_img)) {
                                    $productThumb = uploaded_asset($product->thumbnail_img);
                                } elseif (filter_var($product->thumbnail_img, FILTER_VALIDATE_URL)) {
                                    $productThumb = $product->thumbnail_img;
                                } else {
                                    $productThumb = asset($product->thumbnail_img);
                                }
                            }

                            $productUrl = !empty($product->slug)
                                ? url('/product/' . $product->slug)
                                : '#';

                            $finalPrice = $product->unit_price;
                            if (!empty($product->discount) && !empty($product->discount_type)) {
                                if ($product->discount_type === 'percent') {
                                    $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                                } elseif ($product->discount_type === 'amount') {
                                    $finalPrice = $product->unit_price - $product->discount;
                                }
                            }

                            $finalPrice = max(0, $finalPrice);
                        @endphp

                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                <a href="{{ $productUrl }}" class="d-block">
                                    @if($productThumb)
                                        <img src="{{ $productThumb }}"
                                             alt="{{ $product->name }}"
                                             class="w-100"
                                             style="height:220px; object-fit:cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                                             style="height:220px;">
                                            <div>{{ translate('No image') }}</div>
                                        </div>
                                    @endif
                                </a>

                                <div class="card-body p-3">
                                    <h4 class="h6 fw-600 mb-2">
                                        <a href="{{ $productUrl }}" class="text-reset">
                                            {{ \Illuminate\Support\Str::limit($product->name, 65) }}
                                        </a>
                                    </h4>

                                    <div class="fs-15 fw-700 text-primary mb-0">
                                        {{ single_price($finalPrice) }}
                                    </div>

                                    @if((float) $finalPrice !== (float) $product->unit_price)
                                        <div class="fs-13 text-muted">
                                            <del>{{ single_price($product->unit_price) }}</del>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(!empty($previousPost) || !empty($nextPost))
        <section class="mb-5">
            <div class="container">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                            <div>
                                @if(!empty($previousPost))
                                    <a href="{{ route('frontend.showcase.post', ['id' => $previousPost->id, 'slug' => \Illuminate\Support\Str::slug($previousPost->title ?: $previousPost->id)]) }}"
                                       class="btn btn-soft-secondary">
                                        <i class="las la-arrow-left mr-1"></i>
                                        {{ translate('Previous') }}
                                    </a>
                                @endif
                            </div>

                            <div class="text-center text-muted fw-600">
                                {{ translate('Navigate Showcase Posts') }}
                            </div>

                            <div class="text-right">
                                @if(!empty($nextPost))
                                    <a href="{{ route('frontend.showcase.post', ['id' => $nextPost->id, 'slug' => \Illuminate\Support\Str::slug($nextPost->title ?: $nextPost->id)]) }}"
                                       class="btn btn-soft-secondary">
                                        {{ translate('Next') }}
                                        <i class="las la-arrow-right ml-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
