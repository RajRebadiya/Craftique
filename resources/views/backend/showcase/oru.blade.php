@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3">{{ $page_title }}</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Showcase Oru Details</h5>
    </div>

            <div class="card-body">
                <form action="{{ route('showcase.oru.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="id" value="{{ old('id', $item->id ?? '') }}">

                    <div class="form-group">
                        <label>Section Title</label>
                        <input
                            type="text"
                            class="form-control"
                            name="title"
                            placeholder="Enter title"
                            value="{{ old('title', $item->title ?? '') }}"
                        >
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Main Visual</label>
                        <input
                            type="text"
                            class="form-control"
                            name="main_visual"
                            placeholder="Image or video source"
                            value="{{ old('main_visual', $item->main_visual ?? '') }}"
                        >
                        @error('main_visual')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Text Content</label>
                        <textarea
                            class="form-control"
                            rows="8"
                            name="description"
                            placeholder="Write the content here..."
                        >{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Linked Products</label>
                        <input
                            type="text"
                            class="form-control"
                            name="linked_products"
                            placeholder="Product IDs or selection logic for next phase"
                            value="{{ old('linked_products', $item->linked_products ?? '') }}"
                        >
                        @error('linked_products')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="draft" {{ old('status', $item->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $item->status ?? 'draft') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save Oru</button>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection
