<?php
    $value = null;
    for ($i=0; $i < $child_category->level; $i++){
        $value .= '-';
    }
?>
<li id="<?php echo e($childCategory->id); ?>"><?php echo e($value); ?><?php echo e($childCategory->getTranslation('name')); ?></li>
<?php if($child_category->childrenCategories): ?>
    <?php $__currentLoopData = $child_category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('backend.product.products.child_category', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/backend/product/products/child_category.blade.php ENDPATH**/ ?>