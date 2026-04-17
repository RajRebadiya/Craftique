<?php $__env->startSection('panel_content'); ?>
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-0"><?php echo e(translate('Storefronts')); ?></h1>
                <p class="text-muted mb-0 mt-1">
                    <?php echo e(translate('Manage your seller Storefront posts for the public Showcase feed.')); ?>

                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="<?php echo e(route('seller.showcase.vitrin.create')); ?>" class="btn btn-primary">
                    <?php echo e(translate('Add Storefront')); ?>

                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5 class="mb-0 h6"><?php echo e(translate('Storefront List')); ?></h5>
        </div>

        <div class="card-body">
            <?php if($items->count()): ?>
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo e(translate('Title')); ?></th>
                                <th><?php echo e(translate('Status')); ?></th>
                                <th><?php echo e(translate('Created')); ?></th>
                                <th class="text-right"><?php echo e(translate('Options')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $title = $item->title_gr ?: ($item->title_en ?: $item->title);
                                ?>
                                <tr>
                                    <td><?php echo e($item->id); ?></td>
                                    <td>
                                        <strong><?php echo e($title ?: '-'); ?></strong>
                                    </td>
                                    <td>
                                        <?php if($item->status === 'published'): ?>
                                            <span class="badge badge-inline badge-success"><?php echo e(translate('Published')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-inline badge-secondary"><?php echo e(translate('Draft')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i')); ?></td>
                                    <td class="text-right">
                                        <?php if($item->status === 'published'): ?>
                                            <a href="<?php echo e(route('frontend.showcase.post', ['id' => $item->id, 'slug' => \Illuminate\Support\Str::slug($title ?: $item->id)])); ?>"
                                               target="_blank"
                                               class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                               title="<?php echo e(translate('Preview')); ?>">
                                                <i class="las la-eye"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?php echo e(route('seller.showcase.vitrin.edit', $item->id)); ?>"
                                           class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                           title="<?php echo e(translate('Edit')); ?>">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <form action="<?php echo e(url('/seller/showcase/vitrin/' . $item->id . '/toggle-status')); ?>"
                                              method="POST"
                                              class="d-inline-block">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                    class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                                    title="<?php echo e($item->status === 'published' ? translate('Move to Draft') : translate('Publish')); ?>">
                                                <i class="las <?php echo e($item->status === 'published' ? 'la-eye-slash' : 'la-check-circle'); ?>"></i>
                                            </button>
                                        </form>

                                        <form action="<?php echo e(url('/seller/showcase/vitrin/' . $item->id)); ?>"
                                              method="POST"
                                              class="d-inline-block"
                                              onsubmit="return confirm('<?php echo e(translate('Delete this Storefront?')); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                                    title="<?php echo e(translate('Delete')); ?>">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="aiz-pagination mt-4">
                    <?php echo e($items->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <h5 class="mb-2"><?php echo e(translate('No Storefronts yet')); ?></h5>
                    <p class="text-muted mb-4">
                        <?php echo e(translate('Create your first Storefront to start appearing in the Showcase feed.')); ?>

                    </p>
                    <a href="<?php echo e(route('seller.showcase.vitrin.create')); ?>" class="btn btn-primary">
                        <?php echo e(translate('Add Storefront')); ?>

                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/showcase/vitrin/index.blade.php ENDPATH**/ ?>