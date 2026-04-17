@php
    $locale = app()->getLocale();

    $title = $locale === 'en'
        ? (data_get($item, 'title_en') ?: data_get($item, 'title_gr') ?: data_get($item, 'title'))
        : (data_get($item, 'title_gr') ?: data_get($item, 'title_en') ?: data_get($item, 'title'));

    $subtitle = $locale === 'en'
        ? (data_get($item, 'subtitle_en') ?: data_get($item, 'subtitle_gr') ?: data_get($item, 'subtitle'))
        : (data_get($item, 'subtitle_gr') ?: data_get($item, 'subtitle_en') ?: data_get($item, 'subtitle'));

    $intro = $locale === 'en'
        ? (data_get($item, 'intro_en') ?: data_get($item, 'intro_gr') ?: data_get($item, 'intro'))
        : (data_get($item, 'intro_gr') ?: data_get($item, 'intro_en') ?: data_get($item, 'intro'));

    $description = $locale === 'en'
        ? (data_get($item, 'description_en') ?: data_get($item, 'description_gr') ?: data_get($item, 'description'))
        : (data_get($item, 'description_gr') ?: data_get($item, 'description_en') ?: data_get($item, 'description'));

    $sellerName = data_get($item, 'seller_name');
    $sellerSlug = data_get($item, 'seller_slug');

    $relatedProductsLabel = $locale === 'en' ? 'Related Products' : 'Σχετικά Προϊόντα';
    $creatorLabel = $locale === 'en' ? 'Creator' : 'Δημιουργός';
    $comingSoonTitle = $locale === 'en' ? 'Showcase coming soon' : 'Το Showcase έρχεται σύντομα';
    $comingSoonText = $locale === 'en'
        ? 'There is no published showcase content available yet.'
        : 'Δεν υπάρχει ακόμη δημοσιευμένο περιεχόμενο showcase.';

    $previousLabel = $locale === 'en' ? 'Previous' : 'Προηγούμενο';
    $nextLabel = $locale === 'en' ? 'Next' : 'Επόμενο';

    $prevItem = $prevItem ?? null;
    $nextItem = $nextItem ?? null;

    $imageValue = null;

    if ((data_get($item, 'type') ?? '') === 'history' || (data_get($item, 'type') ?? '') === 'collection') {
        $imageValue = data_get($item, 'cover_image');
    } elseif ((data_get($item, 'type') ?? '') === 'vitrin') {
        $imageValue = data_get($item, 'main_visual');
    }

    $heroImage = null;
    if (!empty($imageValue)) {
        $heroImage = is_numeric($imageValue) ? uploaded_asset($imageValue) : $imageValue;
    }
@endphp

<div class="container py-5">
    @if(!$item)
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white shadow-sm rounded p-5 text-center">
                    <h1 style="font-size:2rem; font-weight:700; margin-bottom:12px;">
                        {{ $comingSoonTitle }}
                    </h1>
                    <p style="font-size:1rem; color:#6b7280; margin-bottom:0;">
                        {{ $comingSoonText }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                @if($heroImage)
                    <div class="mb-4">
                        <img src="{{ $heroImage }}"
                             alt="{{ $title }}"
                             style="width:100%; max-height:520px; object-fit:cover; border-radius:16px;">
                    </div>
                @endif

                @if($prevItem || $nextItem)
                    <div class="d-flex justify-content-between align-items-stretch flex-wrap mb-4" style="gap:12px;">
                        <div class="flex-fill">
                            @if($prevItem)
                                <a href="{{ route('frontend.showcase.post', ['id' => $prevItem->id, 'slug' => \Illuminate\Support\Str::slug($prevItem->title ?: $prevItem->id)]) }}"
                                   class="btn btn-light border w-100 text-left"
                                   style="min-height:64px; display:flex; align-items:center; justify-content:flex-start; white-space:normal;">
                                    <span>
                                        ← {{ $previousLabel }}
                                        <br>
                                        <small class="text-muted">{{ $prevItem->title }}</small>
                                    </span>
                                </a>
                            @endif
                        </div>

                        <div class="flex-fill text-right">
                            @if($nextItem)
                                <a href="{{ route('frontend.showcase.post', ['id' => $nextItem->id, 'slug' => \Illuminate\Support\Str::slug($nextItem->title ?: $nextItem->id)]) }}"
                                   class="btn btn-light border w-100 text-right"
                                   style="min-height:64px; display:flex; align-items:center; justify-content:flex-end; white-space:normal;">
                                    <span>
                                        {{ $nextLabel }} →
                                        <br>
                                        <small class="text-muted">{{ $nextItem->title }}</small>
                                    </span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="text-center mb-4">
                    <h1 style="font-size:2.4rem; font-weight:700; margin-bottom:12px;">
                        {{ $title }}
                    </h1>

                    @if(!empty($sellerName))
                        <div style="font-size:0.95rem; color:#9ca3af; margin-bottom:10px;">
                            {{ $creatorLabel }}:
                            @if(!empty($sellerSlug))
                                <a href="{{ route('frontend.showcase.brand', $sellerSlug) }}"
                                   style="color:inherit; text-decoration:underline;">
                                    {{ $sellerName }}
                                </a>
                            @else
                                {{ $sellerName }}
                            @endif
                        </div>
                    @endif

                    @if(!empty($subtitle))
                        <p style="font-size:1.1rem; color:#6b7280; margin-bottom:10px;">
                            {{ $subtitle }}
                        </p>
                    @endif

                    @if(!empty($intro))
                        <p style="font-size:1.05rem; color:#6b7280; margin-bottom:10px;">
                            {{ $intro }}
                        </p>
                    @endif
                </div>

                @if(!empty($description))
                    <div class="bg-white p-4 p-lg-5 rounded shadow-sm mb-5" style="line-height:1.8; font-size:1rem;">
                        {!! nl2br(e($description)) !!}
                    </div>
                @endif
            </div>
        </div>

        @if($products->count())
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <h2 style="font-size:1.8rem; font-weight:700;">{{ $relatedProductsLabel }}</h2>
                </div>
            </div>

            <div class="row">
                @foreach($products as $product)
                    @php
                        $thumbUrl = null;
                        if (!empty($product->thumbnail_img)) {
                            $thumbUrl = is_numeric($product->thumbnail_img) ? uploaded_asset($product->thumbnail_img) : $product->thumbnail_img;
                        }

                        $productUrl = !empty($product->slug) ? route('product', $product->slug) : 'javascript:void(0)';
                    @endphp

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                            <a href="{{ $productUrl }}" style="text-decoration:none; color:inherit;">
                                <div style="background:#f9fafb; height:280px; display:flex; align-items:center; justify-content:center;">
                                    @if($thumbUrl)
                                        <img src="{{ $thumbUrl }}"
                                             alt="{{ $product->name }}"
                                             style="max-width:100%; max-height:100%; object-fit:contain;">
                                    @else
                                        <div style="color:#9ca3af;">No Image</div>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <h5 style="font-size:1rem; font-weight:600; line-height:1.5;">
                                        {{ $product->name }}
                                    </h5>

                                    @if(!is_null($product->unit_price))
                                        <div style="margin-top:10px; font-weight:700; font-size:1rem;">
                                            €{{ number_format((float)$product->unit_price, 2, '.', ',') }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>