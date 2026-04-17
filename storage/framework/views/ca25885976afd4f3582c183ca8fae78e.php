

<?php $__env->startSection('panel_content'); ?>

    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3"><?php echo e(translate('Products')); ?></h1>
        </div>
      </div>
    </div>

    <div class="row gutters-10 justify-content-center">
        <div class="col-md-4 mx-auto mb-3">
            <a href="<?php echo e(route('seller.products.create')); ?>">
              <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                  <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                      <i class="las la-plus la-3x text-white"></i>
                  </span>
                  <div class="fs-18 text-primary"><?php echo e(translate('Add New Product')); ?></div>
              </div>
            </a>
        </div>

    </div>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6"><?php echo e(translate('All Products')); ?></h5>
                </div>

                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        <?php echo e(translate('Bulk Action')); ?>

                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal"> <?php echo e(translate('Delete selection')); ?></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search" name="search" <?php if(isset($search)): ?> value="<?php echo e($search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Search product')); ?>">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th width="30%"><?php echo e(translate('Name')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Current Qty')); ?></th>
                            <th><?php echo e(translate('Base Price')); ?></th>
                            <?php if(get_setting('product_approve_by_admin') == 1): ?>
                                <th data-breakpoints="md"><?php echo e(translate('Approval')); ?></th>
                            <?php endif; ?>
                            <th data-breakpoints="md"><?php echo e(translate('Published')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Featured')); ?></th>
                            <th data-breakpoints="md" class="text-right"><?php echo e(translate('Options')); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="form-group d-inline-block">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]" value="<?php echo e($product->id); ?>">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('product', $product->slug)); ?>" target="_blank" class="text-reset">
                                        <?php echo e($product->getTranslation('name')); ?>

                                    </a>
                                </td>
                                <td>
                                    <?php
                                        $qty = 0;
                                        foreach ($product->stocks as $key => $stock) {
                                            $qty += $stock->qty;
                                        }
                                        echo $qty;
                                    ?>
                                </td>
                                <td><?php echo e($product->unit_price); ?></td>
                                <?php if(get_setting('product_approve_by_admin') == 1): ?>
                                    <td>
                                        <?php if($product->approved == 1): ?>
                                            <span class="badge badge-inline badge-success"><?php echo e(translate('Approved')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-inline badge-info"><?php echo e(translate('Pending')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_published(this)" value="<?php echo e($product->id); ?>" type="checkbox" <?php if($product->published == 1) echo "checked";?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_featured(this)" value="<?php echo e($product->id); ?>" type="checkbox" <?php if($product->seller_featured == 1) echo "checked";?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td class="text-right">
                                <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="<?php echo e(route('seller.products.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')])); ?>" title="<?php echo e(translate('Edit')); ?>">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="<?php echo e(route('seller.products.duplicate', $product->id)); ?>" class="btn btn-soft-success btn-icon btn-circle btn-sm"  title="<?php echo e(translate('Duplicate')); ?>">
                                    <i class="las la-copy"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('seller.products.destroy', $product->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    <?php echo e($products->links()); ?>

                </div>
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <!-- Delete modal -->
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Bulk Delete modal -->
    <?php echo $__env->make('modals.bulk_delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">

        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;                        
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;                       
                });
            }
          
        });

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('seller.products.featured')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Featured products updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                    location.reload();
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('seller.products.published')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Published products updated successfully')); ?>');
                }
                else if(data == 3){
                    AIZ.plugins.notify('danger', '<?php echo e(translate('GST verification is pending for your account.')); ?>');
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                    location.reload();
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "<?php echo e(route('seller.products.bulk-delete')); ?>",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/seller/product/products/index.blade.php ENDPATH**/ ?>