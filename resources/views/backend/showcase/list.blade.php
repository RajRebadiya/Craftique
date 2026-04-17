@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ $page_title }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            @if($section === 'history')
                <a href="{{ route('frontend.showcase.history') }}" target="_blank" class="btn btn-soft-info mr-2">Preview Public Page</a>
                <a href="{{ route('showcase.history') }}" class="btn btn-primary">Add New Story</a>
            @elseif($section === 'collection')
                <a href="{{ route('frontend.showcase.collection') }}" target="_blank" class="btn btn-soft-info mr-2">Preview Public Page</a>
                <a href="{{ route('showcase.collection') }}" class="btn btn-primary">Add New Collection</a>
            @elseif($section === 'vitrin')
                <a href="{{ route('frontend.showcase.vitrin') }}" target="_blank" class="btn btn-soft-info mr-2">Preview Public Page</a>
                <a href="{{ route('showcase.vitrin') }}" class="btn btn-primary">Add New Storefront</a>
            @elseif($section === 'launch')
                <a href="{{ route('frontend.showcase.launch') }}" target="_blank" class="btn btn-soft-info mr-2">Preview Public Page</a>
                <a href="{{ route('showcase.launch') }}" class="btn btn-primary">Add New Launch</a>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ $page_title }}</h5>
    </div>

            <div class="card-body">
                @if($items->count())
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="80">ID</th>
                                    <th width="90">Image</th>
                                    <th>Title</th>
                                    <th width="140">Status</th>
                                    <th width="180">Created</th>
                                    <th width="340" class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php
                                        $imageValue = null;

                                        if ($section === 'history' || $section === 'collection') {
                                            $imageValue = $item->cover_image;
                                        } elseif ($section === 'vitrin') {
                                            $imageValue = $item->main_visual;
                                        } elseif ($section === 'launch') {
                                            $imageValue = $item->cover_image ?: $item->main_visual;
                                        }

                                        $imageUrl = null;
                                        if (!empty($imageValue)) {
                                            $imageUrl = is_numeric($imageValue) ? uploaded_asset($imageValue) : $imageValue;
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $item->id }}</td>

                                        <td>
                                            @if($imageUrl)
                                                <a href="{{ $imageUrl }}" target="_blank">
                                                    <img src="{{ $imageUrl }}"
                                                         alt="{{ $item->title }}"
                                                         style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb;">
                                                </a>
                                            @else
                                                <div style="width:60px; height:60px; border-radius:8px; border:1px dashed #d1d5db; display:flex; align-items:center; justify-content:center; font-size:11px; color:#9ca3af;">
                                                    No Image
                                                </div>
                                            @endif
                                        </td>

                                        <td>{{ $item->title }}</td>

                                        <td>
                                            @if($item->status === 'published')
                                                <span class="badge badge-inline badge-success">Published</span>
                                            @else
                                                <span class="badge badge-inline badge-secondary">Draft</span>
                                            @endif
                                        </td>

                                        <td>{{ $item->created_at }}</td>

                                        <td class="text-right">
                                            @if($section === 'history')
                                                <a href="{{ route('showcase.history.edit', $item->id) }}" class="btn btn-soft-primary btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <a href="{{ route('showcase.history.status', $item->id) }}" class="btn btn-soft-warning btn-sm mr-1">
                                                    {{ $item->status === 'published' ? 'Set Draft' : 'Publish' }}
                                                </a>
                                                @if($imageUrl)
                                                    <a href="{{ $imageUrl }}" target="_blank" class="btn btn-soft-info btn-sm mr-1">
                                                        Preview
                                                    </a>
                                                @endif
                                                <a href="{{ route('showcase.history.delete', $item->id) }}"
                                                   class="btn btn-soft-danger btn-sm"
                                                   onclick="return confirm('Delete this story item?');">
                                                    Delete
                                                </a>
                                            @elseif($section === 'collection')
                                                <a href="{{ route('showcase.collection.edit', $item->id) }}" class="btn btn-soft-primary btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <a href="{{ route('showcase.collection.status', $item->id) }}" class="btn btn-soft-warning btn-sm mr-1">
                                                    {{ $item->status === 'published' ? 'Set Draft' : 'Publish' }}
                                                </a>
                                                @if($imageUrl)
                                                    <a href="{{ $imageUrl }}" target="_blank" class="btn btn-soft-info btn-sm mr-1">
                                                        Preview
                                                    </a>
                                                @endif
                                                <a href="{{ route('showcase.collection.delete', $item->id) }}"
                                                   class="btn btn-soft-danger btn-sm"
                                                   onclick="return confirm('Delete this collection item?');">
                                                    Delete
                                                </a>
                                            @elseif($section === 'vitrin')
                                                <a href="{{ route('showcase.vitrin.edit', $item->id) }}" class="btn btn-soft-primary btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <a href="{{ route('showcase.vitrin.status', $item->id) }}" class="btn btn-soft-warning btn-sm mr-1">
                                                    {{ $item->status === 'published' ? 'Set Draft' : 'Publish' }}
                                                </a>
                                                @if($imageUrl)
                                                    <a href="{{ $imageUrl }}" target="_blank" class="btn btn-soft-info btn-sm mr-1">
                                                        Preview
                                                    </a>
                                                @endif
                                                <a href="{{ route('showcase.vitrin.delete', $item->id) }}"
                                                   class="btn btn-soft-danger btn-sm"
                                                   onclick="return confirm('Delete this storefront item?');">
                                                    Delete
                                                </a>
                                            @elseif($section === 'launch')
                                                <a href="{{ route('showcase.launch.edit', $item->id) }}" class="btn btn-soft-primary btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <a href="{{ route('showcase.launch.status', $item->id) }}" class="btn btn-soft-warning btn-sm mr-1">
                                                    {{ $item->status === 'published' ? 'Set Draft' : 'Publish' }}
                                                </a>
                                                @if($imageUrl)
                                                    <a href="{{ $imageUrl }}" target="_blank" class="btn btn-soft-info btn-sm mr-1">
                                                        Preview
                                                    </a>
                                                @endif
                                                <a href="{{ route('showcase.launch.delete', $item->id) }}"
                                                   class="btn btn-soft-danger btn-sm"
                                                   onclick="return confirm('Delete this launch item?');">
                                                    Delete
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <h5 class="mb-2">No items found</h5>
                        <p class="text-muted mb-0">Create your first {{ $section }} entry.</p>
                    </div>
                @endif
            </div>
        </div>
</div>
@endsection
