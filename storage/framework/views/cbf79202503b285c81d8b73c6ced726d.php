<?php if(get_setting('product_query_activation') == 1): ?>
    <div class="product-queries-container py-20px px-30px border bg-white border-light-gray rounded-2">
        <p class="fs-20 fw-bold text-dark"><?php echo e(translate(' Product Queries ')); ?> (<?php echo e(count($detailedProduct->product_queries)); ?>)</p>
        <div class="mb-2 bg-white has-transition">
            <!-- Login & Register -->
            <?php if(auth()->guard()->guest()): ?>
                <p class="fs-14 fw-400 mb-0 mt-3"><a
                        href="<?php echo e(route('user.login')); ?>"><?php echo e(translate('Login')); ?></a> 
                        <span class="text-lowercase"><?php echo e(translate('or')); ?></span>
                         <a class="mr-1"
                        href="<?php echo e(route('user.registration')); ?>"><?php echo e(translate('Register ')); ?></a><?php echo e(translate(' to submit your questions to seller')); ?>

                        
                </p>
            <?php endif; ?>

            <!-- Query Submit -->

            
           <?php if(auth()->guard()->check()): ?>
                <div class="query product-queries form px-3 py-3 py-sm-2 border border-light-gray rounded-2 mt-3 has-transition">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="<?php echo e(route('product-queries.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product" value="<?php echo e($detailedProduct->id); ?>">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-9 col-lg-10">
                                <textarea class="form-control border-0 px-0" id="product-queries" rows="3" name="question"
                                    placeholder="Write your question here . . . "></textarea>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                                <input type="submit" value="Submit"
                                    class="bg-orange text-white hov-opacity-80 has-transition text-center fs-14 fw-bold w-100 py-2 rounded-1 border-0 ">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Own Queries -->
                <?php
                    $own_product_queries = $detailedProduct->product_queries->where('customer_id', Auth::id());
                ?>
                <?php if($own_product_queries->count() > 0): ?>
                <div class="mt-4">
                    <h5 class="fs-16 fw-bold text-dark"><?php echo e(translate('My Questions')); ?></h5>
                    <div class="d-flex flex-column pt-20px other-question">
                        <!--Single Question-->
                        <?php $__currentLoopData = $own_product_queries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product_query): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-start single-question">
                            <span class="flex-shrink-0 d-block mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                    viewBox="0 0 24 36">
                                    <g id="Group_23928" data-name="Group 23928"
                                        transform="translate(-654 -2397)">
                                        <path id="Path_28707" data-name="Path 28707" d="M0,0H24V24H0Z"
                                            transform="translate(654 2397)" fill="#363636" />
                                        <text id="Q" transform="translate(666 2414)" fill="#fff"
                                            font-size="14" font-family="SegoeUI-Bold, Segoe UI"
                                            font-weight="700">
                                            <tspan x="-5.308" y="0"><?php echo e(translate('Q')); ?></tspan>
                                        </text>
                                        <path id="Path_28708" data-name="Path 28708" d="M0,0H12L0,12Z"
                                            transform="translate(666 2421)" />
                                    </g>
                                </svg>
                            </span>
                            <div>
                                <p class="fs-14 text-dark fw-400 mb-1 p-0"><?php echo e(strip_tags($product_query->question)); ?></p>
                                <span class="fs-12 text-gray fw-400"><?php echo e($product_query->user->name); ?></span>
                            </div>
                        </div>

                        <!--Single Answer-->
                        <div class="d-flex align-items-start single-question">
                            <span class="flex-shrink-0 d-block mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                    viewBox="0 0 24 36">
                                    <g id="Group_23929" data-name="Group 23929"
                                        transform="translate(-654 -2453)">
                                        <path id="Path_28709" data-name="Path 28709" d="M0,0H24V24H0Z"
                                            transform="translate(654 2453)" fill="#1592e6" />
                                        <text id="A" transform="translate(666 2470)" fill="#fff"
                                            font-size="14" font-family="SegoeUI-Bold, Segoe UI"
                                            font-weight="700">
                                            <tspan x="-4.922" y="0"><?php echo e(translate('A')); ?></tspan>
                                        </text>
                                        <path id="Path_28710" data-name="Path 28710" d="M0,0H12L0,12Z"
                                            transform="translate(666 2477)" fill="#0266cc" />
                                    </g>
                                </svg>
                            </span>
                            <?php

                                if($detailedProduct->added_by == 'seller'){
                                   $product_shop_name= $detailedProduct->user->shop->name;
                                }else{
                                     $product_shop_name= get_setting('site_name');
                                }
                            
                                
                            ?>
                            <div>
                                <p class="fs-14 text-dark fw-400 mb-1 p-0">
                                    <?php if($product_query->reply): ?>
                                        <?php echo e(strip_tags($product_query->reply)); ?>

                                    <?php else: ?>
                                        <span class="text-gray"><?php echo e(translate('Seller did not respond yet')); ?></span>
                                    <?php endif; ?>
                                </p>

                                <span class="fs-12 text-gray fw-400"><?php echo e($product_shop_name); ?></span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
           

            <div class="mt-4 queries-area">
                 <?php echo $__env->make('frontend.partials.product_query_pagination', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/product_details/product_queries.blade.php ENDPATH**/ ?>