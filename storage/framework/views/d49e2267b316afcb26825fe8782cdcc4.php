<?php $__env->startSection('panel_content'); ?>
<?php
    $packageName = optional($shop->seller_package)->name;
    $packageValidUntil = !empty($shop->package_invalid_at)
        ? \Carbon\Carbon::parse($shop->package_invalid_at)->format('d/m/Y')
        : null;

    $stats = $stats ?? [
        'history_total' => 0,
        'collection_total' => 0,
        'vitrin_total' => 0,
        'launch_total' => 0,
        'published_total' => 0,
        'draft_total' => 0,
        'all_total' => 0,
    ];

    $recentItems = $recentItems ?? collect();

    $showcaseLimit = $showcaseLimit ?? null;
    $showcaseUsed = $showcaseUsed ?? 0;
    $showcaseRemaining = $showcaseRemaining ?? null;
    $limitReached = $limitReached ?? false;

    $formatDurationLabel = function ($days) {
        return max(0, (int) $days) . ' ' . translate('Days');
    };

    $formatShowcaseLimitLabel = function ($value) {
        if ($value === '' || $value === null) {
            return translate('Unlimited');
        }

        $value = max(0, (int) $value);

        if ($value === 0) {
            return translate('No Showcase Posts');
        }

        return $value;
    };

?>

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3"><?php echo e(translate('Showcase Center')); ?></h1>
        </div>
    </div>
</div>

