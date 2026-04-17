<?php $__env->startSection('content'); ?>
    <?php
        $typeLabelMap = [
            'history' => translate('Story'),
            'collection' => translate('Collection'),
            'vitrin' => translate('Storefront'),
            'launch' => translate('Launch'),
        ];

        $itemTypeLabel = $typeLabelMap[$item->type] ?? ucfirst($item->type);

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

        $itemSubtitle = $item->subtitle ?? null;
        $itemIntro = $item->intro ?? null;
        $itemDescription = $item->description ?? null;
        $itemHashtags = !empty($item->hashtags) ? array_filter(array_map('trim', explode(',', $item->hashtags))) : [];

        $sellerBrandUrl = !empty($item->seller_slug)
            ? route('frontend.showcase.brand', $item->seller_slug)
            : null;

        $hasCollectionItems = $item->type === 'collection'
            && !empty($collectionItems)
            && $collectionItems->count();
    ?>

    <section class="mb-4">
        <div class="container">
            <div class="bg-white border rounded shadow-sm p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <div class="mb-2">
                            <span class="badge badge-inline badge-soft-primary"><?php echo e($itemTypeLabel); ?></span>
                        </div>

                        <h1 class="h3 fw-700 mb-2"><?php echo e($item->title ?: '-'); ?></h1>

                        <?php if(!empty($itemSubtitle)): ?>
                            <p class="text-muted mb-2"><?php echo e($itemSubtitle); ?></p>
                        <?php endif; ?>

                        <div class="d-flex flex-wrap align-items-center" style="gap:12px;">
                            <?php if(!empty($item->seller_name)): ?>
                                <span class="text-muted">
                                    <?php echo e(translate('By')); ?>:
                                    <?php if($sellerBrandUrl): ?>
                                        <a href="<?php echo e($sellerBrandUrl); ?>" class="fw-600 text-reset">
                                            <?php echo e($item->seller_name); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="fw-600"><?php echo e($item->seller_name); ?></span>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>

                            <?php if(!empty($item->created_at)): ?>
                                <span class="text-muted">
                                    <?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-4 text-lg-right">
                        <a href="<?php echo e(route('frontend.showcase.index')); ?>" class="btn btn-soft-secondary">
                            <?php echo e(translate('Back to Showcase')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="card border-0 shadow-sm overflow-hidden">
                <?php if($visualUrl): ?>
                    <?php if($visualIsVideo): ?>
                        <video controls playsinline class="w-100" style="max-height:680px; background:#000;">
                            <source src="<?php echo e($visualUrl); ?>">
                        </video>
                    <?php else: ?>
                        <img src="<?php echo e($visualUrl); ?>"
                             alt="<?php echo e($item->title); ?>"
                             class="w-100"
                             style="max-height:680px; object-fit:cover;">
                    <?php endif; ?>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                         style="min-height:320px;">
                        <div>
                            <div class="fs-20 fw-700 mb-1"><?php echo e($itemTypeLabel); ?></div>
                            <div class="fs-13"><?php echo e(translate('No preview media available')); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <?php if(!empty($itemIntro)): ?>
                        <div class="mb-4">
                            <h3 class="h5 fw-700 mb-2"><?php echo e(translate('Intro')); ?></h3>
                            <div class="text-secondary">
                                <?php echo nl2br(e($itemIntro)); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($itemDescription)): ?>
                        <div>
                            <h3 class="h5 fw-700 mb-2"><?php echo e(translate('Description')); ?></h3>
                            <div class="text-secondary">
                                <?php echo nl2br(e($itemDescription)); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($itemHashtags)): ?>
                        <div class="mt-4">
                            <h3 class="h5 fw-700 mb-2"><?php echo e(translate('Hashtags')); ?></h3>
                            <div class="d-flex flex-wrap" style="gap:8px;">
                                <?php $__currentLoopData = $itemHashtags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge badge-inline badge-soft-secondary">#<?php echo e($tag); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if($hasCollectionItems): ?>
        <section class="mb-4">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                    <div>
                        <h3 class="h4 fw-700 mb-1"><?php echo e(translate('Linked Products')); ?></h3>
                        <p class="text-muted mb-0"><?php echo e(translate('Browse the products linked to this post.')); ?></p>
                    </div>
                </div>

                <div class="row">
                    <?php $__currentLoopData = $collectionItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collectionItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $cardVisual = $collectionItem->cover_image ?? null;
                            $cardVisualUrl = null;

                            if (!empty($cardVisual)) {
                                if (is_numeric($cardVisual)) {
                                    $cardVisualUrl = uploaded_asset($cardVisual);
                                } elseif (filter_var($cardVisual, FILTER_VALIDATE_URL)) {
                                    $cardVisualUrl = $cardVisual;
                                } else {
                                    $cardVisualUrl = asset($cardVisual);
                                }
                            }

                            $linkedProduct = $collectionItem->product ?? null;
                            $linkedProductUrl = (!empty($linkedProduct) && !empty($linkedProduct->slug))
                                ? url('/product/' . $linkedProduct->slug)
                                : null;

                            $linkedProductThumb = null;
                            if (!empty($linkedProduct) && !empty($linkedProduct->thumbnail_img)) {
                                if (is_numeric($linkedProduct->thumbnail_img)) {
                                    $linkedProductThumb = uploaded_asset($linkedProduct->thumbnail_img);
                                } elseif (filter_var($linkedProduct->thumbnail_img, FILTER_VALIDATE_URL)) {
                                    $linkedProductThumb = $linkedProduct->thumbnail_img;
                                } else {
                                    $linkedProductThumb = asset($linkedProduct->thumbnail_img);
                                }
                            }
                        ?>

                        <div class="col-md-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                <?php if($cardVisualUrl): ?>
                                    <img src="<?php echo e($cardVisualUrl); ?>"
                                         alt="<?php echo e($collectionItem->title ?: translate('Collection Card')); ?>"
                                         class="w-100"
                                         style="height:240px; object-fit:cover;">
                                <?php elseif($linkedProductThumb): ?>
                                    <img src="<?php echo e($linkedProductThumb); ?>"
                                         alt="<?php echo e($linkedProduct->name); ?>"
                                         class="w-100"
                                         style="height:240px; object-fit:cover;">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                                         style="height:240px;">
                                        <div><?php echo e(translate('No image')); ?></div>
                                    </div>
                                <?php endif; ?>

                                <div class="card-body p-3 d-flex flex-column">
                                    <?php if(!empty($collectionItem->title)): ?>
                                        <h4 class="h6 fw-700 mb-2"><?php echo e($collectionItem->title); ?></h4>
                                    <?php endif; ?>

                                    <?php if(!empty($collectionItem->description)): ?>
                                        <p class="text-muted mb-3 flex-grow-1">
                                            <?php echo nl2br(e($collectionItem->description)); ?>

                                        </p>
                                    <?php else: ?>
                                        <div class="flex-grow-1"></div>
                                    <?php endif; ?>

                                    <?php if(!empty($linkedProduct)): ?>
                                        <div class="border-top pt-3 mt-auto">
                                            <div class="fs-13 text-muted mb-1"><?php echo e(translate('Linked Product')); ?></div>

                                            <?php if($linkedProductUrl): ?>
                                                <a href="<?php echo e($linkedProductUrl); ?>" class="fw-600 text-reset d-inline-block mb-2">
                                                    <?php echo e($linkedProduct->name); ?>

                                                </a>
                                            <?php else: ?>
                                                <div class="fw-600 mb-2"><?php echo e($linkedProduct->name); ?></div>
                                            <?php endif; ?>

                                            <?php
                                                $finalPrice = $linkedProduct->unit_price;
                                                if (!empty($linkedProduct->discount) && !empty($linkedProduct->discount_type)) {
                                                    if ($linkedProduct->discount_type === 'percent') {
                                                        $finalPrice = $linkedProduct->unit_price - (($linkedProduct->unit_price * $linkedProduct->discount) / 100);
                                                    } elseif ($linkedProduct->discount_type === 'amount') {
                                                        $finalPrice = $linkedProduct->unit_price - $linkedProduct->discount;
                                                    }
                                                }

                                                $finalPrice = max(0, $finalPrice);
                                            ?>

                                            <div class="fs-15 fw-700 text-primary">
                                                <?php echo e(single_price($finalPrice)); ?>

                                            </div>

                                            <?php if($linkedProductUrl): ?>
                                                <div class="mt-2">
                                                    <a href="<?php echo e($linkedProductUrl); ?>" class="btn btn-soft-primary btn-sm">
                                                        <?php echo e(translate('View Product')); ?>

                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if((!$hasCollectionItems) && !empty($products) && $products->count()): ?>
        <section class="mb-4">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                    <div>
                        <h3 class="h4 fw-700 mb-1"><?php echo e(translate('Related Products')); ?></h3>
                        <p class="text-muted mb-0"><?php echo e(translate('Products linked with this showcase post.')); ?></p>
                    </div>
                </div>

                <div class="row">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $productThumb = null;

                            if (!empty($product->thumbnail_img)) {
                                if (is_numeric($product->thumbnail_img)) {
                                    $productThumb = uploaded_asset($product->thumbnail_img);
                                } elseif (filter_var($product->thumbnail_img, FILTER_VALIDATE_URL)) {
                                    $productThumb = $product->thumbnail_img;
                                } else {
                                    $productThumb = asset($product->thumbnail_img);
                                }
                            }

                            $productUrl = !empty($product->slug)
                                ? url('/product/' . $product->slug)
                                : '#';

                            $finalPrice = $product->unit_price;
                            if (!empty($product->discount) && !empty($product->discount_type)) {
                                if ($product->discount_type === 'percent') {
                                    $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                                } elseif ($product->discount_type === 'amount') {
                                    $finalPrice = $product->unit_price - $product->discount;
                                }
                            }

                            $finalPrice = max(0, $finalPrice);
                        ?>

                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                <a href="<?php echo e($productUrl); ?>" class="d-block">
                                    <?php if($productThumb): ?>
                                        <img src="<?php echo e($productThumb); ?>"
                                             alt="<?php echo e($product->name); ?>"
                                             class="w-100"
                                             style="height:220px; object-fit:cover;">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center"
                                             style="height:220px;">
                                            <div><?php echo e(translate('No image')); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                <div class="card-body p-3">
                                    <h4 class="h6 fw-600 mb-2">
                                        <a href="<?php echo e($productUrl); ?>" class="text-reset">
                                            <?php echo e(\Illuminate\Support\Str::limit($product->name, 65)); ?>

                                        </a>
                                    </h4>

                                    <div class="fs-15 fw-700 text-primary mb-0">
                                        <?php echo e(single_price($finalPrice)); ?>

                                    </div>

                                    <?php if((float) $finalPrice !== (float) $product->unit_price): ?>
                                        <div class="fs-13 text-muted">
                                            <del><?php echo e(single_price($product->unit_price)); ?></del>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if(!empty($previousPost) || !empty($nextPost)): ?>
        <section class="mb-5">
            <div class="container">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                            <div>
                                <?php if(!empty($previousPost)): ?>
                                    <a href="<?php echo e(route('frontend.showcase.post', ['id' => $previousPost->id, 'slug' => \Illuminate\Support\Str::slug($previousPost->title ?: $previousPost->id)])); ?>"
                                       class="btn btn-soft-secondary">
                                        <i class="las la-arrow-left mr-1"></i>
                                        <?php echo e(translate('Previous')); ?>

                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="text-center text-muted fw-600">
                                <?php echo e(translate('Navigate Showcase Posts')); ?>

                            </div>

                            <div class="text-right">
                                <?php if(!empty($nextPost)): ?>
                                    <a href="<?php echo e(route('frontend.showcase.post', ['id' => $nextPost->id, 'slug' => \Illuminate\Support\Str::slug($nextPost->title ?: $nextPost->id)])); ?>"
                                       class="btn btn-soft-secondary">
                                        <?php echo e(translate('Next')); ?>

                                        <i class="las la-arrow-right ml-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/showcase/post.blade.php ENDPATH**/ ?>