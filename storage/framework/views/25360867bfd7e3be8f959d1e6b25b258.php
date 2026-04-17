<?php $__env->startSection('panel_content'); ?>
    <?php
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
    ?>

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0"><?php echo e($page_title ?? translate('Launch Form')); ?></h1>
                <p class="text-muted mb-0 mt-1"><?php echo e(translate('Seller Showcase / Launch')); ?></p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="<?php echo e(route('seller.showcase.launch.index')); ?>" class="btn btn-soft-secondary">
                    <?php echo e(translate('Back to Launches')); ?>

                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-700 mb-2"><?php echo e(translate('Showcase Notes')); ?></h5>
            <ul class="mb-0 pl-3 text-muted">
                <li><?php echo e(translate('Title is required in at least one language.')); ?></li>
                <li><?php echo e(translate('Subtitle is used for the three-word tagline shown in the preview.')); ?></li>
                <li><?php echo e(translate('Main visual is the primary media for the Launch.')); ?></li>
                <li><?php echo e(translate('One product should be linked to the Launch.')); ?></li>
                <li><?php echo e(translate('Hashtags help the feed and search experience.')); ?></li>
            </ul>
        </div>
    </div>

    <form action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($isEdit): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Greek Content')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Title (Greek)')); ?></label>
                            <div class="col-lg-10">
                                <input type="text" name="title_gr" class="form-control" value="<?php echo e($titleGr); ?>" placeholder="<?php echo e(translate('Enter title in Greek')); ?>">
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
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Tagline (Greek)')); ?></label>
                            <div class="col-lg-10">
                                <input type="text" name="subtitle_gr" class="form-control" value="<?php echo e($subtitleGr); ?>" placeholder="<?php echo e(translate('Enter tagline in Greek')); ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Description (Greek)')); ?></label>
                            <div class="col-lg-10">
                                <textarea name="description_gr" class="form-control" rows="6" placeholder="<?php echo e(translate('Enter description in Greek')); ?>"><?php echo e($descriptionGr); ?></textarea>
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
                                <input type="text" name="title_en" class="form-control" value="<?php echo e($titleEn); ?>" placeholder="<?php echo e(translate('Enter title in English')); ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Tagline (English)')); ?></label>
                            <div class="col-lg-10">
                                <input type="text" name="subtitle_en" class="form-control" value="<?php echo e($subtitleEn); ?>" placeholder="<?php echo e(translate('Enter tagline in English')); ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Description (English)')); ?></label>
                            <div class="col-lg-10">
                                <textarea name="description_en" class="form-control" rows="6" placeholder="<?php echo e(translate('Enter description in English')); ?>"><?php echo e($descriptionEn); ?></textarea>
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

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Launch Media')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Main Visual')); ?></label>
                            <div class="col-lg-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="all" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                    </div>
                                    <div class="form-control file-amount"><?php echo e(translate('Choose Image or Video')); ?></div>
                                    <input type="hidden" name="main_visual" class="selected-files" value="<?php echo e($mainVisual); ?>">
                                </div>
                                <div class="file-preview box sm"></div>

                                <?php $__errorArgs = ['main_visual'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger d-block mt-2"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Poster / Cover Image')); ?></label>
                            <div class="col-lg-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                    </div>
                                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                    <input type="hidden" name="cover_image" class="selected-files" value="<?php echo e($coverImage); ?>">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Product Selection')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row mb-0">
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Product')); ?></label>
                            <div class="col-lg-10">
                                <select name="product_id" class="form-control aiz-selectpicker" data-live-search="true">
                                    <option value=""><?php echo e(translate('Select One')); ?></option>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($product->id); ?>" <?php echo e((string) $selectedProductId === (string) $product->id ? 'selected' : ''); ?>>
                                            <?php echo e($product->name); ?> (#<?php echo e($product->id); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['product_id'];
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

                <div class="text-right mb-4">
                    <button type="submit" class="btn btn-primary">
                        <?php echo e($isEdit ? translate('Update Launch') : translate('Save Launch')); ?>

                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                <?php echo $__env->make('partials.showcase_preview', [
                    'previewTitle' => translate('Launch Preview'),
                    'previewNote' => translate('Preview will appear here after you add the media and save the form.'),
                    'previewType' => 'launch',
                    'shopName' => optional(Auth::user()->shop)->name,
                    'shopLogo' => optional(Auth::user()->shop)->logo ? uploaded_asset(optional(Auth::user()->shop)->logo) : ''
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/showcase/launch/form.blade.php ENDPATH**/ ?>