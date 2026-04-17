

<?php $__env->startSection('content'); ?>

<?php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
?>

<div class="page-content">
    <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem border-bottom border-gray">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3"><?php echo e(translate('Add New Product')); ?></h1>
            </div>
            <div class="col text-right">
                <a class="btn btn-xs btn-soft-primary" href="javascript:void(0);" onclick="clearTempdata()">
                    <?php echo e(translate('Clear Tempdata')); ?>

                </a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product_duplicate')): ?>
                <a class="btn btn-xs btn-soft-warning " href="javascript:void(0);" onclick="showProductSelectModal()">
                    <?php echo e(translate('Import Product')); ?>

                </a>
                <?php endif; ?>
            </div>
            
        </div>
    </div>

    <div class="d-sm-flex">
        <!-- page side nav -->
        <div class="page-side-nav c-scrollbar-light px-3 py-2">
            <ul class="nav nav-tabs flex-sm-column border-0" role="tablist" aria-orientation="vertical">
                <!-- General -->
                <li class="nav-item">
                    <a class="nav-link" id="general-tab" href="#general"
                        data-toggle="tab" data-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                        <?php echo e(translate('General')); ?>

                    </a>
                </li>
                <!-- Files & Media -->
                <li class="nav-item">
                    <a class="nav-link" id="files-and-media-tab" href="#files_and_media"
                        data-toggle="tab" data-target="#files_and_media" type="button" role="tab" aria-controls="files_and_media" aria-selected="false">
                        <?php echo e(translate("Files & Media")); ?>

                    </a>
                </li>
                <!-- Price & Stock -->
                <li class="nav-item">
                    <a class="nav-link" id="price-and-stocks-tab" href="#price_and_stocks"
                        data-toggle="tab" data-target="#price_and_stocks" type="button" role="tab" aria-controls="price_and_stocks" aria-selected="false">
                        <?php echo e(translate('Price & Stock')); ?>

                    </a>
                </li>
                <!-- SEO -->
                <li class="nav-item">
                    <a class="nav-link" id="seo-tab" href="#seo"
                        data-toggle="tab" data-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false">
                        <?php echo e(translate('SEO')); ?>

                    </a>
                </li>
                <!-- Shipping -->
                <li class="nav-item">
                    <a class="nav-link" id="shipping-tab" href="#shipping"
                        data-toggle="tab" data-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">
                        <?php echo e(translate('Shipping')); ?>

                    </a>
                </li>

                <!-- Warranty -->
                <li class="nav-item">
                    <a class="nav-link" id="warranty-tab" href="#warranty"
                        data-toggle="tab" data-target="#warranty" type="button" role="tab" aria-controls="warranty" aria-selected="false">
                        <?php echo e(translate('Warranty')); ?>

                    </a>
                </li>

                <!-- Frequently Bought Product -->
                <li class="nav-item">
                    <a class="nav-link" id="frequenty-bought-product-tab" href="#frequenty-bought-product"
                        data-toggle="tab" data-target="#frequenty-bought-product" type="button" role="tab" aria-controls="frequenty-bought-product" aria-selected="false">
                        <?php echo e(translate('Frequently Bought')); ?>

                    </a>
                </li>
            </ul>
        </div>

        <!-- tab content -->
        <div class="flex-grow-1 p-sm-3 p-lg-2rem mb-2rem mb-md-0">
            <!-- Error Meassages -->
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Data type -->
            <input type="hidden" id="data_type" value="physical">

            <form action="<?php echo e(route('products.store')); ?>" method="POST" enctype="multipart/form-data" enctype="multipart/form-data" id="aizSubmitForm">
                <?php echo csrf_field(); ?>
                <div class="tab-content">
                    <!-- General -->
                    <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <!-- Product Information -->
                            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Product Information')); ?></h5>
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-xxl-7 col-xl-6">
                                        <!-- Product Name -->
                                        <div class="form-group mb-2">
                                            <label class="col-from-label fs-13"><?php echo e(translate('Product Name')); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" value="<?php echo e(old('name')); ?>" placeholder="<?php echo e(translate('Product Name')); ?>" onchange="update_sku()">
                                        </div>
                                        <!-- Brand -->
                                        <div class="form-group mb-2" id="brand">
                                            <label class="col-from-label fs-13"><?php echo e(translate('Brand')); ?></label>
                                            <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
                                                <option value=""><?php echo e(translate('Select Brand')); ?></option>
                                                <?php $__currentLoopData = \App\Models\Brand::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($brand->id); ?>" <?php if(old('brand_id') == $brand->id): echo 'selected'; endif; ?>><?php echo e($brand->getTranslation('name')); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <small class="text-muted"><?php echo e(translate("You can choose a brand if you'd like to display your product by brand.")); ?></small>
                                        </div>
                                        <!-- Unit -->
                                        <div class="form-group mb-2">
                                            <label class="col-from-label fs-13"><?php echo e(translate('Unit')); ?> <span class="text-danger">*</span></label>
                                            <input type="text" letter-only class="form-control <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="unit" value="<?php echo e(old('unit')); ?>" placeholder="<?php echo e(translate('Unit (e.g. KG, Pc etc)')); ?>">
                                        </div>
                                        <!-- Weight -->
                                        <div class="form-group mb-2">
                                            <label class="col-from-label fs-13"><?php echo e(translate('Weight')); ?> <small>(<?php echo e(translate('In Kg')); ?>)</small></label>
                                            <input type="number" class="form-control" name="weight" value="0.00"  step="0.001" placeholder="0.00">
                                        </div>
                                        <!-- Minimum Purchase Qty -->
                                        <div class="form-group mb-2">
                                            <label class="col-from-label fs-13"><?php echo e(translate('Minimum Purchase Qty')); ?> <span class="text-danger">*</span></label>
                                            <input type="number" lang="en" class="form-control <?php $__errorArgs = ['min_qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="min_qty" value="<?php echo e(old('min_qty') ?? 1); ?>" placeholder="1" min="1" step="1" integer-only required>
                                            <small class="text-muted"><?php echo e(translate("The minimum quantity needs to be purchased by your customer.")); ?></small>
                                        </div>
                                        <!-- Tags -->
                                        <div class="form-group mb-2">
                                            <label class="col-from-label fs-13"><?php echo e(translate('Tags')); ?></label>
                                            <input type="text" class="form-control aiz-tag-input" name="tags[]" placeholder="<?php echo e(translate('Type and hit enter to add a tag')); ?>">
                                            <small class="text-muted"><?php echo e(translate('This is used for search. Input those words by which cutomer can find this product.')); ?></small>
                                        </div>

                                        <?php if(addon_is_activated('pos_system')): ?>
                                        <!-- Barcode -->
                                        <div class="form-group mb-2">
                                            <label class="col-xxl-3 col-from-label fs-13"><?php echo e(translate('Barcode')); ?></label>
                                            <input type="text" class="form-control" name="barcode" value="<?php echo e(old('barcode')); ?>" placeholder="<?php echo e(translate('Barcode')); ?>">
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Product Category -->
                                    <div class="col-xxl-5 col-xl-6">
                                        <div id="category-card" class="card mb-1 <?php if($errors->has('category_ids') || $errors->has('category_id')): ?> border border-danger <?php endif; ?>">
                                            <div class="card-header">
                                                <h5 class="mb-0 h6"><?php echo e(translate('Product Category')); ?></h5>
                                                <h6 class="float-right fs-13 mb-0">
                                                    <?php echo e(translate('Select Main')); ?>

                                                    <span class="position-relative main-category-info-icon">
                                                        <i class="las la-question-circle fs-18 text-info"></i>
                                                        <span class="main-category-info bg-soft-info p-2 position-absolute d-none border"><?php echo e(translate('This will be used for commission based calculations and homepage category wise product Show.')); ?></span>
                                                    </span>
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="h-400px overflow-auto c-scrollbar-light">
                                                    <ul class="hummingbird-treeview-converter list-unstyled" data-checkbox-name="category_ids[]" data-radio-name="category_id">
                                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li id="<?php echo e($category->id); ?>"><?php echo e($category->getTranslation('name')); ?></li>
                                                            <?php $__currentLoopData = $category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php echo $__env->make('backend.product.products.child_category', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="category-tree-table-error"></div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label class="fs-13"><?php echo e(translate('Description')); ?></label>
                                    <div class="">
                                        <textarea class="aiz-text-editor" name="description"><?php echo e(old('description')); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Refund -->
                            <?php if(addon_is_activated('refund_request')): ?>
                                <h5 class="mb-3 mt-5 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Refund')); ?></h5>
                                <div class="w-100">
                                    <!-- Refundable -->
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label"><?php echo e(translate('Refundable')); ?>?</label>
                                        <div class="col-md-9">
                                            <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                                <input type="checkbox" name="refundable" value="1" onchange="isRefundable()"
                                                    <?php if(get_setting('refund_type') != 'category_based_refund'): ?> checked
                                                    <?php endif; ?>>
                                                <span></span>
                                            </label>
                                            <small id="refundable-note" class="text-muted d-none"></small>
                                        </div>
                                    </div>
                                    <div class="w-100 refund-block d-none">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="form-check-label fw-bold" for="flexCheckChecked">
                                                    <b><?php echo e(translate('Note (Add from preset)')); ?> </b>
                                                </label>
                                            </div>
                                        </div>
        
                                        <input type="hidden" name="refund_note_id" id="refund_note_id">
                                        <div id="refund_note" class="">
        
                                        </div>
                                        <button
                                            type="button"
                                            class="btn btn-block border border-dashed hov-bg-soft-secondary mt-2 fs-14 rounded-0 d-flex align-items-center justify-content-center"
                                            onclick="noteModal('refund')">
                                            <i class="las la-plus"></i>
                                            <span class="ml-2"><?php echo e(translate('Select Refund Note')); ?></span>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Status -->
                            <h5 class="mb-3 mt-5 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Status')); ?></h5>
                            <div class="w-100">
                                <!-- Featured -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Featured')); ?></label>
                                    <div class="col-md-9">
                                        <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                            <input type="checkbox" name="featured" value="1">
                                            <span></span>
                                        </label>
                                        <small class="text-muted"><?php echo e(translate('If you enable this, this product will be granted as a featured product.')); ?></small>
                                    </div>
                                </div>
                                <!-- Todays Deal -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Todays Deal')); ?></label>
                                    <div class="col-md-9">
                                        <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                            <input type="checkbox" name="todays_deal" value="1">
                                            <span></span>
                                        </label>
                                        <small class="text-muted"><?php echo e(translate('If you enable this, this product will be granted as a todays deal product.')); ?></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Flash Deal -->
                            <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;">
                                <?php echo e(translate('Flash Deal')); ?>

                                <small class="text-muted">(<?php echo e(translate('If you want to select this product as a flash deal, you can use it')); ?>)</small>
                            </h5>
                            <div class="w-100">
                                <!-- Add To Flash -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Add To Flash')); ?></label>
                                    <div class="col-xxl-9">
                                        <select class="form-control aiz-selectpicker" name="flash_deal_id" id="flash_deal">
                                            <option value=""><?php echo e(translate('Choose Flash Title')); ?></option>
                                            <?php $__currentLoopData = \App\Models\FlashDeal::where("status", 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flash_deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($flash_deal->id); ?>">
                                                    <?php echo e($flash_deal->title); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Discount -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Discount')); ?></label>
                                    <div class="col-xxl-9">
                                        <input type="number" name="flash_discount" value="0" min="0" step="0.01" class="form-control">
                                    </div>
                                </div>
                                <!-- Discount Type -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Discount Type')); ?></label>
                                    <div class="col-xxl-9">
                                        <select class="form-control aiz-selectpicker" name="flash_discount_type" id="flash_discount_type">
                                            <option value=""><?php echo e(translate('Choose Discount Type')); ?></option>
                                            <option value="amount"><?php echo e(translate('Flat')); ?></option>
                                            <option value="percent"><?php echo e(translate('Percent')); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- GST Rate -->
                            <?php if(addon_is_activated('gst_system')): ?>
                            <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('HSN & GST')); ?></h5>
                            <div class="w-100">
                                <div class="form-group mb-2">
                                    <label class="col-from-label"><?php echo e(translate('HSN Code')); ?></label>
                                    <input type="text" lang="en" placeholder="<?php echo e(translate('HSN Code')); ?>" name="hsn_code" class="form-control">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="col-from-label"><?php echo e(translate('GST Rate (%)')); ?></label>
                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="<?php echo e(translate('GST Rate')); ?>" name="gst_rate" class="form-control">
                                </div>
                            </div>
                            <?php else: ?>
                            <!-- Vat & TAX -->
                            <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Vat & TAX')); ?></h5>
                            <div class="w-100">
                                <?php $__currentLoopData = \App\Models\Tax::where('tax_status', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label for="name">
                                        <?php echo e($tax->name); ?>

                                        <input type="hidden" value="<?php echo e($tax->id); ?>" name="tax_id[]">
                                    </label>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="<?php echo e(translate('Tax')); ?>" name="tax[]" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <select class="form-control aiz-selectpicker" name="tax_type[]">
                                                <option value="amount"><?php echo e(translate('Flat')); ?></option>
                                                <option value="percent"><?php echo e(translate('Percent')); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                   <!-- Files & Media -->
                    <div class="tab-pane fade" id="files_and_media" role="tabpanel"
                        aria-labelledby="files-and-media-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <!-- Product Files & Media -->
                            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;">
                                <?php echo e(translate('Product Files & Media')); ?></h5>
                            <div class="w-100">
                                <!-- Gallery Images -->
                                <div class="form-group mb-2">
                                    <label class="col-form-label"
                                        for="signinSrEmail"><?php echo e(translate('Gallery Images')); ?></label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                <?php echo e(translate('Browse')); ?></div>
                                        </div>
                                        <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                        <input type="hidden" name="photos" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small
                                        class="text-muted"><?php echo e(translate('These images are visible in product details page gallery. Minimum dimensions required: 900px width X 900px height.')); ?></small>
                                </div>
                                <!-- Thumbnail Image -->
                                <div class="form-group mb-2">
                                    <label class="col-form-label"
                                        for="signinSrEmail"><?php echo e(translate('Thumbnail Image')); ?></label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                <?php echo e(translate('Browse')); ?></div>
                                        </div>
                                        <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                        <input type="hidden" name="thumbnail_img" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small
                                            class="text-muted"><?php echo e(translate("This image is visible in all product box. Minimum dimensions required: 195px width X 195px height. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive. If no thumbnail is uploaded, the product's first gallery image will be used as the thumbnail image.")); ?></small>
                                </div>


                                <!--  Video Upload -->
                                <div class="form-group mb-2">
                                    <label class=" col-form-label"
                                        for="signinSrEmail"><?php echo e(translate('Videos')); ?></label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="video"  data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                <?php echo e(translate('Browse')); ?></div>
                                        </div>
                                        <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                        <input type="hidden" name="short_video" class="selected-files" >
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small
                                        class="text-muted"><?php echo e(translate('Try to upload videos under 30 seconds for better performance.')); ?></small>
                                </div>

                                <!-- short_video_thumbnail Upload -->
                                <div class="form-group mb-2">
                                    <label class="col-form-label"
                                        for="signinSrEmail"><?php echo e(translate('Video Thumbnails')); ?></label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true" >
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                <?php echo e(translate('Browse')); ?></div>
                                        </div>
                                        <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                        <input type="hidden" name="short_video_thumbnail"
                                            class="selected-files" >
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small class="text-muted">
                                    <?php echo e(translate('Add thumbnails in the same order as your videos. If you upload only one image, it will be used for all videos.')); ?>

                                    </small>
                                </div>
                            </div>

                                <!-- Youtube Video Link -->
                            <div class="form-group mb-2">
                                <label class="col-from-label"><?php echo e(translate('Youtube video / shorts link')); ?></label>
                                <div class="video-provider-link">
                                    
                                    <?php if(empty($product->video_link)): ?>
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="video_link[]"
                                                    value="" placeholder="<?php echo e(translate('Youtube video / shorts url')); ?>">
                                                <small
                                                    class="text-muted"><?php echo e(translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")); ?></small>
                                            </div>
                                            
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group row d-flex justify-content-end " style="width: 100%">

                                    <button type="button" class="btn btn-block border border-dashed hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center ml-3 mt-3"
                                        data-toggle="add-more"
                                        data-content='<div class="row mb-2">
                                                <div class="col">
                                                    <input type="text" class="form-control" name="video_link[]" value="" placeholder="<?php echo e(translate('Youtube video or short link')); ?>">
                                                    <small class="text-muted"><?php echo e(translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")); ?></small>
                                                </div>
                                                <div class="col-auto d-flex justify-content-end">
                                                        <button type="button" class="my-1 pt-2 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                </div>
                                            </div>'
                                        data-target=".video-provider-link">
                                        <i class="las la-plus mr-2"></i>
                                        <?php echo e(translate('Add Another')); ?> 
                                    </button>
                                </div>
                            </div>
                            <!-- PDF Specification -->
                            <div class="form-group mb-2">
                                <label class="col-form-label"
                                    for="signinSrEmail"><?php echo e(translate('PDF Specification')); ?></label>
                                <div class="input-group" data-toggle="aizuploader" data-type="document">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            <?php echo e(translate('Browse')); ?></div>
                                    </div>
                                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                    <input type="hidden" name="pdf" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price & Stock -->
                    <div class="tab-pane fade" id="price_and_stocks" role="tabpanel" aria-labelledby="price-and-stocks-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <!-- tab Title -->
                            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Product price & stock')); ?></h5>
                            <div class="w-100">
                                <!-- Colors -->
                                <div class="form-group row gutters-5">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" value="<?php echo e(translate('Colors')); ?>" disabled>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple disabled>
                                            <?php $__currentLoopData = \App\Models\Color::orderBy('name', 'asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option  value="<?php echo e($color->code); ?>" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:<?php echo e($color->code); ?>'></span><span><?php echo e($color->name); ?></span></span>"></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="1" type="checkbox" name="colors_active">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Attributes -->
                                <div class="form-group row gutters-5">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" value="<?php echo e(translate('Attributes')); ?>" disabled>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="choice_attributes[]" id="choice_attributes" class="form-control aiz-selectpicker" data-selected-text-format="count" data-live-search="true" multiple data-placeholder="<?php echo e(translate('Choose Attributes')); ?>">
                                            <?php $__currentLoopData = \App\Models\Attribute::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($attribute->id); ?>"><?php echo e($attribute->getTranslation('name')); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <p><?php echo e(translate('Choose the attributes of this product and then input values of each attribute')); ?></p>
                                    <br>
                                </div>

                                <!-- choice options -->
                                <div class="customer_choice_options mb-4" id="customer_choice_options">

                                </div>

                                <!-- Unit price -->
                                <div class="form-group mb-2">
                                    <label class="col-from-label"><?php echo e(translate('Unit price')); ?> <span class="text-danger">*</span></label>
                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="<?php echo e(translate('Unit price')); ?>" name="unit_price" class="form-control <?php $__errorArgs = ['unit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                </div>
                                <!-- Discount Date Range -->
                                <div class="form-group mb-2">
                                    <label class="control-label" for="start_date"><?php echo e(translate('Discount Date Range')); ?></label>
                                    <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="<?php echo e(translate('Select Date')); ?>" data-time-picker="true" data-past-disable="true"  data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                                <!-- Discount -->
                                <div class="form-group mb-2">
                                    <label class="col-from-label"><?php echo e(translate('Discount')); ?> <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="number" lang="en" value="0" step="0.01" placeholder="<?php echo e(translate('Discount')); ?>" name="discount" class="form-control <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control aiz-selectpicker" name="discount_type">
                                                <option value="amount" <?php if(old('discount_type') == 'amount'): echo 'selected'; endif; ?>><?php echo e(translate('Flat')); ?></option>
                                                <option value="percent" <?php if(old('discount_type') == 'percent'): echo 'selected'; endif; ?>><?php echo e(translate('Percent')); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <?php if(addon_is_activated('club_point')): ?>
                                    <!-- club point -->
                                    <div class="form-group mb-2">
                                        <label class=" col-from-label">
                                            <?php echo e(translate('Set Point')); ?>

                                        </label>
                                        <input type="number" lang="en" min="0" value="0" step="1" integer-only placeholder="<?php echo e(translate('1')); ?>" name="earn_point" class="form-control">
                                    </div>
                                <?php endif; ?>

                                <div id="show-hide-div">
                                    <!-- Quantity -->
                                    <div class="form-group mb-2">
                                        <label class="col-from-label"><?php echo e(translate('Quantity')); ?> <span class="text-danger">*</span></label>
                                        <input type="number" lang="en"  value="0" step="1" integer-only placeholder="<?php echo e(translate('Quantity')); ?>" name="current_stock" class="form-control">
                                    </div>
                                    <!-- SKU -->
                                    <div class="form-group">
                                        <label class="col-from-label"><?php echo e(translate('SKU')); ?></label>
                                        <input type="text" placeholder="<?php echo e(translate('SKU')); ?>" name="sku" value="<?php echo e(old('sku')); ?>" class="form-control">
                                    </div>
                                </div>
                                <!-- External link -->
                                <div class="form-group mb-2">
                                    <label class="col-from-label">
                                        <?php echo e(translate('External link')); ?>

                                    </label>
                                    <input type="text" placeholder="<?php echo e(translate('External link')); ?>" value="<?php echo e(old('external_link')); ?>" name="external_link" class="form-control">
                                    <small class="text-muted"><?php echo e(translate('Leave it blank if you do not use external site link')); ?></small>
                                </div>
                                <!-- External link button text -->
                                <div class="form-group mb-2">
                                    <label class="col-from-label">
                                        <?php echo e(translate('External link button text')); ?>

                                    </label>
                                    <input type="text" placeholder="<?php echo e(translate('External link button text')); ?>" name="external_link_btn" value="<?php echo e(old('external_link_btn')); ?>" class="form-control">
                                    <small class="text-muted"><?php echo e(translate('Leave it blank if you do not use external site link')); ?></small>
                                </div>
                                <br>
                                <!-- sku combination -->
                                <div class="sku_combination" id="sku_combination">

                                </div>
                            </div>

                            <!-- Low Stock Quantity -->
                            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Low Stock Quantity Warning')); ?></h5>
                            <div class="w-100 mb-3">
                                <div class="form-group mb-2">
                                    <label class="col-from-label">
                                        <?php echo e(translate('Quantity')); ?>

                                    </label>
                                    <input type="number" name="low_stock_quantity" value="1" min="0" step="1" integer-only class="form-control">
                                </div>
                            </div>

                            <!-- Stock Visibility State -->
                            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Stock Visibility State')); ?></h5>
                            <div class="w-100">
                                <!-- Show Stock Quantity -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Show Stock Quantity')); ?></label>
                                    <div class="col-md-9">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="stock_visibility_state" value="quantity" checked>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Show Stock With Text Only -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Show Stock With Text Only')); ?></label>
                                    <div class="col-md-9">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="stock_visibility_state" value="text">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Hide Stock -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Hide Stock')); ?></label>
                                    <div class="col-md-9">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="stock_visibility_state" value="hide">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <!-- tab Title -->
                            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('SEO Meta Tags')); ?></h5>
                            <div class="w-100">
                                <!-- Meta Title -->
                                <div class="form-group mb-2">
                                    <label class="col-from-label"><?php echo e(translate('Meta Title')); ?></label>
                                    <input type="text" class="form-control" name="meta_title" value="<?php echo e(old('meta_title')); ?>" placeholder="<?php echo e(translate('Meta Title')); ?>">
                                </div>
                                <!-- Description -->
                                <div class="form-group mb-2">
                                    <label class="col-from-label"><?php echo e(translate('Description')); ?></label>
                                    <textarea name="meta_description" rows="8" class="form-control"><?php echo e(old('meta_description')); ?></textarea>
                                </div>
                                <!--Meta Keywords -->
                                <div class="form-group mb-2">
                                    <label class="col-from-label"><?php echo e(translate('Keywords')); ?></label>
                                    <textarea class="resize-off form-control" name="meta_keywords" placeholder="<?php echo e(translate('Keyword, Keyword')); ?>"></textarea>
                                    <small class="text-muted"><?php echo e(translate('Separate with coma')); ?></small>                                   
                                </div> 
                                <!--Meta Image -->
                                <div class="form-group mb-2">
                                    <label class="col-form-label" for="signinSrEmail"><?php echo e(translate('Meta Image')); ?></label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                        </div>
                                        <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                        <input type="hidden" name="meta_img" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping -->
                    <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <!-- Shipping Configuration -->
                            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Shipping Configuration')); ?></h5>
                            <div class="w-100">
                                <!-- Cash On Delivery -->
                                <?php if(get_setting('cash_payment') == '1'): ?>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label"><?php echo e(translate('Cash On Delivery')); ?></label>
                                        <div class="col-md-9">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox" name="cash_on_delivery" value="1" checked="">
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p>
                                        <?php echo e(translate('Cash On Delivery option is disabled. Activate this feature from here')); ?>

                                        <a href="<?php echo e(route('activation.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])); ?>">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Cash Payment Activation')); ?></span>
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <?php if(get_setting('shipping_type') == 'product_wise_shipping'): ?>
                                <!-- Free Shipping -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Free Shipping')); ?></label>
                                    <div class="col-md-9">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="free" checked>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Flat Rate -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Flat Rate')); ?></label>
                                    <div class="col-md-9">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="flat_rate">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Shipping cost -->
                                <div class="flat_rate_shipping_div" style="display: none">
                                    <div class="form-group mb-2">
                                        <label class="col-from-label"><?php echo e(translate('Shipping cost')); ?></label>
                                        <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="<?php echo e(translate('Shipping cost')); ?>" name="flat_shipping_cost" class="form-control">
                                    </div>
                                </div>
                                <!-- Is Product Quantity Mulitiply -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Is Product Quantity Mulitiply')); ?></label>
                                    <div class="col-md-9">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="is_quantity_multiplied" value="1">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <?php else: ?>
                                <p>
                                    <?php echo e(translate('Product wise shipping cost is disable. Shipping cost is configured from here')); ?>

                                    <a href="<?php echo e(route('shipping_configuration.shipping_method')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['shipping_configuration.shipping_method'])); ?>">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Shipping Method')); ?></span>
                                    </a>
                                </p>
                                <?php endif; ?>
                            </div>

                            <!-- Estimate Shipping Time -->
                            <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Estimate Shipping Time')); ?></h5>
                            <div class="w-100">
                                <div class="form-group mb-2">
                                    <label class="col-from-label"><?php echo e(translate('Shipping Days')); ?></label>
                                        <div class="input-group">
                                        <input type="number" class="form-control" name="est_shipping_days" value="<?php echo e(old('est_shipping_days')); ?>" min="1" step="1" integer-only placeholder="<?php echo e(translate('Shipping Days')); ?>">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend"><?php echo e(translate('Days')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Warranty -->
                    <div class="tab-pane fade" id="warranty" role="tabpanel" aria-labelledby="warranty-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Warranty')); ?></h5>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label"><?php echo e(translate('Warranty')); ?></label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="has_warranty" onchange="warrantySelection()">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="w-100 warranty_selection_div d-none">
                                <div class="form-group row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-10">
                                        <select class="form-control aiz-selectpicker" 
                                            name="warranty_id" 
                                            id="warranty_id" 
                                            data-live-search="true">
                                            <option value=""><?php echo e(translate('Select Warranty')); ?></option>
                                            <?php $__currentLoopData = \App\Models\Warranty::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($warranty->id); ?>" <?php if(old('warranty_id') == $warranty->id): echo 'selected'; endif; ?>><?php echo e($warranty->getTranslation('text')); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                        <input type="hidden" name="warranty_note_id" id="warranty_note_id">
                                        
                                        <h5 class="fs-14 fw-600 mb-3 mt-4 pb-3" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Warranty Note')); ?></h5>
                                        <div id="warranty_note" class="">

                                        </div>
                                        <button
                                            type="button"
                                            class="btn btn-block border border-dashed hov-bg-soft-secondary mt-2 fs-14 rounded-0 d-flex align-items-center justify-content-center"
                                            onclick="noteModal('warranty')">
                                            <i class="las la-plus"></i>
                                            <span class="ml-2"><?php echo e(translate('Select Warranty Note')); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Frequently Bought Product -->
                    <div class="tab-pane fade" id="frequenty-bought-product" role="tabpanel" aria-labelledby="frequenty-bought-product-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <!-- tab Title -->
                            <h5 class="mb-3 pb-3 fs-17 fw-700"><?php echo e(translate('Frequently Bought')); ?></h5>
                            <div class="w-100">
                                <div class="d-flex mb-4">
                                    <div class="radio mar-btm mr-5 d-flex align-items-center">
                                        <input id="fq_bought_select_products" type="radio" name="frequently_bought_selection_type" value="product" onchange="fq_bought_product_selection_type()" checked >
                                        <label for="fq_bought_select_products" class="fs-14 fw-700 mb-0 ml-2"><?php echo e(translate('Select Product')); ?></label>
                                    </div>
                                    <div class="radio mar-btm mr-3 d-flex align-items-center">
                                        <input id="fq_bought_select_category" type="radio" name="frequently_bought_selection_type" value="category" onchange="fq_bought_product_selection_type()">
                                        <label for="fq_bought_select_category" class="fs-14 fw-700 mb-0 ml-2"><?php echo e(translate('Select Category')); ?></label>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="fq_bought_select_product_div">

                                            <div id="selected-fq-bought-products">

                                            </div>

                                            <button
                                                type="button"
                                                class="btn btn-block border border-dashed hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
                                                onclick="showFqBoughtProductModal()">
                                                <i class="las la-plus"></i>
                                                <span class="ml-2"><?php echo e(translate('Add More')); ?></span>
                                            </button>
                                        </div>

                                        
                                        <div class="fq_bought_select_category_div d-none">
                                            <div class="form-group row">
                                                <label class="col-md-2 col-from-label"><?php echo e(translate('Category')); ?></label>
                                                <div class="col-md-10">
                                                    <select class="form-control aiz-selectpicker" data-placeholder="<?php echo e(translate('Select a Category')); ?>" name="fq_bought_product_category_id" data-live-search="true" required>
                                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->getTranslation('name')); ?></option>
                                                            <?php $__currentLoopData = $category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php echo $__env->make('categories.child_category', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-4 text-right">
                        <button type="submit" name="button" value="unpublish" data-action="unpublish" class="mx-2 btn btn-light w-230px btn-md rounded-2 fs-14 fw-700 shadow-secondary border-soft-secondary action-btn"><?php echo e(translate('Save & Unpublish')); ?></button>
                        <button type="submit" name="button" value="publish" data-action="publish" class="mx-2 btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success action-btn"><?php echo e(translate('Save & Publish')); ?></button>
                        <button type="button" name="button" value="draft"  class="mx-2 btn btn-secondary w-230px btn-md rounded-2 fs-14 fw-700 shadow-secondary action-btn" id="saveDraftBtn"><?php echo e(translate('Save as Draft')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
	<!-- Frequently Bought Product Select Modal -->
    <?php echo $__env->make('modals.product_select_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('modals.note_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Single Product Select Modal -->
    <?php echo $__env->make('modals.products_select_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- loading Modal -->
    <?php echo $__env->make('modals.loading_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<!-- Treeview js -->
<script src="<?php echo e(static_asset('assets/js/hummingbird-treeview.js')); ?>"></script>

<script type="text/javascript">

    $(document).ready(function() {
        $("#treeview").hummingbird();

        var main_id = '<?php echo e(old("category_id")); ?>';
        var selected_ids = [];
        <?php if(old("category_ids")): ?>
            selected_ids = <?php echo json_encode(old("category_ids"), 15, 512) ?>;
        <?php endif; ?>
        for (let i = 0; i < selected_ids.length; i++) {
            const element = selected_ids[i];
            $('#treeview input:checkbox#'+element).prop('checked',true);
            $('#treeview input:checkbox#'+element).parents( "ul" ).css( "display", "block" );
            $('#treeview input:checkbox#'+element).parents( "li" ).children('.las').removeClass( "la-plus" ).addClass('la-minus');
        }

        if(main_id){
            $('#treeview input:radio[value='+main_id+']').prop('checked',true).trigger('change');
        $('#treeview input:radio[value=' + main_id + ']').next('ul').css("display", "block");
        }

        $('#treeview input:checkbox').on("click", function (){
            let $this = $(this);
            if ($this.prop('checked') && ($('#treeview input:radio:checked').length == 0)) {
                let val = $this.val();
                $('#treeview input:radio[value='+val+']').prop('checked',true);
            }
        });
    });

    $('form').bind('submit', function (e) {
		if ( $(".action-btn").attr('attempted') == 'true' ) {
			//stop submitting the form because we have already clicked submit.
			e.preventDefault();
		}
		else {
			$(".action-btn").attr("attempted", 'true');
		}
    });

    $("[name=shipping_type]").on("change", function (){
        $(".flat_rate_shipping_div").hide();

        if($(this).val() == 'flat_rate'){
            $(".flat_rate_shipping_div").show();
        }
    });

    function add_more_customer_choice_option(i, name){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'<?php echo e(route('products.add-more-choice-option')); ?>',
            data:{
               attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="<?php echo e(translate('Choice Title')); ?>" readonly>\
                    </div>\
                    <div class="col-md-9">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" data-selected-text-format="count" multiple required>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
                AIZ.plugins.bootstrapSelect('refresh');
           }
       });


    }

    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')) {
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        else {
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice",function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    $('input[name="unit_price"]').on('keyup', function() {
        update_sku();
    });

    $('input[name="name"]').on('keyup', function() {
        update_sku();
    });

    function delete_row(em){
        $(em).closest('.form-group row').remove();
        update_sku();
    }

    function delete_variant(em){
        $(em).closest('.variant').remove();
    }

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'<?php echo e(route('products.sku_combination')); ?>',
           data:$('#aizSubmitForm').serialize(),
           success: function(data) {
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.sectionFooTable('#sku_combination');
                if (data.trim().length > 1) {
                   $('#show-hide-div').hide();
                   $('input[name="current_stock"]').removeAttr('integer-only');
                }
                else {
                    $('#show-hide-div').show();
                    $('input[name="current_stock"]').attr('integer-only', 'true');
                }
           }
       });
    }

    $('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);
        $.each($("#choice_attributes option:selected"), function(){
            add_more_customer_choice_option($(this).val(), $(this).text());
        });

        update_sku();
    });

    function fq_bought_product_selection_type(){
        var productSelectionType = $("input[name='frequently_bought_selection_type']:checked").val();
        if(productSelectionType == 'product'){
            $('.fq_bought_select_product_div').removeClass('d-none');
            $('.fq_bought_select_category_div').addClass('d-none');
        }
        else if(productSelectionType == 'category'){
            $('.fq_bought_select_category_div').removeClass('d-none');
            $('.fq_bought_select_product_div').addClass('d-none');
        }
    }

    function showFqBoughtProductModal() {
        $('#fq-bought-product-select-modal').modal('show', {backdrop: 'static'});
    }

    function filterFqBoughtProduct() {
        var searchKey = $('input[name=search_keyword]').val();
        var fqBroughCategory = $('select[name=fq_brough_category]').val();
        $.post('<?php echo e(route('product.search')); ?>', { _token: AIZ.data.csrf, product_id: null, search_key:searchKey, category:fqBroughCategory, product_type:"physical" }, function(data){
            $('#product-list').html(data);
            AIZ.plugins.sectionFooTable('#product-list');
        });
    }

    function addFqBoughtProduct() {
        var selectedProducts = [];
        $("input:checkbox[name=fq_bought_product_id]:checked").each(function() {
            selectedProducts.push($(this).val());
        });

        var fqBoughtProductIds = [];
        $("input[name='fq_bought_product_ids[]']").each(function() {
            fqBoughtProductIds.push($(this).val());
        });

        var productIds = selectedProducts.concat(fqBoughtProductIds.filter((item) => selectedProducts.indexOf(item) < 0))

        $.post('<?php echo e(route('get-selected-products')); ?>', { _token: AIZ.data.csrf, product_ids:productIds}, function(data){
            $('#fq-bought-product-select-modal').modal('hide');
            $('#selected-fq-bought-products').html(data);
            AIZ.plugins.sectionFooTable('#selected-fq-bought-products');
        });
    }

    // Warranty
    function warrantySelection(){
        if($('input[name="has_warranty"]').is(':checked')) {
            $('.warranty_selection_div').removeClass('d-none');
            $('#warranty_id').attr('required', true);
        }
        else {
            $('.warranty_selection_div').addClass('d-none');
            $('#warranty_id').removeAttr('required');
        }
    }

    // Refundable
    function isRefundable() {
        const refundType = "<?php echo e(get_setting('refund_type')); ?>";
        const $refundable = $('input[name="refundable"]');
        const $mainCategoryRadio = $('input[name="category_id"]:checked');
        const $note = $('#refundable-note');

        $refundable.off('change.isRefundableLock');

        if (!refundType) {
            $refundable.prop('checked', false);
            $refundable.prop('disabled', true);
            $('.refund-block').addClass('d-none');
            $note.text('<?php echo e(translate("Refund system is not configured.")); ?>')
                .removeClass('d-none');
            return;
        }

        if (refundType !== 'category_based_refund') {
            $refundable.prop('disabled', false);
            $note.addClass('d-none');
            $('.refund-block').toggleClass('d-none', !$refundable.is(':checked'));
            return;
        }

        if (!$mainCategoryRadio.length) {
            $refundable.prop('checked', false);
            $refundable.prop('disabled', true);
            $('.refund-block').addClass('d-none');
            $note.text('<?php echo e(translate("Your refund type is category based. At first select the main category.")); ?>')
                .removeClass('d-none');
            return;
        }

        const categoryId = $mainCategoryRadio.val();
        $.ajax({
            type: 'POST',
            url: '<?php echo e(route("admin.products.check_refundable_category")); ?>',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                category_id: categoryId
            },
            success: function (response) {
                if (response.status === 'success' && response.is_refundable) {
                    $refundable.prop('disabled', false);
                    $note.text('<?php echo e(translate("This product allows refunds.")); ?>')
                        .removeClass('d-none');
                    $refundable.on('change.isRefundableLock', function () {
                        if (!$refundable.is(':checked')) {
                            $('.refund-block').addClass('d-none');
                        } else {
                            $('.refund-block').removeClass('d-none');
                        }
                    });
                } else {
                    $refundable.prop('checked', false);
                    $refundable.prop('disabled', true);
                    $('.refund-block').addClass('d-none');
                    $note.text('<?php echo e(translate("Selected main category has no refund. Select a refundable category.")); ?>')
                        .removeClass('d-none');
                }
            },
            error: function () {
                $refundable.prop('checked', false);
                $refundable.prop('disabled', true);
                $('.refund-block').addClass('d-none');
                $note.text('<?php echo e(translate("Could not verify category refund status.")); ?>')
                    .removeClass('d-none');
            }
        });
    }
    
    function noteModal(noteType){
        $.post('<?php echo e(route('get_notes')); ?>',{_token:'<?php echo e(@csrf_token()); ?>', note_type: noteType}, function(data){
            $('#note_modal #note_modal_content').html(data);
            $('#note_modal').modal('show', {backdrop: 'static'});
        });
    }

    function addNote(noteId, noteType){
        var noteDescription = $('#note_description_'+ noteId).val();
        $('#'+noteType+'_note_id').val(noteId);
        $('#'+noteType+'_note').html(noteDescription);
        $('#'+noteType+'_note').addClass('border border-gray my-2 p-2');
        $('#note_modal').modal('hide');
    }

