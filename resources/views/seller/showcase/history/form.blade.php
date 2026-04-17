@extends('seller.layouts.app')

@section('panel_content')
    @php
        $isEdit = !empty($item);
        $formAction = $isEdit
            ? route('seller.showcase.history.update', $item->id)
            : route('seller.showcase.history.store');

        $hashtags = old('hashtags', $item->hashtags ?? '');
        $storyVideo = old('story_video', $item->main_visual ?? '');
        $coverImage = old('cover_image', $item->cover_image ?? '');
        $status = old('status', $item->status ?? 'draft');
        $selectedProductId = old('product_id', $selectedProductId ?? '');
    @endphp

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0">{{ $page_title ?? translate('Story Form') }}</h1>
                <p class="text-muted mb-0 mt-1">{{ translate('Seller Showcase / Story') }}</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('seller.showcase.history.index') }}" class="btn btn-soft-secondary">
                    {{ translate('Back to Stories') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-700 mb-2">{{ translate('Showcase Notes') }}</h5>
            <ul class="mb-0 pl-3 text-muted">
                <li>{{ translate('Select one product and upload the Story video.') }}</li>
                <li>{{ translate('Poster image is optional and can be picked from suggested frames or uploaded.') }}</li>
                <li>{{ translate('Add hashtags to help discovery.') }}</li>
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
                        <h5 class="mb-0 h6">{{ translate('Video Upload') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ translate('Story Video') }}</label>
                            <div class="col-lg-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="video" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Video') }}</div>
                                    <input type="hidden" name="story_video" class="selected-files story-video-input" value="{{ $storyVideo }}">
                                </div>
                                <div class="file-preview box sm"></div>

                                @error('story_video')
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
                        <input type="hidden" name="poster_image_data" class="story-poster-data" value="{{ old('poster_image_data') }}">

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
            </div>

            <div class="col-lg-4">
                @include('partials.showcase_preview', [
                    'previewTitle' => translate('Story Preview'),
                    'previewNote' => translate('Preview will appear here after you add the video and save the form.'),
                    'previewType' => 'story',
                    'shopName' => optional(Auth::user()->shop)->name,
                    'shopLogo' => optional(Auth::user()->shop)->logo ? uploaded_asset(optional(Auth::user()->shop)->logo) : ''
                ])
            </div>
        </div>

        <div class="text-right mb-4">
            <button type="submit" class="btn btn-primary">
                {{ $isEdit ? translate('Update Story') : translate('Save Story') }}
            </button>
        </div>
    </form>

    @include('partials.story_poster_script')
@endsection
