@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Write')}}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('seller.blog.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Write Post')}}</span>
            </a>
        </div>
    </div>
</div>
<br>

<div class="card">
    <form id="sort_blogs" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Write') }}</h5>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </div>
        </div>
    </form>
    <div class="card-body">
        <table class="table mb-0 aiz-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Title')}}</th>
                    <th data-breakpoints="lg">{{translate('Category')}}</th>
                    <th data-breakpoints="lg">{{translate('Short Description')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $key => $blog)
                <tr>
                    <td>
                        {{ ($key+1) + ($blogs->currentPage() - 1) * $blogs->perPage() }}
                    </td>
                    <td>
                        {{ $blog->title }}
                    </td>
                    <td>
                        @if($blog->category != null)
                            {{ $blog->category->category_name }}
                        @else
                            --
                        @endif
                    </td>
                    <td>
                        {{ $blog->short_description }}
                    </td>
                    <td class="text-right">
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('seller.blog.edit',$blog->id)}}" title="{{ translate('Edit') }}">
                            <i class="las la-pen"></i>
                        </a>
                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('seller.blog.destroy', $blog->id)}}" title="{{ translate('Delete') }}">
                            <i class="las la-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $blogs->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
