<?php
    $value = null;
    for ($i=0; $i < $child_category->level; $i++){
        $value .= '-';
    }
?>

<li  d-item="<?php echo e($childCategory->products_count); ?>" id="preorder_<?php echo e($childCategory->id); ?>"><?php echo e($value); ?>

    <?php echo e($childCategory->getTranslation('name')); ?>

    <?php if($childCategory->products_count > 0): ?>
        <?php echo e("   (". $childCategory->products_count . ")"); ?>

    <?php endif; ?>
</li>

<?php if($child_category->childrenCategories): ?>
    <?php $__currentLoopData = $child_category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('frontend.product_listing_page_child_category_preorder', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/product_listing_page_child_category_preorder.blade.php ENDPATH**/ ?>