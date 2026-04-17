<?php
    $hashtagsValue = $hashtagsValue ?? '';
    $fieldName = $fieldName ?? 'hashtags';
    $labelText = $labelText ?? 'Hashtags';
    $showLabel = $showLabel ?? true;
    $suggestedTags = $suggestedTags ?? ['Handmade', 'Wood', 'Tool', 'Bag', 'Fashion'];
    $wrapperId = $wrapperId ?? ('hashtag_' . uniqid());
?>

<div id="<?php echo e($wrapperId); ?>" class="hashtag-input-wrap">
    <?php if($showLabel): ?>
        <label class="d-block mb-2"><?php echo e(translate($labelText)); ?></label>
    <?php endif; ?>

    <div class="d-flex flex-column" style="gap:8px;">
        <div class="input-group flex-nowrap" style="max-width:420px;">
            <div class="input-group-prepend">
                <span class="input-group-text text-muted fw-700">#</span>
            </div>
            <select class="form-control aiz-selectpicker js-hashtag-select" data-live-search="true">
                <option value=""><?php echo e(translate('Add Hashtags')); ?></option>
                <?php $__currentLoopData = $suggestedTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tag); ?>"><?php echo e($tag); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="input-group-append">
                <button type="button" class="btn btn-light js-hashtag-add">
                    <?php echo e(translate('Add')); ?>

                </button>
            </div>
        </div>

        <input type="text" class="form-control js-hashtag-input" placeholder="<?php echo e(translate('Type a tag')); ?>" style="max-width:320px;">
    </div>

    <div class="mt-2 text-muted js-hashtag-line"></div>
    <input type="hidden" name="<?php echo e($fieldName); ?>" class="js-hashtag-hidden" value="<?php echo e($hashtagsValue); ?>">
</div>

<script>
    (function () {
        var wrapper = document.getElementById(<?php echo json_encode($wrapperId, 15, 512) ?>);
        if (!wrapper) return;

        var input = wrapper.querySelector('.js-hashtag-input');
        var select = wrapper.querySelector('.js-hashtag-select');
        var addBtn = wrapper.querySelector('.js-hashtag-add');
        var hidden = wrapper.querySelector('.js-hashtag-hidden');
        var line = wrapper.querySelector('.js-hashtag-line');

        function normalizeTag(tag) {
            return tag.replace(/^#+/, '').trim();
        }

        function getTags() {
            var current = hidden.value || '';
            return current.split(',')
                .map(function (tag) { return normalizeTag(tag); })
                .filter(Boolean);
        }

        function setTags(tags) {
            var unique = [];
            tags.forEach(function (tag) {
                if (!tag) return;
                if (unique.indexOf(tag) === -1) {
                    unique.push(tag);
                }
            });
            hidden.value = unique.join(', ');
            line.textContent = unique.join(', ');
        }

        function addTag(tag) {
            var tags = getTags();
            var normalized = normalizeTag(tag);
            if (!normalized) return;
            if (tags.indexOf(normalized) === -1) {
                tags.push(normalized);
            }
            setTags(tags);
        }

        addBtn.addEventListener('click', function () {
            var tag = input.value || select.value;
            addTag(tag);
            input.value = '';
            if (select) {
                select.value = '';
                if (window.AIZ && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect();
                }
            }
        });

        setTags(getTags());
    })();
</script>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/partials/hashtag_input.blade.php ENDPATH**/ ?>