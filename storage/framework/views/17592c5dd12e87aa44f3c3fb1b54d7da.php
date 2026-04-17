<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col border-right border-bottom has-transition hov-shadow-out z-1 ">
        <?php if(isset($product_type) && $product_type == 'preorder_product'): ?>
            <?php echo $__env->make('preorder.frontend.product_box3', [
                'product' => $product,
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make(
                'frontend.product_box_for_listing_page',
                ['product' => $product]
            , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/product_listing_products.blade.php ENDPATH**/ ?>