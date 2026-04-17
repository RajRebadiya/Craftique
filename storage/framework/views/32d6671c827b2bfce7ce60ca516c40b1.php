        <?php
            $newest_preorder_products = \App\Models\PreorderProduct::where('is_published', 1)
    ->where(function ($query) {
        $query->whereHas('user', function ($q) {
            $q->where('user_type', 'admin');
        })->orWhereHas('user.shop', function ($q) {
            $q->where('verification_status', 1);
        });
    })
    ->latest()
    ->limit(12)
    ->get();

        ?>
        <?php if(count($newest_preorder_products) > 0): ?>
            <section class="py-1 mt-4" style="">
                <div class="container">
                    <div class="border">
                        <!-- Top Section -->
                        <div class="d-flex p-3 p-sm-4 align-items-baseline justify-content-between">
                            <!-- Title -->
                            <h3 class="fs-16 fs-md-20 fw-700 mb-0">
                                <span class=""><?php echo e(translate('Newest Preorder Products')); ?></span>
                            </h3>
                            <!-- Links -->
                            <div class="d-flex">
                                <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                    href="<?php echo e(route('all_preorder_products')); ?>"><?php echo e(translate('View All Products')); ?></a>
                            </div>
                        </div>
                        <div class="d-sm-flex bg-white">
                            <!-- Banner -->
                            <?php
                                $newest_preorder_banner_image = get_setting('newest_preorder_banner_image', null, $lang);
                            ?>
                            <div class="px-3 px-sm-4">
                                <div class="w-sm-270px h-320px mx-auto">
                                    <a href="<?php echo e(route('all_preorder_products')); ?>" class="d-block w-100 w-xl-auto hov-scale-img overflow-hidden">
                                        <img src="<?php echo e(uploaded_asset($newest_preorder_banner_image)); ?>"
                                            alt="<?php echo e(translate('Newest Preorder Products')); ?>"
                                            class="img-fit h-100 has-transition"
                                            onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                                    </a>
                                </div>
                            </div>
                            <!-- Products -->
                            <div class="px-0 px-sm-4 w-100 overflow-hidden">
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none bg-white pt-3" data-items="5"
                                    data-xxl-items="5" data-xl-items="3.5" data-lg-items="3" data-md-items="2" data-sm-items="1"
                                    data-xs-items="2" data-arrows='true' data-infinite='false'>
                                    <?php $__currentLoopData = $newest_preorder_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $__env->make('preorder.frontend.product_box2',['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/preorder/frontend/home_page/newest_preorder.blade.php ENDPATH**/ ?>