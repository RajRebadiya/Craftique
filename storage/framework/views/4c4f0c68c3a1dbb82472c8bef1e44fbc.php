<?php if(get_setting('home_categories') != null): ?>
    <?php
        $home_categories = json_decode(get_setting('home_categories'));
        $categories = get_category($home_categories);
    ?>
    <?php if(count($categories) > 0): ?>
        <div class="py-4" style="background: <?php echo e(get_setting('home_categories_section_bg_color')); ?>;">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category_key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $category_name = $category->getTranslation('name');
                ?>
                <section class="py-3" style="">
                    <div class="container">
                        <div class="px-3">
                            <div class="row gutters-16 p-3 p-sm-2rem rounded-2 overflow-hidden <?php if(get_setting('home_categories_content_outline') == 1): ?> border <?php endif; ?>"
                                style="background: <?php echo e(get_setting('home_categories_content_bg_color')); ?>; border-color: <?php echo e(get_setting('home_categories_content_outline_color')); ?> !important;">
                                <!-- Home category banner & name -->
                                <div class="col-auto pl-0">
                                    <div class="h-180px w-150px size-md-200px size-lg-280px mx-auto">
                                        <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="d-block h-100 w-100 w-xl-auto hov-scale-img rounded-3 overflow-hidden home-category-banner">
                                            <span class="position-absolute h-100 w-100 overflow-hidden">
                                                <img src="<?php echo e(isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg')); ?>"
                                                    alt="<?php echo e($category_name); ?>"
                                                    class="img-fit h-100 has-transition"
                                                    onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                                            </span>
                                            <span class="home-category-name fs-15 fw-600 text-white text-center">
                                                <span class=""><?php echo e($category_name); ?></span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <!-- Category Products -->
                                <div class="col pl-0 pl-sm-3 w-100 overflow-hidden pr-0">
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5"
                                        data-xxl-items="5" data-xl-items="3.5" data-lg-items="3" data-md-items="2" data-sm-items="2"
                                        data-xs-items="1" data-arrows='true' data-infinite='false'>
                                        <?php $__currentLoopData = get_cached_products($category->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product_key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="carousel-box px-3 position-relative has-transition hov-animate-outline">
                                                <?php echo $__env->make('frontend.'.get_setting('homepage_select').'.partials.product_box_2', ['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/reclassic/partials/home_categories_section.blade.php ENDPATH**/ ?>