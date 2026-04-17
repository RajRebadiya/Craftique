@extends('seller.layouts.app')

@section('panel_content')
@php
    $packageName = optional($shop->seller_package)->name;
    $packageValidUntil = !empty($shop->package_invalid_at)
        ? \Carbon\Carbon::parse($shop->package_invalid_at)->format('d/m/Y')
        : null;

    $stats = $stats ?? [
        'history_total' => 0,
        'collection_total' => 0,
        'vitrin_total' => 0,
        'launch_total' => 0,
        'published_total' => 0,
        'draft_total' => 0,
        'all_total' => 0,
    ];

    $recentItems = $recentItems ?? collect();

    $showcaseLimit = $showcaseLimit ?? null;
    $showcaseUsed = $showcaseUsed ?? 0;
    $showcaseRemaining = $showcaseRemaining ?? null;
    $limitReached = $limitReached ?? false;

    $formatDurationLabel = function ($days) {
        return max(0, (int) $days) . ' ' . translate('Days');
    };

    $formatShowcaseLimitLabel = function ($value) {
        if ($value === '' || $value === null) {
            return translate('Unlimited');
        }

        $value = max(0, (int) $value);

        if ($value === 0) {
            return translate('No Showcase Posts');
        }

        return $value;
    };

@endphp

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Showcase Center') }}</h1>
        </div>
    </div>
</div>

