@extends('frontend.layouts.app')

@section('content')
    @php
        $pageTypeLabel = translate('Storefront');
        $feedUrl = route('frontend.showcase.index', ['type' => 'vitrin']);

        $rawVisual = null;
        $visualUrl = null;
        $visualIsVideo = false;

        if (!empty($item)) {
            $rawVisual = ($item->main_visual ?? null) ?: ($item->cover_image ?? null);

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
        }

        $sellerBrandUrl = !empty($item) && !empty($item->seller_slug)
            ? route('frontend.showcase.brand', $item->seller_slug)
            : null;
    @endphp

    <section class="mb-4">
        <div class="container">
            <div class="bg-white border rounded shadow-sm p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <span class="badge badge-inline badge-soft-primary mb-2">{{ $pageTypeLabel }}</span>
                        <h1 class="h3 fw-700 mb-2">{{ translate('Storefront Showcase') }}</h1>
                        <p class="text-muted mb-0">
                            {{ translate('Latest published Storefront from the Showcase feed.') }}
                        </p>
                    </div>

                    <div class="col-lg-4 text-lg-right">
                        <a href="{{ $feedUrl }}" class="btn btn-soft-secondary">
                            {{ translate('Open Storefront Feed') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(!empty($item))
        <section class="mb-4">
            <div class="container">
                <div class="card border-0 shadow-sm overflow-hidden">
                    @if($visualUrl)
                        @if($visualIsVideo)
                            <video controls playsinline class="w-100" style="max-height: 680px; background:#000;">
                                <source src="{{ $visualUrl }}">
                            </video>
                        @else
                            <img src="{{ $visualUrl }}"
                                 alt="{{ $item->title }}"
                                 class="w-100 img-fit"
                                 style="max-height: 680px;">
                        @endif
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                             style="min-height:320px;">
                            <div>
                                <div class="fs-20 fw-700 mb-1">{{ $pageTypeLabel }}</div>
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
                        <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                            <div>
                                <h2 class="h3 fw-700 mb-1">{{ $item->title ?: '-' }}</h2>

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

                            <div>
                                <a href="{{ route('frontend.showcase.post', ['id' => $item->id, 'slug' => \Illuminate\Support\Str::slug($item->title ?: $item->id)]) }}"
                                   class="btn btn-primary">
                                    {{ translate('Open Full Post') }}
                                </a>
                            </div>
                        </div>

                        @if(!empty($item->subtitle))
                            <div class="mb-3 text-secondary">
                                {!! nl2br(e($item->subtitle)) !!}
                            </div>
                        @endif

                        @if(!empty($item->description))
                            <div class="text-secondary">
                                {!! nl2br(e($item->description)) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @if(!empty($products) && $products->count())
            <section class="mb-5">
                <div class="container">
                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                        <div>
                            <h3 class="h4 fw-700 mb-1">{{ translate('Related Products') }}</h3>
                            <p class="text-muted mb-0">{{ translate('Products linked with this Storefront.') }}</p>
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

                                $productUrl = !empty($product->slug) ? url('/product/' . $product->slug) : '#';

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
                                            <img src="{{ $productThumb }}" alt="{{ $product->name }}" class="img-fit w-100" style="height:220px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center" style="height:220px;">
                                                <div class="fs-13">{{ translate('No image') }}</div>
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
    @else
        <section class="mb-5">
            <div class="container">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <h4 class="fw-700 mb-2">{{ translate('No Storefront found') }}</h4>
                        <p class="text-muted mb-3">
                            {{ translate('There is no published Storefront available right now.') }}
                        </p>
                        <a href="{{ route('frontend.showcase.index') }}" class="btn btn-soft-primary">
                            {{ translate('Open Showcase Feed') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
