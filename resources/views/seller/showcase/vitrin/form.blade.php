@extends('seller.layouts.app')

@section('panel_content')
    @php
        $isEdit = !empty($item);
        $formAction = $isEdit
            ? route('seller.showcase.vitrin.update', $item->id)
            : route('seller.showcase.vitrin.store');

        $titleGr = old('title_gr', $item->title_gr ?? $item->title ?? '');
        $titleEn = old('title_en', $item->title_en ?? '');

        $descriptionGr = old('description_gr', $item->description_gr ?? $item->description ?? '');
        $descriptionEn = old('description_en', $item->description_en ?? '');

        $hashtags = old('hashtags', $item->hashtags ?? '');
        $mainVisual = old('main_visual', $item->main_visual ?? '');
        $coverImage = old('cover_image', $item->cover_image ?? '');
        $posterImageData = old('poster_image_data');
        $status = old('status', $item->status ?? 'draft');
        $selectedProducts = old('product_ids', $selectedProducts ?? []);
    @endphp

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0">{{ $page_title ?? translate('Storefront Form') }}</h1>
                <p class="text-muted mb-0 mt-1">{{ translate('Seller Showcase / Storefront') }}</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('seller.showcase.vitrin.index') }}" class="btn btn-soft-secondary">
                    {{ translate('Back to Storefronts') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-700 mb-2">{{ translate('Showcase Notes') }}</h5>
            <ul class="mb-0 pl-3 text-muted">
                <li>{{ translate('Title is required in at least one language.') }}</li>
                <li>{{ translate('Main visual is the primary media.') }}</li>
                <li>{{ translate('If you upload a video, you can pick a poster from suggested frames, choose a frame manually, or upload an image.') }}</li>
                <li>{{ translate('Linked products are optional.') }}</li>
                <li>{{ translate('You can save as draft or publish directly.') }}</li>
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
                        <h5 class="mb-0 h6">{{ translate('Storefront Cover / Main Visual') }}</h5>
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
                                    <input type="hidden" name="main_visual" class="selected-files story-video-input" value="{{ $mainVisual }}">
                                </div>
                                <div class="file-preview box sm"></div>

                                @error('main_visual')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" data-story-poster>
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Poster / Frame Selection (Optional)') }}</h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="poster_image_data" class="story-poster-data" value="{{ $posterImageData }}">

                        <div class="d-flex flex-wrap mb-3" style="gap:10px;">
                            <button type="button" class="btn btn-soft-primary btn-sm story-poster-tab" data-target="suggested">
                                {{ translate('Select a Suggested Frame') }}
                            </button>
                            <button type="button" class="btn btn-soft-secondary btn-sm story-poster-tab" data-target="video">
                                {{ translate('Choose a Frame from Video') }}
                            </button>
                            <button type="button" class="btn btn-soft-secondary btn-sm story-poster-tab" data-target="upload">
                                {{ translate('Upload an Image') }}
                            </button>
                        </div>

                        <div class="story-poster-panel" data-panel="suggested">
                            <p class="text-muted mb-2">
                                {{ translate('Suggested frames will appear after video upload. Select one to use as poster.') }}
                            </p>
                            <div class="story-frame-empty text-muted small mb-3">
                                {{ translate('Upload a video to see suggested frames.') }}
                            </div>
                            <div class="d-flex align-items-center story-frame-grid" style="gap:10px; overflow-x:auto;"></div>
                        </div>

                        <div class="story-poster-panel story-video-panel d-none" data-panel="video">
                            <div class="row align-items-center">
                                <div class="col-lg-6 mb-3 mb-lg-0">
                                    <video class="w-100 rounded border story-video-player" controls muted playsinline></video>
                                </div>
                                <div class="col-lg-6">
                                    <label class="text-muted small mb-2 d-block">{{ translate('Pick a frame and use it as poster') }}</label>
                                    <input type="range" class="form-control-range story-video-range" min="0" step="1" value="0">
                                    <button type="button" class="btn btn-soft-primary btn-sm mt-3 story-capture-btn">
                                        {{ translate('Use this frame') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="story-poster-panel d-none" data-panel="upload">
                            <div class="form-group row mb-0">
                                <label class="col-lg-2 col-form-label">{{ translate('Poster Image') }}</label>
                                <div class="col-lg-10">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="cover_image" class="selected-files story-cover-input" value="{{ $coverImage }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="text-muted small mb-2">{{ translate('Selected poster preview') }}</div>
                            <div class="story-selected-preview text-muted small">{{ translate('No poster selected yet.') }}</div>
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
                        {{ $isEdit ? translate('Update Storefront') : translate('Save Storefront') }}
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                @include('partials.showcase_preview', [
                    'previewTitle' => translate('Storefront Preview'),
                    'previewNote' => translate('Preview will appear here after you add the media and save the form.'),
                    'previewType' => 'vitrin',
                    'shopName' => optional(Auth::user()->shop)->name,
                    'shopLogo' => optional(Auth::user()->shop)->logo ? uploaded_asset(optional(Auth::user()->shop)->logo) : ''
                ])

                @include('seller.showcase._products', [
                    'products' => $products,
                    'selectedProducts' => $selectedProducts,
                    'showcaseCategories' => $showcaseCategories ?? [],
                    'productCategoryMap' => $productCategoryMap ?? []
                ])
            </div>
        </div>
    </form>

    @include('partials.story_poster_script')
@endsection
