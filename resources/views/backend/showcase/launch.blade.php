@extends('backend.layouts.app')

@section('content')
@php
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
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3">{{ $page_title }}</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 pl-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('showcase.launch.store') }}" method="POST">
    @csrf

    <input type="hidden" name="id" value="{{ old('id', $item->id ?? '') }}">

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="fw-700 mb-2">Launch Media Notes</h5>
                            <p class="text-muted mb-2">
                                Launch is built with a main visual, optional cover image, a three-word tagline, and one linked product.
                            </p>
                            <ul class="mb-0 pl-3 text-muted">
                                <li>Title is required in at least one language.</li>
                                <li>Main visual must use a 16:9 HD landscape ratio.</li>
                                <li>Recommended sizes: 1280 x 720 or 1920 x 1080.</li>
                                <li>Cover image is optional and acts as preview / fallback.</li>
                                <li>Three words appear as the launch tagline.</li>
                                <li>Exactly one product should be linked.</li>
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
                                <label>Title (GR)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="title_gr"
                                    placeholder="Enter title in Greek"
                                    value="{{ $titleGr }}"
                                >
                                @error('title_gr')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Tagline (GR)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="subtitle_gr"
                                    placeholder="Tagline in Greek"
                                    value="{{ $subtitleGr }}"
                                >
                            </div>

                            <div class="form-group mb-0">
                                <label>Description (GR)</label>
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    name="description_gr"
                                    placeholder="Launch description in Greek..."
                                >{{ $descriptionGr }}</textarea>
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
                                    placeholder="Enter title in English"
                                    value="{{ $titleEn }}"
                                >
                            </div>

                            <div class="form-group">
                                <label>Tagline (EN)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="subtitle_en"
                                    placeholder="Tagline in English"
                                    value="{{ $subtitleEn }}"
                                >
                            </div>

                            <div class="form-group mb-0">
                                <label>Description (EN)</label>
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    name="description_en"
                                    placeholder="Launch description in English..."
                                >{{ $descriptionEn }}</textarea>
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
                                <div class="input-group" data-toggle="aizuploader" data-type="all" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose Image or Video</div>
                                    <input
                                        type="hidden"
                                        name="main_visual"
                                        class="selected-files"
                                        value="{{ $mainVisual }}"
                                    >
                                    <input type="hidden" name="launch_media_width" value="{{ old('launch_media_width') }}">
                                    <input type="hidden" name="launch_media_height" value="{{ old('launch_media_height') }}">
                                    <input type="hidden" name="launch_media_kind" value="{{ old('launch_media_kind') }}">
                                </div>
                                <div class="file-preview box sm"></div>
                                @error('main_visual')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    Expected ratio: 16:9 landscape only. Minimum 1280 x 720, recommended 1920 x 1080.
                                </small>
                                <small class="text-danger d-block mt-2 launch-media-error" style="display:none;"></small>
                            </div>

                            <div class="form-group">
                                <label>Poster / Cover Image</label>
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
                                <button type="submit" class="btn btn-primary">Save Launch</button>
                            </div>
                        </div>
                    </div>
                </div>

        <div class="col-lg-4">
            @include('partials.showcase_preview', [
                'previewTitle' => translate('Launch Preview'),
                'previewNote' => translate('Preview will appear here after you add the media and save the form.'),
                'previewType' => 'launch'
            ])

            @include('backend.showcase._products')
            @error('product_ids')
                <small class="text-danger d-block mt-2">{{ $message }}</small>
            @enderror
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form[action="{{ route('showcase.launch.store') }}"]');
    if (!form) {
        return;
    }

    var mainVisualInput = form.querySelector('input[name="main_visual"]');
    var coverInput = form.querySelector('input[name="cover_image"]');
    var mediaWidthInput = form.querySelector('input[name="launch_media_width"]');
    var mediaHeightInput = form.querySelector('input[name="launch_media_height"]');
    var mediaKindInput = form.querySelector('input[name="launch_media_kind"]');
    var mediaError = form.querySelector('.launch-media-error');
    var lastCheckedValue = null;
    var validationToken = 0;
    var hasLandscapeValidationError = false;
    var ratioMessage = 'Launch main visual must use a 16:9 landscape ratio such as 1280x720 or 1920x1080.';
    var sizeMessage = 'Launch main visual must be at least 1280x720.';

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
            return Promise.resolve(normalizeFileUrl({ file_name: firstId }));
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

    function clearInvalidMedia() {
        clearUploaderPreview(mainVisualInput);
        if (coverInput) {
            clearUploaderPreview(coverInput);
        }
        setMediaMeta('', '', '');
    }

    function validateImage(url, token) {
        return new Promise(function(resolve) {
            var img = new Image();
            img.onload = function() {
                resolve(token === validationToken ? { valid: true, width: img.naturalWidth, height: img.naturalHeight, kind: 'image' } : { valid: true });
            };
            img.onerror = function() {
                resolve(token === validationToken ? { valid: false } : { valid: true });
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
                resolve(token === validationToken ? { valid: true, width: video.videoWidth, height: video.videoHeight, kind: 'video' } : { valid: true });
            };
            video.onerror = function() {
                resolve(token === validationToken ? { valid: false } : { valid: true });
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
            return (isVideo ? validateVideo(url, token) : validateImage(url, token)).then(function(meta) {
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
