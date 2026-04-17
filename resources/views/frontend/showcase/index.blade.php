@extends('frontend.layouts.app')

@section('content')
    @php
        $typeLabels = [
            'history' => translate('Story'),
            'collection' => translate('Collection'),
            'vitrin' => translate('Storefront'),
            'launch' => translate('Launch'),
        ];

        $currentType = $filters['type'] ?? 'all';
        $currentSort = $filters['sort'] ?? 'newest';

        $feedAction = !empty($isBrandPage) && !empty($shop?->slug)
            ? route('frontend.showcase.brand', $shop->slug)
            : route('frontend.showcase.index');
    @endphp

    <section class="mb-4">
        <div class="container">
            <div class="bg-white border rounded shadow-sm p-4 p-lg-5">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <h1 class="h3 fw-700 mb-2">{{ $pageTitle ?? translate('Showcase') }}</h1>
                        @if(!empty($pageSubtitle))
                            <p class="text-muted mb-0">{{ $pageSubtitle }}</p>
                        @endif
                    </div>

                    <div class="col-lg-4 text-lg-right">
                        @if(!empty($isBrandPage) && !empty($shop?->slug))
                            <a href="{{ route('frontend.showcase.index') }}" class="btn btn-soft-secondary">
                                {{ translate('View All Showcase') }}
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
                <div class="card-body p-4">
                    <form method="GET" action="{{ $feedAction }}">
                        <div class="row align-items-end">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label fw-600">{{ translate('Type') }}</label>
                                <select name="type" class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity">
                                    <option value="all" {{ $currentType === 'all' ? 'selected' : '' }}>{{ translate('All') }}</option>
                                    <option value="history" {{ $currentType === 'history' ? 'selected' : '' }}>{{ translate('Story') }}</option>
                                    <option value="collection" {{ $currentType === 'collection' ? 'selected' : '' }}>{{ translate('Collection') }}</option>
                                    <option value="vitrin" {{ $currentType === 'vitrin' ? 'selected' : '' }}>{{ translate('Storefront') }}</option>
                                    <option value="launch" {{ $currentType === 'launch' ? 'selected' : '' }}>{{ translate('Launch') }}</option>
                                </select>
                            </div>

                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label fw-600">{{ translate('Sort') }}</label>
                                <select name="sort" class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity">
                                    <option value="newest" {{ $currentSort === 'newest' ? 'selected' : '' }}>{{ translate('Newest First') }}</option>
                                    <option value="oldest" {{ $currentSort === 'oldest' ? 'selected' : '' }}>{{ translate('Oldest First') }}</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ translate('Apply') }}
                                </button>
                            </div>
                        </div>
                    </form>
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
                            $typeLabel = $typeLabels[$item->type] ?? ucfirst($item->type);

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

                            $postUrl = route('frontend.showcase.post', [
                                'id' => $item->id,
                                'slug' => \Illuminate\Support\Str::slug($item->title ?: $item->id),
                            ]);

                            $brandUrl = !empty($item->seller_slug)
                                ? route('frontend.showcase.brand', $item->seller_slug)
                                : null;

                            $summaryText = $item->description ?: ($item->intro ?: ($item->subtitle ?: ''));
                        @endphp

                        <div class="col-md-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                <a href="{{ $postUrl }}" class="d-block text-reset">
                                    @if($visualUrl)
                                        @if($visualIsVideo)
                                            <div class="position-relative bg-dark" style="height:240px;">
                                                <video muted playsinline class="w-100 h-100" style="object-fit:cover;">
                                                    <source src="{{ $visualUrl }}">
                                                </video>
                                                <div class="position-absolute" style="top:12px; right:12px;">
                                                    <span class="badge badge-inline badge-dark">{{ translate('Video') }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <img src="{{ $visualUrl }}"
                                                 alt="{{ $item->title }}"
                                                 class="w-100"
                                                 style="height:240px; object-fit:cover;">
                                        @endif
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                                             style="height:240px;">
                                            <div>
                                                <div class="fw-700 mb-1">{{ $typeLabel }}</div>
                                                <div class="fs-13">{{ translate('No preview media') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </a>

                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="mb-2 d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                                        <span class="badge badge-inline badge-soft-primary">{{ $typeLabel }}</span>

                                        @if(!empty($item->created_at))
                                            <span class="text-muted fs-12">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="h6 fw-700 mb-2">
                                        <a href="{{ $postUrl }}" class="text-reset">
                                            {{ \Illuminate\Support\Str::limit($item->title ?: '-', 80) }}
                                        </a>
                                    </h3>

                                    @if(!empty($summaryText))
                                        <p class="text-muted mb-3 flex-grow-1">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($summaryText), 120) }}
                                        </p>
                                    @else
                                        <div class="flex-grow-1"></div>
                                    @endif

                                    @if(!empty($item->hashtags))
                                        @php
                                            $showcaseTags = array_filter(array_map('trim', explode(',', $item->hashtags)));
                                        @endphp
                                        <div class="mb-3">
                                            @foreach($showcaseTags as $tag)
                                                <span class="badge badge-inline badge-soft-secondary mr-1 mb-1">#{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto" style="gap:10px;">
                                        <div class="text-muted fs-13">
                                            @if(!empty($item->seller_name))
                                                {{ translate('By') }}:
                                                @if($brandUrl)
                                                    <a href="{{ $brandUrl }}" class="fw-600 text-reset">
                                                        {{ $item->seller_name }}
                                                    </a>
                                                @else
                                                    <span class="fw-600">{{ $item->seller_name }}</span>
                                                @endif
                                            @endif
                                        </div>

                                        <a href="{{ $postUrl }}" class="btn btn-soft-primary btn-sm">
                                            {{ translate('View') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="aiz-pagination">
                    {{ $showcaseItems->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <h3 class="h5 fw-700 mb-2">{{ translate('No showcase posts found') }}</h3>
                        <p class="text-muted mb-0">
                            {{ translate('There are no published Showcase items for the selected filters yet.') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