</script>
<script>
    $(document).ready(function(){
        var hash = document.location.hash;
        if (hash) {
            $('.nav-tabs a[href="'+hash+'"]').tab('show');
        }else{
            $('.nav-tabs a[href="#general"]').tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });
    });

</script>

<?php echo $__env->make('partials.product.product_temp_data', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <script type="text/javascript">
    $(document).ready(function () {
        warrantySelection();
        isRefundable();

        $(document).on('change', 'input[name="category_id"]', function () {
            isRefundable();
        });

        $('input[name="refundable"]').on('change', function () {
            if (!$('input[name="refundable"]').prop('disabled')) {
                $('.refund-block').toggleClass('d-none', !$(this).is(':checked'));
            }
        });
    });

    function showProductSelectModal() {
        $('#products_select_modal').modal('show', {backdrop: 'static'});
        $('#products_select_modal #modal-title-text').text("<?php echo e(translate('Copy Products')); ?>");
        $('#products_select_modal .action-btn').text("<?php echo e(translate('Copy')); ?>").attr('onclick', 'duplicateProduct()');
    }

    function filterProductByCategory() {
        var searchKey = $('input[name=search_product_keyword]').val();
        var selectedCategory = $('select[name=selected_Products_category]').val();
        $.post('<?php echo e(route('products.search')); ?>', { _token: AIZ.data.csrf, product_id: null, search_key:searchKey, category:selectedCategory, product_type:"physical",single_select: 1 }, function(data){
            $('#products-list').html(data);
            AIZ.plugins.sectionFooTable('#products-list');
        });
    }

    var duplicateProductUrl = "<?php echo e(route('products.duplicate', ':id')); ?>";

   // innitially assign pid null
    let draftProductId = null;

   $(document).ready(function() {
        function saveDraft() {
            let form = $('#aizSubmitForm')[0];
            let formData = new FormData(form);

            // Update Draft
            if (draftProductId) {
                formData.append('id', draftProductId);
            }
            let draftBtn = $('#saveDraftBtn');
            let draftBtnText = draftBtn.length ? draftBtn.text() : '';
            if (draftBtn.length) {
                draftBtn.prop('disabled', true).html('<i class="las la-spinner la-spin mr-2"></i> '+AIZ.local.saving_as_draft);
            }

            $.ajax({
                url: "<?php echo e(route('products.store_as_draft')); ?>",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        draftProductId = response.product_id;

                        // Update form action for future edits
                        $('#aizSubmitForm').attr('action', "<?php echo e(url('admin/products/update')); ?>/" + draftProductId);

                        if ($('#aizSubmitForm input[name="_method"]').length === 0) {
                            $('#aizSubmitForm').append('<input type="hidden" name="_method" value="POST">');
                        }

                        if (draftBtn.length) {
                         draftBtn.prop('disabled', false).html('<i class="las la-check-circle mr-2"></i>'+draftBtnText);
                        }
                        AIZ.plugins.notify('success',  `${response.message}`);
                        savedClearTempdata();
                    } else {
                        if (draftBtn.length) {
                            draftBtn.prop('disabled', false).html('<i class="las la-exclamation-circle text-danger mr-2"></i>'+draftBtnText);
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        Object.values(errors).forEach(function(fieldErrors) {
                            // fieldErrors.forEach(function(error) {
                            //     AIZ.plugins.notify('danger', error);
                            // });
                        if (draftBtn.length) {
                            draftBtn.prop('disabled', false).html('<i class="las la-exclamation-circle text-danger mr-2"></i>'+draftBtnText);
                        }
                        });
                    } else {
                        if (draftBtn.length) {
                            draftBtn.prop('disabled', false).html('<i class="las la-exclamation-circle text-danger mr-2"></i>'+draftBtnText);
                        }
                         //AIZ.plugins.notify('danger', AIZ.local.error_occured_while_processing);
                    }
                }
            });
        }

        // Auto-save on tab click
        $('a[data-toggle="tab"]').on('show.bs.tab', function() {
            var productName = $('input[name="name"]').val();
            if (productName && productName.trim() !== '') {
                saveDraft();
            } 
        });

        $('#saveDraftBtn').on('click', function(e) {
            e.preventDefault();
            saveDraft();
        });

    });


    


</script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/backend/product/products/create.blade.php ENDPATH**/ ?>