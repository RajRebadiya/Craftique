@extends('seller.layouts.app')

@section('panel_content')
    @php
        $authUser = auth()->user();
        $shop = optional($authUser)->shop;
        $currentPackage = optional($shop)->seller_package;

        $currentShowcaseCount = $shop
            ? \Illuminate\Support\Facades\DB::table('showcases')->where('seller_id', $shop->id)->count()
            : 0;

        $currentPackageShowcaseLimitRaw = $currentPackage ? $currentPackage->showcase_post_limit : null;

        $normalizeShowcaseLimit = function ($value) {
            if ($value === '' || $value === null) {
                return null;
            }

            return max(0, (int) $value);
        };

        $formatShowcaseLimit = function ($value) use ($normalizeShowcaseLimit) {
            $value = $normalizeShowcaseLimit($value);

            if ($value === null) {
                return translate('Unlimited Showcase Posts');
            }

            if ($value === 0) {
                return translate('No Showcase Posts');
            }

            return $value . ' ' . translate('Showcase Post Limit');
        };

        $formatDurationLabel = function ($days) {
            return max(0, (int) $days) . ' ' . translate('Days');
        };

        $currentPackageShowcaseLimit = $normalizeShowcaseLimit($currentPackageShowcaseLimitRaw);
    @endphp

    <section class="py-8 bg-soft-primary">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto text-center">
                    <h3 class="h1 mb-0 fw-700">{{ translate('Premium Packages for Sellers') }}</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 py-lg-5">
        <div class="container">
            @if ($shop)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h4 class="h6 fw-700 mb-2">{{ translate('Your current seller package status') }}</h4>

                                @if ($currentPackage)
                                    <p class="mb-1 text-muted">
                                        {{ translate('Current package') }}:
                                        <strong>{{ $currentPackage->getTranslation('name') }}</strong>
                                    </p>
                                    <p class="mb-1 text-muted">
                                        {{ translate('Current showcase usage') }}:
                                        <strong>{{ $currentShowcaseCount }}</strong>
                                    </p>
                                    <p class="mb-1 text-muted">
                                        {{ translate('Current showcase limit') }}:
                                        <strong>
                                            @if (is_null($currentPackageShowcaseLimit))
                                                {{ translate('Unlimited') }}
                                            @else
                                                {{ $currentPackageShowcaseLimit }}
                                            @endif
                                        </strong>
                                    </p>
                                    <p class="mb-0 text-muted">
                                        {{ translate('Current validity') }}:
                                        <strong>{{ $formatDurationLabel($currentPackage->duration) }}</strong>
                                    </p>
                                @else
                                    <p class="mb-0 text-muted">
                                        {{ translate('You do not have an active seller package yet.') }}
                                    </p>
                                @endif
                            </div>

                            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                                <span class="badge badge-inline badge-info fs-13">
                                    {{ translate('Showcase purchase rules are checked before checkout') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 gutters-10 justify-content-center">
                @foreach ($seller_packages as $key => $seller_package)
                    @php
                        $candidateShowcaseLimit = $normalizeShowcaseLimit($seller_package->showcase_post_limit ?? null);
                        $isCurrentPackage = $currentPackage && ((int) $currentPackage->id === (int) $seller_package->id);

                        $canPurchase = true;
                        $purchaseBlockedReason = null;

                        if ($shop) {
                            if ($candidateShowcaseLimit !== null && $currentShowcaseCount > $candidateShowcaseLimit) {
                                $canPurchase = false;
                                $purchaseBlockedReason = translate('This package showcase limit is lower than your current showcase posts.');
                            }

                            if ($canPurchase && $currentPackage) {
                                if ($currentPackageShowcaseLimit === null) {
                                    if ($candidateShowcaseLimit !== null) {
                                        $canPurchase = false;
                                        $purchaseBlockedReason = translate('You can not downgrade the package.');
                                    }
                                } else {
                                    if ($candidateShowcaseLimit !== null && $candidateShowcaseLimit < $currentPackageShowcaseLimit) {
                                        $canPurchase = false;
                                        $purchaseBlockedReason = translate('You can not downgrade the package.');
                                    }
                                }
                            }
                        }
                    @endphp

                    <div class="col">
                        <div class="card overflow-hidden h-100 {{ !$canPurchase ? 'border border-warning' : '' }}">
                            <div class="card-body">
                                <div class="text-center mb-4 mt-3">
                                    <img class="mw-100 mx-auto mb-4" src="{{ uploaded_asset($seller_package->logo) }}"
                                        height="100">

                                    <div class="d-flex justify-content-center align-items-center flex-wrap mb-2" style="gap:8px;">
                                        <h5 class="mb-0 h5 fw-600">{{ $seller_package->getTranslation('name') }}</h5>

                                        @if ($isCurrentPackage)
                                            <span class="badge badge-inline badge-success">
                                                {{ translate('Current Package') }}
                                            </span>
                                        @endif

                                        @if (!$canPurchase)
                                            <span class="badge badge-inline badge-warning">
                                                {{ translate('Not Available') }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="fs-15 mb-2">
                                        <i class="las la-check text-success mr-2"></i>
                                        {{ $formatShowcaseLimit($seller_package->showcase_post_limit) }}
                                    </p>

                                    <p class="fs-15 mb-2">
                                        <i class="las la-check text-success mr-2"></i>
                                        {{ translate('Validity') }}: {{ $formatDurationLabel($seller_package->duration) }}
                                    </p>

                                    @if ($shop)
                                        <p class="fs-13 text-muted mb-0">
                                            {{ translate('Your current showcase usage') }}:
                                            <strong>{{ $currentShowcaseCount }}</strong>
                                        </p>
                                    @endif
                                </div>

                                <div class="mb-4 d-flex align-items-center justify-content-center">
                                    @if ($seller_package->amount == 0)
                                        <span class="fs-30 fw-600 lh-1 mb-0">{{ translate('Free') }}</span>
                                    @else
                                        <span class="fs-30 fw-600 lh-1 mb-0">{{ single_price($seller_package->amount) }}</span>
                                    @endif
                                    <span class="text-secondary border-left ml-2 pl-2">
                                        {{ $formatDurationLabel($seller_package->duration) }}
                                    </span>
                                </div>

                                @if (!$canPurchase)
                                    <div class="alert alert-warning py-2 px-3 mb-3 text-left" role="alert">
                                        <div class="fs-13 fw-600 mb-1">{{ translate('Purchase blocked for this package') }}</div>
                                        <div class="fs-12 mb-0">{{ $purchaseBlockedReason }}</div>
                                    </div>
                                @endif

                                <div class="text-center">
                                    @if ($seller_package->amount == 0)
                                        @if ($canPurchase)
                                            <button class="btn btn-primary fw-600"
                                                onclick="get_free_package({{ $seller_package->id }})">
                                                {{ translate('Free Package') }}
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-soft-secondary fw-600" disabled>
                                                {{ translate('Free Package') }}
                                            </button>
                                        @endif
                                    @else
                                        @if ($canPurchase)
                                            @if (addon_is_activated('offline_payment'))
                                                <button class="btn btn-primary fw-600"
                                                    onclick="select_payment_type({{ $seller_package->id }})">
                                                    {{ translate('Purchase Package') }}
                                                </button>
                                            @else
                                                <button class="btn btn-primary fw-600"
                                                    onclick="show_price_modal({{ $seller_package->id }})">
                                                    {{ translate('Purchase Package') }}
                                                </button>
                                            @endif
                                        @else
                                            <button type="button" class="btn btn-soft-secondary fw-600" disabled>
                                                {{ translate('Purchase Package') }}
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <div class="modal fade" id="select_payment_type_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Select Payment Type') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="package_id" name="package_id" value="">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Payment Type') }}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control aiz-selectpicker" onchange="payment_type(this.value)"
                                    data-minimum-results-for-search="Infinity">
                                    <option value="">{{ translate('Select One') }}</option>
                                    <option value="online">{{ translate('Online payment') }}</option>
                                    <option value="offline">{{ translate('Offline payment') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-sm btn-primary transition-3d-hover mr-1"
                            id="select_type_cancel" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="price_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Purchase Your Package') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="" id="package_payment_form" action="{{ route('seller_packages.purchase') }}"
                    method="post">
                    @csrf
                    <input type="hidden" name="seller_package_id" value="">
                    <div class="modal-body" style="overflow-y: unset;">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Payment Method') }}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" name="payment_option">
                                        @include('partials.online_payment_options')
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-sm btn-secondary transition-3d-hover mr-1"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit"
                                class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('Confirm') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="offline_seller_package_purchase_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{ translate('Offline Package Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="offline_seller_package_purchase_modal_body"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function select_payment_type(id) {
            $('input[name=package_id]').val(id);
            $('#select_payment_type_modal').modal('show');
        }

        function payment_type(type) {
            var package_id = $('#package_id').val();
            if (type == 'online') {
                $("#select_type_cancel").click();
                show_price_modal(package_id);
            } else if (type == 'offline') {
                $("#select_type_cancel").click();
                $.post('{{ route('seller.offline_seller_package_purchase_modal') }}', {
                    _token: '{{ csrf_token() }}',
                    package_id: package_id
                }, function(data) {
                    $('#offline_seller_package_purchase_modal_body').html(data);
                    $('#offline_seller_package_purchase_modal').modal('show');
                });
            }
        }

        function show_price_modal(id) {
            $('input[name=seller_package_id]').val(id);
            $('#price_modal').modal('show');
        }

        function get_free_package(id) {
            $('input[name=seller_package_id]').val(id);
            $('#package_payment_form').submit();
        }
    </script>
@endsection
