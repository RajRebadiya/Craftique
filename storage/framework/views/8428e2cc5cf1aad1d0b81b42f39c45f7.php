<div
    class="frequently-bought-container py-20px px-30px border bg-white border-light-gray rounded-2">
    <?php
        $shopslug = $detailedProduct->user->shop
            ? $detailedProduct->user->shop->slug
            : 'in-house';
    ?>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <p class="fs-20 fw-bold text-dark m-0"><?php echo e(translate('Products from this Seller')); ?></p>
        <a type="button"
            class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center"
            href="<?php echo e(route('same_seller_products', $shopslug)); ?>">
            <span><i class="las la-angle-right fs-20 fw-600"></i></span>
            <span class="fs-12 mr-2 text"><?php echo e(translate('View All')); ?></span>
        </a>
    </div>

    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="6" data-xxl-items="6"
        data-xl-items="6" data-lg-items="5" data-md-items="4" data-sm-items="4"
        data-xs-items="3" data-arrows="false" data-dots="false" data-autoplay="true"
        data-infinite="true">

        <!--Single-->
        <?php $__empty_1 = true; $__currentLoopData = get_same_seller_products($detailedProduct->user_id , 20); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $same_seller_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="carousel-box">
            <div
                class="img h-90px w-90px h-sm-100px w-sm-100px h-md-150px w-md-150px h-lg-170px w-lg-170px h-xxl-190px w-xxl-190px rounded-2 overflow-hidden position-relative image-hover-effect">
                <a href="<?php echo e(route('product', $same_seller_product->slug)); ?>" title="<?php echo e($same_seller_product->getTranslation('name')); ?>">
                    <img class="lazyload img-fit m-auto has-transition product-main-image"
                        src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>" data-src="<?php echo e(uploaded_asset($same_seller_product->thumbnail_img)); ?>"
                        alt="<?php echo e($same_seller_product->getTranslation('name')); ?>" onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">

                    <img class="lazyload img-fit m-auto has-transition product-main-image product-hover-image position-absolute"
                        src="<?php echo e(get_first_product_image($same_seller_product->thumbnail, $same_seller_product->photos)); ?>" alt="<?php echo e($same_seller_product->getTranslation('name')); ?>"
                        title="<?php echo e($same_seller_product->getTranslation('name')); ?>" onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                </a>
            </div>
            <div class="mt-2 pr-3">
                <h3 class="fw-400 fs-13 text-truncate-1 lh-1-4 mb-1">
                    <a href="<?php echo e(route('product', $same_seller_product->slug)); ?>" class="text-reset hov-text-primary hov-text-primary"><?php echo e($same_seller_product->getTranslation('name')); ?></a>
                </h3>
                <div class="fw-700 fs-14 mb-1 mt-2">
                     <span ><?php echo e(home_discounted_base_price($same_seller_product)); ?></span>
                    <?php if(home_base_price($same_seller_product) != home_discounted_base_price($same_seller_product)): ?>
                        <del
                            class="fw-700 opacity-60 ml-1"><?php echo e(home_base_price($same_seller_product)); ?></del>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-2 w-100">
            <h5 class="fs-16 fw-bold text-dark"><?php echo e(translate('No products from this seller found!')); ?></h5>
        </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/product_details/from_this_seller_products.blade.php ENDPATH**/ ?>