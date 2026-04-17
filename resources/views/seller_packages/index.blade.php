@extends('backend.layouts.app')

@section('content')
@php
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

<div class="aiz-titlebar mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3 class="h3">{{ translate('All Seller Packages') }}</h3>
        </div>
        @can('add_seller_package')
            <div class="col-md-6 text-md-right">
                <a href="{{ route('seller_packages.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Package') }}</span>
                </a>
            </div>
        @endcan
    </div>
</div>

<div class="row">
    @foreach ($seller_packages as $seller_package)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card h-100">
                <div class="card-body text-center">
                    <img
                        alt="{{ translate('Package Logo') }}"
                        src="{{ uploaded_asset($seller_package->logo) }}"
                        class="mw-100 mx-auto mb-4"
                        height="150"
                    >

                    <p class="mb-3 h6 fw-600">{{ $seller_package->getTranslation('name') }}</p>

                    <p class="h4">{{ single_price($seller_package->amount) }}</p>

                    <p class="fs-15 mb-2">
                        {{ translate('Showcase Post Limit') }}:
                        <b class="text-bold">{{ $formatShowcaseLimitLabel($seller_package->showcase_post_limit ?? null) }}</b>
                    </p>

                    <p class="fs-15 mb-3">
                        {{ translate('Validity') }}:
                        <b class="text-bold">{{ $formatDurationLabel($seller_package->duration) }}</b>
                    </p>

                    <div class="mar-top">
                        @can('edit_seller_package')
                            <a href="{{ route('seller_packages.edit', ['id' => $seller_package->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                               class="btn btn-sm btn-info">
                                {{ translate('Edit') }}
                            </a>
                        @endcan

                        @can('delete_seller_package')
                            <a href="#"
                               data-href="{{ route('seller_packages.destroy', $seller_package->id) }}"
                               class="btn btn-sm btn-danger confirm-delete">
                                {{ translate('Delete') }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