<?php if(!$hasActivePackage): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h2 class="h5 fw-700 mb-2"><?php echo e(translate('Showcase requires an active seller package')); ?></h2>
            <p class="text-muted mb-3">
                <?php echo e(translate('To use the Showcase center, you need an active seller subscription/package first.')); ?>

            </p>

            <a href="<?php echo e(route('seller.seller_packages_list')); ?>" class="btn btn-primary">
                <?php echo e(translate('View Seller Packages')); ?>

            </a>
        </div>
    </div>

    <?php if($packages->count()): ?>
        <div class="row">
            <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h5 fw-700 mb-2"><?php echo e($package->name); ?></h3>

                            <div class="fs-18 fw-700 text-primary mb-3">
                                <?php echo e(single_price($package->amount)); ?>

                            </div>

                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <strong><?php echo e(translate('Validity')); ?>:</strong>
                                    <?php echo e($formatDurationLabel($package->duration)); ?>

                                </li>
                                <li class="mb-2">
                                    <strong><?php echo e(translate('Showcase Limit')); ?>:</strong>
                                    <?php echo e($formatShowcaseLimitLabel($package->showcase_post_limit)); ?>

                                </li>
                            </ul>

                            <a href="<?php echo e(route('seller.seller_packages_list')); ?>" class="btn btn-soft-primary">
                                <?php echo e(translate('Open Packages Page')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="h5 fw-700 mb-2"><?php echo e(translate('Your Showcase center is active')); ?></h2>
                    <p class="text-muted mb-1">
                        <?php echo e(translate('Active package')); ?>:
                        <strong><?php echo e($packageName ?: translate('Assigned Package')); ?></strong>
                        <?php if($packageValidUntil): ?>
                            — <?php echo e(translate('Valid until')); ?>: <strong><?php echo e($packageValidUntil); ?></strong>
                        <?php endif; ?>
                    </p>

                    <?php if(!is_null($daysRemaining)): ?>
                        <p class="text-muted mb-0">
                            <?php echo e(translate('Days remaining')); ?>:
                            <strong><?php echo e($daysRemaining); ?></strong>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                    <a href="<?php echo e(route('seller.seller_packages_list')); ?>" class="btn btn-soft-secondary">
                        <?php echo e(translate('Manage Packages')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row gutters-16 mb-4">
        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1"><?php echo e(translate('All Posts')); ?></div>
                    <div class="fs-24 fw-700"><?php echo e($stats['all_total']); ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1"><?php echo e(translate('Stories')); ?></div>
                    <div class="fs-24 fw-700"><?php echo e($stats['history_total']); ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1"><?php echo e(translate('Collections')); ?></div>
                    <div class="fs-24 fw-700"><?php echo e($stats['collection_total']); ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1"><?php echo e(translate('Storefronts')); ?></div>
                    <div class="fs-24 fw-700"><?php echo e($stats['vitrin_total']); ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1"><?php echo e(translate('Launches')); ?></div>
                    <div class="fs-24 fw-700"><?php echo e($stats['launch_total']); ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1"><?php echo e(translate('Published')); ?></div>
                    <div class="fs-24 fw-700 text-success"><?php echo e($stats['published_total']); ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted fs-12 mb-1"><?php echo e(translate('Drafts')); ?></div>
                    <div class="fs-24 fw-700 text-warning"><?php echo e($stats['draft_total']); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="h5 fw-700 mb-2"><?php echo e(translate('Showcase Package Usage')); ?></h3>

                    <?php if($showcaseLimit === null): ?>
                        <p class="text-muted mb-0">
                            <?php echo e(translate('Your package allows unlimited showcase posts.')); ?>

                            <?php echo e(translate('Used')); ?>: <strong><?php echo e($showcaseUsed); ?></strong>
                        </p>
                    <?php else: ?>
                        <p class="text-muted mb-0">
                            <?php echo e(translate('Limit')); ?>: <strong><?php echo e($showcaseLimit); ?></strong>
                            — <?php echo e(translate('Used')); ?>: <strong><?php echo e($showcaseUsed); ?></strong>
                            — <?php echo e(translate('Remaining')); ?>: <strong><?php echo e($showcaseRemaining); ?></strong>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                    <?php if($limitReached): ?>
                        <span class="badge badge-inline badge-danger fs-13">
                            <?php echo e(translate('Limit Reached')); ?>

                        </span>
                    <?php elseif($showcaseLimit !== null): ?>
                        <span class="badge badge-inline badge-success fs-13">
                            <?php echo e(translate('Quota Available')); ?>

                        </span>
                    <?php else: ?>
                        <span class="badge badge-inline badge-info fs-13">
                            <?php echo e(translate('Unlimited Showcase')); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 fw-700 mb-2"><?php echo e(translate('Story')); ?></h3>
                    <p class="text-muted mb-4">
                        <?php echo e(translate('Create and manage seller story posts for the public Showcase feed.')); ?>

                    </p>

                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <a href="<?php echo e(route('seller.showcase.history.index')); ?>" class="btn btn-primary">
                            <?php echo e(translate('Manage Stories')); ?>

                        </a>

                        <?php if($limitReached): ?>
                            <button type="button" class="btn btn-soft-secondary" disabled>
                                <?php echo e(translate('Limit Reached')); ?>

                            </button>
                        <?php else: ?>
                            <a href="<?php echo e(route('seller.showcase.history.create')); ?>" class="btn btn-soft-primary">
                                <?php echo e(translate('Add Story')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 fw-700 mb-2"><?php echo e(translate('Collection')); ?></h3>
                    <p class="text-muted mb-4">
                        <?php echo e(translate('Create and manage seller collection posts for the public Showcase feed.')); ?>

                    </p>

                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <a href="<?php echo e(route('seller.showcase.collection.index')); ?>" class="btn btn-primary">
                            <?php echo e(translate('Manage Collections')); ?>

                        </a>

                        <?php if($limitReached): ?>
                            <button type="button" class="btn btn-soft-secondary" disabled>
                                <?php echo e(translate('Limit Reached')); ?>

                            </button>
                        <?php else: ?>
                            <a href="<?php echo e(route('seller.showcase.collection.create')); ?>" class="btn btn-soft-primary">
                                <?php echo e(translate('Add Collection')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 fw-700 mb-2"><?php echo e(translate('Storefront')); ?></h3>
                    <p class="text-muted mb-4">
                        <?php echo e(translate('Create and manage seller storefront posts for the public Showcase feed.')); ?>

                    </p>

                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <a href="<?php echo e(route('seller.showcase.vitrin.index')); ?>" class="btn btn-primary">
                            <?php echo e(translate('Manage Storefronts')); ?>

                        </a>

                        <?php if($limitReached): ?>
                            <button type="button" class="btn btn-soft-secondary" disabled>
                                <?php echo e(translate('Limit Reached')); ?>

                            </button>
                        <?php else: ?>
                            <a href="<?php echo e(route('seller.showcase.vitrin.create')); ?>" class="btn btn-soft-primary">
                                <?php echo e(translate('Add Storefront')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 fw-700 mb-2"><?php echo e(translate('Launch')); ?></h3>
                    <p class="text-muted mb-4">
                        <?php echo e(translate('Create and manage seller launch posts for the public Showcase feed.')); ?>

                    </p>

                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <a href="<?php echo e(route('seller.showcase.launch.index')); ?>" class="btn btn-primary">
                            <?php echo e(translate('Manage Launches')); ?>

                        </a>

                        <?php if($limitReached): ?>
                            <button type="button" class="btn btn-soft-secondary" disabled>
                                <?php echo e(translate('Limit Reached')); ?>

                            </button>
                        <?php else: ?>
                            <a href="<?php echo e(route('seller.showcase.launch.create')); ?>" class="btn btn-soft-primary">
                                <?php echo e(translate('Add Launch')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0 h6"><?php echo e(translate('Recent Showcase Activity')); ?></h5>
        </div>
        <div class="card-body">
            <?php if($recentItems->count()): ?>
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo e(translate('Type')); ?></th>
                                <th><?php echo e(translate('Title')); ?></th>
                                <th><?php echo e(translate('Status')); ?></th>
                                <th><?php echo e(translate('Created')); ?></th>
                                <th class="text-right"><?php echo e(translate('Options')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $title = $item->title_gr ?: ($item->title_en ?: $item->title);

                                    $editRoute = null;
                                    $typeLabel = ucfirst($item->type);

                                    if ($item->type === 'history') {
                                        $editRoute = route('seller.showcase.history.edit', $item->id);
                                        $typeLabel = translate('Story');
                                    } elseif ($item->type === 'collection') {
                                        $editRoute = route('seller.showcase.collection.edit', $item->id);
                                        $typeLabel = translate('Collection');
                                    } elseif ($item->type === 'vitrin') {
                                        $editRoute = route('seller.showcase.vitrin.edit', $item->id);
                                        $typeLabel = translate('Storefront');
                                    } elseif ($item->type === 'launch') {
                                        $editRoute = route('seller.showcase.launch.edit', $item->id);
                                        $typeLabel = translate('Launch');
                                    }
                                ?>
                                <tr>
                                    <td><?php echo e($item->id); ?></td>
                                    <td><?php echo e($typeLabel); ?></td>
                                    <td><strong><?php echo e($title ?: '-'); ?></strong></td>
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

                                        <?php if($editRoute): ?>
                                            <a href="<?php echo e($editRoute); ?>"
                                               class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                               title="<?php echo e(translate('Edit')); ?>">
                                                <i class="las la-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <h6 class="mb-2"><?php echo e(translate('No showcase activity yet')); ?></h6>
                    <p class="text-muted mb-0"><?php echo e(translate('Start by creating your first Story, Collection, Storefront or Launch.')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h3 class="h5 fw-700 mb-3"><?php echo e(translate('Public preview shortcuts')); ?></h3>

            <div class="d-flex flex-wrap" style="gap: 12px;">
                <a href="<?php echo e(route('frontend.showcase.index')); ?>" target="_blank" class="btn btn-soft-primary">
                    <?php echo e(translate('Open Showcase Feed')); ?>

                </a>

                <?php if(!empty($shop->slug)): ?>
                    <a href="<?php echo e(route('frontend.showcase.brand', $shop->slug)); ?>" target="_blank" class="btn btn-soft-info">
                        <?php echo e(translate('Open My Brand Showcase')); ?>

                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/showcase/index.blade.php ENDPATH**/ ?>