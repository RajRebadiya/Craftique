@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0">{{ translate('Collections') }}</h1>
                <p class="text-muted mb-0 mt-1">
                    {{ translate('Manage your seller Collection posts for the public Showcase feed.') }}
                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('seller.showcase.collection.create') }}" class="btn btn-primary">
                    {{ translate('Add Collection') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Collection List') }}</h5>
        </div>

        <div class="card-body">
            @if($items->count())
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Title') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Created') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                @php
                                    $title = $item->title_gr ?: ($item->title_en ?: $item->title);
                                @endphp
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <strong>{{ $title ?: '-' }}</strong>
                                    </td>
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

                                        <a href="{{ route('seller.showcase.collection.edit', $item->id) }}"
                                           class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                           title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <form action="{{ url('/seller/showcase/collection/' . $item->id . '/toggle-status') }}"
                                              method="POST"
                                              class="d-inline-block">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                                    title="{{ $item->status === 'published' ? translate('Move to Draft') : translate('Publish') }}">
                                                <i class="las {{ $item->status === 'published' ? 'la-eye-slash' : 'la-check-circle' }}"></i>
                                            </button>
                                        </form>

                                        <form action="{{ url('/seller/showcase/collection/' . $item->id) }}"
                                              method="POST"
                                              class="d-inline-block"
                                              onsubmit="return confirm('{{ translate('Delete this Collection?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                                    title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="aiz-pagination mt-4">
                    {{ $items->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="mb-2">{{ translate('No Collections yet') }}</h5>
                    <p class="text-muted mb-4">
                        {{ translate('Create your first Collection to start appearing in the Showcase feed.') }}
                    </p>
                    <a href="{{ route('seller.showcase.collection.create') }}" class="btn btn-primary">
                        {{ translate('Add Collection') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection