<?php if(count($combinations) > 0): ?>
<table class="table table-bordered aiz-table">
	<thead>
		<tr>
			<td class="text-center">
				<?php echo e(translate('Variant')); ?>

			</td>
			<td class="text-center">
				<?php echo e(translate('Variant Price')); ?>

			</td>
			<td class="text-center" data-breakpoints="lg">
				<?php echo e(translate('SKU')); ?>

			</td>
			<td class="text-center" data-breakpoints="lg">
				<?php echo e(translate('Quantity')); ?>

			</td>
			<td class="text-center" data-breakpoints="lg">
				<?php echo e(translate('Photo')); ?>

			</td>
		</tr>
	</thead>
	<tbody>
	<?php $__currentLoopData = $combinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $combination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<?php
			$sku = '';
			foreach (explode(' ', $product_name) as $key => $value) {
				$sku .= substr($value, 0, 1);
			}

			$str = '';
			foreach ($combination as $key => $item){
				if($key > 0 ){
					$str .= '-'.str_replace(' ', '', $item);
					$sku .='-'.str_replace(' ', '', $item);
				}
				else{
					if($colors_active == 1){
						$color_name = \App\Models\Color::where('code', $item)->first()->name;
						$str .= $color_name;
						$sku .='-'.$color_name;
					}
					else{
						$str .= str_replace(' ', '', $item);
						$sku .='-'.str_replace(' ', '', $item);
					}
				}
			}
		?>
		<?php if(strlen($str) > 0): ?>
			<tr class="variant">
				<td>
					<label for="" class="control-label"><?php echo e($str); ?></label>
				</td>
				<td>
					<input type="number" lang="en" name="price_<?php echo e($str); ?>" value="<?php echo e($unit_price); ?>" min="0" step="0.01" class="form-control" required>
				</td>
				<td>
					<input type="text" name="sku_<?php echo e($str); ?>" value="" class="form-control">
				</td>
				<td>
					<input type="number" lang="en" name="qty_<?php echo e($str); ?>" value="10" min="0" step="1" class="form-control" required>
				</td>
				<td>
					<div class=" input-group " data-toggle="aizuploader" data-type="image">
						<div class="input-group-prepend">
							<div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
						</div>
						<div class="form-control file-amount text-truncate"><?php echo e(translate('Choose File')); ?></div>
						<input type="hidden" name="img_<?php echo e($str); ?>" class="selected-files">
					</div>
					<div class="file-preview box sm"></div>
				</td>
			</tr>
		<?php endif; ?>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</tbody>
</table>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/backend/product/products/sku_combinations.blade.php ENDPATH**/ ?>