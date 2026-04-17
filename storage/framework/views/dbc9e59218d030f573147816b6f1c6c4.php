<?php
    $homepageShowcaseItems = \Illuminate\Support\Facades\DB::table('showcases')
        ->leftJoin('shops', 'showcases.seller_id', '=', 'shops.id')
        ->where('showcases.status', 'published')
        ->orderByDesc('showcases.created_at')
        ->select(
            'showcases.*',
            'shops.name as seller_name',
            'shops.slug as seller_slug'
        )
        ->limit(6)
        ->get();

    $locale = app()->getLocale();

    $homepageShowcaseItems = $homepageShowcaseItems->map(function ($item) use ($locale) {
        $item->title = $locale === 'en'
            ? ($item->title_en ?: $item->title_gr ?: $item->title)
            : ($item->title_gr ?: $item->title_en ?: $item->title);

        $item->subtitle = $locale === 'en'
            ? (($item->subtitle_en ?? null) ?: ($item->subtitle_gr ?? null) ?: ($item->subtitle ?? null))
            : (($item->subtitle_gr ?? null) ?: ($item->subtitle_en ?? null) ?: ($item->subtitle ?? null));

        $item->intro = $locale === 'en'
            ? (($item->intro_en ?? null) ?: ($item->intro_gr ?? null) ?: ($item->intro ?? null))
            : (($item->intro_gr ?? null) ?: ($item->intro_en ?? null) ?: ($item->intro ?? null));

        $item->description = $locale === 'en'
            ? ($item->description_en ?: $item->description_gr ?: $item->description)
            : ($item->description_gr ?: $item->description_en ?: $item->description);

        return $item;
    });

    $typeLabelMap = [
        'history' => translate('Story'),
        'collection' => translate('Collection'),
        'vitrin' => translate('Storefront'),
        'launch' => translate('Launch'),
    ];
?>

<?php if($homepageShowcaseItems->count()): ?>
    <section class="mb-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                <div>
                    <h3 class="h4 fw-700 mb-1"><?php echo e(translate('Showcase')); ?></h3>
                    <p class="text-muted mb-0">
                        <?php echo e(translate('Latest Stories, Collections, Storefronts and Launches from creators.')); ?>

                    </p>
                </div>

                <div>
                    <a href="<?php echo e(route('frontend.showcase.index')); ?>" class="btn btn-soft-primary btn-sm">
                        <?php echo e(translate('View All')); ?>

                    </a>
                </div>
            </div>

            <div class="row">
                <?php $__currentLoopData = $homepageShowcaseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $itemTitle = $item->title ?: '-';
                        $itemTypeLabel = $typeLabelMap[$item->type] ?? ucfirst($item->type);
                        $itemText = $item->description ?: ($item->intro ?: ($item->subtitle ?? null));

                        $rawVisual = $item->type === 'history'
                            ? (($item->main_visual ?? null) ?: ($item->cover_image ?? null))
                            : (($item->type === 'vitrin')
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
                            'slug' => \Illuminate\Support\Str::slug($itemTitle ?: $item->id)
                        ]);

                        $sellerBrandUrl = !empty($item->seller_slug)
                            ? route('frontend.showcase.brand', $item->seller_slug)
                            : null;
                    ?>

                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            <?php if($visualUrl && !$visualIsVideo): ?>
                                <a href="<?php echo e($postUrl); ?>" class="d-block">
                                    <img src="<?php echo e($visualUrl); ?>"
                                         alt="<?php echo e($itemTitle); ?>"
                                         class="w-100"
                                         style="height:240px; object-fit:cover;">
                                </a>
                            <?php elseif($visualIsVideo): ?>
                                <a href="<?php echo e($postUrl); ?>"
                                   class="d-flex align-items-center justify-content-center bg-dark text-white text-center"
                                   style="height:240px; text-decoration:none;">
                                    <div>
                                        <div class="fs-17 fw-700 mb-1"><?php echo e($itemTypeLabel); ?></div>
                                        <div class="fs-13"><?php echo e(translate('Video Preview')); ?></div>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e($postUrl); ?>"
                                   class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                                   style="height:240px; text-decoration:none;">
                                    <div>
                                        <div class="fs-17 fw-700 mb-1"><?php echo e($itemTypeLabel); ?></div>
                                        <div class="fs-13"><?php echo e(translate('No preview image')); ?></div>
                                    </div>
                                </a>
                            <?php endif; ?>

                            <div class="card-body p-3 d-flex flex-column">
                                <div class="mb-2 d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                                    <span class="badge badge-inline badge-soft-primary">
                                        <?php echo e($itemTypeLabel); ?>

                                    </span>

                                    <?php if(!empty($item->created_at)): ?>
                                        <span class="text-muted fs-12">
                                            <?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                <h4 class="h6 fw-700 mb-2">
                                    <a href="<?php echo e($postUrl); ?>" class="text-reset">
                                        <?php echo e(\Illuminate\Support\Str::limit($itemTitle, 60)); ?>

                                    </a>
                                </h4>

                                <?php if(!empty($itemText)): ?>
                                    <p class="text-muted mb-3 flex-grow-1">
                                        <?php echo e(\Illuminate\Support\Str::limit(strip_tags($itemText), 90)); ?>

                                    </p>
                                <?php else: ?>
                                    <div class="flex-grow-1"></div>
                                <?php endif; ?>

                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto" style="gap:10px;">
                                    <div class="text-muted fs-12">
                                        <?php if(!empty($item->seller_name)): ?>
                                            <?php if($sellerBrandUrl): ?>
                                                <a href="<?php echo e($sellerBrandUrl); ?>" class="text-reset fw-600">
                                                    <?php echo e($item->seller_name); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="fw-600"><?php echo e($item->seller_name); ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <a href="<?php echo e($postUrl); ?>" class="btn btn-soft-primary btn-sm">
                                        <?php echo e(translate('View Post')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/reclassic/partials/showcase_home_section.blade.php ENDPATH**/ ?>