<?php
    $best_selling_products = get_best_selling_products(20);
    $best_selling_section_bg = get_setting('best_selling_section_bg_color');
?>
<?php if(get_setting('best_selling') == 1 && count($best_selling_products) > 0): ?>
    <section class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            <div class="p-3 p-md-2rem rounded-2 <?php if(get_setting('best_selling_section_outline') == 1): ?> border <?php endif; ?>" style="background: <?php echo e($best_selling_section_bg != null ? $best_selling_section_bg : '#ffffff'); ?>; border-color: <?php echo e(get_setting('best_selling_section_outline_color')); ?> !important; padding-bottom: 0 !important;">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span class=""><?php echo e(translate('Best Selling')); ?></span>
                    </h3>
                    <!-- Links -->
                    <div class="d-flex">
                        <a type="button" class="arrow-prev slide-arrow link-disable text-secondary mr-2" onclick="clickToSlide('slick-prev','section_best_selling')"><i class="las la-angle-left fs-20 fw-600"></i></a>
                        <a type="button" class="arrow-next slide-arrow text-secondary ml-2" onclick="clickToSlide('slick-next','section_best_selling')"><i class="las la-angle-right fs-20 fw-600"></i></a>
                    </div>
                </div>
                <!-- Product Section -->
                <div class="px-sm-3">
                    <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='false'>
                        <?php $__currentLoopData = $best_selling_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="carousel-box position-relative px-3 has-transition hov-animate-outline">
                            <?php echo $__env->make('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/reclassic/partials/best_selling_section.blade.php ENDPATH**/ ?>