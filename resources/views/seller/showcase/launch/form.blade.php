@extends('seller.layouts.app')

@section('panel_content')
    @php
        $isEdit = !empty($item);
        $formAction = $isEdit
            ? route('seller.showcase.launch.update', $item->id)
            : route('seller.showcase.launch.store');

        $titleGr = old('title_gr', $item->title_gr ?? $item->title ?? '');
        $titleEn = old('title_en', $item->title_en ?? '');

        $subtitleGr = old('subtitle_gr', $item->subtitle_gr ?? $item->subtitle ?? '');
        $subtitleEn = old('subtitle_en', $item->subtitle_en ?? '');

        $descriptionGr = old('description_gr', $item->description_gr ?? $item->description ?? '');
        $descriptionEn = old('description_en', $item->description_en ?? '');

        $hashtags = old('hashtags', $item->hashtags ?? '');
        $mainVisual = old('main_visual', $item->main_visual ?? '');
        $coverImage = old('cover_image', $item->cover_image ?? '');
        $status = old('status', $item->status ?? 'draft');
        $selectedProductId = old('product_id', $selectedProductId ?? '');
    @endphp

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0">{{ $page_title ?? translate('Launch Form') }}</h1>
                <p class="text-muted mb-0 mt-1">{{ translate('Seller Showcase / Launch') }}</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('seller.showcase.launch.index') }}" class="btn btn-soft-secondary">
                    {{ translate('Back to Launches') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-700 mb-2">{{ translate('Showcase Notes') }}</h5>
            <ul class="mb-0 pl-3 text-muted">
                <li>{{ translate('Title is required in at least one language.') }}</li>
                <li>{{ translate('Subtitle is used for the three-word tagline shown in the preview.') }}</li>
                <li>{{ translate('Main visual is the primary media for the Launch.') }}</li>
                <li>{{ translate('One product should be linked to the Launch.') }}</li>
                <li>{{ translate('Hashtags help the feed and search experience.') }}</li>
            </ul>
        </div>
    </div>

    <form action="{{ $formAction }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Greek Content') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ translate('Title (Greek)') }}</label>
                            <div class="col-lg-10">
                                <input type="text" name="title_gr" class="form-control" value="{{ $titleGr }}" placeholder="{{ translate('Enter title in Greek') }}">
                                @error('title_gr')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ translate('Tagline (Greek)') }}</label>
                            <div class="col-lg-10">
                                <input type="text" name="subtitle_gr" class="form-control" value="{{ $subtitleGr }}" placeholder="{{ translate('Enter tagline in Greek') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label">{{ translate('Description (Greek)') }}</label>
                            <div class="col-lg-10">
                                <textarea name="description_gr" class="form-control" rows="6" placeholder="{{ translate('Enter description in Greek') }}">{{ $descriptionGr }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('English Content') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ translate('Title (English)') }}</label>
                            <div class="col-lg-10">
                                <input type="text" name="title_en" class="form-control" value="{{ $titleEn }}" placeholder="{{ translate('Enter title in English') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ translate('Tagline (English)') }}</label>
                            <div class="col-lg-10">
                                <input type="text" name="subtitle_en" class="form-control" value="{{ $subtitleEn }}" placeholder="{{ translate('Enter tagline in English') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label">{{ translate('Description (English)') }}</label>
                            <div class="col-lg-10">
                                <textarea name="description_en" class="form-control" rows="6" placeholder="{{ translate('Enter description in English') }}">{{ $descriptionEn }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Hashtags') }}</h5>
                    </div>
                    <div class="card-body">
                        @include('partials.hashtag_input', [
                            'hashtagsValue' => $hashtags,
                            'fieldName' => 'hashtags',
                            'labelText' => 'Hashtags',
                            'showLabel' => false
                        ])
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Launch Media') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ translate('Main Visual') }}</label>
                            <div class="col-lg-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="all" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Image or Video') }}</div>
                                    <input type="hidden" name="main_visual" class="selected-files" value="{{ $mainVisual }}">
                                </div>
                                <div class="file-preview box sm"></div>

                                @error('main_visual')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label">{{ translate('Poster / Cover Image') }}</label>
                            <div class="col-lg-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="cover_image" class="selected-files" value="{{ $coverImage }}">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Selection') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label">{{ translate('Product') }}</label>
                            <div class="col-lg-10">
                                <select name="product_id" class="form-control aiz-selectpicker" data-live-search="true">
                                    <option value="">{{ translate('Select One') }}</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ (string) $selectedProductId === (string) $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (#{{ $product->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Publishing') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label">{{ translate('Status') }}</label>
                            <div class="col-lg-10">
                                <select name="status" class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity">
                                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>{{ translate('Draft') }}</option>
                                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>{{ translate('Published') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right mb-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? translate('Update Launch') : translate('Save Launch') }}
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                @include('partials.showcase_preview', [
                    'previewTitle' => translate('Launch Preview'),
                    'previewNote' => translate('Preview will appear here after you add the media and save the form.'),
                    'previewType' => 'launch',
                    'shopName' => optional(Auth::user()->shop)->name,
                    'shopLogo' => optional(Auth::user()->shop)->logo ? uploaded_asset(optional(Auth::user()->shop)->logo) : ''
                ])
            </div>
        </div>
    </form>
@endsection
