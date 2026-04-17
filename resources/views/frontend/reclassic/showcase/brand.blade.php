@php
    $currentType = request('type', 'all');
    $currentSort = request('sort', 'newest');
    $showcaseBaseUrl = url()->current();

    $showcaseTypeTabs = [
        'all' => translate('All'),
        'history' => translate('Story'),
        'collection' => translate('Collection'),
        'vitrin' => translate('Storefront'),
        'launch' => translate('Launch'),
    ];
@endphp

<div class="card border-0 shadow-sm mb-4">
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
