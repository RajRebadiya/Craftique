@extends('seller.layouts.app')

@section('panel_content')
    @php
        $isEdit = !empty($item);
        $formAction = $isEdit
            ? route('seller.showcase.collection.update', $item->id)
            : route('seller.showcase.collection.store');

        $titleGr = old('title_gr', $item->title_gr ?? $item->title ?? '');
        $titleEn = old('title_en', $item->title_en ?? '');

        $introGr = old('intro_gr', $item->intro_gr ?? $item->intro ?? '');
        $introEn = old('intro_en', $item->intro_en ?? '');

        $descriptionGr = old('description_gr', $item->description_gr ?? $item->description ?? '');
        $descriptionEn = old('description_en', $item->description_en ?? '');

        $coverImage = old('cover_image', $item->cover_image ?? '');
        $hashtags = old('hashtags', $item->hashtags ?? '');
        $status = old('status', $item->status ?? 'draft');

        $collectionItems = old('collection_items', $collectionItems ?? []);
        if (!is_array($collectionItems) || empty($collectionItems)) {
            $collectionItems = [[
                'title_gr' => '',
                'title_en' => '',
                'description_gr' => '',
                'description_en' => '',
                'cover_image' => '',
                'product_id' => '',
            ]];
        }

        $productOptions = $products->map(function ($product) {
            return [
                'id' => (int) $product->id,
                'label' => $product->name . ' (#' . $product->id . ')',
            ];
        })->values()->all();
    @endphp

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0">{{ $page_title ?? translate('Collection Form') }}</h1>
                <p class="text-muted mb-0 mt-1">{{ translate('Seller Showcase / Collection') }}</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('seller.showcase.collection.index') }}" class="btn btn-soft-secondary">
                    {{ translate('Back to Collections') }}
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
                <li>{{ translate('Cover image is optional and can be used as preview / fallback.') }}</li>
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
            <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Greek Content') }}</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ translate('Title (Greek)') }}</label>
                    <div class="col-lg-10">
                        <input type="text" name="title_gr" class="form-control" value="{{ $titleGr }}" placeholder="{{ translate('Enter collection title in Greek') }}">
                        @error('title_gr')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ translate('Intro (Greek)') }}</label>
                    <div class="col-lg-10">
                        <textarea name="intro_gr" class="form-control" rows="3" placeholder="{{ translate('Short intro in Greek') }}">{{ $introGr }}</textarea>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <label class="col-lg-2 col-form-label">{{ translate('Description (Greek)') }}</label>
                    <div class="col-lg-10">
                        <textarea name="description_gr" class="form-control" rows="6" placeholder="{{ translate('Collection description in Greek') }}">{{ $descriptionGr }}</textarea>
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
                        <input type="text" name="title_en" class="form-control" value="{{ $titleEn }}" placeholder="{{ translate('Enter collection title in English') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ translate('Intro (English)') }}</label>
                    <div class="col-lg-10">
                        <textarea name="intro_en" class="form-control" rows="3" placeholder="{{ translate('Short intro in English') }}">{{ $introEn }}</textarea>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <label class="col-lg-2 col-form-label">{{ translate('Description (English)') }}</label>
                    <div class="col-lg-10">
                        <textarea name="description_en" class="form-control" rows="6" placeholder="{{ translate('Collection description in English') }}">{{ $descriptionEn }}</textarea>
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
                <h5 class="mb-0 h6">{{ translate('Collection Cover / Main Visual') }}</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ translate('Collection Cover Image') }}</label>
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

                <div class="form-group row mb-0">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                <h5 class="mb-0 h6">{{ translate('Collection Cards / Items') }}</h5>
                <button type="button" id="add-collection-item" class="btn btn-soft-primary btn-sm">
                    <i class="las la-plus mr-1"></i>{{ translate('Add Card') }}
                </button>
            </div>
            <div class="card-body">
                <div id="collection-items-wrap">
                    @foreach($collectionItems as $index => $collectionItem)
                        @php
                            $rowTitleGr = $collectionItem['title_gr'] ?? '';
                            $rowTitleEn = $collectionItem['title_en'] ?? '';
                            $rowDescriptionGr = $collectionItem['description_gr'] ?? '';
                            $rowDescriptionEn = $collectionItem['description_en'] ?? '';
                            $rowCoverImage = $collectionItem['cover_image'] ?? '';
                            $rowProductId = $collectionItem['product_id'] ?? '';
                        @endphp

                        <div class="card border mb-3 js-collection-item-card">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                                <strong class="js-collection-item-title">{{ translate('Card') }} #{{ $loop->iteration }}</strong>
                                <button type="button" class="btn btn-soft-danger btn-sm js-remove-collection-item">
                                    {{ translate('Remove') }}
                                </button>
                            </div>

                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">{{ translate('Card Title (Greek)') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="collection_items[{{ $index }}][title_gr]" class="form-control" value="{{ $rowTitleGr }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">{{ translate('Card Title (English)') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="collection_items[{{ $index }}][title_en]" class="form-control" value="{{ $rowTitleEn }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">{{ translate('Card Description (Greek)') }}</label>
                                    <div class="col-lg-10">
                                        <textarea name="collection_items[{{ $index }}][description_gr]" class="form-control" rows="4">{{ $rowDescriptionGr }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">{{ translate('Card Description (English)') }}</label>
                                    <div class="col-lg-10">
                                        <textarea name="collection_items[{{ $index }}][description_en]" class="form-control" rows="4">{{ $rowDescriptionEn }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">{{ translate('Card Cover Image') }}</label>
                                    <div class="col-lg-10">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="collection_items[{{ $index }}][cover_image]" class="selected-files" value="{{ $rowCoverImage }}">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <label class="col-lg-2 col-form-label">{{ translate('Linked Product') }}</label>
                                    <div class="col-lg-10">
                                        <select name="collection_items[{{ $index }}][product_id]" class="form-control">
                                            <option value="">{{ translate('Select One') }}</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ (string) $rowProductId === (string) $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} (#{{ $product->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

        <div class="text-right">
            <button type="submit" class="btn btn-primary">
                {{ $isEdit ? translate('Update Collection') : translate('Save Collection') }}
            </button>
        </div>
            </div>

            <div class="col-lg-6">
                @include('partials.showcase_preview', [
                    'previewTitle' => translate('Collection Preview'),
                    'previewNote' => translate('Preview will appear here after you add the cards and save the form.'),
                    'previewType' => 'collection',
                    'shopName' => optional(Auth::user()->shop)->name,
                    'shopLogo' => optional(Auth::user()->shop)->logo ? uploaded_asset(optional(Auth::user()->shop)->logo) : ''
                ])
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var wrap = document.getElementById('collection-items-wrap');
            var addBtn = document.getElementById('add-collection-item');
            var productOptions = @json($productOptions);
            var nextIndex = {{ count($collectionItems) }};

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function productSelectOptions(selectedValue) {
                var html = '<option value="">' + escapeHtml(@json(translate('Select One'))) + '</option>';

                productOptions.forEach(function (product) {
                    var selected = String(selectedValue || '') === String(product.id) ? 'selected' : '';
                    html += '<option value="' + escapeHtml(product.id) + '" ' + selected + '>' + escapeHtml(product.label) + '</option>';
                });

                return html;
            }

            function renderCardTitleNumbers() {
                var cards = wrap.querySelectorAll('.js-collection-item-card');
                cards.forEach(function (card, index) {
                    var titleEl = card.querySelector('.js-collection-item-title');
                    if (titleEl) {
                        titleEl.textContent = @json(translate('Card')) + ' #' + (index + 1);
                    }
                });
            }

            function itemCardTemplate(index) {
                return ''
                    + '<div class="card border mb-3 js-collection-item-card">'
                    + '    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">'
                    + '        <strong class="js-collection-item-title">' + escapeHtml(@json(translate('Card'))) + ' #' + (index + 1) + '</strong>'
                    + '        <button type="button" class="btn btn-soft-danger btn-sm js-remove-collection-item">' + escapeHtml(@json(translate('Remove'))) + '</button>'
                    + '    </div>'
                    + '    <div class="card-body">'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(@json(translate('Card Title (Greek)'))) + '</label>'
                    + '            <div class="col-lg-10"><input type="text" name="collection_items[' + index + '][title_gr]" class="form-control" value=""></div>'
                    + '        </div>'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(@json(translate('Card Title (English)'))) + '</label>'
                    + '            <div class="col-lg-10"><input type="text" name="collection_items[' + index + '][title_en]" class="form-control" value=""></div>'
                    + '        </div>'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(@json(translate('Card Description (Greek)'))) + '</label>'
                    + '            <div class="col-lg-10"><textarea name="collection_items[' + index + '][description_gr]" class="form-control" rows="4"></textarea></div>'
                    + '        </div>'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(@json(translate('Card Description (English)'))) + '</label>'
                    + '            <div class="col-lg-10"><textarea name="collection_items[' + index + '][description_en]" class="form-control" rows="4"></textarea></div>'
                    + '        </div>'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(@json(translate('Card Cover Image'))) + '</label>'
                    + '            <div class="col-lg-10">'
                    + '                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">'
                    + '                    <div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">' + escapeHtml(@json(translate('Browse'))) + '</div></div>'
                    + '                    <div class="form-control file-amount">' + escapeHtml(@json(translate('Choose File'))) + '</div>'
                    + '                    <input type="hidden" name="collection_items[' + index + '][cover_image]" class="selected-files" value="">'
                    + '                </div>'
                    + '                <div class="file-preview box sm"></div>'
                    + '            </div>'
                    + '        </div>'
                    + '        <div class="form-group row mb-0">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(@json(translate('Linked Product'))) + '</label>'
                    + '            <div class="col-lg-10"><select name="collection_items[' + index + '][product_id]" class="form-control">' + productSelectOptions('') + '</select></div>'
                    + '        </div>'
                    + '    </div>'
                    + '</div>';
            }

            if (addBtn && wrap) {
                addBtn.addEventListener('click', function () {
                    wrap.insertAdjacentHTML('beforeend', itemCardTemplate(nextIndex));
                    nextIndex++;
                    renderCardTitleNumbers();
                    if (window.AIZ && AIZ.plugins) {
                        if (AIZ.plugins.bootstrapSelect) {
                            AIZ.plugins.bootstrapSelect('refresh');
                        }
                        if (AIZ.plugins.aizUppy) {
                            AIZ.plugins.aizUppy();
                        }
                    }
                });

                wrap.addEventListener('click', function (event) {
                    var removeBtn = event.target.closest('.js-remove-collection-item');
                    if (!removeBtn) {
                        return;
                    }

                    var cards = wrap.querySelectorAll('.js-collection-item-card');
                    if (cards.length <= 1) {
                        return;
                    }

                    var card = removeBtn.closest('.js-collection-item-card');
                    if (card) {
                        card.remove();
                        renderCardTitleNumbers();
                    }
                });

                renderCardTitleNumbers();
            }
        });
    </script>
@endsection
