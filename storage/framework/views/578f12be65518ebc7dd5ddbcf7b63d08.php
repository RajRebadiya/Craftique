<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <div class="d-block text-center my-3">
                <?php if(optional(Auth::user()->shop)->logo != null): ?>
                    <img class="mw-100 mb-3" src="<?php echo e(uploaded_asset(optional(Auth::user()->shop)->logo)); ?>"
                        class="brand-icon" alt="<?php echo e(get_setting('site_name')); ?>">
                <?php else: ?>
                    <img class="mw-100 mb-3" src="<?php echo e(uploaded_asset(get_setting('header_logo'))); ?>" class="brand-icon"
                        alt="<?php echo e(get_setting('site_name')); ?>">
                <?php endif; ?>
                <h3 class="fs-16  m-0 text-primary"><?php echo e(optional(Auth::user()->shop)->name); ?></h3>
                <p class="text-primary"><?php echo e(Auth::user()->email); ?></p>
            </div>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm" type="text" name=""
                    placeholder="<?php echo e(translate('Search in menu')); ?>" id="menu-search" onkeyup="menuSearch()">
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('seller.dashboard')); ?>" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Dashboard')); ?></span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="javascript:void(0);" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.showcase.index', 'seller.showcase.history.index', 'seller.showcase.collection.index', 'seller.showcase.vitrin.index', 'seller.showcase.launch.index', 'seller.blog.index', 'seller.blog.create', 'seller.blog.edit'])); ?>">
                        <i class="las la-store-alt aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Showcase')); ?></span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.showcase.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.showcase.index'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Showcase Center')); ?></span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.showcase.history.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.showcase.history.index', 'seller.showcase.history.create', 'seller.showcase.history.edit'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Story')); ?></span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.showcase.collection.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.showcase.collection.index', 'seller.showcase.collection.create', 'seller.showcase.collection.edit'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Collections')); ?></span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.showcase.vitrin.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.showcase.vitrin.index', 'seller.showcase.vitrin.create', 'seller.showcase.vitrin.edit'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Storefront')); ?></span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.showcase.launch.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.showcase.launch.index', 'seller.showcase.launch.create', 'seller.showcase.launch.edit'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Launch')); ?></span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.blog.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.blog.index', 'seller.blog.create', 'seller.blog.edit'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Write')); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Products')); ?></span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.products')); ?>"
                                class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.products', 'seller.products.create', 'seller.products.edit'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Products')); ?></span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.categories_wise_product_discount')); ?>"
                                class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Category-Wise Discount')); ?></span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.product_bulk_upload.index')); ?>"
                                class="aiz-side-nav-link <?php echo e(areActiveRoutes(['product_bulk_upload.index'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Product Bulk Upload')); ?></span>
                            </a>
                        </li>
                        <?php if(get_setting('digital_product_manage_by_seller') == 1): ?>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.digitalproducts')); ?>"
                                class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.digitalproducts', 'seller.digitalproducts.create', 'seller.digitalproducts.edit'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Digital Products')); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.product-reviews')); ?>"
                                class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.product-reviews', 'seller.detail-reviews'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Product Reviews')); ?></span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.custom_label.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.custom_label.edit', 'seller.custom_label.create'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Custom Label')); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php if(addon_is_activated('preorder') && (get_setting('seller_preorder_product') == 1)): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-clock aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Preorder')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.preorder.dashboard')); ?>"
                                    class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Dashboard')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.preorder-product.create')); ?>"
                                    class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Add New Preorder Product')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.preorder-product.index')); ?>"
                                    class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.preorder-product.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Preorder Products')); ?></span>
                                </a>
                            </li>

                            <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Orders (Preorder)')); ?></span>
                                    <span class="aiz-side-nav-arrow"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('seller.all_preorder.list')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.preorder-order.show'])); ?> }}">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('All Orders')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('seller.delayed_prepayment_preorders.list')); ?>" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Delayed Prepayment Orders')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('seller.delayed_final_orders.list')); ?>" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Delayed Final Orders')); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.preorder-settings')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate("Preorder Settings")); ?></span>
                                </a>
                            </li>

                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.preorder-commission-history')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate("Preorder Commission History")); ?></span>
                                </a>
                            </li>

                            <?php if(get_setting('conversation_system') == 1): ?>
                                <li class="aiz-side-nav-item">
                                    <?php
                                        $preorderConversation = get_non_viewed_preorder_conversations();
                                    ?>    
                                    <a href="<?php echo e(route('seller.preorder-conversations.index')); ?>"
                                        class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.preorder-conversations.index','seller.preorder-conversations.show'])); ?>">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Product Conversations')); ?></span>
                                        <?php if($preorderConversation > 0): ?>
                                            <span class="badge badge-danger">(<?php echo e($preorderConversation); ?>)</span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(get_setting('product_query_activation') == 1): ?>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('seller.preorder_product_query.index')); ?>"
                                        class="aiz-side-nav-link <?php echo e(areActiveRoutes(['preorder_product_query.index','preorder_product_query.show'])); ?>">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Product Queries')); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.preorder_product_reviews')); ?>"
                                    class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.preorder_product_detail_reviews'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Product Reviews')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="16.001" viewBox="0 0 16 16.001">
                                <path id="Union_64" data-name="Union 64" d="M.333,16A.315.315,0,0,1,0,15.668V.335A.315.315,0,0,1,.333,0h9.31a.285.285,0,0,1,.123.014A.318.318,0,0,1,9.9.1l2.667,2.667.009.01a.293.293,0,0,1,.079.132.274.274,0,0,1,.012.112V5.835l1.267-1.267a.322.322,0,0,1,.466,0l1.5,1.5a.322.322,0,0,1,0,.466L12.667,9.768v5.9a.315.315,0,0,1-.333.333Zm.334-.666H12v-4.9L9.133,13.3a.3.3,0,0,1-.233.1H8.882L6.4,14.468a.2.2,0,0,1-.133.033.332.332,0,0,1-.3-.466l.589-1.368H2.667a.333.333,0,0,1,0-.667H6.843l.258-.6a.321.321,0,0,1,.176-.177L8.5,10H2.667a.333.333,0,0,1,0-.667h6.5L12,6.5V3.335H9.667A.315.315,0,0,1,9.333,3V.668H.667Zm6.233-1.8,1.4-.6-.8-.8-.1.239a.323.323,0,0,1-.074.172Zm2-.967,6.3-6.3-.283-.283-6.3,6.3ZM7.867,11.534l.284.284,6.3-6.3-.283-.283L12.624,6.777a.291.291,0,0,1-.115.115L9.558,9.844a.291.291,0,0,1-.115.115ZM10,2.668h1.533L10.767,1.9,10,1.135ZM2.667,7.335a.333.333,0,0,1,0-.667H10a.333.333,0,1,1,0,.667Zm0-2.668a.333.333,0,1,1,0-.666H10a.333.333,0,1,1,0,.666Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Notes')); ?></span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <?php if(get_setting('seller_can_add_note')): ?>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link" href="<?php echo e(route('seller.note.create')); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Add New Note')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('seller.note.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.note.edit'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Note List')); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('seller.uploaded-files.index')); ?>"
                        class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.uploaded-files.index', 'seller.uploads.create'])); ?>">
                        <i class="las la-folder-open aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Uploaded Files')); ?></span>
                    </a>
                </li>
                <?php if(addon_is_activated('seller_subscription')): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Package')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.seller_packages_list')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Packages')); ?></span>
                                </a>
                            </li>

                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.packages_payment_list')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Purchase Packages')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if(get_setting('coupon_system') == 1): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('seller.coupon.index')); ?>"
                            class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.coupon.index', 'seller.coupon.create', 'seller.coupon.edit'])); ?>">
                            <i class="las la-bullhorn aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Coupon')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(addon_is_activated('wholesale') && get_setting('seller_wholesale_product') == 1): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('seller.wholesale_products_list')); ?>"
                            class="aiz-side-nav-link <?php echo e(areActiveRoutes(['wholesale_product_create.seller', 'wholesale_product_edit.seller'])); ?>">
                            <i class="las la-luggage-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Wholesale Products')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(addon_is_activated('auction') && get_setting('seller_auction_product') == 1): ?>
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-gavel aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Auction')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('auction_products.seller.index')); ?>"
                                    class="aiz-side-nav-link <?php echo e(areActiveRoutes(['auction_products.seller.index', 'auction_product_create.seller', 'auction_product_edit.seller', 'product_bids.seller'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('All Auction Products')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('auction_products_orders.seller')); ?>"
                                    class="aiz-side-nav-link <?php echo e(areActiveRoutes(['auction_products_orders.seller'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Auction Product Orders')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(addon_is_activated('pos_system') &&
                        get_setting('pos_activation_for_seller') != null &&
                        get_setting('pos_activation_for_seller') != 0): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-tasks aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('POS System')); ?></span>
                            <?php if(env('DEMO_MODE') == 'On'): ?>
                                <span class="badge badge-inline badge-danger">Addon</span>
                            <?php endif; ?>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('poin-of-sales.seller_index')); ?>"
                                    class="aiz-side-nav-link <?php echo e(areActiveRoutes(['poin-of-sales.seller_index'])); ?>">
                                    <i class="las la-fax aiz-side-nav-icon"></i>
                                    <span class="aiz-side-nav-text"><?php echo e(translate('POS Manager')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('pos.configuration')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('POS Configuration')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.pos.orders')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('POS Orders')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.pos.products')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('POS Products')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(addon_is_activated('gst_system')): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-percentage aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('GST System')); ?></span>
                            <?php if(env('DEMO_MODE') == 'On'): ?>
                                <span class="badge badge-inline badge-danger">Addon</span>
                            <?php endif; ?>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.products.hsn-gst.assigns')); ?>"
                                    class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.products.hsn-gst.assigns'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('HSN Assign')); ?></span>
                                </a>
                            </li>
                            <?php if(addon_is_activated('wholesale')): ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.products.wholesale-hsn-gst.assigns')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Wholesale Products')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if(addon_is_activated('preorder')): ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.products.preorder-hsn-gst.assigns')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Preorder Products')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if(addon_is_activated('auction')): ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.products.auction-hsn-gst.assigns')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Auction Products')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('seller.orders.index')); ?>"
                        class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.orders.index', 'seller.orders.show'])); ?>">
                        <i class="las la-money-bill aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Orders')); ?></span>
                    </a>
                </li>
                <?php if(addon_is_activated('refund_request')): ?>
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-backward aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Refund')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.vendor_refund_request')); ?>"
                                    class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.vendor_refund_request', 'reason_show'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Received Refund Request')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('seller.refund_configuration')); ?>"
                                    class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.refund_configuration'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Refund Configuration')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php endif; ?>


                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('seller.shop.index')); ?>"
                        class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.shop.index'])); ?>">
                        <i class="las la-cog aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Shop Setting')); ?></span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('seller.payments.index')); ?>"
                        class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.payments.index'])); ?>">
                        <i class="las la-history aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Payment History')); ?></span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('seller.money_withdraw_requests.index')); ?>"
                        class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.money_withdraw_requests.index'])); ?>">
                        <i class="las la-money-bill-wave-alt aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Money Withdraw')); ?></span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('seller.commission-history.index')); ?>" class="aiz-side-nav-link">
                        <i class="las la-file-alt aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Commission')); ?></span>
                    </a>
                </li>

                <?php if(get_setting('conversation_system') == 1): ?>
                    <?php
                        $conversation = \App\Models\Conversation::where('sender_id', Auth::user()->id)
                            ->where('sender_viewed', 0)
                            ->get();
                    ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('seller.conversations.index')); ?>"
                            class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.conversations.index', 'seller.conversations.show'])); ?>">
                            <i class="las la-comment aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Conversations')); ?></span>
                            <?php if(count($conversation) > 0): ?>
                                <span class="badge badge-success">(<?php echo e(count($conversation)); ?>)</span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(get_setting('product_query_activation') == 1): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('seller.product_query.index')); ?>"
                            class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.product_query.index'])); ?>">
                            <i class="las la-question-circle aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Product Queries')); ?></span>

                        </a>
                    </li>
                <?php endif; ?>

                <?php
                    $support_ticket = DB::table('tickets')
                        ->where('client_viewed', 0)
                        ->where('user_id', Auth::user()->id)
                        ->count();
                ?>
                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('seller.support_ticket.index')); ?>"
                        class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.support_ticket.index'])); ?>">
                        <i class="las la-atom aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Support Ticket')); ?></span>
                        <?php if($support_ticket > 0): ?>
                            <span class="badge badge-inline badge-success"><?php echo e($support_ticket); ?></span>
                        <?php endif; ?>
                    </a>
                </li>

            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/inc/seller_sidenav.blade.php ENDPATH**/ ?>