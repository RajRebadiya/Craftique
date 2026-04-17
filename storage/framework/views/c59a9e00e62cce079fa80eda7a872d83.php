

<?php $__env->startSection('meta_title'); ?><?php echo e($detailedProduct->meta_title); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('meta_description'); ?><?php echo e($detailedProduct->meta_description); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('meta_keywords'); ?><?php echo e($detailedProduct->tags); ?>,<?php echo e($detailedProduct->meta_keywords); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('meta'); ?>
    <?php
        $availability = "out of stock";
        $qty = 0;
        if($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        }
        else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if($qty > 0){
            $availability = "in stock";
        }
    ?>
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="<?php echo e($detailedProduct->meta_title); ?>">
    <meta itemprop="description" content="<?php echo e($detailedProduct->meta_description); ?>">
    <meta itemprop="image" content="<?php echo e(uploaded_asset($detailedProduct->meta_img)); ?>">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="<?php echo e($detailedProduct->meta_title); ?>">
    <meta name="twitter:description" content="<?php echo e($detailedProduct->meta_description); ?>">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="<?php echo e(uploaded_asset($detailedProduct->meta_img)); ?>">
    <meta name="twitter:data1" content="<?php echo e(single_price($detailedProduct->unit_price)); ?>">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo e($detailedProduct->meta_title); ?>" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="<?php echo e(route('product', $detailedProduct->slug)); ?>" />
    <meta property="og:image" content="<?php echo e(uploaded_asset($detailedProduct->meta_img)); ?>" />
    <meta property="og:description" content="<?php echo e($detailedProduct->meta_description); ?>" />
    <meta property="og:site_name" content="<?php echo e(get_setting('meta_title')); ?>" />
    <meta property="og:price:amount" content="<?php echo e(single_price($detailedProduct->unit_price)); ?>" />
    <meta property="product:brand" content="<?php echo e($detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME')); ?>">
    <meta property="product:availability" content="<?php echo e($availability); ?>">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="<?php echo e(number_format($detailedProduct->unit_price, 2)); ?>">
    <meta property="product:retailer_item_id" content="<?php echo e($detailedProduct->slug); ?>">
    <meta property="product:price:currency"
        content="<?php echo e(get_system_default_currency()->code); ?>" />
    <meta property="fb:app_id" content="<?php echo e(env('FACEBOOK_PIXEL_ID')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="product-details">
        <!--PRODUCT DETAILS TOP SECTION START-->
        <div class="container">
            <div class="pt-30px pb-6">
                <div class="row">
                    <!--LEFT SIDE SLIDER-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="product-slider-wrapper mb-2rem mb-lg-0">
                            <!--BREADCRUMB-->
                            <ul class="breadcrumb bg-transparent pt-0 px-0 pb-10px d-flex align-items-center">
                                <li class="fs-12 fw-400 has-transition opacity-50 hov-opacity-100">
                                    <a class="text-reset" href="<?php echo e(route('home')); ?>"><?php echo e(translate('Home')); ?></a>
                                </li>
                                <i class="las la-angle-right fs-12 fw-600 text-gray hide_cat1"></i>
                                <li class="fs-12 fw-400 has-transition opacity-50 hov-opacity-100">
                                    <a class="text-reset" href="<?php echo e(route('products.category', $detailedProduct->main_category->slug)); ?>"><?php echo e(translate($detailedProduct->main_category->name ?? '')); ?></a>
                                </li>
                                <i class="las la-angle-right fs-12 fw-600 text-gray hide_cat1"></i>
                                <li class="fs-12 fw-400 has-transition  text-reset">
                                    <?php echo e(strlen($detailedProduct->getTranslation('name')) > 50
                                        ? substr($detailedProduct->getTranslation('name'), 0, 50).'...'
                                        : $detailedProduct->getTranslation('name')); ?>

                                </li>
                            </ul>
                            <?php echo $__env->make('frontend.product_details.image_gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>
                    <!--RIGHT SIDE-->
                     <?php echo $__env->make('frontend.product_details.details', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
        <!--PRODUCT DETAILS TOP SECTION END-->


        <!-- ======== PRODUCT DETAILS NAV TAB START ======== -->
        <div id="smart-bar-trigger"></div>
        <div class="product-details-nav-tab mb-5">
            <div class="nav-tab-header bg-white">
                <div class="container mb-32px">
                    <div class="tab-scroll-wrapper">
                        <ul id="tabLinks" class="m-0 p-0 d-flex position-relative" type="none">
                            <li class="mr-2rem"><a href="#description"
                                    class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition"><?php echo e(translate('Description')); ?></a>
                            </li>
                            <?php if($detailedProduct->auction_product != 1): ?>
                            <li class="mr-2rem"><a href="#relatedProduct"
                                    class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition"><?php echo e(translate('Related Products')); ?></a></li>
                            <?php endif; ?>
                            <li class="mr-2rem"><a href="#reviewsRatings"
                                    class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition"><?php echo e(translate('Reviews & Ratings')); ?></a></li>
                            <?php if(get_setting('product_query_activation') == 1): ?>
                            <li class="mr-2rem"><a href="#product_query"
                                    class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition"><?php echo e(translate('Product Queries')); ?> (<?php echo e(count($detailedProduct->product_queries)); ?>)</a></li>
                            <?php endif; ?>
                            <?php if($detailedProduct->auction_product != 1): ?>
                            <li class="mr-2rem"><a href="#frequentlyBought"
                                    class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition"><?php echo e(translate('Frequently Bought')); ?></a></li>
                            <li class="mr-2rem"><a href="#fromThisSeller"
                                    class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition"><?php echo e(translate('More from this Seller')); ?></a></li>
                            <?php endif; ?>
                            <span class="tab-underline"></span>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="container d-flex flex-column">
                <!--DESCRIPTION SECTION START-->
                <section id="description">
                    <div class="py-30px px-30px border  bg-white border-light-gray rounded-2">
                        <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                            <?php echo $detailedProduct->getTranslation('description'); ?>
                        </div>
                    </div>
                </section>
                <!--DESCRIPTION SECTION END-->

                <?php if($detailedProduct->auction_product != 1): ?>
                <!--RELATED PRODUCTS SECTION START-->
                <section id="relatedProduct">
                    <?php echo $__env->make('frontend.product_details.related_products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </section>
                <!--RELATED PRODUCTS SECTION END-->
                <?php endif; ?>

                <!--REVIEWS & RATINGS SECTION START-->
                <?php echo $__env->make('frontend.product_details.review_section', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               <!--REVIEWS & RATINGS SECTION END-->

                <?php if(get_setting('product_query_activation') == 1): ?>
                <!--PRODUCT QUERIES START-->
                <section id="product_query">
                   <?php echo $__env->make('frontend.product_details.product_queries', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </section>
                <!--PRODUCT QUERIES END-->
                <?php endif; ?>


                <?php if($detailedProduct->auction_product != 1): ?>
                <!--FREQUENT BOUGTH TOGETHER START-->
                <section id="frequentlyBought">
                    <?php echo $__env->make('frontend.product_details.frequently_bought_together', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </section>
                <!--FREQUENT BOUGTH TOGETHER END-->

                <!--FROM THIS SELLER START-->
                <section id="fromThisSeller">
                    <?php echo $__env->make('frontend.product_details.from_this_seller_products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </section>
                <!--FROM THIS SELLER END-->
                <?php endif; ?>
            </div>
        </div>
        <!-- ======== PRODUCT DETAILS NAV TAB END ======== -->
    </div>
    <?php if($detailedProduct->auction_product != 1): ?>
    <?php echo $__env->make('frontend.smart_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>

    <?php echo $__env->make('frontend.partials.image_viewer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Image Modal -->
    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="size-300px size-lg-450px">
                        <img class="img-fit h-100 lazyload"
                            src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                            data-src=""
                            onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5"><?php echo e(translate('Any query about this product')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="<?php echo e(route('conversations.store')); ?>" method="POST"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($detailedProduct->id); ?>">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3 rounded-1" name="title"
                                value="<?php echo e($detailedProduct->name); ?>" placeholder="<?php echo e(translate('Product Name')); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control rounded-1" rows="8" name="message" required
                                placeholder="<?php echo e(translate('Your Question')); ?>"><?php echo e(route('product', $detailedProduct->slug)); ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-1"
                            data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-1 w-100px"><?php echo e(translate('Send')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bid Modal -->
    <?php if($detailedProduct->auction_product == 1): ?>
        <?php 
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
            $gst_rate = gst_applicable_product_rate($detailedProduct->id);
        ?>
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('Bid For Product')); ?> <small>(<?php echo e(translate('Min Bid Amount: ').$min_bid_amount); ?>)</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="<?php echo e(route('auction_product_bids.store')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo e($detailedProduct->id); ?>">
                            <div class="form-group">
                                <label class="form-label">
                                    <?php echo e(translate('Place Bid Price')); ?>

                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="<?php echo e($min_bid_amount); ?>" placeholder="<?php echo e(translate('Enter Amount')); ?>" required>
                                    <?php if($gst_rate != null): ?>
                                        <small class="text-danger"><?php echo e(translate('An')); ?> <?php echo e($gst_rate); ?>% <?php echo e(translate('GST will be applied if you win the bid and proceed with the purchase')); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1"><?php echo e(translate('Submit')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Size chart show Modal -->
    <?php echo $__env->make('modals.size_chart_show_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Product Warranty Modal -->
    <div class="modal fade" id="warranty-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6"><?php echo e(translate('Warranty Note')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    <?php if($detailedProduct->warranty_note_id != null): ?>
                        <p><?php echo e($detailedProduct->warrantyNote->getTranslation('description')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Refund Modal -->
    <div class="modal fade" id="refund-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6"><?php echo e(translate('Refund Note')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    <?php if($detailedProduct->refund_note_id != null): ?>
                        <p><?php echo e($detailedProduct->refundNote->getTranslation('description')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

   <!-- Product Share Modal -->
    <div class="modal fade" id="social-share-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header flex-column pb-0 border-0">
                    <div class="w-80px h-80px rounded-circle bg-light border border-white border-width-3 link-circle-box d-flex align-items-center justify-content-center"> 
                        <i class="las la-link fs-28"></i>
                    </div>
                    <button type="button" class="close fs-10 w-25px h-25px rounded-circle bg-white hov-bg-soft-light has-transition d-flex align-items-center justify-content-center mr-1 mb-1" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    <div class="col-12 col-lg-10 col-xl-8 mx-auto text-center">
                        <h4 class="fs-20 fw-700 text-dark"><?php echo e(translate('Share with Friends')); ?></h4>
                        <span class="fs-14 text-gray fw-400"><?php echo e(translate('Trading is more effective when you share products with friends!')); ?></span>
                    </div>
                    <div class="my-4 my-lg-5">
                        <h5 class="fs-16 fw-600 text-dark"><?php echo e(translate('Share you link')); ?></h5>
                        <div class="py-3 px-3 bg-light rounded-2 border-0 d-flex align-items-center justify-content-between share-link">
                            <span class="fs-14 text-gray fw-400 flex-grow-1 text-truncate-1 has-transition"><?php echo e(route('product', $detailedProduct->slug)); ?></span>
                            <button type="button" id="link-cpurl-btn" data-attrcpy="<?php echo e(translate('Copied')); ?>" data-url="<?php echo e(route('product', $detailedProduct->slug)); ?>" onclick="CopyToClipboard(this)" class="border-0 bg-transparent flex-shrink-0 copy-link-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                                    <path id="Path_45213" data-name="Path 45213" d="M124.8-867.2a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-9.6a1.541,1.541,0,0,1,.47-1.13,1.541,1.541,0,0,1,1.13-.47H132a1.541,1.541,0,0,1,1.13.47,1.541,1.541,0,0,1,.47,1.13v9.6a1.541,1.541,0,0,1-.47,1.13,1.541,1.541,0,0,1-1.13.47Zm0-1.6H132v-9.6h-7.2Zm-3.2,4.8a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-11.2h1.6v11.2h8.8v1.6Zm3.2-4.8v0Z" transform="translate(-120 880)" fill="#919199"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                     <div class="pb-3">
                        <h5 class="fs-16 fw-600 text-dark"><?php echo e(translate('Share to')); ?></h5>
                        <div class="aiz-share text-center"></div>
                     </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('script'); ?>
   <!-- ======================== Message Seller Start ================== -->
    <script>

        function show_chat_modal() {
            <?php if(Auth::check()): ?>
                $('#chat_modal').modal('show');
            <?php else: ?>
                $('#login_modal').modal('show');
            <?php endif; ?>
        }
    </script>
    
   <!-- ======================== Message Seller End ================== -->

   <!-- ======================== Product Variant Height Controll Start ================== -->
    <script type="text/javascript">
        $(document).on('click', '#toggleHeight', function () {

            var $btn = $(this);
            var $variant = $btn.closest('.product-variant');
            var $text = $btn.find('.toggle-text');
            var isCollapsed = $variant.hasClass('collapsed');

            // Toggle state
            $variant.toggleClass('collapsed');

            // Change text (HTML allowed)
            $text.html(
                isCollapsed
                    ? $btn.data('less') + ' <i class="las la-angle-up ms-1"></i>'
                    : $btn.data('more') + ' <i class="las la-angle-down ms-1"></i>'
            );

        });
    </script>

    <!-- ======================== Product Variant Height Controll End ================== -->

    <!-- ======================== Product Swipper Slide Start ================== -->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            /*------ Thumbnails Swiper ------*/
            var thumbSwiper = new Swiper(".thumb-slider", {
                direction: "vertical",
                slidesPerView: 5,
                spaceBetween: 16,
                watchSlidesProgress: true,

                breakpoints: {
                    0: {
                        direction: "horizontal",
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    768: {
                        direction: "vertical",
                        slidesPerView: 5,
                    }
                }
            });

            /*------ Product Main Swiper Slide ------ */
            var mainSwiper = new Swiper(".main-slider", {
                spaceBetween: 10,
                thumbs: {
                    swiper: thumbSwiper
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                }
            });

            /*------ Manual Scroll Thumbnail Slider Buttons ------ */
           const thumbBtnUp = document.querySelector(".thumb-btn-up");
            const thumbBtnDown = document.querySelector(".thumb-btn-down");

            if (thumbBtnUp && thumbBtnDown) {

                thumbBtnUp.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    thumbSwiper.slidePrev();
                });

                thumbBtnDown.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    thumbSwiper.slideNext();
                });

                function updateThumbButtons(swiper) {
                    thumbBtnUp.classList.toggle("disabled", swiper.isBeginning);
                    thumbBtnDown.classList.toggle("disabled", swiper.isEnd);
                }

                updateThumbButtons(thumbSwiper);

                thumbSwiper.on("slideChange", function () {
                    updateThumbButtons(this);
                });

                thumbSwiper.on("reachBeginning", function () {
                    updateThumbButtons(this);
                });

                thumbSwiper.on("reachEnd", function () {
                    updateThumbButtons(this);
                });
            }

        });
    </script>
    <!-- ======================== Product Swipper Slide End ================== -->


    <!-- ======================== Flash Sale Timer Start ================== -->
    <script type="text/javascript">
        

        function startSimpleCountdown(endDate, countdownEl) {
            // Select flashBox once here so the update function can see it
            const flashBox = document.getElementById("flashSaleBox");

            function update() {
                const now = new Date();
                const diff = endDate - now;

                if (diff > 0) {
                    const totalSeconds = Math.floor(diff / 1000);
                    const days = Math.floor(totalSeconds / (60 * 60 * 24));
                    const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
                    const mins = Math.floor((totalSeconds % (60 * 60)) / 60);
                    const secs = totalSeconds % 60;
                    countdownEl.innerHTML = `${days}D : ${hours}H : ${mins}M : ${secs}S`;
                } else {
                    countdownEl.innerHTML = "Expired";
                    if (flashBox) {
                        flashBox.classList.add("expired");
                        flashBox.style.animation = "none";
                    }
                    clearInterval(timer);
                }
            }

            update();
            const timer = setInterval(update, 1000);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const countdownEl = document.querySelector('.flashSaleCountdown');
            
            if (countdownEl && countdownEl.dataset.endDate) {
                const endDateStr = countdownEl.dataset.endDate;
                const parsedEndDate = isNaN(endDateStr) 
                    ? new Date(endDateStr.replace(/-/g, '/')) 
                    : new Date(parseInt(endDateStr) * 1000);

                startSimpleCountdown(parsedEndDate, countdownEl);
            }
        });
                </script>
    <!-- ======================== Flash Sale Timer End ================== -->

    <!-- ======================== Product Details Nav Tab Start ================== -->
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll("#tabLinks .nav-link");
        const underline = document.querySelector(".tab-underline");
        const tabWrapper = document.querySelector(".tab-scroll-wrapper");
        const sections = document.querySelectorAll("section");

        function moveUnderline(activeLink) {
            const rect = activeLink.getBoundingClientRect();
            const parentRect = activeLink.parentElement.parentElement.getBoundingClientRect();

            underline.style.width = rect.width + "px";
            underline.style.left = (rect.left - parentRect.left) + "px";

            // Auto scroll to keep active tab in view
            tabWrapper.scrollTo({
                left: activeLink.offsetLeft - 50,
                behavior: "smooth"
            });
        }

        // Smooth scroll on click
        navLinks.forEach(function(link) {
            link.addEventListener("click", function(e) {
                e.preventDefault();

                const href = this.getAttribute("href");
                const target = document.querySelector(href);

                if (!target) return;

                const targetOffset =
                    target.getBoundingClientRect().top + window.pageYOffset - 150;

                window.scrollTo({
                    top: targetOffset,
                    behavior: "smooth"
                });

                moveUnderline(this);
            });
        });


        // Scrollspy
        window.addEventListener("scroll", () => {
            const scrollPos = window.scrollY + 120;

            sections.forEach(sec => {
                const top = sec.offsetTop;
                const bottom = top + sec.offsetHeight;

                // if current scroll inside this section
                if (scrollPos >= top && scrollPos < bottom) {
                    const activeLink = document.querySelector(`a[href="#${sec.id}"]`);
                    if (activeLink) moveUnderline(activeLink);
                }
            });
        });


        // initial underline
        window.addEventListener("load", () => {
            moveUnderline(navLinks[0]);
        });
     });
    </script>

    <!-- ======================== Product Details Nav Tab End ================== -->

    <!-- ======================== Filter Rating Selector Start ================== -->
    <script type="text/javascript">
       $(document).on('click', '.rating-point', function (e) {
            if ($(e.target).is('input')) {
                return;
            }
            $('.rating-point').removeClass('active');
            $(this).addClass('active');
            let val = $(this).find('input').val();
            $(this).find('input').prop('checked', true);
            getReviews(0, val);
        });
        
    </script>
    <!-- ======================== Filter Rating Selector End ================== -->

   <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '<?php echo e(translate('Link copied to clipboard')); ?>');
                //reset temp input value
            } catch (err) {
                AIZ.plugins.notify('danger', '<?php echo e(translate('Oops, unable to copy')); ?>');
            }
            $temp.remove();
        }


        function SKUCopyToClipboard() {
            // Directly get the text currently shown in the SKU span
            var skuText = $('#variant_sku').text().trim();

            if (skuText === "") {
                AIZ.plugins.notify('warning', 'No SKU to copy');
                return;
            }

            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(skuText).select();

            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '<?php echo e(translate('SKU copied to clipboard')); ?>');
            } catch (err) {
                AIZ.plugins.notify('danger', '<?php echo e(translate('Oops, unable to copy')); ?>');
            }
            
            $temp.remove();
        }

        function show_chat_modal() {
            <?php if(Auth::check()): ?>
                $('#chat_modal').modal('show');
            <?php else: ?>
                $('#login_modal').modal('show');
            <?php endif; ?>
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if(window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {type: type},
            }).done(function(data) {
                $('.'+section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }


        $(document).on('click', '.see-more-btn', function () {
            getReviews(1, null);
        });
        // Pagination end

        function reviewBySort() {
            getReviews();
        }

        function reviewByImages(){
            getReviews(null, null, 1);
        }


        function getReviews(seemore= false, rating = null, images= null) {
            let sortBy = $('#sortBy').val();
            let limit = parseInt($('.see-more-btn').attr('data-limit')) || 0;
            if (seemore) {
                limit += 3;
            }
            if(images!= null){
                rating =null;
                sortBy = images;
            }
            if (rating == null) {
                $('.rating-point').removeClass('active');
            }
            const loaderHtml = `
                <div class="h-500px w-100 d-flex justify-content-center align-items-center loader-wrapper">
                    <div class="footable-loader" style="height: 100vh !important;">
                        <span class="fooicon fooicon-loader"></span>
                    </div>
                </div>`;
            //alert(limit);
            $('.reviews-area').html(loaderHtml);
            $.ajax({
                url: `<?php echo e(route('products.reviews')); ?>`,
                method: 'GET',
                data: {
                    slug: '<?php echo e($detailedProduct->slug); ?>',
                    limit: limit,
                    sort_by: sortBy,
                    rating: rating,
                    images: images
                },
                beforeSend: function () {
                    // Show loader
                    $('.reviews-area').html(loaderHtml);
                },
                success: function (res) {

                    // Remove loader
                    $('.reviews-area').empty();

                    if (res.html && res.html.trim() !== '') {
                        $('.reviews-area').append(res.html);
                        $('#see-more-btn').attr('data-limit', limit);
                    }

                    // Hide button if no more reviews
                    if (!res.has_more) {
                        $('#seeMoreReviews').hide();
                    }
                },
                error: function () {
                    $('.reviews-area').html('<p class="text-danger text-center">Failed to load reviews.</p>');
                }
            });
        }


    //    document.addEventListener('click', function(e) {
    //         const label = e.target.closest('.rating-point-select');
    //         if (!label) return;
    //         if (label.dataset.listenerAdded === 'true') return;
    //         label.dataset.listenerAdded = 'true';
    //         document.querySelectorAll('.rating-point-select')
    //             .forEach(l => l.classList.remove('active', 'border-primary'));
    //         label.classList.add('active', 'border-primary');
    //         const rating = label.dataset.value;
    //         getReviews(0, rating);
    //     });


        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal(){
            <?php if(isCustomer() || isSeller()): ?>
                $('#bid_for_detail_product').modal('show');
          	<?php elseif(isAdmin()): ?>
                AIZ.plugins.notify('warning', '<?php echo e(translate("Sorry, Only customers & Sellers can Bid.")); ?>');
            <?php else: ?>
                $('#login_modal').modal('show');
            <?php endif; ?>
        }

        function product_review(product_id,order_id) {
            <?php if(isCustomer()): ?>
                <?php if($review_status == 1): ?>
                    $.post('<?php echo e(route('product_review_modal')); ?>', {
                        _token: '<?php echo e(@csrf_token()); ?>',
                        product_id: product_id,
                        order_id: order_id
                    }, function(data) {
                        $('#product-review-modal-content').html(data);
                        $('#product-review-modal').modal('show', {
                            backdrop: 'static'
                        });
                        AIZ.extra.inputRating();
                    });
                <?php else: ?>
                    AIZ.plugins.notify('warning', '<?php echo e(translate("Sorry, You need to buy this product to give review.")); ?>');
                <?php endif; ?>
            <?php elseif(Auth::check() && !isCustomer()): ?>
                AIZ.plugins.notify('warning', '<?php echo e(translate("Sorry, Only customers can give review.")); ?>');
            <?php else: ?>
                $('#login_modal').modal('show');
            <?php endif; ?>
        }

        function showSizeChartDetail(id, name){
            $('#size-chart-show-modal .modal-title').html('');
            $('#size-chart-show-modal .modal-body').html('');
            if (id == 0) {
                AIZ.plugins.notify('warning', '<?php echo e(translate("Sorry, There is no size guide found for this product.")); ?>');
                return false;
            }
            $.ajax({
                type: "GET",
                url: "<?php echo e(route('size-charts-show', '')); ?>/"+id,
                data: {},
                success: function(data) {
                    $('#size-chart-show-modal .modal-title').html(name);
                    $('#size-chart-show-modal .modal-body').html(data);
                    $('#size-chart-show-modal').modal('show');
                }
            });
        }

        function getRandomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function updateViewerCount() {
            const countElement = document.querySelector('#live-product-viewing-visitors .count');
            const min = parseInt(`<?php echo e(get_setting('min_custom_product_visitors')); ?>`);
            const max = parseInt(`<?php echo e(get_setting('max_custom_product_visitors')); ?>`);
            const randomNumber = getRandomNumber(min, max);
            countElement.textContent = randomNumber;
            const randomTime = getRandomNumber(5000, 10000);
            setTimeout(updateViewerCount, randomTime);
        }
        
    </script>
    <?php if(get_setting('show_custom_product_visitors')==1): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateViewerCount();
            getReviews();
        });
    </script>
    <?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getReviews();
        });
    </script>
    <!-- ======================== Custom Viewer End  ================== -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/product_details.blade.php ENDPATH**/ ?>