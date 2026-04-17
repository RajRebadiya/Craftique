<?php if($last > 1): ?>
<nav>
    <ul class="pagination">
        
        <?php if($current == 1): ?>
            <li class="page-item disabled"><span class="page-link">
                 <i class="las la-angle-left fs-20 fw-600 position_middle"></i>
            </span></li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link page-btn " style="font-size: 20px;" href="#" data-page="<?php echo e($current - 1); ?>">
                     <i class="las la-angle-left fs-20 fw-600 position_middle"></i>
                </a>
            </li>
        <?php endif; ?>

        
        <?php if($current > 4): ?>
            <li class="page-item">
                <a class="page-link page-btn" href="#" data-page="1">1</a>
            </li>
            <?php if($current > 5): ?>
                <li class="page-item disabled"><span class="page-link">…</span></li>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php for($i = max(1, $current - 3); $i <= min($last, $current + 3); $i++): ?>
            <?php if($i == $current): ?>
                <li class="page-item active"><span class="page-link"><?php echo e($i); ?></span></li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link page-btn" href="#" data-page="<?php echo e($i); ?>"><?php echo e($i); ?></a>
                </li>
            <?php endif; ?>
        <?php endfor; ?>

        
        <?php if($current < $last - 3): ?>
            <?php if($current < $last - 4): ?>
                <li class="page-item disabled"><span class="page-link">…</span></li>
            <?php endif; ?>
            <li class="page-item">
                <a class="page-link page-btn" href="#" data-page="<?php echo e($last); ?>"><?php echo e($last); ?></a>
            </li>
        <?php endif; ?>

        
        <?php if($current < $last): ?>
            <li class="page-item">
                <a class="page-link page-btn" href="#" data-page="<?php echo e($current + 1); ?>">
                    <i class="las la-angle-right fs-20 fw-600 position_middle"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link">
                    <i class="las la-angle-right fs-20 fw-600 position_middle"></i>
                </span>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/product_listing_pagination.blade.php ENDPATH**/ ?>