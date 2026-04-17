<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-flex">
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start mr-2 mr-md-3 ml-0" data-toggle="aiz-mobile-nav">
            <button class="aiz-mobile-toggler">
                <span></span>
            </button>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-stretch flex-grow-xl-1">
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            <div class="d-flex justify-content-around align-items-center align-items-stretch">
                <div class="aiz-topbar-item">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-icon btn-circle btn-light" href="<?php echo e(route('home')); ?>" target="_blank" title="<?php echo e(translate('Browse Website')); ?>">
                            <i class="las la-globe"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php if(addon_is_activated('pos_system')): ?>
                <div class="d-flex justify-content-around align-items-center align-items-stretch ml-3">
                    <div class="aiz-topbar-item">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-icon btn-circle btn-light" href="<?php echo e(route('poin-of-sales.seller_index')); ?>" target="_blank" title="<?php echo e(translate('POS')); ?>">
                                <i class="las la-print"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-around align-items-center align-items-stretch">

             <!-- Notifications -->
             <div class="aiz-topbar-item mr-3">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn-icon p-0 d-flex justify-content-center align-items-center">
                            <span class="d-flex align-items-center position-relative">
                                <i class="las la-bell fs-24"></i>
                                <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                                    <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"></span>
                                <?php endif; ?>
                            </span>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-xl py-0">
                        <div class="notifications">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link text-dark active" data-toggle="tab" data-type="order" href="javascript:void(0);"
                                        data-target="#orders-notifications" role="tab" id="orders-tab"><?php echo e(translate('Orders')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="preorder" href="javascript:void(0);"
                                        data-target="#preorders-notifications" role="tab" id="preorders-tab"><?php echo e(translate('Preorders')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="seller" href="javascript:void(0);"
                                        data-target="#sellers-notifications" role="tab" id="sellers-tab"><?php echo e(translate('Products')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="seller" href="javascript:void(0);"
                                        data-target="#payouts-notifications" role="tab" id="sellers-tab"><?php echo e(translate('Payouts')); ?></a>
                                </li>
                            </ul>
                            <div class="tab-content c-scrollbar-light overflow-auto" style="height: 75vh; max-height: 400px; overflow-y: auto;">
                                <div class="tab-pane active" id="orders-notifications" role="tabpanel">
                                    <?php if (isset($component)) { $__componentOriginalef905fc8725302b31569e2c1bd20944b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef905fc8725302b31569e2c1bd20944b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.unread_notification','data' => ['notifications' => auth()->user()->unreadNotifications()->where('type', 'App\Notifications\OrderNotification')->take(20)->get()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('unread_notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['notifications' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->unreadNotifications()->where('type', 'App\Notifications\OrderNotification')->take(20)->get())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef905fc8725302b31569e2c1bd20944b)): ?>
<?php $attributes = $__attributesOriginalef905fc8725302b31569e2c1bd20944b; ?>
<?php unset($__attributesOriginalef905fc8725302b31569e2c1bd20944b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef905fc8725302b31569e2c1bd20944b)): ?>
<?php $component = $__componentOriginalef905fc8725302b31569e2c1bd20944b; ?>
<?php unset($__componentOriginalef905fc8725302b31569e2c1bd20944b); ?>
<?php endif; ?>
                                </div>
                                <div class="tab-pane" id="preorders-notifications" role="tabpanel">
                                    <?php if (isset($component)) { $__componentOriginalef905fc8725302b31569e2c1bd20944b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef905fc8725302b31569e2c1bd20944b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.unread_notification','data' => ['notifications' => auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PreorderNotification')->take(20)->get()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('unread_notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['notifications' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PreorderNotification')->take(20)->get())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef905fc8725302b31569e2c1bd20944b)): ?>
