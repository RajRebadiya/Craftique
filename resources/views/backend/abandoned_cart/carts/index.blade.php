@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All Abandoned Carts') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Abandoned Carts') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg" width="10%">ID</th>
                        <th>{{ translate('UUID') }}</th>
                        <th>{{ translate('Email') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th data-breakpoints="lg">{{ translate('Subscribed?') }}</th>
                        <th>{{ translate('Products') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            <td>{{ $cart->id }}</td>
                            <td>{{ $cart->uuid }}</td>
                            <td>{{ $cart->email }}</td>
                            <td>
                                <span class="badge badge-inline badge-secondary text-capitalize">
                                    {{ ucfirst($cart->status) }}
                                </span>
                            </td>
                            <td>{{ $cart->is_unsubscribed ? 'No' : 'Yes' }}</td>
                            <td>
                                @foreach ($cart['cart']['items'] as $item)
                                    {{ $item['product']['name'] }} <a
                                        href="{{ route('product', $item['product']['slug']) }}" target="_blank"
                                        title="{{ translate('View') }}"><i class="las la-eye"></i></a><br>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $carts->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection
