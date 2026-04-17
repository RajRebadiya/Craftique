<?php $__env->startSection('panel_content'); ?>
    <?php
        $isEdit = !empty($item);
        $formAction = $isEdit
            ? route('seller.showcase.history.update', $item->id)
            : route('seller.showcase.history.store');

        $hashtags = old('hashtags', $item->hashtags ?? '');
        $storyVideo = old('story_video', $item->main_visual ?? '');
        $coverImage = old('cover_image', $item->cover_image ?? '');
        $status = old('status', $item->status ?? 'draft');
        $selectedProductId = old('product_id', $selectedProductId ?? '');
    ?>

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0"><?php echo e($page_title ?? translate('Story Form')); ?></h1>
                <p class="text-muted mb-0 mt-1"><?php echo e(translate('Seller Showcase / Story')); ?></p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="<?php echo e(route('seller.showcase.history.index')); ?>" class="btn btn-soft-secondary">
                    <?php echo e(translate('Back to Stories')); ?>

                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-700 mb-2"><?php echo e(translate('Showcase Notes')); ?></h5>
            <ul class="mb-0 pl-3 text-muted">
                <li><?php echo e(translate('Select one product and upload the Story video.')); ?></li>
                <li><?php echo e(translate('Poster image is optional and can be picked from suggested frames or uploaded.')); ?></li>
                <li><?php echo e(translate('Add hashtags to help discovery.')); ?></li>
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
            <div class="col-lg-8">
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
                        <h5 class="mb-0 h6"><?php echo e(translate('Video Upload')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php echo e(translate('Story Video')); ?></label>
                            <div class="col-lg-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="video" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                    </div>
                                    <div class="form-control file-amount"><?php echo e(translate('Choose Video')); ?></div>
                                    <input type="hidden" name="story_video" class="selected-files story-video-input" value="<?php echo e($storyVideo); ?>">
                                </div>
                                <div class="file-preview box sm"></div>

                                <?php $__errorArgs = ['story_video'];
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
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" data-story-poster>
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Poster / Frame Selection (Optional)')); ?></h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="poster_image_data" class="story-poster-data" value="<?php echo e(old('poster_image_data')); ?>">

                        <div class="d-flex flex-wrap mb-3" style="gap:10px;">
                            <button type="button" class="btn btn-soft-primary btn-sm story-poster-tab" data-target="suggested">
                                <?php echo e(translate('Select a Suggested Frame')); ?>

                            </button>
                            <button type="button" class="btn btn-soft-secondary btn-sm story-poster-tab" data-target="video">
                                <?php echo e(translate('Choose a Frame from Video')); ?>

                            </button>
                            <button type="button" class="btn btn-soft-secondary btn-sm story-poster-tab" data-target="upload">
                                <?php echo e(translate('Upload an Image')); ?>

                            </button>
                        </div>

                        <div class="story-poster-panel" data-panel="suggested">
                            <p class="text-muted mb-2">
                                <?php echo e(translate('Suggested frames will appear after video upload. Select one to use as poster.')); ?>

                            </p>
                            <div class="story-frame-empty text-muted small mb-3">
                                <?php echo e(translate('Upload a video to see suggested frames.')); ?>

                            </div>
                            <div class="d-flex align-items-center story-frame-grid" style="gap:10px; overflow-x:auto;"></div>
                        </div>

                        <div class="story-poster-panel story-video-panel d-none" data-panel="video">
                            <div class="row align-items-center">
                                <div class="col-lg-6 mb-3 mb-lg-0">
                                    <video class="w-100 rounded border story-video-player" controls muted playsinline></video>
                                </div>
                                <div class="col-lg-6">
                                    <label class="text-muted small mb-2 d-block"><?php echo e(translate('Pick a frame and use it as poster')); ?></label>
                                    <input type="range" class="form-control-range story-video-range" min="0" step="1" value="0">
                                    <button type="button" class="btn btn-soft-primary btn-sm mt-3 story-capture-btn">
                                        <?php echo e(translate('Use this frame')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="story-poster-panel d-none" data-panel="upload">
                            <div class="form-group row mb-0">
                                <label class="col-lg-2 col-form-label"><?php echo e(translate('Poster Image')); ?></label>
                                <div class="col-lg-10">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                        </div>
                                        <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                        <input type="hidden" name="cover_image" class="selected-files story-cover-input" value="<?php echo e($coverImage); ?>">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="text-muted small mb-2"><?php echo e(translate('Selected poster preview')); ?></div>
                            <div class="story-selected-preview text-muted small"><?php echo e(translate('No poster selected yet.')); ?></div>
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
            </div>

            <div class="col-lg-4">
                <?php echo $__env->make('partials.showcase_preview', [
                    'previewTitle' => translate('Story Preview'),
                    'previewNote' => translate('Preview will appear here after you add the video and save the form.'),
                    'previewType' => 'story',
                    'shopName' => optional(Auth::user()->shop)->name,
                    'shopLogo' => optional(Auth::user()->shop)->logo ? uploaded_asset(optional(Auth::user()->shop)->logo) : ''
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

        <div class="text-right mb-4">
            <button type="submit" class="btn btn-primary">
                <?php echo e($isEdit ? translate('Update Story') : translate('Save Story')); ?>

            </button>
        </div>
    </form>

    <?php echo $__env->make('partials.story_poster_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/showcase/history/form.blade.php ENDPATH**/ ?>