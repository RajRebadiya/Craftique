<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3"><?php echo e($page_title); ?></h1>
        </div>
        <div class="col-md-6 text-md-right">
            <?php if($section === 'history'): ?>
                <a href="<?php echo e(route('frontend.showcase.history')); ?>" target="_blank" class="btn btn-soft-info mr-2">Preview Public Page</a>
                <a href="<?php echo e(route('showcase.history')); ?>" class="btn btn-primary">Add New Story</a>
            <?php elseif($section === 'collection'): ?>
                <a href="<?php echo e(route('frontend.showcase.collection')); ?>" target="_blank" class="btn btn-soft-info mr-2">Preview Public Page</a>
                <a href="<?php echo e(route('showcase.collection')); ?>" class="btn btn-primary">Add New Collection</a>
            <?php elseif($section === 'vitrin'): ?>
                <a href="<?php echo e(route('frontend.showcase.vitrin')); ?>" target="_blank" class="btn btn-soft-info mr-2">Preview Public Page</a>
                <a href="<?php echo e(route('showcase.vitrin')); ?>" class="btn btn-primary">Add New Storefront</a>
            <?php elseif($section === 'launch'): ?>
                <a href="<?php echo e(route('frontend.showcase.launch')); ?>" target="_blank" class="btn btn-soft-info mr-2">Preview Public Page</a>
                <a href="<?php echo e(route('showcase.launch')); ?>" class="btn btn-primary">Add New Launch</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><?php echo e($page_title); ?></h5>
    </div>

            <div class="card-body">
                <?php if($items->count()): ?>
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="80">ID</th>
                                    <th width="90">Image</th>
                                    <th>Title</th>
                                    <th width="140">Status</th>
                                    <th width="180">Created</th>
                                    <th width="340" class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $imageValue = null;

                                        if ($section === 'history' || $section === 'collection') {
                                            $imageValue = $item->cover_image;
                                        } elseif ($section === 'vitrin') {
                                            $imageValue = $item->main_visual;
                                        } elseif ($section === 'launch') {
                                            $imageValue = $item->cover_image ?: $item->main_visual;
                                        }

                                        $imageUrl = null;
                                        if (!empty($imageValue)) {
                                            $imageUrl = is_numeric($imageValue) ? uploaded_asset($imageValue) : $imageValue;
                                        }
                                    ?>

                                    <tr>
                                        <td><?php echo e($item->id); ?></td>

                                        <td>
                                            <?php if($imageUrl): ?>
                                                <a href="<?php echo e($imageUrl); ?>" target="_blank">
                                                    <img src="<?php echo e($imageUrl); ?>"
                                                         alt="<?php echo e($item->title); ?>"
                                                         style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb;">
                                                </a>
                                            <?php else: ?>
                                                <div style="width:60px; height:60px; border-radius:8px; border:1px dashed #d1d5db; display:flex; align-items:center; justify-content:center; font-size:11px; color:#9ca3af;">
                                                    No Image
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <td><?php echo e($item->title); ?></td>

                                        <td>
                                            <?php if($item->status === 'published'): ?>
                                                <span class="badge badge-inline badge-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge badge-inline badge-secondary">Draft</span>
                                            <?php endif; ?>
                                        </td>

                                        <td><?php echo e($item->created_at); ?></td>

                                        <td class="text-right">
                                            <?php if($section === 'history'): ?>
                                                <a href="<?php echo e(route('showcase.history.edit', $item->id)); ?>" class="btn btn-soft-primary btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <a href="<?php echo e(route('showcase.history.status', $item->id)); ?>" class="btn btn-soft-warning btn-sm mr-1">
                                                    <?php echo e($item->status === 'published' ? 'Set Draft' : 'Publish'); ?>

                                                </a>
                                                <?php if($imageUrl): ?>
                                                    <a href="<?php echo e($imageUrl); ?>" target="_blank" class="btn btn-soft-info btn-sm mr-1">
                                                        Preview
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('showcase.history.delete', $item->id)); ?>"
                                                   class="btn btn-soft-danger btn-sm"
                                                   onclick="return confirm('Delete this story item?');">
                                                    Delete
                                                </a>
                                            <?php elseif($section === 'collection'): ?>
                                                <a href="<?php echo e(route('showcase.collection.edit', $item->id)); ?>" class="btn btn-soft-primary btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <a href="<?php echo e(route('showcase.collection.status', $item->id)); ?>" class="btn btn-soft-warning btn-sm mr-1">
                                                    <?php echo e($item->status === 'published' ? 'Set Draft' : 'Publish'); ?>

                                                </a>
                                                <?php if($imageUrl): ?>
                                                    <a href="<?php echo e($imageUrl); ?>" target="_blank" class="btn btn-soft-info btn-sm mr-1">
                                                        Preview
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('showcase.collection.delete', $item->id)); ?>"
                                                   class="btn btn-soft-danger btn-sm"
                                                   onclick="return confirm('Delete this collection item?');">
                                                    Delete
                                                </a>
                                            <?php elseif($section === 'vitrin'): ?>
                                                <a href="<?php echo e(route('showcase.vitrin.edit', $item->id)); ?>" class="btn btn-soft-primary btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <a href="<?php echo e(route('showcase.vitrin.status', $item->id)); ?>" class="btn btn-soft-warning btn-sm mr-1">
                                                    <?php echo e($item->status === 'published' ? 'Set Draft' : 'Publish'); ?>

                                                </a>
                                                <?php if($imageUrl): ?>
                                                    <a href="<?php echo e($imageUrl); ?>" target="_blank" class="btn btn-soft-info btn-sm mr-1">
                                                        Preview
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('showcase.vitrin.delete', $item->id)); ?>"
                                                   class="btn btn-soft-danger btn-sm"
                                                   onclick="return confirm('Delete this storefront item?');">
                                                    Delete
                                                </a>
                                            <?php elseif($section === 'launch'): ?>
                                                <a href="<?php echo e(route('showcase.launch.edit', $item->id)); ?>" class="btn btn-soft-primary btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <a href="<?php echo e(route('showcase.launch.status', $item->id)); ?>" class="btn btn-soft-warning btn-sm mr-1">
                                                    <?php echo e($item->status === 'published' ? 'Set Draft' : 'Publish'); ?>

                                                </a>
                                                <?php if($imageUrl): ?>
                                                    <a href="<?php echo e($imageUrl); ?>" target="_blank" class="btn btn-soft-info btn-sm mr-1">
                                                        Preview
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('showcase.launch.delete', $item->id)); ?>"
                                                   class="btn btn-soft-danger btn-sm"
                                                   onclick="return confirm('Delete this launch item?');">
                                                    Delete
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <h5 class="mb-2">No items found</h5>
                        <p class="text-muted mb-0">Create your first <?php echo e($section); ?> entry.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/backend/showcase/list.blade.php ENDPATH**/ ?>