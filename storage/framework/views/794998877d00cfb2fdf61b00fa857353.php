<?php $__env->startSection('content'); ?>
<?php
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
?>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3"><?php echo e($page_title); ?></h1>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0 pl-3">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('showcase.launch.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <input type="hidden" name="id" value="<?php echo e(old('id', $item->id ?? '')); ?>">

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
                                <li>Main visual is the primary asset.</li>
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

                            <div class="form-group">
                                <label>Title (GR)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="title_gr"
                                    placeholder="Enter title in Greek"
                                    value="<?php echo e($titleGr); ?>"
                                >
                                <?php $__errorArgs = ['title_gr'];
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

                            <div class="form-group">
                                <label>Tagline (GR)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="subtitle_gr"
                                    placeholder="Tagline in Greek"
                                    value="<?php echo e($subtitleGr); ?>"
                                >
                            </div>

                            <div class="form-group mb-0">
                                <label>Description (GR)</label>
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    name="description_gr"
                                    placeholder="Launch description in Greek..."
                                ><?php echo e($descriptionGr); ?></textarea>
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
                                    value="<?php echo e($titleEn); ?>"
                                >
                            </div>

                            <div class="form-group">
                                <label>Tagline (EN)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="subtitle_en"
                                    placeholder="Tagline in English"
                                    value="<?php echo e($subtitleEn); ?>"
                                >
                            </div>

                            <div class="form-group mb-0">
                                <label>Description (EN)</label>
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    name="description_en"
                                    placeholder="Launch description in English..."
                                ><?php echo e($descriptionEn); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Hashtags</h5>
                        </div>
                        <div class="card-body">
                            <?php echo $__env->make('partials.hashtag_input', [
                                'hashtagsValue' => $hashtags,
                                'fieldName' => 'hashtags',
                                'labelText' => 'Hashtags'
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                        value="<?php echo e($mainVisual); ?>"
                                    >
                                </div>
                                <div class="file-preview box sm"></div>
                                <?php $__errorArgs = ['main_visual'];
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
                                        value="<?php echo e($coverImage); ?>"
                                    >
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>

                            <div class="form-group mb-0">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="draft" <?php echo e($status == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                    <option value="published" <?php echo e($status == 'published' ? 'selected' : ''); ?>>Published</option>
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
                                <button type="submit" class="btn btn-primary">Save Launch</button>
                            </div>
                        </div>
                    </div>
                </div>

        <div class="col-lg-4">
            <?php echo $__env->make('partials.showcase_preview', [
                'previewTitle' => translate('Launch Preview'),
                'previewNote' => translate('Preview will appear here after you add the media and save the form.'),
                'previewType' => 'launch'
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php echo $__env->make('backend.showcase._products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php $__errorArgs = ['product_ids'];
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
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/backend/showcase/launch.blade.php ENDPATH**/ ?>