

<?php $__env->startSection('panel_content'); ?>
    <div class="page-content mx-0">
        <div class="aiz-titlebar mt-2 mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3"><?php echo e(translate('Add Your Product')); ?></h1>
                </div>
                <div class="col text-right">
                    <a class="btn btn-xs btn-soft-primary" href="javascript:void(0);" onclick="clearTempdata()">
                        <?php echo e(translate('Clear Tempdata')); ?>

                    </a>
                </div>
            </div>
        </div>

        <!-- Error Meassages -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Data type -->
        <input type="hidden" id="data_type" value="physical">

        <form class="" action="<?php echo e(route('seller.products.store')); ?>" method="POST" enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-8">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="added_by" value="seller">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Product Information')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Product Name')); ?> <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="<?php echo e(translate('Product Name')); ?>" onchange="update_sku()" required>
                                </div>
                            </div>
                            <div class="form-group row" id="brand">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Brand')); ?></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value=""><?php echo e(translate('Select Brand')); ?></option>
                                        <?php $__currentLoopData = \App\Models\Brand::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($brand->id); ?>"><?php echo e($brand->getTranslation('name')); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Unit')); ?> <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="unit"
                                        placeholder="<?php echo e(translate('Unit (e.g. KG, Pc etc)')); ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Weight')); ?>

                                    <small>(<?php echo e(translate('In Kg')); ?>)</small></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="weight" step="0.01" value="0.00"
                                        placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Minimum Purchase Qty')); ?> <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" class="form-control" name="min_qty" value="1"
                                        min="1" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Tags')); ?></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        placeholder="<?php echo e(translate('Type and hit enter to add a tag')); ?>">
                                </div>
                            </div>
                            <?php if(addon_is_activated('pos_system')): ?>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Barcode')); ?></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="barcode"
                                            placeholder="<?php echo e(translate('Barcode')); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Product Images')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Gallery Images')); ?></label>
                                <div class="col-md-8">
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
                                    <small class="text-muted"><?php echo e(translate('These images are visible in product details page gallery. Minimum dimensions required: 900px width X 900px height.')); ?></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Thumbnail Image')); ?></label>
                                <div class="col-md-8">
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
                                    <small class="text-muted"><?php echo e(translate("This image is visible in all product box. Minimum dimensions required: 195px width X 195px height. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.")); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Product Videos')); ?></h5>
                        </div>
                        <div class="card-body">
                           

                            <!--  Video Upload -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail"><?php echo e(translate('Videos')); ?></label>
                                <div class="col-md-9">
                                    <div class="input-group" data-toggle="aizuploader" data-type="video"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                <?php echo e(translate('Browse')); ?></div>
                                        </div>
                                        <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                        <input type="hidden" name="short_video" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small class="text-muted"><?php echo e(translate('Try to upload videos under 30 seconds for better performance.')); ?></small>
                                </div>
                            </div>

                            <!-- short_video_thumbnail Upload -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail"><?php echo e(translate('Video Thumbnails')); ?></label>
                                <div class="col-md-9">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                <?php echo e(translate('Browse')); ?></div>
                                        </div>
                                        <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                        <input type="hidden" name="short_video_thumbnail" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                   <small class="text-muted">
                                    <?php echo e(translate('Add thumbnails in the same order as your videos. If you upload only one image, it will be used for all videos.')); ?>

                                    </small>
                                </div>
                            </div>


                            <!-- Youtube Video Link -->
                            <div class="form-group row mb-5">
                                <label
                                    class="col-md-3 col-from-label"><?php echo e(translate('Youtube video / shorts link')); ?></label>
                                <div class="video-provider-link col-md-9">
                                    
                                    <?php if(empty($product->video_link)): ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="video_link[]"
                                                    value=""
                                                    placeholder="<?php echo e(translate('Youtube video / shorts url')); ?>">
                                                <small
                                                    class="text-muted"><?php echo e(translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")); ?></small>
                                            </div>

                                        </div>
                                    <?php endif; ?>


                                    
                                </div>
                                <div class="form-group row mb-5 d-flex justify-content-end " style="width: 100%">

                                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="add-more"
                                        data-content='<div class="row">
                                                    <div class="col-md-11">
                                                        <input type="text" class="form-control" name="video_link[]" value="" placeholder="<?php echo e(translate('Youtube video or short link')); ?>">
                                                        <small class="text-muted"><?php echo e(translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")); ?></small>
                                                    </div>
                                                    <div class="col-1 d-flex justify-content-end">
                                                            <button type="button" class="mt-1 btn btn-icon  btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                                <i class="las la-times"></i>
                                                            </button>
                                                    </div>
                                                </div>'
                                        data-target=".video-provider-link">
                                        <?php echo e(translate('Add Another')); ?> 
                                    </button>
                                </div>





                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Product Variation')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="<?php echo e(translate('Colors')); ?>" disabled>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" name="colors[]"
                                        data-selected-text-format="count" id="colors" multiple disabled>
                                        <?php $__currentLoopData = \App\Models\Color::orderBy('name', 'asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($color->code); ?>"
                                                data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:<?php echo e($color->code); ?>'></span><span><?php echo e($color->name); ?></span></span>">
                                            </option>
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

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="<?php echo e(translate('Attributes')); ?>"
                                        disabled>
                                </div>
                                <div class="col-md-8">
                                    <select name="choice_attributes[]" id="choice_attributes"
                                        class="form-control aiz-selectpicker" data-live-search="true"
                                        data-selected-text-format="count" multiple
                                        data-placeholder="<?php echo e(translate('Choose Attributes')); ?>">
                                        <?php $__currentLoopData = \App\Models\Attribute::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($attribute->id); ?>"><?php echo e($attribute->getTranslation('name')); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <p><?php echo e(translate('Choose the attributes of this product and then input values of each attribute')); ?>

                                </p>
                                <br>
                            </div>

                            <div class="customer_choice_options" id="customer_choice_options">

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Product price + stock')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Unit price')); ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="<?php echo e(translate('Unit price')); ?>" name="unit_price" class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 control-label"
                                    for="start_date"><?php echo e(translate('Discount Date Range')); ?> </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control aiz-date-range" name="date_range"
                                        placeholder="<?php echo e(translate('Select Date')); ?>" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Discount')); ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="<?php echo e(translate('Discount')); ?>" name="discount" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="amount"><?php echo e(translate('Flat')); ?></option>
                                        <option value="percent"><?php echo e(translate('Percent')); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div id="show-hide-div">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label"><?php echo e(translate('Quantity')); ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0" step="1"
                                            placeholder="<?php echo e(translate('Quantity')); ?>" name="current_stock"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        <?php echo e(translate('SKU')); ?>

                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" placeholder="<?php echo e(translate('SKU')); ?>" name="sku"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <?php if(get_setting('product_external_link_for_seller') == 1): ?>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        <?php echo e(translate('External link')); ?>

                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" placeholder="<?php echo e(translate('External link')); ?>"
                                            name="external_link" class="form-control">
                                        <small
                                            class="text-muted"><?php echo e(translate('Leave it blank if you do not use external site link')); ?></small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        <?php echo e(translate('External link button text')); ?>

                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" placeholder="<?php echo e(translate('External link button text')); ?>"
                                            name="external_link_btn" class="form-control">
                                        <small
                                            class="text-muted"><?php echo e(translate('Leave it blank if you do not use external site link')); ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <br>
                            <div class="sku_combination" id="sku_combination">

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Product Description')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Description')); ?></label>
                                <div class="col-md-8">
                                    <textarea class="aiz-text-editor" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('PDF Specification')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail"><?php echo e(translate('PDF Specification')); ?></label>
                                <div class="col-md-8">
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
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('SEO Meta Tags')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Meta Title')); ?></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="meta_title"
                                        placeholder="<?php echo e(translate('Meta Title')); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Description')); ?></label>
                                <div class="col-md-8">
                                    <textarea name="meta_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Keywords')); ?></label>
                                <div class="col-md-8">
                                    <textarea class="resize-off form-control" name="meta_keywords" placeholder="<?php echo e(translate('Keyword, Keyword')); ?>"></textarea>
                                    <small class="text-muted"><?php echo e(translate('Separate with coma')); ?></small>                                   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail"><?php echo e(translate('Meta Image')); ?></label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                <?php echo e(translate('Browse')); ?></div>
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

                    
                    <?php if(addon_is_activated('refund_request')): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6"><?php echo e(translate('Refund')); ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label"><?php echo e(translate('Refundable')); ?>?</label>
                                    <div class="col-md-10">
                                        <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                            <input type="checkbox" name="refundable" checked value="1"
                                                onchange="isRefundable()">
                                            <span></span>
                                        </label>
                                        <small id="refundable-note" class="text-muted d-none"></small>
                                    </div>
                                </div>

                                <div class="w-100 refund-block d-none">
                                    <div class="form-group row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <input type="hidden" name="refund_note_id" id="refund_note_id">
                                            
                                            <h5 class="fs-14 fw-600 mb-3 mt-4 pb-3" style="border-bottom: 1px dashed #e4e5eb;"><?php echo e(translate('Refund Note')); ?></h5>
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
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Warranty')); ?></h5>
                        </div>
                        <div class="card-body">
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

                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Frequently Bought')); ?></h5>
                        </div>
                        <div class="w-100">
                            <div class="d-flex my-3">
                                <div class="align-items-center d-flex mar-btm ml-4 mr-5 radio">
                                    <input id="fq_bought_select_products" type="radio" name="frequently_bought_selection_type" value="product" onchange="fq_bought_product_selection_type()" checked >
                                    <label for="fq_bought_select_products" class="fs-14 fw-500 mb-0 ml-2"><?php echo e(translate('Select Product')); ?></label>
                                </div>
                                <div class="radio mar-btm mr-3 d-flex align-items-center">
                                    <input id="fq_bought_select_category" type="radio" name="frequently_bought_selection_type" value="category" onchange="fq_bought_product_selection_type()">
                                    <label for="fq_bought_select_category" class="fs-14 fw-500 mb-0 ml-2"><?php echo e(translate('Select Category')); ?></label>
                                </div>
                            </div>

                            <div class="px-3 px-md-4">
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
                </div>

                <div class="col-lg-4">

                    <div class="card">
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
                            <div class="h-300px overflow-auto c-scrollbar-light">
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

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">
                                <?php echo e(translate('Shipping Configuration')); ?>

                            </h5>
                        </div>

                        <div class="card-body">
                            <?php if(get_setting('shipping_type') == 'product_wise_shipping'): ?>
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label"><?php echo e(translate('Free Shipping')); ?></label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="free" checked>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label"><?php echo e(translate('Flat Rate')); ?></label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="flat_rate">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flat_rate_shipping_div" style="display: none">
                                    <div class="form-group row">
                                        <label class="col-md-6 col-from-label"><?php echo e(translate('Shipping cost')); ?></label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="0"
                                                step="0.01" placeholder="<?php echo e(translate('Shipping cost')); ?>"
                                                name="flat_shipping_cost" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label"><?php echo e(translate('Is Product Quantity Mulitiply')); ?></label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="is_quantity_multiplied" value="1">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>
                                    <?php echo e(translate('Shipping configuration is maintained by Admin.')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Low Stock Quantity Warning')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="name">
                                    <?php echo e(translate('Quantity')); ?>

                                </label>
                                <input type="number" name="low_stock_quantity" value="1" min="0"
                                    step="1" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">
                                <?php echo e(translate('Stock Visibility State')); ?>

                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label"><?php echo e(translate('Show Stock Quantity')); ?></label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="stock_visibility_state" value="quantity" checked>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label"><?php echo e(translate('Show Stock With Text Only')); ?></label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="stock_visibility_state" value="text">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label"><?php echo e(translate('Hide Stock')); ?></label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="stock_visibility_state" value="hide">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Cash On Delivery')); ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if(get_setting('cash_payment') == '1'): ?>
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label"><?php echo e(translate('Status')); ?></label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="cash_on_delivery" value="1" checked="">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>
                                    <?php echo e(translate('Cash On Delivery activation is maintained by Admin.')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Estimate Shipping Time')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="name">
                                    <?php echo e(translate('Shipping Days')); ?>

                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="est_shipping_days" min="1"
                                        step="1" placeholder="<?php echo e(translate('Shipping Days')); ?>">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend"><?php echo e(translate('Days')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <?php if(addon_is_activated('gst_system')): ?>
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('HSN & GST')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-2">
                                <label class="col-from-label"><?php echo e(translate('HSN Code')); ?> <span class="text-danger">*</span></label>
                                <input type="text" lang="en"
                                    placeholder="<?php echo e(translate('HSN Code')); ?>" name="hsn_code" class="form-control"
                                    required>
                            </div>
                            <div class="form-group mb-2">
                                <label class="col-from-label"><?php echo e(translate('GST Rate')); ?> <span class="text-danger">*</span></label>
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="<?php echo e(translate('GST Rate')); ?>" name="gst_rate" class="form-control"
                                    required>
                            </div>
                        </div>
                        
                        <?php else: ?>
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('VAT & Tax')); ?></h5>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = \App\Models\Tax::where('tax_status', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label for="name">
                                    <?php echo e($tax->name); ?>

                                    <input type="hidden" value="<?php echo e($tax->id); ?>" name="tax_id[]">
                                </label>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="number" lang="en" min="0" value="0" step="0.01"
                                            placeholder="<?php echo e(translate('Tax')); ?>" name="tax[]" class="form-control"
                                            required>
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
                <div class="col-12">
                    <div class="mar-all text-right mb-2">
                        <button type="submit" name="button" value="publish"
                            class="btn btn-primary"><?php echo e(translate('Upload Product')); ?></button>
                    </div>
                </div>
            </div>

        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
	<!-- Frequently Bought Product Select Modal -->
    <?php echo $__env->make('modals.product_select_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('modals.note_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- Treeview js -->
<script src="<?php echo e(static_asset('assets/js/hummingbird-treeview.js')); ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#treeview").hummingbird();

        $('#treeview input:checkbox').on("click", function (){
            let $this = $(this);
            if ($this.prop('checked') && ($('#treeview input:radio:checked').length == 0)) {
                let val = $this.val();
                $('#treeview input:radio[value='+val+']').prop('checked',true);
            }
        });
    });

    $("[name=shipping_type]").on("change", function() {
        $(".product_wise_shipping_div").hide();
        $(".flat_rate_shipping_div").hide();
        if ($(this).val() == 'product_wise') {
            $(".product_wise_shipping_div").show();
        }
        if ($(this).val() == 'flat_rate') {
            $(".flat_rate_shipping_div").show();
        }

    });

    function add_more_customer_choice_option(i, name) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '<?php echo e(route('seller.products.add-more-choice-option')); ?>',
            data: {
                attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                    <div class="form-group row">\
                        <div class="col-md-3">\
                            <input type="hidden" name="choice_no[]" value="' + i + '">\
                            <input type="text" class="form-control" name="choice[]" value="' + name +
                    '" placeholder="<?php echo e(translate('Choice Title')); ?>" readonly>\
                        </div>\
                        <div class="col-md-8">\
                            <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_' + i + '[]" multiple>\
                                ' + obj + '\
                            </select>\
                        </div>\
                    </div>');
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });


    }

    $('input[name="colors_active"]').on('change', function() {
        if (!$('input[name="colors_active"]').is(':checked')) {
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        } else {
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice", function() {
        update_sku();
    });

    $('#colors').on('change', function() {
            update_sku();
        });

    $('input[name="unit_price"]').on('keyup', function() {
        update_sku();
    });

    // $('input[name="name"]').on('keyup', function() {
    //     update_sku();
    // });

    function delete_row(em) {
        $(em).closest('.form-group row').remove();
        update_sku();
    }

    function delete_variant(em) {
        $(em).closest('.variant').remove();
    }

    function update_sku() {
        $.ajax({
            type: "POST",
            url: '<?php echo e(route('seller.products.sku_combination')); ?>',
            data: $('#choice_form').serialize(),
            success: function(data) {
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.sectionFooTable('#sku_combination');
                if (data.trim().length > 1) {
                    $('#show-hide-div').hide();
                } else {
                    $('#show-hide-div').show();
                }
            }
        });
    }

    $('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);
        $.each($("#choice_attributes option:selected"), function() {
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
        $.post('<?php echo e(route('seller.product.search')); ?>', { _token: AIZ.data.csrf, product_id: null, search_key:searchKey, category:fqBroughCategory, product_type:"physical" }, function(data){
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

        $.post('<?php echo e(route('seller.get-selected-products')); ?>', { _token: AIZ.data.csrf, product_ids:productIds}, function(data){
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
            url: '<?php echo e(route("seller.products.check_refundable_category")); ?>',
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
    $(document).ready(function () {
        var hash = document.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        } else {
            $('.nav-tabs a[href="#general"]').tab('show');
        }

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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/product/products/create.blade.php ENDPATH**/ ?>