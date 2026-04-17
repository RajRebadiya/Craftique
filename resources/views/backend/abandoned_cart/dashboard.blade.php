@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col-lg-3 offset-lg-8 col-12">
                    <div class="form-group mb-0">
                        <input type="text" class="aiz-date-range form-control" value="{{ $date }}" name="date"
                            placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y" data-separator=" to "
                            data-advanced-range="true" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-1 col-12 pt-lg-0 pt-2">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary btn-block">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row gutters-10">
        <div class="col-lg-4 col-6">
            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div>
                        {{ translate('Recoverable Orders') }}
                    </div>
                    <div class="h3 fw-700 mb-3">
                        {{ $data['recoverable_orders'] }}
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div>
                        {{ translate('Recoverable Revenue') }}
                    </div>
                    <div class="h3 fw-700 mb-3">{{ format_price($data['recoverable_revenue']) }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div>
                        {{ translate('Recovered Orders') }}
                    </div>
                    <div class="h3 fw-700 mb-3">{{ $data['recovered_orders'] }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div>
                        {{ translate('Recovered Revenue') }}
                    </div>
                    <div class="h3 fw-700 mb-3">{{ format_price($data['recovered_revenue']) }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="bg-grad-5 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div>
                        {{ translate('Lost Orders') }}
                    </div>
                    <div class="h3 fw-700 mb-3">{{ $data['lost_orders'] }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="bg-danger text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div>
                        {{ translate('Recovery Rate') }}
                    </div>
                    <div class="h3 fw-700 mb-3">{{ $data['recovery_rate'] }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
@endsection
