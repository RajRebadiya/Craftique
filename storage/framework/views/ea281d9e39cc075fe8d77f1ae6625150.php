<div class="related-product-container py-20px px-30px border bg-white border-light-gray rounded-2">
    <p class="fs-20 fw-bold text-dark"><?php echo e(translate('Related Products')); ?></p>

    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="6" data-xxl-items="6"
        data-xl-items="6" data-lg-items="5" data-md-items="4" data-sm-items="4" data-xs-items="3"
        data-arrows="false" data-dots="false" data-autoplay="true" data-infinite="true">

        <!--Single-->
        <?php $__empty_1 = true; $__currentLoopData = get_related_products_by_category($detailedProduct->category_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $related_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="carousel-box">
            <div
                class="img h-90px w-90px h-sm-100px w-sm-100px h-md-150px w-md-150px h-lg-170px w-lg-170px h-xxl-190px w-xxl-190px rounded-2 overflow-hidden position-relative image-hover-effect">
                <a href="<?php echo e(route('product', $related_product->slug)); ?>" title="">
                    <img class="lazyload img-fit m-auto has-transition product-main-image"
                        src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>" data-src="<?php echo e(uploaded_asset($related_product->thumbnail_img)); ?>"
                        alt="<?php echo e($related_product->name); ?>" onerror=" this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">

                    <img class="lazyload img-fit m-auto has-transition product-main-image product-hover-image position-absolute"
                        src="<?php echo e(get_first_product_image($related_product->thumbnail, $related_product->photos)); ?>" alt="<?php echo e($related_product->name); ?>"
                        title="" onerror=" this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                </a>
            </div>
            <div class="mt-2 pr-3">
                <h3 class="fw-400 fs-13 text-truncate-2 lh-1-4 mb-1 h-35px">
                    <a href="<?php echo e(route('product', $related_product->slug)); ?>" class="text-reset hov-text-primary hov-text-primary"><?php echo e($related_product->name); ?></a>
                </h3>
                <div class="fw-700 fs-14 mb-1 mt-2">
                    <span ><?php echo e(home_discounted_base_price($related_product)); ?></span>
                    <?php if(home_base_price($related_product) != home_discounted_base_price($related_product)): ?>
                        <del
                            class="fw-700 opacity-60 ml-1"><?php echo e(home_base_price($related_product)); ?></del>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center w-100">
            <h5 class="fs-16 fw-bold text-dark"><?php echo e(translate('No related products found!')); ?></h5>
            <span>
               <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#e3e3e3"><path d="M626-533q22.5 0 38.25-15.75T680-587q0-22.5-15.75-38.25T626-641q-22.5 0-38.25 15.75T572-587q0 22.5 15.75 38.25T626-533Zm-292 0q22.5 0 38.25-15.75T388-587q0-22.5-15.75-38.25T334-641q-22.5 0-38.25 15.75T280-587q0 22.5 15.75 38.25T334-533Zm146.17 116Q413-417 358.5-379.5T278-280h53q22-42 62.17-65 40.18-23 87.5-23 47.33 0 86.83 23.5T630-280h52q-25-63-79.83-100-54.82-37-122-37ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 340q142.38 0 241.19-98.81Q820-337.63 820-480q0-142.38-98.81-241.19T480-820q-142.37 0-241.19 98.81Q140-622.38 140-480q0 142.37 98.81 241.19Q337.63-140 480-140Z"/></svg>
            </span>
        </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/product_details/related_products.blade.php ENDPATH**/ ?>