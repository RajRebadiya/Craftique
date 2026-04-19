<?php $__env->startSection('panel_content'); ?>
    <?php
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
    ?>

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0"><?php echo e($page_title ?? translate('Collection Form')); ?></h1>
                <p class="text-muted mb-0 mt-1"><?php echo e(translate('Seller Showcase / Collection')); ?></p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="<?php echo e(route('seller.showcase.collection.index')); ?>" class="btn btn-soft-secondary">
                    <?php echo e(translate('Back to Collections')); ?>

                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-700 mb-2"><?php echo e(translate('Showcase Notes')); ?></h5>
            <ul class="mb-0 pl-3 text-muted">
                <li><?php echo e(translate('Title is required in at least one language.')); ?></li>
                <li><?php echo e(translate('Main visual is the primary media.')); ?></li>
                <li><?php echo e(translate('The first selected product image is used automatically as the collection main visual.')); ?></li>
                <li><?php echo e(translate('Linked products are optional.')); ?></li>
                <li><?php echo e(translate('You can save as draft or publish directly.')); ?></li>
            </ul>
        </div>
    </div>

    <form action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($isEdit): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Greek Content')); ?></h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Title (Greek)')); ?></label>
                    <div class="col-lg-10">
                        <input type="text" name="title_gr" class="form-control" value="<?php echo e($titleGr); ?>" placeholder="<?php echo e(translate('Enter collection title in Greek')); ?>">
                        <?php $__errorArgs = ['title_gr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger d-block mt-1"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Intro (Greek)')); ?></label>
                    <div class="col-lg-10">
                        <textarea name="intro_gr" class="form-control" rows="3" placeholder="<?php echo e(translate('Short intro in Greek')); ?>"><?php echo e($introGr); ?></textarea>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Description (Greek)')); ?></label>
                    <div class="col-lg-10">
                        <textarea name="description_gr" class="form-control" rows="6" placeholder="<?php echo e(translate('Collection description in Greek')); ?>"><?php echo e($descriptionGr); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('English Content')); ?></h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Title (English)')); ?></label>
                    <div class="col-lg-10">
                        <input type="text" name="title_en" class="form-control" value="<?php echo e($titleEn); ?>" placeholder="<?php echo e(translate('Enter collection title in English')); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Intro (English)')); ?></label>
                    <div class="col-lg-10">
                        <textarea name="intro_en" class="form-control" rows="3" placeholder="<?php echo e(translate('Short intro in English')); ?>"><?php echo e($introEn); ?></textarea>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Description (English)')); ?></label>
                    <div class="col-lg-10">
                        <textarea name="description_en" class="form-control" rows="6" placeholder="<?php echo e(translate('Collection description in English')); ?>"><?php echo e($descriptionEn); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Hashtags')); ?></h5>
            </div>
            <div class="card-body">
                <?php echo $__env->make('partials.hashtag_input', [
                    'hashtagsValue' => $hashtags,
                    'fieldName' => 'hashtags',
                    'labelText' => 'Hashtags',
                    'showLabel' => false
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
        <input type="hidden" name="cover_image" value="<?php echo e($coverImage); ?>">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                <h5 class="mb-0 h6"><?php echo e(translate('Collection Cards / Items')); ?></h5>
                <button type="button" id="add-collection-item" class="btn btn-soft-primary btn-sm">
                    <i class="las la-plus mr-1"></i><?php echo e(translate('Add Card')); ?>

                </button>
            </div>
            <div class="card-body">
                <div id="collection-items-wrap">
                    <?php $__currentLoopData = $collectionItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $collectionItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $rowTitleGr = $collectionItem['title_gr'] ?? '';
                            $rowTitleEn = $collectionItem['title_en'] ?? '';
                            $rowDescriptionGr = $collectionItem['description_gr'] ?? '';
                            $rowDescriptionEn = $collectionItem['description_en'] ?? '';
                            $rowCoverImage = $collectionItem['cover_image'] ?? '';
                            $rowProductId = $collectionItem['product_id'] ?? '';
                        ?>

                        <div class="card border mb-3 js-collection-item-card">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                                <strong class="js-collection-item-title"><?php echo e(translate('Card')); ?> #<?php echo e($loop->iteration); ?></strong>
                                <button type="button" class="btn btn-soft-danger btn-sm js-remove-collection-item">
                                    <?php echo e(translate('Remove')); ?>

                                </button>
                            </div>

                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Card Title (Greek)')); ?></label>
                                    <div class="col-lg-10">
                                        <input type="text" name="collection_items[<?php echo e($index); ?>][title_gr]" class="form-control" value="<?php echo e($rowTitleGr); ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Card Title (English)')); ?></label>
                                    <div class="col-lg-10">
                                        <input type="text" name="collection_items[<?php echo e($index); ?>][title_en]" class="form-control" value="<?php echo e($rowTitleEn); ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Card Description (Greek)')); ?></label>
                                    <div class="col-lg-10">
                                        <textarea name="collection_items[<?php echo e($index); ?>][description_gr]" class="form-control" rows="4"><?php echo e($rowDescriptionGr); ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Card Description (English)')); ?></label>
                                    <div class="col-lg-10">
                                        <textarea name="collection_items[<?php echo e($index); ?>][description_en]" class="form-control" rows="4"><?php echo e($rowDescriptionEn); ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Card Cover Image')); ?></label>
                                    <div class="col-lg-10">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                            </div>
                                            <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                            <input type="hidden" name="collection_items[<?php echo e($index); ?>][cover_image]" class="selected-files" value="<?php echo e($rowCoverImage); ?>">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Linked Product')); ?></label>
                                    <div class="col-lg-10">
                                        <select name="collection_items[<?php echo e($index); ?>][product_id]" class="form-control">
                                            <option value=""><?php echo e(translate('Select One')); ?></option>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($product->id); ?>" <?php echo e((string) $rowProductId === (string) $product->id ? 'selected' : ''); ?>>
                                                    <?php echo e($product->name); ?> (#<?php echo e($product->id); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Publishing')); ?></h5>
            </div>
            <div class="card-body">
                <div class="form-group row mb-0">
                    <label class="col-lg-2 col-form-label"><?php echo e(translate('Status')); ?></label>
                    <div class="col-lg-10">
                        <select name="status" class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity">
                            <option value="draft" <?php echo e($status === 'draft' ? 'selected' : ''); ?>><?php echo e(translate('Draft')); ?></option>
                            <option value="published" <?php echo e($status === 'published' ? 'selected' : ''); ?>><?php echo e(translate('Published')); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">
                <?php echo e($isEdit ? translate('Update Collection') : translate('Save Collection')); ?>

            </button>
        </div>
            </div>

            <div class="col-lg-6">
                <?php echo $__env->make('partials.showcase_preview', [
                    'previewTitle' => translate('Collection Preview'),
                    'previewNote' => translate('Preview will appear here after you add the cards and save the form.'),
                    'previewType' => 'collection',
                    'shopName' => optional(Auth::user()->shop)->name,
                    'shopLogo' => optional(Auth::user()->shop)->logo ? uploaded_asset(optional(Auth::user()->shop)->logo) : ''
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var wrap = document.getElementById('collection-items-wrap');
            var addBtn = document.getElementById('add-collection-item');
            var productOptions = <?php echo json_encode($productOptions, 15, 512) ?>;
            var nextIndex = <?php echo e(count($collectionItems)); ?>;

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function productSelectOptions(selectedValue) {
                var html = '<option value="">' + escapeHtml(<?php echo json_encode(translate('Select One'), 15, 512) ?>) + '</option>';

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
                        titleEl.textContent = <?php echo json_encode(translate('Card'), 15, 512) ?> + ' #' + (index + 1);
                    }
                });
            }

            function itemCardTemplate(index) {
                return ''
                    + '<div class="card border mb-3 js-collection-item-card">'
                    + '    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">'
                    + '        <strong class="js-collection-item-title">' + escapeHtml(<?php echo json_encode(translate('Card'), 15, 512) ?>) + ' #' + (index + 1) + '</strong>'
                    + '        <button type="button" class="btn btn-soft-danger btn-sm js-remove-collection-item">' + escapeHtml(<?php echo json_encode(translate('Remove'), 15, 512) ?>) + '</button>'
                    + '    </div>'
                    + '    <div class="card-body">'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(<?php echo json_encode(translate('Card Title (Greek)'), 15, 512) ?>) + '</label>'
                    + '            <div class="col-lg-10"><input type="text" name="collection_items[' + index + '][title_gr]" class="form-control" value=""></div>'
                    + '        </div>'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(<?php echo json_encode(translate('Card Title (English)'), 15, 512) ?>) + '</label>'
                    + '            <div class="col-lg-10"><input type="text" name="collection_items[' + index + '][title_en]" class="form-control" value=""></div>'
                    + '        </div>'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(<?php echo json_encode(translate('Card Description (Greek)'), 15, 512) ?>) + '</label>'
                    + '            <div class="col-lg-10"><textarea name="collection_items[' + index + '][description_gr]" class="form-control" rows="4"></textarea></div>'
                    + '        </div>'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(<?php echo json_encode(translate('Card Description (English)'), 15, 512) ?>) + '</label>'
                    + '            <div class="col-lg-10"><textarea name="collection_items[' + index + '][description_en]" class="form-control" rows="4"></textarea></div>'
                    + '        </div>'
                    + '        <div class="form-group row">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(<?php echo json_encode(translate('Card Cover Image'), 15, 512) ?>) + '</label>'
                    + '            <div class="col-lg-10">'
                    + '                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">'
                    + '                    <div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">' + escapeHtml(<?php echo json_encode(translate('Browse'), 15, 512) ?>) + '</div></div>'
                    + '                    <div class="form-control file-amount">' + escapeHtml(<?php echo json_encode(translate('Choose File'), 15, 512) ?>) + '</div>'
                    + '                    <input type="hidden" name="collection_items[' + index + '][cover_image]" class="selected-files" value="">'
                    + '                </div>'
                    + '                <div class="file-preview box sm"></div>'
                    + '            </div>'
                    + '        </div>'
                    + '        <div class="form-group row mb-0">'
                    + '            <label class="col-lg-2 col-form-label">' + escapeHtml(<?php echo json_encode(translate('Linked Product'), 15, 512) ?>) + '</label>'
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/showcase/collection/form.blade.php ENDPATH**/ ?>