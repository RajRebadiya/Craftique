<?php $__env->startSection('content'); ?>
    <?php
        $titleGr = old('title_gr', $item->title_gr ?? ($item->title ?? ''));
        $titleEn = old('title_en', $item->title_en ?? '');

        $introGr = old('intro_gr', $item->intro_gr ?? ($item->intro ?? ''));
        $introEn = old('intro_en', $item->intro_en ?? '');

        $descriptionGr = old('description_gr', $item->description_gr ?? ($item->description ?? ''));
        $descriptionEn = old('description_en', $item->description_en ?? '');

        $coverImage = old('cover_image', $item->cover_image ?? '');
        $hashtags = old('hashtags', $item->hashtags ?? '');
        $billingPeriod = old('billing_period', $item->billing_period ?? '');
        $status = old('status', $item->status ?? 'draft');

        $collectionItems = old('collection_items', $collectionItems ?? []);
        if (!is_array($collectionItems) || empty($collectionItems)) {
            $collectionItems = [
                [
                    'title_gr' => '',
                    'title_en' => '',
                    'description_gr' => '',
                    'description_en' => '',
                    'cover_image' => '',
                    'product_id' => '',
                ],
            ];
        }

        $productOptions = collect($products ?? [])
            ->map(function ($product) {
                return [
                    'id' => (int) $product->id,
                    'label' => $product->name . ' (#' . $product->id . ')',
                ];
            })
            ->values()
            ->all();
    ?>

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="h3"><?php echo e($page_title); ?></h1>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('showcase.collection.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <input type="hidden" name="id" value="<?php echo e(old('id', $item->id ?? '')); ?>">

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-700 mb-2">Collection Structure Notes</h5>
                        <p class="text-muted mb-2">
                            This admin form now follows the current Collection blueprint: central content plus per-card
                            items.
                        </p>
                        <ul class="mb-0 pl-3 text-muted">
                            <li>Central / Unified: title, intro and description.</li>
                            <li>Per Card: title, cover image, description and linked product.</li>
                            <li>Legacy linked products remain compatible through controller sync.</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Greek Content</h5>
                    </div>

                    <div class="card-body">
                        <?php if(isset($shops)): ?>
                            <div class="form-group">
                                <label>Creator / Brand</label>
                                <select class="form-control" name="seller_id">
                                    <option value="">Select creator / brand</option>
                                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($shop->id); ?>"
                                            <?php echo e(old('seller_id', $item->seller_id ?? '') == $shop->id ? 'selected' : ''); ?>>
                                            <?php echo e($shop->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['seller_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Τίτλος (GR)</label>
                            <input type="text" class="form-control" name="title_gr" placeholder="Εισαγωγή τίτλου"
                                value="<?php echo e($titleGr); ?>">
                            <?php $__errorArgs = ['title_gr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label>Σύντομο Intro (GR)</label>
                            <input type="text" class="form-control" name="intro_gr" placeholder="Εισαγωγή σύντομου intro"
                                value="<?php echo e($introGr); ?>">
                            <?php $__errorArgs = ['intro_gr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group mb-0">
                            <label>Περιγραφή (GR)</label>
                            <textarea class="form-control" rows="6" name="description_gr" placeholder="Γράψε την περιγραφή στα ελληνικά..."><?php echo e($descriptionGr); ?></textarea>
                            <?php $__errorArgs = ['description_gr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">English Content</h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>Title (EN)</label>
                            <input type="text" class="form-control" name="title_en" placeholder="Enter title"
                                value="<?php echo e($titleEn); ?>">
                            <?php $__errorArgs = ['title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label>Short Intro (EN)</label>
                            <input type="text" class="form-control" name="intro_en" placeholder="Enter short intro"
                                value="<?php echo e($introEn); ?>">
                            <?php $__errorArgs = ['intro_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group mb-0">
                            <label>Description (EN)</label>
                            <textarea class="form-control" rows="6" name="description_en" placeholder="Write the description in English..."><?php echo e($descriptionEn); ?></textarea>
                            <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Hashtags</h5>
                    </div>

                    <div class="card-body">
                        <?php echo $__env->make('partials.hashtag_input', [
                            'hashtagsValue' => $hashtags,
                            'fieldName' => 'hashtags',
                            'labelText' => 'Hashtags',
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Collection Cover / Main Visual</h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>Collection Cover Image</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" name="cover_image" class="selected-files"
                                    value="<?php echo e($coverImage); ?>">
                            </div>
                            <div class="file-preview box sm"></div>
                            <?php $__errorArgs = ['cover_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group mb-0">
                            <label>Subscription Type</label>
                            <select class="form-control" name="billing_period">
                                <option value="">Select billing period</option>
                                <option value="monthly" <?php echo e($billingPeriod == 'monthly' ? 'selected' : ''); ?>>Monthly
                                </option>
                                <option value="yearly" <?php echo e($billingPeriod == 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                            </select>
                            <?php $__errorArgs = ['billing_period'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                        <h5 class="mb-0">Collection Cards / Items</h5>
                        <button type="button" id="add-collection-item" class="btn btn-soft-primary btn-sm">
                            <i class="las la-plus mr-1"></i>Add Card
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info">
                            Each card can have its own title, cover image, description and linked product.
                        </div>

                        <div id="collection-items-wrap">
                            <?php $__currentLoopData = $collectionItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $collectionItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $rowTitleGr = $collectionItem['title_gr'] ?? '';
                                    $rowTitleEn = $collectionItem['title_en'] ?? '';
                                    $rowDescriptionGr = $collectionItem['description_gr'] ?? '';
                                    $rowDescriptionEn = $collectionItem['description_en'] ?? '';
                                    $rowCoverImage = $collectionItem['cover_image'] ?? '';
                                    $rowProductId = $collectionItem['product_id'] ?? '';
                                ?>

                                <div class="card border mb-3 js-collection-item-card">
                                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
                                        style="gap:10px;">
                                        <strong>Collection Card #<?php echo e($loop->iteration); ?></strong>
                                        <button type="button"
                                            class="btn btn-soft-danger btn-sm js-remove-collection-item">
                                            Remove
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Card Title (GR)</label>
                                            <input type="text" name="collection_items[<?php echo e($index); ?>][title_gr]"
                                                class="form-control" value="<?php echo e($rowTitleGr); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Card Title (EN)</label>
                                            <input type="text" name="collection_items[<?php echo e($index); ?>][title_en]"
                                                class="form-control" value="<?php echo e($rowTitleEn); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Card Description (GR)</label>
                                            <textarea name="collection_items[<?php echo e($index); ?>][description_gr]" class="form-control" rows="4"><?php echo e($rowDescriptionGr); ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Card Description (EN)</label>
                                            <textarea name="collection_items[<?php echo e($index); ?>][description_en]" class="form-control" rows="4"><?php echo e($rowDescriptionEn); ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Card Cover Image</label>
                                            <div class="input-group" data-toggle="aizuploader" data-type="image"
                                                data-multiple="false">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                        Browse</div>
                                                </div>
                                                <div class="form-control file-amount">Choose File</div>
                                                <input type="hidden"
                                                    name="collection_items[<?php echo e($index); ?>][cover_image]"
                                                    class="selected-files" value="<?php echo e($rowCoverImage); ?>">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label>Linked Product</label>
                                            <select name="collection_items[<?php echo e($index); ?>][product_id]"
                                                class="form-control">
                                                <option value="">Select One</option>
                                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($product->id); ?>"
                                                        <?php echo e((string) $rowProductId === (string) $product->id ? 'selected' : ''); ?>>
                                                        <?php echo e($product->name); ?> (#<?php echo e($product->id); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Publishing</h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="draft" <?php echo e($status == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                <option value="published" <?php echo e($status == 'published' ? 'selected' : ''); ?>>Published
                                </option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary">Save Collection</button>
                </div>
            </div>

            <div class="col-lg-6">
                <?php echo $__env->make('partials.showcase_preview', [
                    'previewTitle' => translate('Collection Preview'),
                    'previewNote' => translate(
                        'Preview will appear here after you add the cards and save the form.'),
                    'previewType' => 'collection',
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrap = document.getElementById('collection-items-wrap');
            const addBtn = document.getElementById('add-collection-item');
            const productOptions = <?php echo json_encode($productOptions, 15, 512) ?>;
            let nextIndex = <?php echo e(count($collectionItems)); ?>;

            function escapeHtml(value) {
                return String(value || '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function productSelectOptions(selectedValue = '') {
                let html = '<option value="">Select One</option>';

                productOptions.forEach(function(product) {
                    const selected = String(selectedValue) === String(product.id) ? 'selected' : '';
                    html += '<option value="' + escapeHtml(product.id) + '" ' + selected + '>' + escapeHtml(
                        product.label) + '</option>';
                });

                return html;
            }

            function itemCardTemplate(index) {
                return `
            <div class="card border mb-3 js-collection-item-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                    <strong>Collection Card #${index + 1}</strong>
                    <button type="button" class="btn btn-soft-danger btn-sm js-remove-collection-item">
                        Remove
                    </button>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label>Card Title (GR)</label>
                        <input type="text" name="collection_items[${index}][title_gr]" class="form-control" value="">
                    </div>

                    <div class="form-group">
                        <label>Card Title (EN)</label>
                        <input type="text" name="collection_items[${index}][title_en]" class="form-control" value="">
                    </div>

                    <div class="form-group">
                        <label>Card Description (GR)</label>
                        <textarea name="collection_items[${index}][description_gr]" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Card Description (EN)</label>
                        <textarea name="collection_items[${index}][description_en]" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Card Cover Image</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                            </div>
                            <div class="form-control file-amount">Choose File</div>
                            <input type="hidden" name="collection_items[${index}][cover_image]" class="selected-files" value="">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="form-group mb-0">
                        <label>Linked Product</label>
                        <select name="collection_items[${index}][product_id]" class="form-control">
                            ${productSelectOptions('')}
                        </select>
                    </div>
                </div>
            </div>
        `;
            }

            addBtn.addEventListener('click', function() {
                wrap.insertAdjacentHTML('beforeend', itemCardTemplate(nextIndex));

                // Sirf last added card pe initialize karo
                const newCard = wrap.lastElementChild;

                if (window.AIZ && AIZ.plugins) {
                    if (AIZ.plugins.bootstrapSelect) {
                        $(newCard).find('select').bootstrapSelect(); // scoped
                    }
                    if (AIZ.plugins.aizUppy) {
                        $(newCard).find('[data-toggle="aizuploader"]').each(function() {
                            AIZ.plugins.aizUppy(this); // agar API allow kare
                        });
                    }
                }

                nextIndex++;
            });

            wrap.addEventListener('click', function(event) {
                const removeBtn = event.target.closest('.js-remove-collection-item');
                if (!removeBtn) return;

                const card = removeBtn.closest('.js-collection-item-card');
                if (card) {
                    card.remove();
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/backend/showcase/collection.blade.php ENDPATH**/ ?>