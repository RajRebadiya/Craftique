<section class="mt-2 mt-md-3 py-3 py-md-4" style="background: <?php echo e(get_setting('auction_section_bg_color')); ?>;">
    <div class="container my-2 my-md-3">
        <div class="p-3 p-md-2rem rounded-2 <?php if(get_setting('auction_section_outline') == 1): ?> border <?php endif; ?>" style="background: <?php echo e(get_setting('auction_content_bg_color')); ?>; border-color: <?php echo e(get_setting('auction_section_outline_color')); ?> !important;">
            <!-- Top Section -->
            <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                    <span class=""><?php echo e(translate('Auction Products')); ?></span>
                </h3>
                <!-- Links -->
                <div class="d-flex">
                    <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary" href="<?php echo e(route('auction_products.all')); ?>"><?php echo e(translate('View All Products')); ?></a>
                </div>
            </div>
            <!-- Products Section -->
            <div class="row gutters-16">
                <div class="col-xl-4 col-lg-6 mb-3 mb-lg-0">
                    <div class="h-100 w-100 overflow-hidden">
                        <a href="<?php echo e(route('auction_products.all')); ?>" class="hov-scale-img">
                            <img class="img-fit lazyload mx-auto h-400px h-lg-485px has-transition"
                                src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                                data-src="<?php echo e(uploaded_asset(get_setting('auction_banner_image', null, get_system_language()->code))); ?>"
                                alt="<?php echo e(env('APP_NAME')); ?> promo"
                                onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                        </a>
                    </div>
                </div>
                <?php
                    $products = get_auction_products(6, null);
                ?>
                <div class="col-xl-8 col-lg-6">
                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="2" data-xxl-items="2" data-xl-items="2" data-lg-items="1" data-md-items="2" data-sm-items="1" data-xs-items="1"  data-arrows="true" data-dots="false">
                        <?php
                            $init = 0 ;
                            $end = 2 ;
                        ?>
                        <?php for($i = 0; $i < 2; $i++): ?>
                            <div class="carousel-box border-top <?php if($i >= 1): ?> border-right <?php endif; ?> border-transparent">
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($key >= $init && $key <= $end): ?>
                                        <div class="position-relative has-transition hov-animate-outline border-left border-bottom border-transparent">
                                            <div class="row align-items-center hov-scale-img">
                                                <div class="col-auto py-10px">
                                                    <a href="<?php echo e(route('auction-product', $product->slug)); ?>" class="d-block pl-1">
                                                        <span class="overflow-hidden size-100px size-sm-120px size-md-140px d-flex align-items-center">
                                                            <img class="w-100 mh-100 lazyload has-transition"
                                                            src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                                                            data-src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>"
                                                            alt="<?php echo e($product->getTranslation('name')); ?>"
                                                            onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="col py-3">
                                                    <div class="mb-2 d-none d-md-block pr-1">
                                                        <h3 class="fw-400 fs-14 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                            <a href="<?php echo e(route('auction-product', $product->slug)); ?>" class="d-block text-reset hov-text-primary"><?php echo e($product->getTranslation('name')); ?></a>
                                                        </h3>
                                                    </div>

                                                    <div class="fs-14">
                                                        <span class="text-secondary"><?php echo e(translate('Starting Bid')); ?></span><br>
                                                        <span class="fw-700 text-primary"><?php echo e(single_price($product->starting_bid)); ?></span>
                                                    </div>
                                                    <?php
                                                        $highest_bid = $product->bids->max('amount');
                                                        $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $product->starting_bid;
                                                    ?>
                                                    <button class="btn btn-soft-primary btn-sm rounded-2 py-1 mt-2" onclick="bid_single_modal(<?php echo e($product->id); ?>, <?php echo e($min_bid_amount); ?>)"><?php echo e(translate('Place Bid')); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $init += 3;
                                    $end += 3;
                                ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/auction/frontend/reclassic/auction_products_section.blade.php ENDPATH**/ ?>