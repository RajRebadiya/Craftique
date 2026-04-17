@php
    $homepageShowcaseItems = \Illuminate\Support\Facades\DB::table('showcases')
        ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
        ->where('showcases.status', 'published')
        ->orderByDesc('showcases.created_at')
        ->select(
            'showcases.*',
            'shops.name as seller_name',
            'shops.slug as seller_slug'
        )
        ->limit(6)
        ->get();

    $locale = app()->getLocale();

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

    $typeLabelMap = [
        'history' => translate('Story'),
        'collection' => translate('Collection'),
        'vitrin' => translate('Storefront'),
        'launch' => translate('Launch'),
    ];
@endphp

@if($homepageShowcaseItems->count())
    <section class="mb-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                <div>
                    <h3 class="h4 fw-700 mb-1">{{ translate('Showcase') }}</h3>
                    <p class="text-muted mb-0">
                        {{ translate('Latest Stories, Collections, Storefronts and Launches from creators.') }}
                    </p>
                </div>

                <div>
                    <a href="{{ route('frontend.showcase.index') }}" class="btn btn-soft-primary btn-sm">
                        {{ translate('View All') }}
                    </a>
                </div>
            </div>

            <div class="row">
                @foreach($homepageShowcaseItems as $item)
                    @php
                        $itemTitle = $item->title ?: '-';
                        $itemTypeLabel = $typeLabelMap[$item->type] ?? ucfirst($item->type);
                        $itemText = $item->intro ?: ($item->description ?? null);

                        $rawVisual = $item->cover_image ?: ($item->main_visual ?? null);
                        $visualUrl = null;

                        if (!empty($rawVisual)) {
                            if (is_numeric($rawVisual)) {
                                $visualUrl = uploaded_asset($rawVisual);
                            } elseif (filter_var($rawVisual, FILTER_VALIDATE_URL)) {
                                $visualUrl = $rawVisual;
                            } else {
                                $visualUrl = asset($rawVisual);
                            }
                        }

                        $postUrl = route('frontend.showcase.post', [
                            'id' => $item->id,
                            'slug' => \Illuminate\Support\Str::slug($itemTitle ?: $item->id)
                        ]);
                    @endphp

                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            @if($visualUrl)
                                <a href="{{ $postUrl }}" class="d-block">
                                    <img src="{{ $visualUrl }}"
                                         alt="{{ $itemTitle }}"
                                         class="img-fit w-100"
                                         style="height:240px;">
                                </a>
                            @else
                                <a href="{{ $postUrl }}"
                                   class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                                   style="height:240px; text-decoration:none;">
                                    <div>
                                        <div class="fs-17 fw-700 mb-1">{{ $itemTypeLabel }}</div>
                                        <div class="fs-13">{{ translate('No preview image') }}</div>
                                    </div>
                                </a>
                            @endif

                            <div class="card-body p-3">
                                <div class="mb-2 d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                                    <span class="badge badge-inline badge-soft-primary">
                                        {{ $itemTypeLabel }}
                                    </span>

                                    @if(!empty($item->seller_name))
                                        <span class="text-muted fs-12">
                                            {{ $item->seller_name }}
                                        </span>
                                    @endif
                                </div>

                                <h4 class="h6 fw-700 mb-2">
                                    <a href="{{ $postUrl }}" class="text-reset">
                                        {{ \Illuminate\Support\Str::limit($itemTitle, 60) }}
                                    </a>
                                </h4>

                                @if(!empty($itemText))
                                    <p class="text-muted mb-3">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($itemText), 90) }}
                                    </p>
                                @endif

                                <a href="{{ $postUrl }}" class="btn btn-soft-primary btn-sm">
                                    {{ translate('View Post') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
