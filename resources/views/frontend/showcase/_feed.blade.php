@php
    $filters = $filters ?? ['type' => 'all', 'sort' => 'newest'];

    $currentType = $filters['type'] ?? request('type', 'all');
    $currentSort = $filters['sort'] ?? request('sort', 'newest');
    $showcaseBaseUrl = url()->current();

    $showcaseTypeTabs = [
        'all' => translate('All'),
        'history' => translate('Story'),
        'collection' => translate('Collection'),
        'vitrin' => translate('Storefront'),
        'launch' => translate('Launch'),
    ];

    $typeLabelMap = [
        'history' => translate('Story'),
        'collection' => translate('Collection'),
        'vitrin' => translate('Storefront'),
        'launch' => translate('Launch'),
    ];
@endphp

<section class="mb-4">
    <div class="container">
        <div class="bg-white border rounded shadow-sm p-4">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <h1 class="h3 fw-700 mb-2">{{ $pageTitle ?? translate('Showcase') }}</h1>

                    @if(!empty($pageSubtitle))
                        <p class="text-muted mb-0">{{ $pageSubtitle }}</p>
                    @endif
                </div>

                <div class="col-lg-4 text-lg-right">
                    @if(!empty($isBrandPage) && !empty($shop))
                        <a href="{{ route('frontend.showcase.index') }}" class="btn btn-soft-secondary">
                            {{ translate('Open All Showcase') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="container">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 p-lg-4">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <div class="d-flex flex-wrap" style="gap:10px;">
                            @foreach($showcaseTypeTabs as $tabValue => $tabLabel)
                                <a href="{{ $showcaseBaseUrl }}?type={{ $tabValue }}&sort={{ $currentSort }}"
                                   class="btn {{ $currentType === $tabValue ? 'btn-primary' : 'btn-soft-secondary' }}">
                                    {{ $tabLabel }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="d-flex align-items-center justify-content-lg-end" style="gap:10px;">
                            <label class="mb-0 fw-600">{{ translate('Sort') }}:</label>

                            <select class="form-control aiz-selectpicker"
                                    data-minimum-results-for-search="Infinity"
                                    onchange="window.location.href='{{ $showcaseBaseUrl }}?type={{ $currentType }}&sort=' + this.value;">
                                <option value="newest" {{ $currentSort === 'newest' ? 'selected' : '' }}>
                                    {{ translate('Newest') }}
                                </option>
                                <option value="oldest" {{ $currentSort === 'oldest' ? 'selected' : '' }}>
                                    {{ translate('Oldest') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="container">
        @if(!empty($showcaseItems) && $showcaseItems->count())
            <div class="row">
                @foreach($showcaseItems as $item)
                    @php
                        $itemTitle = $item->title ?: '-';
                        $itemSubtitle = $item->subtitle ?? null;
                        $itemDescription = $item->intro ?: ($item->description ?? null);
                        $itemTypeLabel = $typeLabelMap[$item->type] ?? ucfirst($item->type);

$visualUrl = null;
$visualIsVideo = false;
$hasCoverImage = !empty($item->cover_image);

if ($hasCoverImage) {
    $rawVisual = $item->cover_image;

    if (is_numeric($rawVisual)) {
        $visualUrl = uploaded_asset($rawVisual);
    } elseif (filter_var($rawVisual, FILTER_VALIDATE_URL)) {
        $visualUrl = $rawVisual;
    } else {
        $visualUrl = asset($rawVisual);
    }
} elseif (!empty($item->main_visual)) {
    $rawVisual = $item->main_visual;

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
                            'slug' => \Illuminate\Support\Str::slug($itemTitle ?: $item->id)
                        ]);
                    @endphp

                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
@if($visualUrl && !$visualIsVideo)
    <a href="{{ $postUrl }}" class="d-block">
        <img src="{{ $visualUrl }}"
             alt="{{ $itemTitle }}"
             class="img-fit w-100"
             style="height:260px;">
    </a>
@elseif($visualIsVideo)
    <a href="{{ $postUrl }}"
       class="d-flex align-items-center justify-content-center bg-dark text-white text-center"
       style="height:260px; text-decoration:none;">
        <div>
            <div class="fs-18 fw-700 mb-1">{{ $itemTypeLabel }}</div>
            <div class="fs-13">{{ translate('Video Preview') }}</div>
        </div>
    </a>
@else
    <a href="{{ $postUrl }}" class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
       style="height:260px; text-decoration:none;">
        <div>
            <div class="fs-18 fw-600 mb-1">{{ $itemTypeLabel }}</div>
            <div class="fs-13">{{ translate('No preview image') }}</div>
        </div>
    </a>
@endif

                            <div class="card-body p-4">
                                <div class="mb-2 d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                                    <span class="badge badge-inline badge-soft-primary">
                                        {{ $itemTypeLabel }}
                                    </span>

                                    @if(!empty($item->created_at))
                                        <span class="text-muted fs-12">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="h5 fw-700 mb-2">
                                    <a href="{{ $postUrl }}" class="text-reset">
                                        {{ $itemTitle }}
                                    </a>
                                </h3>

                                @if(!empty($itemSubtitle))
                                    <p class="text-muted mb-2">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($itemSubtitle), 90) }}
                                    </p>
                                @endif

                                @if(!empty($itemDescription))
                                    <p class="text-muted mb-3">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($itemDescription), 140) }}
                                    </p>
                                @endif

                                <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                                    <div class="text-muted fs-13">
                                        @if(!empty($item->seller_name))
                                            {{ translate('By') }}:
                                            @if(!empty($item->seller_slug))
                                                <a href="{{ route('frontend.showcase.brand', $item->seller_slug) }}" class="text-reset fw-600">
                                                    {{ $item->seller_name }}
                                                </a>
                                            @else
                                                <span class="fw-600">{{ $item->seller_name }}</span>
                                            @endif
                                        @endif
                                    </div>

                                    <a href="{{ $postUrl }}" class="btn btn-soft-primary btn-sm">
                                        {{ translate('View Post') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="aiz-pagination mt-4">
                {{ $showcaseItems->links() }}
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h4 class="fw-700 mb-2">{{ translate('No showcase posts found') }}</h4>
                    <p class="text-muted mb-0">
                        {{ translate('Try another filter or check back later for new Stories, Collections, Storefronts and Launches.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</section>
