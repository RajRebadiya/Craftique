

<?php $__env->startSection('panel_content'); ?>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6"><?php echo e(translate('Notifications')); ?></h5>
        </div>
        <div class="col-md-3 text-right">
            <div class="btn-group mb-2">
                <button type="button" class="btn py-0" data-toggle="dropdown" aria-expanded="false">
                    <i class="las la-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button onclick="bulk_notification_delete()" class="dropdown-item"><?php echo e(translate('Delete Selection')); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <?php if (isset($component)) { $__componentOriginal0d8d3c14ebd2b92d484be47e6c018839 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification','data' => ['notifications' => $notifications,'isLinkable' => true,'isDeletable' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['notifications' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($notifications),'is_linkable' => true,'is_deletable' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839)): ?>
<?php $attributes = $__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839; ?>
<?php unset($__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d8d3c14ebd2b92d484be47e6c018839)): ?>
<?php $component = $__componentOriginal0d8d3c14ebd2b92d484be47e6c018839; ?>
<?php unset($__componentOriginal0d8d3c14ebd2b92d484be47e6c018839); ?>
<?php endif; ?>
        </ul>

        <?php echo e($notifications->links()); ?>

        
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            $('.check-one:checkbox').prop('checked', this.checked);
        });

        function bulk_notification_delete() {
            let notificationIds = [];
            $(".check-one[name='id[]']:checked").each(function() {
                notificationIds.push($(this).val());
            });
            $.post('<?php echo e(route('seller.notifications.bulk_delete')); ?>', {_token:'<?php echo e(csrf_token()); ?>', notification_ids:notificationIds}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Notification Deleted successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
                location.reload();
            });
        }
    </script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('seller.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/notification/index.blade.php ENDPATH**/ ?>