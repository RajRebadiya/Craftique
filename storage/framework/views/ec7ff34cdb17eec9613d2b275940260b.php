<?php
    $photos = $detailedProduct->photos != null ? explode(',', $detailedProduct->photos) : [];

    $videos = $detailedProduct->video_link;

    $short_video = $detailedProduct->short_video != null ? explode(',', $detailedProduct->short_video) : [];
    $short_video_thumb =
    $detailedProduct->short_video != null ? explode(',', $detailedProduct->short_video_thumbnail) : [];
    $total_gallery= count($photos)  +count($short_video) + (is_iterable($videos) ? count($videos) : 0);

?>
<div class="row">
    <div class="col-md-2 col-lg-3 col-xl-2 order-2 order-md-1">
        <!--THUMBNAILS SLIDER-->
        <div class="thumb-container position-relative overflow-hidden rounded-corner-8px">
            <div class="swiper thumb-slider w-100 h-100">
                <div class="swiper-wrapper">
                    <?php if($detailedProduct->digital == 0): ?>
                        <?php if($detailedProduct->stocks && $detailedProduct->stocks->count()): ?>
                            <?php $__currentLoopData = $detailedProduct->stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($stock->image != null): ?>
                                    <div class="swiper-slide rounded-corner-8px border border-light-gray bg-light cursor-pointer overflow-hidden d-flex align-items-center justify-content-center"
                                        data-variation-image="<?php echo e($stock->image); ?>">
                                        <img src="<?php echo e(uploaded_asset($stock->image)); ?>"
                                            class="img-fluid object-fit-cover object-position-center"
                                            alt="">
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide rounded-corner-8px border border-light-gray bg-light cursor-pointer overflow-hidden d-flex align-items-center justify-content-center">
                            <img src="<?php echo e(uploaded_asset($photo)); ?>"
                                class="img-fluid object-fit-cover object-position-center"
                                alt="">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Video -->
                    <?php $__currentLoopData = $short_video; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!--Single-->
                    <div
                        class="swiper-slide position-relative rounded-corner-8px border  border-light-gray bg-light cursor-pointer overflow-hidden d-flex align-items-center justify-content-center">
                        
                            <img class="img-fluid object-fit-cover object-position-center position-absolute z-1" src="<?php echo e($detailedProduct->short_video_thumbnail
                            ? uploaded_asset(
                                count($short_video_thumb) == count($short_video) ? $short_video_thumb[$index] : $short_video_thumb[0],
                            )
                            : ''); ?>"
                        onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                        <span class="position-absolute z-2">
                            <i class="las la-play-circle fs-36 text-gray has-transition"></i>
                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- videoLink -->
                    <?php if(!empty($videos) && is_iterable($videos)): ?>
                    <?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $youtube_id = youtubeVideoId($video);
                            $youtube_thumb = 'https://img.youtube.com/vi/' . $youtube_id . '/hqdefault.jpg';
                        ?>
                        <!--Single-->
                        <div
                            class="swiper-slide position-relative rounded-corner-8px border  border-light-gray bg-light cursor-pointer overflow-hidden d-flex align-items-center justify-content-center" data-variation="youtube">
                            
                                <img class="img-fluid object-fit-cover object-position-cent" src="<?php echo e($youtube_thumb); ?>"
                            onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                            <span class="position-absolute z-2">
                                <i class="las la-play-circle fs-36 text-gray has-transition"></i>
                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                        
                </div>
            </div>
            <!--Thumb Button-->
            <?php if($total_gallery > 6): ?>
            <div class="thumb-slider-btn position-absolute bottom-0 left-0 w-100 d-flex">
                <button type="button"
                    class="thumb-btn-up border-0  d-flex align-items-center justify-content-center cursor-pointer">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111723"><path d="m480-555.69-184 184L267.69-400 480-612.31 692.31-400 664-371.69l-184-184Z"/></svg>
                    </span>
                </button>
                <button type="button"
                    class="thumb-btn-down border-0  d-flex align-items-center justify-content-center cursor-pointer">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111723"><path d="M480-371.69 267.69-584 296-612.31l184 184 184-184L692.31-584 480-371.69Z"/></svg>
                    </span>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- MAIN SLIDER -->
    <div class="col-md-10 col-lg-9 col-xl-10 pl-lg-0 order-1 order-md-2">
        <div class="swiper main-slider position-relative d-flex align-items-center justify-content-center">
            <div class="swiper-wrapper">

                

                
                <?php if($detailedProduct->digital == 0): ?>
                    <?php if($detailedProduct->stocks && $detailedProduct->stocks->count()): ?>
                        <?php $__currentLoopData = $detailedProduct->stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($stock->image != null): ?>
                                <div
                                    class="swiper-slide rounded-corner-8px border  border-light-gray bg-light overflow-hidden lightbox-item"
                                    data-variation="<?php echo e($stock->variant); ?>">
                                    <img src="<?php echo e(uploaded_asset($stock->image)); ?>" class="img-fluid w-100 h-100 lightbox-source" alt="">
                                    <div class="img-preview-btn wd-show-product-gallery-wrap rounded-pill overflow-hidden">
                                        <a href="#"
                                            class="border-0 bg-transparent d-inline-flex align-items-center woodmart-show-product-gallery">
                                            <span
                                                class="preview-icon-container w-50px h-50px d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white">
                                                <i class="las la-expand-arrows-alt fs-16 text-dark"></i>
                                            </span>
                                            <span class="fs-14 fw-400 text-dark preview-btn-text flex-shrink-0"><?php echo e(translate('Click to Enlarge')); ?></span>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!--Single-->
                <div
                    class="swiper-slide rounded-corner-8px border  border-light-gray bg-light overflow-hidden lightbox-item">
                    <img src="<?php echo e(uploaded_asset($photo)); ?>" class="img-fluid w-100 h-100 lightbox-source" alt="">
                    <div class="img-preview-btn wd-show-product-gallery-wrap rounded-pill overflow-hidden">
                        <a href="#"
                            class="border-0 bg-transparent d-inline-flex align-items-center woodmart-show-product-gallery">
                            <span
                                class="preview-icon-container w-50px h-50px d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white">
                                <i class="las la-expand-arrows-alt fs-16 text-dark"></i>
                            </span>
                            <span class="fs-14 fw-400 text-dark preview-btn-text flex-shrink-0"><?php echo e(translate('Click to Enlarge')); ?></span>
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                

                <!-- Video  -->
                <?php $__currentLoopData = $short_video; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!--Single-->
                <div
                    class="swiper-slide rounded-corner-8px border  border-light-gray bg-light overflow-hidden">
                    <video class="w-100 h-100" controls poster="<?php echo e($detailedProduct->short_video_thumbnail ? uploaded_asset(
                                    count($short_video_thumb) == count($short_video) ? $short_video_thumb[$index] : $short_video_thumb[0],
                                ) : ''); ?>" disablePictureInPicture>
                        <source src="<?php echo e(uploaded_asset($video)); ?>" type="video/mp4">
                    </video>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- Video Link -->

                <!--Single-->
                <?php if(!empty($videos) && is_iterable($videos)): ?>
                <?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div
                    class="swiper-slide rounded-corner-8px border  border-light-gray bg-light overflow-hidden">
                    <iframe class="w-100 h-100 border-0"
                        src="<?php echo e(convertToEmbedUrl($video)); ?>"
                        title="YouTube Video"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin">
                    </iframe>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <!--Swipper Buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/frontend/product_details/image_gallery.blade.php ENDPATH**/ ?>