<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['notifications']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['notifications']); ?>
<?php foreach (array_filter((['notifications']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<div class="form-group">
    <div class="aiz-checkbox-inline ml-3">
        <label class="aiz-checkbox">
            <input type="checkbox" class="check-all">
            <span class="aiz-square-check"></span><?php echo e(translate('Select All')); ?>

        </label>
    </div>
</div>

<?php
    $notificationShowDesign = get_setting('notification_show_type');
    if($notificationShowDesign != 'only_text'){
        $notifyImageDesign = '';
        if($notificationShowDesign == 'design_2'){
            $notifyImageDesign = 'rounded-1';
        }
        elseif($notificationShowDesign == 'design_3'){
            $notifyImageDesign = 'rounded-circle';
        }
    }
?>
<?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $showNotification = true;
        if (($notification->type == 'App\Notifications\PreorderNotification') && !addon_is_activated('preorder'))
        {
            $showNotification = false;
        }
    ?>
    <?php if($showNotification): ?>
        <li class="list-group-item d-flex justify-content-between align-items- py-3">
            <div class="media text-inherit">
                <div class="media-body">
                    <?php
                        $user_type = auth()->user()->user_type;
                        $notificationType = get_notification_type($notification->notification_type_id, 'id');
                        $notifyContent = $notificationType->getTranslation('default_text');
                    ?>
                    <div class="d-flex">
                        <div class="form-group d-inline-block">
                            <label class="aiz-checkbox">
                                <input type="checkbox" class="check-one" name='id[]' value="<?php echo e($notification->id); ?>">
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>

                        <?php if($notificationShowDesign != 'only_text'): ?>
                            <div class="size-35px mr-2">
                                <img
                                    src="<?php echo e(uploaded_asset($notificationType->image)); ?>"
                                    onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/notification.png')); ?>';"
                                    class="img-fit h-100 <?php echo e($notifyImageDesign); ?>" >
                            </div>
                        <?php endif; ?>
                        <div>
                            
                            <?php if($notification->type == 'App\Notifications\OrderNotification'): ?>
                                <?php
                                    $orderCode  = $notification->data['order_code'];
                                    $orderId  = $notification->data['order_id'];
                                    $route = $user_type == 'admin' ?
                                            route('all_orders.show', encrypt($orderId)) :
                                            route('seller.orders.show', encrypt($orderId));
                                    $orderCode = "<a href='".$route."'>".$orderCode."</a>";
                                    $notifyContent = str_replace('[[order_code]]', $orderCode, $notifyContent);
                                ?>

                            
                            <?php elseif($notification->type == 'App\Notifications\ShopVerificationNotification'): ?>
                                <?php
                                    if($notification->data['status'] == 'submitted'){
                                        $route = route('sellers.show_verification_request', $notification->data['id']);
                                        $shopName = "<a href='".$route."'>".$notification->data['name']."</a>";
                                        $notifyContent = str_replace('[[shop_name]]', $shopName, $notifyContent);
                                    }
                                ?>

                            
                            <?php elseif($notification->type == 'App\Notifications\ShopProductNotification'): ?>
                                <?php
                                    $product_id     = $notification->data['id'];
                                    $product_type   = $notification->data['type'];
                                    $lang           = env('DEFAULT_LANGUAGE');
                                    $route = $user_type == 'admin'
                                        ? ( $product_type == 'physical'
                                            ? route('products.seller.edit', ['id'=>$product_id, 'lang'=>$lang])
                                            : route('digitalproducts.edit', ['id'=>$product_id, 'lang'=>$lang] ))
                                        : ( $product_type == 'physical'
                                            ? route('seller.products.edit', ['id'=>$product_id, 'lang'=>$lang])
                                            : route('seller.digitalproducts.edit',  ['id'=>$product_id, 'lang'=>$lang] ));
                                    $productName = "<a href='".$route."'>".$notification->data['name']."</a>";

                                    $notifyContent = str_replace('[[product_name]]', $productName, $notifyContent);
                                ?>

                            
                            <?php elseif($notification->type == 'App\Notifications\PayoutNotification'): ?>
                                <?php
                                    $amount = single_price($notification->data['payment_amount']);
                                    $route = $user_type == 'admin'
                                        ? ( $notification->data['status'] == 'pending' ? route('withdraw_requests_all') : route('sellers.payment_histories'))
                                        : ( $notification->data['status'] == 'pending' ? route('seller.money_withdraw_requests.index') : route('seller.payments.index'));
                                    $shopName = "<a href='".$route."'>".$notification->data['name']."</a>";

                                    $notifyContent = str_replace('[[shop_name]]', $shopName, $notifyContent);
                                    $notifyContent = str_replace('[[amount]]', $amount, $notifyContent);
                                ?>
                            <?php elseif(($notification->type == 'App\Notifications\PreorderNotification')): ?>
                                <?php
                                    $orderCode  = $notification->data['order_code'];
                                    $orderId  = $notification->data['preorder_id'];
                                    $route = $user_type == 'admin' ?
                                            route('preorder-order.show', encrypt($orderId)) :
                                            route('seller.preorder-order.show', encrypt($orderId));
                                    $orderCode = "<a href='".$route."'>".$orderCode."</a>";
                                    $notifyContent = str_replace('[[order_code]]', $orderCode, $notifyContent);
                                ?>
                            <?php endif; ?>
                            <p class="mb-1 text-truncate-2">
                                <?php echo $notifyContent; ?>

                            </p>
                            <small class="text-muted">
                                <?php echo e(date('F j Y, g:i a', strtotime($notification->created_at))); ?>

                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <li class="list-group-item">
        <div class="py-4 text-center fs-16">
            <?php echo e(translate('No notification found')); ?>

        </div>
    </li>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/components/notification.blade.php ENDPATH**/ ?>