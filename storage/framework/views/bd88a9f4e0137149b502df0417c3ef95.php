<?php $__env->startSection('content'); ?>
<?php
    $storyVideo = old('story_video', $item->main_visual ?? '');
    $coverImage = old('cover_image', $item->cover_image ?? '');
    $hashtags = old('hashtags', $item->hashtags ?? '');
    $selectedProductId = old('product_id', $selectedProducts[0] ?? '');
?>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3"><?php echo e($page_title); ?></h1>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<form action="<?php echo e(route('showcase.history.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <input type="hidden" name="id" value="<?php echo e(old('id', $item->id ?? '')); ?>">

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Showcase Story Details</h5>
                        </div>

                        <div class="card-body">
                            <?php if(isset($shops)): ?>
                            <div class="form-group">
                                <label>Creator / Brand</label>
                                <select class="form-control" name="seller_id">
                                    <option value="">Select creator / brand</option>
                                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($shop->id); ?>" <?php echo e(old('seller_id', $item->seller_id ?? '') == $shop->id ? 'selected' : ''); ?>>
                                            <?php echo e($shop->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['seller_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <?php endif; ?>

                            <hr>
                            <h6 class="mb-3">Product Selection</h6>

                            <div class="form-group">
                                <label>Product</label>
                                <select name="product_id" class="form-control aiz-selectpicker" data-live-search="true">
                                    <option value="">Select One</option>
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
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <hr>
                            <h6 class="mb-3">Story Video</h6>

                            <div class="form-group">
                                <label>Video Upload</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="video" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose Video</div>
                                    <input
                                        type="hidden"
                                        name="story_video"
                                        class="selected-files story-video-input"
                                        value="<?php echo e($storyVideo); ?>"
                                    >
                                </div>
                                <div class="file-preview box sm"></div>
                                <?php $__errorArgs = ['story_video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <hr>
                            <h6 class="mb-3">Poster / Frame Selection (Optional)</h6>

                            <div class="card border-0 shadow-sm mb-3" data-story-poster>
                                <div class="card-body">
                                    <input type="hidden" name="poster_image_data" class="story-poster-data" value="<?php echo e(old('poster_image_data')); ?>">

                                    <div class="d-flex flex-wrap mb-3" style="gap:10px;">
                                        <button type="button" class="btn btn-soft-primary btn-sm story-poster-tab" data-target="suggested">
                                            Select a Suggested Frame
                                        </button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm story-poster-tab" data-target="video">
                                            Choose a Frame from Video
                                        </button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm story-poster-tab" data-target="upload">
                                            Upload an Image
                                        </button>
                                    </div>

                                    <div class="story-poster-panel" data-panel="suggested">
                                        <p class="text-muted mb-2">
                                            Suggested frames will appear after video upload. Select one to use as poster.
                                        </p>
                                        <div class="story-frame-empty text-muted small mb-3">Upload a video to see suggested frames.</div>
                                        <div class="d-flex align-items-center story-frame-grid" style="gap:10px; overflow-x:auto;"></div>
                                    </div>

                                    <div class="story-poster-panel story-video-panel d-none" data-panel="video">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3 mb-lg-0">
                                                <video class="w-100 rounded border story-video-player" controls muted playsinline></video>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="text-muted small mb-2 d-block">Pick a frame and use it as poster</label>
                                                <input type="range" class="form-control-range story-video-range" min="0" step="1" value="0">
                                                <button type="button" class="btn btn-soft-primary btn-sm mt-3 story-capture-btn">
                                                    Use this frame
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="story-poster-panel d-none" data-panel="upload">
                                        <div class="form-group mb-0">
                                            <label>Poster Image</label>
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
                                                    class="selected-files story-cover-input"
                                                    value="<?php echo e($coverImage); ?>"
                                                >
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="text-muted small mb-2">Selected poster preview</div>
                                        <div class="story-selected-preview text-muted small">No poster selected yet.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo $__env->make('partials.hashtag_input', [
                                    'hashtagsValue' => $hashtags,
                                    'fieldName' => 'hashtags',
                                    'labelText' => 'Hashtags'
                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="draft" <?php echo e(old('status', $item->status ?? 'draft') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                    <option value="published" <?php echo e(old('status', $item->status ?? 'draft') == 'published' ? 'selected' : ''); ?>>Published</option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">Save Story</button>
                            </div>
                        </div>
                    </div>
                </div>

        <div class="col-lg-4">
            <?php echo $__env->make('partials.showcase_preview', [
                'previewTitle' => translate('Story Preview'),
                'previewNote' => translate('Preview will appear here after you add the video and save the form.'),
                'previewType' => 'story'
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</form>

<?php echo $__env->make('partials.story_poster_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/backend/showcase/history.blade.php ENDPATH**/ ?>