<?php $attributes = $__attributesOriginalef905fc8725302b31569e2c1bd20944b; ?>
<?php unset($__attributesOriginalef905fc8725302b31569e2c1bd20944b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef905fc8725302b31569e2c1bd20944b)): ?>
<?php $component = $__componentOriginalef905fc8725302b31569e2c1bd20944b; ?>
<?php unset($__componentOriginalef905fc8725302b31569e2c1bd20944b); ?>
<?php endif; ?>
                                </div>
                                <div class="tab-pane" id="sellers-notifications" role="tabpanel">
                                    <?php if (isset($component)) { $__componentOriginalef905fc8725302b31569e2c1bd20944b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef905fc8725302b31569e2c1bd20944b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.unread_notification','data' => ['notifications' => auth()->user()->unreadNotifications()->where('type', 'like', '%shop%')->take(20)->get()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('unread_notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['notifications' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->unreadNotifications()->where('type', 'like', '%shop%')->take(20)->get())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef905fc8725302b31569e2c1bd20944b)): ?>
<?php $attributes = $__attributesOriginalef905fc8725302b31569e2c1bd20944b; ?>
<?php unset($__attributesOriginalef905fc8725302b31569e2c1bd20944b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef905fc8725302b31569e2c1bd20944b)): ?>
<?php $component = $__componentOriginalef905fc8725302b31569e2c1bd20944b; ?>
<?php unset($__componentOriginalef905fc8725302b31569e2c1bd20944b); ?>
<?php endif; ?>
                                </div>
                                <div class="tab-pane" id="payouts-notifications" role="tabpanel">
                                    <?php if (isset($component)) { $__componentOriginalef905fc8725302b31569e2c1bd20944b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef905fc8725302b31569e2c1bd20944b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.unread_notification','data' => ['notifications' => auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PayoutNotification')->take(20)->get()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('unread_notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['notifications' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PayoutNotification')->take(20)->get())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef905fc8725302b31569e2c1bd20944b)): ?>
<?php $attributes = $__attributesOriginalef905fc8725302b31569e2c1bd20944b; ?>
<?php unset($__attributesOriginalef905fc8725302b31569e2c1bd20944b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef905fc8725302b31569e2c1bd20944b)): ?>
<?php $component = $__componentOriginalef905fc8725302b31569e2c1bd20944b; ?>
<?php unset($__componentOriginalef905fc8725302b31569e2c1bd20944b); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-center border-top">
                            <a href="<?php echo e(route('seller.all-notification')); ?>" class="text-reset d-block py-2">
                                <?php echo e(translate('View All Notifications')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php
                if(Session::has('locale')){
                    $locale = Session::get('locale', Config::get('app.locale'));
                }
                else{
                    $locale = env('DEFAULT_LANGUAGE');
                }
            ?>
            <div class="aiz-topbar-item ml-2">
                <div class="align-items-stretch d-flex dropdown " id="lang-change">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn-icon">
                            <img src="<?php echo e(static_asset('assets/img/flags/'.$locale.'.png')); ?>" height="11">
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-xs">

                        <?php $__currentLoopData = \App\Models\Language::where('status', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="javascript:void(0)" data-flag="<?php echo e($language->code); ?>" class="dropdown-item <?php if($locale == $language->code): ?> active <?php endif; ?>">
                                    <img src="<?php echo e(static_asset('assets/img/flags/'.$language->code.'.png')); ?>" class="mr-2">
                                    <span class="language"><?php echo e($language->name); ?></span>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>

            <div class="aiz-topbar-item ml-2">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="avatar avatar-sm mr-md-2">
                                <img
                                    src="<?php echo e(uploaded_asset(Auth::user()->avatar_original)); ?>"
                                    onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/avatar-place.png')); ?>';"
                                >
                            </span>
                            <span class="d-none d-md-block">
                                <span class="d-block fw-500"><?php echo e(Auth::user()->name); ?></span>
                                <span class="d-block small opacity-60"><?php echo e(Auth::user()->user_type); ?></span>
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                        <a href="<?php echo e(route('seller.profile.index')); ?>" class="dropdown-item">
                            <i class="las la-user-circle"></i>
                            <span><?php echo e(translate('Profile')); ?></span>
                        </a>

                        <a href="<?php echo e(route('logout')); ?>" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i>
                            <span><?php echo e(translate('Logout')); ?></span>
                        </a>
                    </div>
                </div>
            </div><!-- .aiz-topbar-item -->
        </div>
    </div>
</div><!-- .aiz-topbar -->
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/inc/seller_nav.blade.php ENDPATH**/ ?>