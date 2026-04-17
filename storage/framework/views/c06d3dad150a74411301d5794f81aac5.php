<?php $__env->startSection('content'); ?>
    <?php
        $typeLabels = [
            'history' => translate('Story'),
            'collection' => translate('Collection'),
            'vitrin' => translate('Storefront'),
            'launch' => translate('Launch'),
        ];

        $currentType = $filters['type'] ?? 'all';
        $currentSort = $filters['sort'] ?? 'newest';

        $feedAction = !empty($isBrandPage) && !empty($shop?->slug)
            ? route('frontend.showcase.brand', $shop->slug)
            : route('frontend.showcase.index');
    ?>

    <section class="mb-4">
        <div class="container">
            <div class="bg-white border rounded shadow-sm p-4 p-lg-5">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <h1 class="h3 fw-700 mb-2"><?php echo e($pageTitle ?? translate('Showcase')); ?></h1>
                        <?php if(!empty($pageSubtitle)): ?>
                            <p class="text-muted mb-0"><?php echo e($pageSubtitle); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-4 text-lg-right">
                        <?php if(!empty($isBrandPage) && !empty($shop?->slug)): ?>
                            <a href="<?php echo e(route('frontend.showcase.index')); ?>" class="btn btn-soft-secondary">
                                <?php echo e(translate('View All Showcase')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="<?php echo e($feedAction); ?>">
                        <div class="row align-items-end">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label fw-600"><?php echo e(translate('Type')); ?></label>
                                <select name="type" class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity">
                                    <option value="all" <?php echo e($currentType === 'all' ? 'selected' : ''); ?>><?php echo e(translate('All')); ?></option>
                                    <option value="history" <?php echo e($currentType === 'history' ? 'selected' : ''); ?>><?php echo e(translate('Story')); ?></option>
                                    <option value="collection" <?php echo e($currentType === 'collection' ? 'selected' : ''); ?>><?php echo e(translate('Collection')); ?></option>
                                    <option value="vitrin" <?php echo e($currentType === 'vitrin' ? 'selected' : ''); ?>><?php echo e(translate('Storefront')); ?></option>
                                    <option value="launch" <?php echo e($currentType === 'launch' ? 'selected' : ''); ?>><?php echo e(translate('Launch')); ?></option>
                                </select>
                            </div>

                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label fw-600"><?php echo e(translate('Sort')); ?></label>
                                <select name="sort" class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity">
                                    <option value="newest" <?php echo e($currentSort === 'newest' ? 'selected' : ''); ?>><?php echo e(translate('Newest First')); ?></option>
                                    <option value="oldest" <?php echo e($currentSort === 'oldest' ? 'selected' : ''); ?>><?php echo e(translate('Oldest First')); ?></option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <?php echo e(translate('Apply')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <div class="container">
            <?php if(!empty($showcaseItems) && $showcaseItems->count()): ?>
                <div class="row">
                    <?php $__currentLoopData = $showcaseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $typeLabel = $typeLabels[$item->type] ?? ucfirst($item->type);

                            $rawVisual = $item->type === 'history'
                                ? (($item->main_visual ?? null) ?: ($item->cover_image ?? null))
                                : (($item->type === 'vitrin' || $item->type === 'launch')
                                    ? (($item->main_visual ?? null) ?: ($item->cover_image ?? null))
                                    : (($item->cover_image ?? null) ?: ($item->main_visual ?? null)));

                            $visualUrl = null;
                            $visualIsVideo = false;

                            if (!empty($rawVisual)) {
                                if (is_numeric($rawVisual)) {
                                    $visualUrl = uploaded_asset($rawVisual);
                                } elseif (filter_var($rawVisual, FILTER_VALIDATE_URL)) {
                                    $visualUrl = $rawVisual;
                                } else {
                                    $visualUrl = asset($rawVisual);
                                }

                                $extension = strtolower(pathinfo(parse_url($visualUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                                $visualIsVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);
                            }

                            $postUrl = route('frontend.showcase.post', [
                                'id' => $item->id,
                                'slug' => \Illuminate\Support\Str::slug($item->title ?: $item->id),
                            ]);

                            $brandUrl = !empty($item->seller_slug)
                                ? route('frontend.showcase.brand', $item->seller_slug)
                                : null;

                            $summaryText = $item->description ?: ($item->intro ?: ($item->subtitle ?: ''));
                        ?>

                        <div class="col-md-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                <a href="<?php echo e($postUrl); ?>" class="d-block text-reset">
                                    <?php if($visualUrl): ?>
                                        <?php if($visualIsVideo): ?>
                                            <div class="position-relative bg-dark" style="height:240px;">
                                                <video muted playsinline class="w-100 h-100" style="object-fit:cover;">
                                                    <source src="<?php echo e($visualUrl); ?>">
                                                </video>
                                                <div class="position-absolute" style="top:12px; right:12px;">
                                                    <span class="badge badge-inline badge-dark"><?php echo e(translate('Video')); ?></span>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <img src="<?php echo e($visualUrl); ?>"
                                                 alt="<?php echo e($item->title); ?>"
                                                 class="w-100"
                                                 style="height:240px; object-fit:cover;">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                                             style="height:240px;">
                                            <div>
                                                <div class="fw-700 mb-1"><?php echo e($typeLabel); ?></div>
                                                <div class="fs-13"><?php echo e(translate('No preview media')); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="mb-2 d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                                        <span class="badge badge-inline badge-soft-primary"><?php echo e($typeLabel); ?></span>

                                        <?php if(!empty($item->created_at)): ?>
                                            <span class="text-muted fs-12">
                                                <?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="h6 fw-700 mb-2">
                                        <a href="<?php echo e($postUrl); ?>" class="text-reset">
                                            <?php echo e(\Illuminate\Support\Str::limit($item->title ?: '-', 80)); ?>

                                        </a>
                                    </h3>

                                    <?php if(!empty($summaryText)): ?>
                                        <p class="text-muted mb-3 flex-grow-1">
                                            <?php echo e(\Illuminate\Support\Str::limit(strip_tags($summaryText), 120)); ?>

                                        </p>
                                    <?php else: ?>
                                        <div class="flex-grow-1"></div>
                                    <?php endif; ?>

                                    <?php if(!empty($item->hashtags)): ?>
                                        <?php
                                            $showcaseTags = array_filter(array_map('trim', explode(',', $item->hashtags)));
                                        ?>
                                        <div class="mb-3">
                                            <?php $__currentLoopData = $showcaseTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge badge-inline badge-soft-secondary mr-1 mb-1">#<?php echo e($tag); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto" style="gap:10px;">
                                        <div class="text-muted fs-13">
                                            <?php if(!empty($item->seller_name)): ?>
                                                <?php echo e(translate('By')); ?>:
                                                <?php if($brandUrl): ?>
                                                    <a href="<?php echo e($brandUrl); ?>" class="fw-600 text-reset">
                                                        <?php echo e($item->seller_name); ?>

                                                    </a>
                                                <?php else: ?>
                                                    <span class="fw-600"><?php echo e($item->seller_name); ?></span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>

                                        <a href="<?php echo e($postUrl); ?>" class="btn btn-soft-primary btn-sm">
                                            <?php echo e(translate('View')); ?>

                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="aiz-pagination">
                    <?php echo e($showcaseItems->links()); ?>

                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <h3 class="h5 fw-700 mb-2"><?php echo e(translate('No showcase posts found')); ?></h3>
                        <p class="text-muted mb-0">
                            <?php echo e(translate('There are no published Showcase items for the selected filters yet.')); ?>

                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/showcase/index.blade.php ENDPATH**/ ?>