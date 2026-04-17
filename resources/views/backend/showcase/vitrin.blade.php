@extends('backend.layouts.app')

@section('content')
@php
    $titleGr = old('title_gr', $item->title_gr ?? $item->title ?? '');
    $titleEn = old('title_en', $item->title_en ?? '');

    $descriptionGr = old('description_gr', $item->description_gr ?? $item->description ?? '');
    $descriptionEn = old('description_en', $item->description_en ?? '');

    $mainVisual = old('main_visual', $item->main_visual ?? '');
    $coverImage = old('cover_image', $item->cover_image ?? '');
    $hashtags = old('hashtags', $item->hashtags ?? '');
    $status = old('status', $item->status ?? 'draft');
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3">{{ $page_title }}</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('showcase.vitrin.store') }}" method="POST">
    @csrf

    <input type="hidden" name="id" value="{{ old('id', $item->id ?? '') }}">

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="fw-700 mb-2">Storefront Media Notes</h5>
                            <p class="text-muted mb-2">
                                Storefront is built with one main visual media, optional cover image, text and linked products.
                            </p>
                            <ul class="mb-0 pl-3 text-muted">
                                <li>Title is required in at least one language.</li>
                                <li>Main visual is the primary asset.</li>
                                <li>Cover image is optional and acts as preview / fallback.</li>
                                <li>Recommended main visual ratio: 1:1.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Greek Content</h5>
                        </div>

                        <div class="card-body">
                            @if(isset($shops))
                                <div class="form-group">
                                    <label>Creator / Brand</label>
                                    <select class="form-control" name="seller_id">
                                        <option value="">Select creator / brand</option>
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop->id }}" {{ old('seller_id', $item->seller_id ?? '') == $shop->id ? 'selected' : '' }}>
                                                {{ $shop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('seller_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Τίτλος (GR)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="title_gr"
                                    placeholder="Εισαγωγή τίτλου"
                                    value="{{ $titleGr }}"
                                >
                                @error('title_gr')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Περιγραφή (GR)</label>
                                <textarea
                                    class="form-control"
                                    rows="6"
                                    name="description_gr"
                                    placeholder="Γράψε την περιγραφή στα ελληνικά..."
                                >{{ $descriptionGr }}</textarea>
                                @error('description_gr')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">English Content</h5>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label>Title (EN)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="title_en"
                                    placeholder="Enter title"
                                    value="{{ $titleEn }}"
                                >
                                @error('title_en')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Description (EN)</label>
                                <textarea
                                    class="form-control"
                                    rows="6"
                                    name="description_en"
                                    placeholder="Write the description in English..."
                                >{{ $descriptionEn }}</textarea>
                                @error('description_en')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Hashtags</h5>
                        </div>
                        <div class="card-body">
                            @include('partials.hashtag_input', [
                                'hashtagsValue' => $hashtags,
                                'fieldName' => 'hashtags',
                                'labelText' => 'Hashtags'
                            ])
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Media & Publishing</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Main Visual</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input
                                        type="hidden"
                                        name="main_visual"
                                        class="selected-files"
                                        value="{{ $mainVisual }}"
                                    >
                                </div>
                                <div class="file-preview box sm"></div>
                                @error('main_visual')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Optional Cover Image</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input
                                        type="hidden"
                                        name="cover_image"
                                        class="selected-files"
                                        value="{{ $coverImage }}"
                                    >
                                </div>
                                <div class="file-preview box sm"></div>
                                @error('cover_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                    <div class="form-group mb-0">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $status == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">Save Storefront</button>
                            </div>
                        </div>
                    </div>
                </div>

        <div class="col-lg-4">
            @include('partials.showcase_preview', [
                'previewTitle' => translate('Storefront Preview'),
                'previewNote' => translate('Preview will appear here after you add the media and save the form.'),
                'previewType' => 'vitrin'
            ])

            @include('backend.showcase._products', [
                'allowMultiple' => true,
                'selectedProducts' => $selectedProducts ?? [],
                'showcaseCategories' => $showcaseCategories ?? [],
                'productCategoryMap' => $productCategoryMap ?? []
            ])
        </div>
    </div>
</form>
@endsection
