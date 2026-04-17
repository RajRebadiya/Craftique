<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Products</h5>
    </div>

    <div class="card-body">
        <?php
            $allCategories = $showcaseCategories ?? collect();
            $rootCategories = $allCategories->filter(fn($cat) => empty($cat->parent_id));
            $subCategories = $allCategories->filter(fn($cat) => !empty($cat->parent_id));
            $productCategoryMap = $productCategoryMap ?? [];
        ?>

        <div class="form-group mb-3">
            <label class="mb-1 text-muted">Category</label>
            <select class="form-control" id="showcaseCategorySelect">
                <option value="">Select category</option>
                <?php $__currentLoopData = $rootCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->getTranslation('name')); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label class="mb-1 text-muted">Subcategory</label>
            <select class="form-control" id="showcaseSubcategorySelect">
                <option value="">Select subcategory</option>
                <?php $__currentLoopData = $subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" data-parent="<?php echo e($category->parent_id); ?>">
                        <?php echo e($category->getTranslation('name')); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label class="aiz-checkbox">
                <input type="checkbox" id="showcaseAutoSelect" checked>
                <span class="text-muted">Auto-select all products from selected category/subcategory</span>
                <span class="aiz-square-check"></span>
            </label>
        </div>

        <div class="form-group mb-3">
            <input type="text" class="form-control" id="showcaseProductSearch" placeholder="Search products...">
        </div>

        <div id="showcaseProductList" style="max-height: 650px; overflow-y: auto;">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $map = $productCategoryMap[$product->id] ?? [];
                    $categoryId = $map['category_id'] ?? '';
                    $subcategoryId = $map['subcategory_id'] ?? '';
                ?>
                <div class="border rounded p-2 mb-2 showcase-product-row">
                    <label class="d-flex align-items-start mb-0" style="gap:10px; cursor:pointer;">
                        <input
                            type="checkbox"
                            name="product_ids[]"
                            value="<?php echo e($product->id); ?>"
                            <?php echo e(in_array($product->id, $selectedProducts ?? []) ? 'checked' : ''); ?>

                            style="margin-top:4px;"
                            class="js-showcase-product"
                        >
                        <span style="line-height:1.4;">
                            <strong
                                data-category-id="<?php echo e($categoryId); ?>"
                                data-subcategory-id="<?php echo e($subcategoryId); ?>"
                            >#<?php echo e($product->id); ?></strong><br>
                            <span class="showcase-product-name"><?php echo e($product->name); ?></span>
                        </span>
                    </label>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="mb-0 text-muted">No products found.</p>
            <?php endif; ?>
        </div>
        <p class="mt-2 mb-0 text-muted small" id="showcaseProductHint">
            Select a category to load products.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('showcaseProductSearch');
    var rows = document.querySelectorAll('.showcase-product-row');
    var categorySelect = document.getElementById('showcaseCategorySelect');
    var subcategorySelect = document.getElementById('showcaseSubcategorySelect');
    var autoSelect = document.getElementById('showcaseAutoSelect');

    function applyFilters() {
        var term = searchInput ? searchInput.value.toLowerCase() : '';
        var categoryId = categorySelect ? categorySelect.value : '';
        var subcategoryId = subcategorySelect ? subcategorySelect.value : '';
        var hint = document.getElementById('showcaseProductHint');
        var isAuto = autoSelect ? autoSelect.checked : false;
        var hasCheckedRows = false;

        rows.forEach(function (row) {
            var input = row.querySelector('input[type="checkbox"]');
            if (input && input.checked) {
                hasCheckedRows = true;
            }
        });

        if (!categoryId) {
            rows.forEach(function (row) {
                var input = row.querySelector('input[type="checkbox"]');
                var text = row.innerText.toLowerCase();
                var matchText = !term || text.indexOf(term) !== -1;
                var keepVisible = !!(input && input.checked && matchText);
                row.style.display = keepVisible ? '' : 'none';
            });
            if (hint) {
                hint.style.display = hasCheckedRows ? 'none' : '';
            }
            return;
        }
        if (hint) {
            hint.style.display = 'none';
        }

        rows.forEach(function (row) {
            var text = row.innerText.toLowerCase();
            var tag = row.querySelector('strong[data-category-id]');
            var rowCategory = tag ? tag.getAttribute('data-category-id') : '';
            var rowSubcategory = tag ? tag.getAttribute('data-subcategory-id') : '';

            var matchText = text.indexOf(term) !== -1;
            var matchCategory = !categoryId || rowCategory === categoryId;
            var matchSubcategory = !subcategoryId || rowSubcategory === subcategoryId;

            var visible = (matchText && matchCategory && matchSubcategory);
            row.style.display = visible ? '' : 'none';
            if (isAuto) {
                var input = row.querySelector('input[type="checkbox"]');
                if (input) input.checked = visible;
            }
        });
    }

    function updateSubcategoryOptions() {
        if (!subcategorySelect) return;
        var categoryId = categorySelect ? categorySelect.value : '';
        subcategorySelect.disabled = !categoryId;
        Array.from(subcategorySelect.options).forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }
            var parent = option.getAttribute('data-parent') || '';
            option.hidden = categoryId && parent !== categoryId;
        });
        if (subcategorySelect.value && subcategorySelect.selectedOptions[0].hidden) {
            subcategorySelect.value = '';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', applyFilters);
    }
    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            updateSubcategoryOptions();
            applyFilters();
        });
        updateSubcategoryOptions();
    }
    if (subcategorySelect) {
        subcategorySelect.addEventListener('change', applyFilters);
    }
    if (autoSelect) {
        autoSelect.addEventListener('change', applyFilters);
    }

    applyFilters();

    <?php if(empty($allowMultiple)): ?>
        var productInputs = document.querySelectorAll('.js-showcase-product');
        productInputs.forEach(function (input) {
            input.addEventListener('change', function () {
                if (!this.checked) {
                    return;
                }
                productInputs.forEach(function (other) {
                    if (other !== input) {
                        other.checked = false;
                    }
                });
            });
        });
    <?php endif; ?>
});
</script>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/backend/showcase/_products.blade.php ENDPATH**/ ?>