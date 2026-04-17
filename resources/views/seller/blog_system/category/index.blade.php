@extends('seller.layouts.app')

@section('panel_content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Categories')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ translate('Blog Categories') }}</h5>
    </div>
    <div class="card-body">
        <table class="table mb-0 aiz-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Category') }}</th>
                    <th>{{ translate('Slug') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $category->category_name }}</td>
                    <td>{{ $category->slug }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
