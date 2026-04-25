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
        $posterImageData = old('poster_image_data');
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
                <li>{{ translate('Main visual must use a 16:9 HD landscape ratio.') }}</li>
                <li>{{ translate('Recommended sizes: 1280x720 or 1920x1080.') }}</li>
                <li>{{ translate('If you upload a video, you can pick a poster from suggested frames, choose a frame manually, or upload an image.') }}</li>
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
                                    <input type="hidden" name="main_visual" class="selected-files story-video-input" value="{{ $mainVisual }}">
                                    <input type="hidden" name="launch_media_width" value="{{ old('launch_media_width') }}">
                                    <input type="hidden" name="launch_media_height" value="{{ old('launch_media_height') }}">
                                    <input type="hidden" name="launch_media_kind" value="{{ old('launch_media_kind') }}">
                                </div>
                                <div class="file-preview box sm"></div>

                                @error('main_visual')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    {{ translate('Expected ratio: 16:9 landscape only. Minimum 1280x720, recommended 1920x1080.') }}
                                </small>
                                <small class="text-danger d-block mt-2 launch-media-error" style="display:none;"></small>
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

    @include('partials.story_poster_script')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form[action="{{ $formAction }}"]');
            if (!form) {
                return;
            }

            var mainVisualInput = form.querySelector('input[name="main_visual"]');
            var coverInput = form.querySelector('input[name="cover_image"]');
            var posterDataInput = form.querySelector('input[name="poster_image_data"]');
            var mediaWidthInput = form.querySelector('input[name="launch_media_width"]');
            var mediaHeightInput = form.querySelector('input[name="launch_media_height"]');
            var mediaKindInput = form.querySelector('input[name="launch_media_kind"]');
            var mediaError = form.querySelector('.launch-media-error');
            var selectedPreview = form.querySelector('.story-selected-preview');
            var lastCheckedValue = null;
            var validationToken = 0;
            var hasLandscapeValidationError = false;
            var ratioMessage = @json(translate('Launch main visual must use a 16:9 landscape ratio such as 1280x720 or 1920x1080.'));
            var sizeMessage = @json(translate('Launch main visual must be at least 1280x720.'));

            if (!mainVisualInput) {
                return;
            }

            function isUrl(value) {
                return /^https?:\/\//i.test(value) || /^\/|^data:/i.test(value);
            }

            function getAppUrl() {
                if (window.AIZ && AIZ.data && AIZ.data.appUrl) {
                    return AIZ.data.appUrl;
                }
                var meta = document.querySelector('meta[name="app-url"]');
                return meta ? meta.getAttribute('content') : '';
            }

            function getCsrfToken() {
                if (window.AIZ && AIZ.data && AIZ.data.csrf) {
                    return AIZ.data.csrf;
                }
                var meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            }

            function fetchFileInfo(ids) {
                if (typeof $ === 'undefined') {
                    return Promise.resolve([]);
                }

                var appUrl = getAppUrl();
                if (!appUrl) {
                    return Promise.resolve([]);
                }

                return new Promise(function(resolve) {
                    $.post(
                        appUrl.replace(/\/$/, '') + "/aiz-uploader/get_file_by_ids",
                        {
                            _token: getCsrfToken(),
                            ids: ids
                        },
                        function(data) {
                            resolve(Array.isArray(data) ? data : []);
                        }
                    ).fail(function() {
                        resolve([]);
                    });
                });
            }

            function normalizeFileUrl(file) {
                if (!file) {
                    return null;
                }
                var url = file.file_url || file.file_name || file.file_path || file.file;
                if (!url) {
                    return null;
                }
                if (isUrl(url)) {
                    return url;
                }
                var appUrl = getAppUrl();
                if (appUrl) {
                    return appUrl.replace(/\/$/, '') + '/' + String(url).replace(/^\//, '');
                }
                return url;
            }

            function resolveMediaUrl(value) {
                if (!value) {
                    return Promise.resolve(null);
                }

                if (isUrl(value)) {
                    return Promise.resolve(value);
                }

                var firstId = String(value).split(',')[0].trim();
                if (!firstId) {
                    return Promise.resolve(null);
                }

                if (/\/|\.|uploads/i.test(firstId)) {
                    return Promise.resolve(normalizeFileUrl({
                        file_name: firstId
                    }));
                }

                return fetchFileInfo(firstId).then(function(files) {
                    return files.length ? normalizeFileUrl(files[0]) : null;
                });
            }

            function setError(message) {
                if (!mediaError) {
                    return;
                }
                mediaError.textContent = message || '';
                mediaError.style.display = message ? 'block' : 'none';
                hasLandscapeValidationError = !!message;
            }

            function setMediaMeta(width, height, kind) {
                if (mediaWidthInput) mediaWidthInput.value = width || '';
                if (mediaHeightInput) mediaHeightInput.value = height || '';
                if (mediaKindInput) mediaKindInput.value = kind || '';
            }

            function clearUploaderPreview(input) {
                if (!input) {
                    return;
                }
                input.value = '';
                var previewBox = input.closest('.input-group') ? input.closest('.input-group').nextElementSibling : null;
                if (previewBox && previewBox.classList.contains('file-preview')) {
                    previewBox.innerHTML = '';
                }
            }

            function resetPosterSelection() {
                if (posterDataInput) {
                    posterDataInput.value = '';
                }
                if (coverInput) {
                    clearUploaderPreview(coverInput);
                }
                if (selectedPreview) {
                    selectedPreview.innerHTML =
                        '<div class="text-muted small">{{ translate("No poster selected yet.") }}</div>';
                }
            }

            function clearInvalidMedia() {
                clearUploaderPreview(mainVisualInput);
                resetPosterSelection();
                setMediaMeta('', '', '');
            }

            function validateImage(url, token) {
                return new Promise(function(resolve) {
                    var img = new Image();
                    img.onload = function() {
                        resolve(token === validationToken ? {
                            valid: true,
                            width: img.naturalWidth,
                            height: img.naturalHeight,
                            kind: 'image'
                        } : {
                            valid: true
                        });
                    };
                    img.onerror = function() {
                        resolve(token === validationToken ? {
                            valid: false
                        } : {
                            valid: true
                        });
                    };
                    img.src = url;
                });
            }

            function validateVideo(url, token) {
                return new Promise(function(resolve) {
                    var video = document.createElement('video');
                    video.preload = 'metadata';
                    video.muted = true;
                    video.playsInline = true;
                    video.onloadedmetadata = function() {
                        resolve(token === validationToken ? {
                            valid: true,
                            width: video.videoWidth,
                            height: video.videoHeight,
                            kind: 'video'
                        } : {
                            valid: true
                        });
                    };
                    video.onerror = function() {
                        resolve(token === validationToken ? {
                            valid: false
                        } : {
                            valid: true
                        });
                    };
                    video.src = url;
                });
            }

            function validateDimensions(meta) {
                if (!meta || !meta.valid || !meta.width || !meta.height) {
                    return ratioMessage;
                }

                if (meta.width < 1280 || meta.height < 720) {
                    return sizeMessage;
                }

                var ratio = meta.width / meta.height;
                return Math.abs(ratio - (16 / 9)) > 0.02 ? ratioMessage : '';
            }

            function validateLaunchMedia() {
                var currentValue = (mainVisualInput.value || '').trim();
                if (!currentValue) {
                    lastCheckedValue = '';
                    setMediaMeta('', '', '');
                    if (!hasLandscapeValidationError) {
                        setError('');
                    }
                    return;
                }
                if (currentValue === lastCheckedValue) {
                    return;
                }

                lastCheckedValue = currentValue;
                validationToken += 1;
                var token = validationToken;

                resolveMediaUrl(currentValue).then(function(url) {
                    if (token !== validationToken || !url) {
                        return;
                    }

                    var isVideo = /\.(mp4|mov|webm|ogg)$/i.test(url);
                    return (isVideo ? validateVideo(url, token) : validateImage(url, token)).then(function(
                        meta) {
                        if (token !== validationToken) {
                            return;
                        }
                        var dimensionError = validateDimensions(meta);
                        if (dimensionError) {
                            clearInvalidMedia();
                            setError(dimensionError);
                            lastCheckedValue = '';
                            return;
                        }
                        setMediaMeta(meta.width, meta.height, meta.kind);
                        setError('');
                    });
                });
            }

            mainVisualInput.addEventListener('change', validateLaunchMedia);
            setInterval(validateLaunchMedia, 1200);
            validateLaunchMedia();
        });
    </script>
@endsection