@if(!$hasActivePackage)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h2 class="h5 fw-700 mb-2">{{ translate('Showcase requires an active seller package') }}</h2>
            <p class="text-muted mb-3">
                {{ translate('To use the Showcase center, you need an active seller subscription/package first.') }}
            </p>

            <a href="{{ route('seller.seller_packages_list') }}" class="btn btn-primary">
                {{ translate('View Seller Packages') }}
            </a>
        </div>
    </div>

    @if($packages->count())
        <div class="row">
            @foreach($packages as $package)
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h5 fw-700 mb-2">{{ $package->name }}</h3>

                            <div class="fs-18 fw-700 text-primary mb-3">
                                {{ single_price($package->amount) }}
                            </div>

                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <strong>{{ translate('Validity') }}:</strong>
                                    {{ $formatDurationLabel($package->duration) }}
                                </li>
                                <li class="mb-2">
                                    <strong>{{ translate('Showcase Limit') }}:</strong>
                                    {{ $formatShowcaseLimitLabel($package->showcase_post_limit) }}
                                </li>
                            </ul>

                            <a href="{{ route('seller.seller_packages_list') }}" class="btn btn-soft-primary">
                                {{ translate('Open Packages Page') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@else
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="h5 fw-700 mb-2">{{ translate('Your Showcase center is active') }}</h2>
                    <p class="text-muted mb-1">
                        {{ translate('Active package') }}:
                        <strong>{{ $packageName ?: translate('Assigned Package') }}</strong>
                        @if($packageValidUntil)
                            — {{ translate('Valid until') }}: <strong>{{ $packageValidUntil }}</strong>
                        @endif
                    </p>

                    @if(!is_null($daysRemaining))
                        <p class="text-muted mb-0">
                            {{ translate('Days remaining') }}:
                            <strong>{{ $daysRemaining }}</strong>
                        </p>
                    @endif
                </div>

                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                    <a href="{{ route('seller.seller_packages_list') }}" class="btn btn-soft-secondary">
                        {{ translate('Manage Packages') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row gutters-16 mb-4">
        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1">{{ translate('All Posts') }}</div>
                    <div class="fs-24 fw-700">{{ $stats['all_total'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1">{{ translate('Stories') }}</div>
                    <div class="fs-24 fw-700">{{ $stats['history_total'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1">{{ translate('Collections') }}</div>
                    <div class="fs-24 fw-700">{{ $stats['collection_total'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1">{{ translate('Storefronts') }}</div>
                    <div class="fs-24 fw-700">{{ $stats['vitrin_total'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1">{{ translate('Launches') }}</div>
                    <div class="fs-24 fw-700">{{ $stats['launch_total'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1">{{ translate('Published') }}</div>
                    <div class="fs-24 fw-700 text-success">{{ $stats['published_total'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1">{{ translate('Drafts') }}</div>
                    <div class="fs-24 fw-700 text-warning">{{ $stats['draft_total'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="h5 fw-700 mb-2">{{ translate('Showcase Package Usage') }}</h3>

                    @if($showcaseLimit === null)
                        <p class="text-muted mb-0">
                            {{ translate('Your package allows unlimited showcase posts.') }}
                            {{ translate('Used') }}: <strong>{{ $showcaseUsed }}</strong>
                        </p>
                    @else
                        <p class="text-muted mb-0">
                            {{ translate('Limit') }}: <strong>{{ $showcaseLimit }}</strong>
                            — {{ translate('Used') }}: <strong>{{ $showcaseUsed }}</strong>
                            — {{ translate('Remaining') }}: <strong>{{ $showcaseRemaining }}</strong>
                        </p>
                    @endif
                </div>

                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                    @if($limitReached)
                        <span class="badge badge-inline badge-danger fs-13">
                            {{ translate('Limit Reached') }}
                        </span>
                    @elseif($showcaseLimit !== null)
                        <span class="badge badge-inline badge-success fs-13">
                            {{ translate('Quota Available') }}
                        </span>
                    @else
                        <span class="badge badge-inline badge-info fs-13">
                            {{ translate('Unlimited Showcase') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 fw-700 mb-2">{{ translate('Story') }}</h3>
                    <p class="text-muted mb-4">
                        {{ translate('Create and manage seller story posts for the public Showcase feed.') }}
                    </p>

                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <a href="{{ route('seller.showcase.history.index') }}" class="btn btn-primary">
                            {{ translate('Manage Stories') }}
                        </a>

                        @if($limitReached)
                            <button type="button" class="btn btn-soft-secondary" disabled>
                                {{ translate('Limit Reached') }}
                            </button>
                        @else
                            <a href="{{ route('seller.showcase.history.create') }}" class="btn btn-soft-primary">
                                {{ translate('Add Story') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 fw-700 mb-2">{{ translate('Collection') }}</h3>
                    <p class="text-muted mb-4">
                        {{ translate('Create and manage seller collection posts for the public Showcase feed.') }}
                    </p>

                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <a href="{{ route('seller.showcase.collection.index') }}" class="btn btn-primary">
                            {{ translate('Manage Collections') }}
                        </a>

                        @if($limitReached)
                            <button type="button" class="btn btn-soft-secondary" disabled>
                                {{ translate('Limit Reached') }}
                            </button>
                        @else
                            <a href="{{ route('seller.showcase.collection.create') }}" class="btn btn-soft-primary">
                                {{ translate('Add Collection') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 fw-700 mb-2">{{ translate('Storefront') }}</h3>
                    <p class="text-muted mb-4">
                        {{ translate('Create and manage seller storefront posts for the public Showcase feed.') }}
                    </p>

                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <a href="{{ route('seller.showcase.vitrin.index') }}" class="btn btn-primary">
                            {{ translate('Manage Storefronts') }}
                        </a>

                        @if($limitReached)
                            <button type="button" class="btn btn-soft-secondary" disabled>
                                {{ translate('Limit Reached') }}
                            </button>
                        @else
                            <a href="{{ route('seller.showcase.vitrin.create') }}" class="btn btn-soft-primary">
                                {{ translate('Add Storefront') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 fw-700 mb-2">{{ translate('Launch') }}</h3>
                    <p class="text-muted mb-4">
                        {{ translate('Create and manage seller launch posts for the public Showcase feed.') }}
                    </p>

                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <a href="{{ route('seller.showcase.launch.index') }}" class="btn btn-primary">
                            {{ translate('Manage Launches') }}
                        </a>

                        @if($limitReached)
                            <button type="button" class="btn btn-soft-secondary" disabled>
                                {{ translate('Limit Reached') }}
                            </button>
                        @else
                            <a href="{{ route('seller.showcase.launch.create') }}" class="btn btn-soft-primary">
                                {{ translate('Add Launch') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Recent Showcase Activity') }}</h5>
        </div>
        <div class="card-body">
            @if($recentItems->count())
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Type') }}</th>
                                <th>{{ translate('Title') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Created') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentItems as $item)
                                @php
                                    $title = $item->title_gr ?: ($item->title_en ?: $item->title);

                                    $editRoute = null;
                                    $typeLabel = ucfirst($item->type);

                                    if ($item->type === 'history') {
                                        $editRoute = route('seller.showcase.history.edit', $item->id);
                                        $typeLabel = translate('Story');
                                    } elseif ($item->type === 'collection') {
                                        $editRoute = route('seller.showcase.collection.edit', $item->id);
                                        $typeLabel = translate('Collection');
                                    } elseif ($item->type === 'vitrin') {
                                        $editRoute = route('seller.showcase.vitrin.edit', $item->id);
                                        $typeLabel = translate('Storefront');
                                    } elseif ($item->type === 'launch') {
                                        $editRoute = route('seller.showcase.launch.edit', $item->id);
                                        $typeLabel = translate('Launch');
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $typeLabel }}</td>
                                    <td><strong>{{ $title ?: '-' }}</strong></td>
                                    <td>
                                        @if($item->status === 'published')
                                            <span class="badge badge-inline badge-success">{{ translate('Published') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-secondary">{{ translate('Draft') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="text-right">
                                        @if($item->status === 'published')
                                            <a href="{{ route('frontend.showcase.post', ['id' => $item->id, 'slug' => \Illuminate\Support\Str::slug($title ?: $item->id)]) }}"
                                               target="_blank"
                                               class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                               title="{{ translate('Preview') }}">
                                                <i class="las la-eye"></i>
                                            </a>
                                        @endif

                                        @if($editRoute)
                                            <a href="{{ $editRoute }}"
                                               class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                               title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <h6 class="mb-2">{{ translate('No showcase activity yet') }}</h6>
                    <p class="text-muted mb-0">{{ translate('Start by creating your first Story, Collection, Storefront or Launch.') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h3 class="h5 fw-700 mb-3">{{ translate('Public preview shortcuts') }}</h3>

            <div class="d-flex flex-wrap" style="gap: 12px;">
                <a href="{{ route('frontend.showcase.index') }}" target="_blank" class="btn btn-soft-primary">
                    {{ translate('Open Showcase Feed') }}
                </a>

                @if(!empty($shop->slug))
                    <a href="{{ route('frontend.showcase.brand', $shop->slug) }}" target="_blank" class="btn btn-soft-info">
                        {{ translate('Open My Brand Showcase') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
@endsection
