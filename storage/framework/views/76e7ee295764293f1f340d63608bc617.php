<?php
    $previewTitle = $previewTitle ?? translate('Preview');
    $previewNote = $previewNote ?? translate('Preview will appear after saving.');
    $previewType = $previewType ?? 'generic';
    $shopName = $shopName ?? (optional(Auth::user()->shop)->name ?? '');
    $shopLogo =
        $shopLogo ?? (optional(Auth::user()->shop)->logo ? uploaded_asset(optional(Auth::user()->shop)->logo) : '');
?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header">
        <h5 class="mb-0 h6"><?php echo e($previewTitle); ?></h5>
    </div>
    <div class="card-body">
        <div class="showcase-preview border rounded bg-white" data-preview-type="<?php echo e($previewType); ?>"
            data-shop-name="<?php echo e($shopName); ?>" data-shop-logo="<?php echo e($shopLogo); ?>">
            <div class="showcase-preview__empty text-center text-muted">
                <div class="mb-2"><?php echo e($previewNote); ?></div>
                <div class="small"><?php echo e(translate('This area is reserved for the live preview.')); ?></div>
            </div>

            <div class="showcase-preview__content d-none">
                <div class="showcase-preview__frame">
                    <div class="showcase-preview__header">
                        <div class="showcase-preview__logo"></div>
                        <div class="showcase-preview__meta">
                            <div class="showcase-preview__name">Shop Name</div>
                            <div class="showcase-preview__brand text-muted"></div>
                        </div>
                        <button type="button" class="btn btn-sm btn-soft-primary showcase-preview__follow">
                            <?php echo e(translate('Follow')); ?>

                        </button>
                    </div>

                    <div class="showcase-preview__body">
                        <div class="showcase-preview__media"></div>
                        <div class="showcase-preview__title"></div>
                        <div class="showcase-preview__subtitle text-muted"></div>
                        <div class="showcase-preview__description text-muted"></div>
                        <div class="showcase-preview__hashtags text-muted"></div>
                        <div class="showcase-preview__story-product d-none"></div>
                        <div class="showcase-preview__vitrin-thumbs d-none"></div>
                        <div class="showcase-preview__vitrin-products-label d-none">
                            <?php echo e(translate('Shop our selection')); ?></div>
                        <div class="showcase-preview__vitrin-grid d-none"></div>
                        <div class="showcase-preview__collection-layout d-none">
                            <div class="showcase-preview__collection-main">
                                <div class="showcase-preview__collection-slider">
                                    <div class="showcase-preview__collection-main-meta">
                                        <div class="showcase-preview__collection-main-title"></div>
                                        <div class="showcase-preview__collection-main-by text-muted"></div>
                                        <div class="showcase-preview__collection-main-desc text-muted"></div>
                                        <div class="showcase-preview__collection-main-hashtags text-muted"></div>
                                    </div>
                                    <div class="showcase-preview__collection-slide"></div>
                                    <div class="showcase-preview__collection-main-detail">
                                        <div class="showcase-preview__collection-active-title"></div>
                                        <div class="showcase-preview__collection-active-desc text-muted"></div>
                                        <div class="showcase-preview__collection-main-product d-none"></div>
                                        <button type="button" class="showcase-preview__collection-main-btn">
                                            <?php echo e(translate('View Collection')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase-preview__collection-active">
                                <div class="showcase-preview__cards d-none"></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary showcase-preview__cta">
                            <?php echo e(translate('View Product')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .showcase-preview {
        min-height: 260px;
        padding: 20px;
    }

    .showcase-preview__frame {
        max-width: 420px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .showcase-preview__header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .showcase-preview__logo {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #6b7280;
        overflow: hidden;
    }

    .showcase-preview__logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .showcase-preview__meta {
        flex: 1;
    }

    .showcase-preview__name {
        font-weight: 600;
    }

    .showcase-preview__media {
        width: 100%;
        background: #f3f4f6;
        border-radius: 10px;
        overflow: hidden;
        min-height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }

    .showcase-preview__media img,
    .showcase-preview__media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__subtitle {
        display: none;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__body {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__title {
        order: 1;
        font-weight: 800;
        font-size: 14px;
        margin-top: 2px;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__description {
        order: 2;
        font-size: 12px;
        opacity: 0.9;
        margin-top: -6px;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__media {
        order: 3;
        min-height: 260px;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__vitrin-thumbs {
        order: 4;
        margin-top: 0;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__vitrin-products-label {
        order: 5;
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #111827;
        margin-top: -2px;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__vitrin-grid {
        order: 6;
        margin-top: 0;
    }

    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__hashtags,
    .showcase-preview[data-preview-type="vitrin"] .showcase-preview__cta {
        display: none !important;
    }

    .showcase-preview[data-preview-type="vitrin"]:not([data-vitrin-detail-open="1"]) .showcase-preview__media,
    .showcase-preview[data-preview-type="vitrin"]:not([data-vitrin-detail-open="1"]) .showcase-preview__vitrin-grid {
        display: none !important;
    }

    .showcase-preview[data-preview-type="vitrin"][data-vitrin-detail-open="1"] .showcase-preview__vitrin-thumbs {
        display: none !important;
    }

    .showcase-preview__vitrin-media {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .showcase-preview__vitrin-media>img,
    .showcase-preview__vitrin-media>video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__vitrin-overlay {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.94);
        padding: 10px;
        overflow: auto;
        border-radius: 10px;
        backdrop-filter: blur(2px);
    }

    .showcase-preview__vitrin-overlay-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
        color: #111827;
        font-weight: 700;
        font-size: 12px;
    }

    .showcase-preview__vitrin-overlay-close {
        border: 1px solid rgba(0, 0, 0, 0.1);
        background: #fff;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .showcase-preview__vitrin-overlay-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .showcase-preview__vitrin-overlay-item {
        border: 1px solid rgba(0, 0, 0, 0.08);
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
    }

    .showcase-preview__vitrin-overlay-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        display: block;
        background: #f3f4f6;
    }

    .showcase-preview__vitrin-overlay-body {
        padding: 10px 12px 12px;
    }

    .showcase-preview__vitrin-overlay-title {
        font-weight: 800;
        font-size: 12px;
        line-height: 1.2;
        margin-bottom: 8px;
        color: #111827;
    }

    .showcase-preview__vitrin-overlay-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .showcase-preview__vitrin-overlay-price {
        font-weight: 800;
        color: #111827;
        font-size: 12px;
    }

    .showcase-preview__vitrin-overlay-buy {
        border: 1px solid #c7a08e;
        background: #c7a08e;
        color: #fff;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 800;
    }

    .showcase-preview__cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin-top: 12px;
    }

    .showcase-preview__card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }

    .showcase-preview__card img {
        width: 100%;
        height: 90px;
        object-fit: cover;
    }

    .showcase-preview__card-title {
        padding: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .showcase-preview__hashtags {
        margin-top: 6px;
        font-size: 12px;
    }

    .showcase-preview__story-product {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
        padding: 8px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
    }

    .showcase-preview__story-product-thumb,
    .showcase-preview__story-product-placeholder {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        overflow: hidden;
        flex: 0 0 46px;
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    }

    .showcase-preview__story-product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__story-product-body {
        min-width: 0;
        flex: 1;
    }

    .showcase-preview__story-product-name {
        font-size: 12px;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }

    .showcase-preview__story-product-price {
        font-size: 11px;
        color: #6b7280;
        margin-top: 2px;
    }

    .showcase-preview[data-preview-type="story"] .showcase-preview__body {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .showcase-preview[data-preview-type="story"] .showcase-preview__media {
        order: 1;
        margin-bottom: 0;
    }

    .showcase-preview[data-preview-type="story"] .showcase-preview__title {
        order: 2;
        font-size: 12px;
        font-weight: 700;
    }

    .showcase-preview[data-preview-type="story"] .showcase-preview__hashtags {
        order: 3;
        display: block;
        margin-top: -4px;
    }

    .showcase-preview[data-preview-type="story"] .showcase-preview__story-product {
        order: 4;
    }

    .showcase-preview[data-preview-type="story"] .showcase-preview__cta {
        order: 5;
        align-self: flex-start;
    }

    .showcase-preview[data-preview-type="story"] .showcase-preview__subtitle,
    .showcase-preview[data-preview-type="story"] .showcase-preview__description {
        display: none;
    }

    .showcase-preview[data-preview-type="launch"] .showcase-preview__header {
        display: none;
    }

    .showcase-preview[data-preview-type="launch"] .showcase-preview__cta {
        display: none;
    }

    .showcase-preview[data-preview-type="collection"] {
        background: #f7f2ee;
    }

    .showcase-preview[data-preview-type="collection"] .showcase-preview__frame {
        max-width: 980px;
        background: transparent;
        border: none;
        box-shadow: none;
        padding: 0;
    }

    .showcase-preview[data-preview-type="collection"] .showcase-preview__cards {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .showcase-preview[data-preview-type="collection"] .showcase-preview__brand {
        display: none !important;
    }

    .showcase-preview__collection-layout {
        display: grid;
        grid-template-columns: minmax(280px, 320px) minmax(0, 1fr);
        gap: 22px;
        align-items: start;
    }

    .showcase-preview__collection-main,
    .showcase-preview__collection-active {
        width: 100%;
    }

    .showcase-preview__collection-main {
        display: flex;
        justify-content: center;
    }

    .showcase-preview__collection-slider {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 312px;
        background: #cfa68f;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .showcase-preview__collection-slide {
        order: 2;
        position: relative;
        height: 330px;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 14px 14px;
    }

    .showcase-preview__collection-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 14px;
    }

    .showcase-preview__collection-slide-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 999px;
        background: rgba(17, 24, 39, 0.55);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        z-index: 2;
    }

    .showcase-preview__collection-slide-nav--prev {
        left: 14px;
    }

    .showcase-preview__collection-slide-nav--next {
        right: 14px;
    }

    .showcase-preview__collection-main-meta {
        order: 1;
        padding: 14px 18px 8px;
        background: transparent;
        color: #fff;
        text-align: center;
    }

    .showcase-preview__collection-main-title {
        font-weight: 800;
        font-size: 18px;
        line-height: 1.15;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__collection-main-by {
        font-size: 12px;
        margin-bottom: 6px;
        color: rgba(255, 255, 255, 0.92) !important;
    }

    .showcase-preview__collection-main-desc {
        font-size: 11px;
        line-height: 1.35;
        margin-bottom: 8px;
        color: rgba(255, 255, 255, 0.92) !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__collection-main-hashtags {
        font-size: 11px;
        line-height: 1.3;
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.92) !important;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__collection-main-detail {
        order: 3;
        display: flex;
        flex-direction: column;
        gap: 7px;
        padding: 0 18px 18px;
        color: #fff;
    }

    .showcase-preview__collection-active-title {
        font-size: 13px;
        font-weight: 800;
        text-align: center;
        line-height: 1.25;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__collection-active-desc {
        font-size: 11px;
        text-align: center;
        color: rgba(255, 255, 255, 0.9) !important;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__collection-main-product {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        padding: 10px;
        font-size: 12px;
        color: #7a5a4b;
    }

    .showcase-preview__collection-main-product .product-row {
        display: grid;
        grid-template-columns: 40px 1fr auto;
        gap: 8px 10px;
        align-items: center;
    }

    .showcase-preview__collection-main-product .product-thumb,
    .showcase-preview__collection-main-product .product-thumb-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        overflow: hidden;
        background: rgba(122, 90, 75, 0.12);
        grid-row: 1 / span 2;
    }

    .showcase-preview__collection-main-product .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__collection-main-product .product-name {
        font-weight: 800;
        line-height: 1.2;
        color: #7a5a4b;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__collection-main-product .product-price {
        font-weight: 800;
        justify-self: end;
        color: #7a5a4b;
    }

    .showcase-preview__collection-main-product .product-icons {
        grid-column: 3;
        display: flex;
        gap: 6px;
        justify-content: flex-end;
        color: #7a5a4b;
        font-size: 12px;
    }

    .showcase-preview__collection-main-btn {
        border: 1px solid rgba(255, 255, 255, 0.75);
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 999px;
        padding: 8px 12px;
        font-size: 11px;
        font-weight: 800;
        width: 100%;
    }

    .showcase-preview__collection-card {
        background: #cfa68f;
        border-radius: 16px;
        padding: 12px;
        color: #fff;
        display: flex;
        flex-direction: column;
        gap: 8px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        min-height: 244px;
    }

    .showcase-preview__collection-media {
        border-radius: 12px;
        overflow: hidden;
        height: 140px;
        background: rgba(255, 255, 255, 0.15);
    }

    .showcase-preview__collection-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .showcase-preview__collection-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 10px;
        padding: 8px 10px;
        font-size: 12px;
        margin-top: auto;
    }

    .showcase-preview__collection-footer .brand {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }

    .showcase-preview__collection-footer .brand-logo {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        color: #7a5a4b;
        font-weight: 700;
    }

    .showcase-preview__collection-footer .brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .showcase-preview__collection-btn {
        border: 1px solid rgba(255, 255, 255, 0.65);
        color: #fff;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        background: transparent;
    }

    @media (max-width: 991px) {
        .showcase-preview__collection-layout {
            grid-template-columns: 1fr;
        }

        .showcase-preview__collection-slider {
            max-width: 100%;
        }
    }

    .showcase-preview__collection-product {
        background: rgba(255, 255, 255, 0.18);
        border-radius: 10px;
        padding: 9px;
        font-size: 11px;
        margin-top: 6px;
    }

    .showcase-preview__collection-product .product-row {
        display: grid;
        grid-template-columns: 44px 1fr auto;
        gap: 8px 10px;
        align-items: center;
    }

    .showcase-preview__collection-product .product-thumb,
    .showcase-preview__collection-product .product-thumb-placeholder {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.3);
        grid-row: 1 / span 2;
    }

    .showcase-preview__collection-product .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__collection-product .product-name {
        font-weight: 700;
        line-height: 1.2;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__collection-product .product-price {
        font-weight: 700;
        justify-self: end;
        white-space: nowrap;
    }

    .showcase-preview__collection-product .product-cta {
        grid-column: 2 / -1;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.65);
        background: rgba(255, 255, 255, 0.9);
        color: #7a5a4b;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .showcase-preview__card-title {
        font-size: 13px;
        font-weight: 800;
        line-height: 1.25;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__collection-card .text-white-50.small {
        line-height: 1.35;
        min-height: 28px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__vitrin-thumbs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .showcase-preview[data-preview-type="vitrin"]:not([data-vitrin-detail-open="1"]) .showcase-preview__vitrin-thumbs {
        display: flex !important;
    }

    .showcase-preview__vitrin-thumb {
        width: 72px;
        height: 72px;
        border-radius: 10px;
        overflow: hidden;
        background: #f3f4f6;
        border: 1px solid rgba(0, 0, 0, 0.06);
        cursor: pointer;
        padding: 0;
    }

    .showcase-preview[data-preview-type="vitrin"]:not([data-vitrin-detail-open="1"]) .showcase-preview__vitrin-thumb:first-child {
        width: 100%;
        height: 220px;
        border-radius: 12px;
    }

    .showcase-preview__vitrin-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__vitrin-thumb video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__vitrin-thumb-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        display: block;
    }

    .showcase-preview__vitrin-grid {
        margin-top: 12px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .showcase-preview__vitrin-item {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .showcase-preview__vitrin-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__vitrin-item-image-placeholder {
        width: 100%;
        height: 120px;
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        display: block;
    }

    .showcase-preview__vitrin-item-body {
        padding: 10px 12px 12px;
    }

    .showcase-preview__vitrin-item-title {
        font-weight: 700;
        font-size: 12px;
        line-height: 1.2;
        margin-bottom: 6px;
    }

    .showcase-preview__vitrin-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 12px;
    }

    .showcase-preview__vitrin-item-row .price {
        font-weight: 700;
        color: #111827;
    }

    .showcase-preview__vitrin-item-row .buy {
        border: 1px solid #c7a08e;
        background: #c7a08e;
        color: #fff;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .showcase-preview[data-preview-type="launch"] .showcase-preview__frame {
        max-width: 520px;
        padding: 0;
        overflow: hidden;
    }

    .showcase-preview__launch-card {
        background: #c7a08e;
        color: #fff;
        border-radius: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 14px 14px 0;
    }

    .showcase-preview__launch-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 12px;
    }

    .showcase-preview__launch-shop {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .showcase-preview__launch-shop .shop-logo {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        overflow: hidden;
        color: #7a5a4b;
    }

    .showcase-preview__launch-shop .shop-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .showcase-preview__launch-follow {
        background: rgba(255, 255, 255, 0.85);
        color: #7a5a4b;
        border: none;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 700;
    }

    .showcase-preview__launch-media {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 84px;
        gap: 12px;
        align-items: stretch;
    }

    .showcase-preview__launch-main {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        overflow: hidden;
        min-height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .showcase-preview__launch-main img,
    .showcase-preview__launch-main video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .showcase-preview__launch-thumbs {
        display: grid;
        grid-template-rows: repeat(4, 1fr);
        gap: 8px;
    }

    .showcase-preview__launch-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        overflow: hidden;
        min-height: 44px;
    }

    .showcase-preview__launch-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__launch-brand {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        opacity: 0.95;
        font-weight: 700;
    }

    .showcase-preview__launch-brand .brand-logo {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        overflow: hidden;
        color: #7a5a4b;
    }

    .showcase-preview__launch-brand .brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .showcase-preview__launch-title {
        font-weight: 800;
        margin-top: -2px;
        font-size: 14px;
        line-height: 1.15;
    }

    .showcase-preview__launch-tagline {
        font-size: 12px;
        opacity: 0.95;
        margin-top: -6px;
    }

    .showcase-preview__launch-description {
        font-size: 11px;
        line-height: 1.45;
        opacity: 0.92;
        margin-top: -6px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .showcase-preview__launch-hashtags {
        font-size: 11px;
        opacity: 0.9;
        margin-top: -4px;
    }

    .showcase-preview__launch-product {
        display: grid;
        grid-template-columns: 50px minmax(0, 1fr) auto auto;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.88);
        color: #7a5a4b;
        border-top: 1px solid rgba(122, 90, 75, 0.12);
        padding: 12px 14px;
        font-size: 12px;
        margin-left: -14px;
        margin-right: -14px;
    }

    .showcase-preview__launch-product-thumb,
    .showcase-preview__launch-product-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        overflow: hidden;
        background: #ead8cf;
    }

    .showcase-preview__launch-product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .showcase-preview__launch-product-meta {
        min-width: 0;
    }

    .showcase-preview__launch-product-name {
        font-weight: 800;
        line-height: 1.15;
        color: #7a5a4b;
    }

    .showcase-preview__launch-product .price {
        font-weight: 800;
        font-size: 13px;
        color: #7a5a4b;
        white-space: nowrap;
    }

    .showcase-preview__launch-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        color: #7a5a4b;
        font-size: 10px;
        font-weight: 800;
        min-width: 18px;
    }

    .showcase-preview__launch-action {
        line-height: 1;
    }

    .showcase-preview__launch-cart {
        width: calc(100% + 28px);
        margin-left: -14px;
        margin-right: -14px;
        border: none;
        border-radius: 0 0 12px 12px;
        padding: 10px 12px;
        background: rgba(167, 118, 92, 0.75);
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-size: 11px;
        font-weight: 800;
    }

    .showcase-preview__launch-side-placeholder {
        width: 100%;
        height: 100%;
        min-height: 44px;
        display: block;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.35), rgba(255, 255, 255, 0.18));
    }

    .showcase-preview__launch-brand-name {
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .showcase-preview__launch-open,
    .showcase-preview__launch-product .cta,
    .showcase-preview__launch-products {
        display: none !important;
    }

    .showcase-preview__launch-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.94);
        padding: 10px;
        border-radius: 12px;
        overflow: auto;
        backdrop-filter: blur(2px);
    }

    .showcase-preview__launch-overlay-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
        color: #111827;
        font-weight: 800;
        font-size: 12px;
    }

    .showcase-preview__launch-overlay-close {
        border: 1px solid rgba(0, 0, 0, 0.1);
        background: #fff;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 800;
    }

    .showcase-preview__launch-overlay-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .showcase-preview__launch-overlay-item {
        border: 1px solid rgba(0, 0, 0, 0.08);
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
    }

    .showcase-preview__launch-overlay-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        display: block;
        background: #f3f4f6;
    }

    .showcase-preview__launch-overlay-body {
        padding: 10px 12px 12px;
    }

    .showcase-preview__launch-overlay-title {
        font-weight: 800;
        font-size: 12px;
        line-height: 1.2;
        margin-bottom: 8px;
        color: #111827;
    }

    .showcase-preview__launch-overlay-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .showcase-preview__launch-overlay-price {
        font-weight: 800;
        color: #111827;
        font-size: 12px;
    }

    .showcase-preview__launch-overlay-buy {
        border: 1px solid #c7a08e;
        background: #c7a08e;
        color: #fff;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 800;
    }
</style>

<script>
    (function() {
        var fileUrlCache = {};
        var productInfoCache = {};
        var T = {
            follow: <?php echo json_encode(translate('Follow'), 15, 512) ?>,
            viewProduct: <?php echo json_encode(translate('View Product'), 15, 512) ?>,
            viewStorefront: <?php echo json_encode(translate('View Storefront'), 15, 512) ?>,
            viewCollection: <?php echo json_encode(translate('View Collection'), 15, 512) ?>,
            addToCart: <?php echo json_encode(translate('Add to Cart'), 15, 512) ?>,
            backToProducts: <?php echo json_encode(translate('Back to Products'), 15, 512) ?>,
            elegantTagline: <?php echo json_encode(translate('Elegant Design Luxury'), 15, 512) ?>,
            storefront: <?php echo json_encode(translate('Storefront'), 15, 512) ?>,
        };

        function getAppUrl() {
            var meta = document.querySelector('meta[name="app-url"]');
            var fromMeta = meta ? meta.getAttribute('content') : '';
            if (fromMeta) return fromMeta;
            return (window.APP_URL || window.appUrl || window.location.origin || '').toString();
        }

        function getCsrfToken() {
            var meta = document.querySelector('meta[name="csrf-token"]');
            var fromMeta = meta ? meta.getAttribute('content') : '';
            if (fromMeta) return fromMeta;
            var input = document.querySelector('input[name="_token"]');
            return input ? (input.value || '') : '';
        }

        function isUrl(value) {
            return /^https?:\/\//i.test(value) || /^\/|^data:/i.test(value);
        }

        function fetchFileInfo(ids) {
            if (typeof $ === 'undefined') {
                return Promise.resolve([]);
            }
            var appUrl = getAppUrl();
            if (!appUrl) {
                return Promise.resolve([]);
            }
            return new Promise(function(resolve) {
                $.post(
                    appUrl.replace(/\/$/, '') + "/aiz-uploader/get_file_by_ids", {
                        _token: getCsrfToken(),
                        ids: ids
                    },
                    function(data) {
                        resolve(Array.isArray(data) ? data : []);
                    }
                ).fail(function() {
                    resolve([]);
                });
            });
        }

        function fetchProductInfo(ids) {
            if (typeof $ === 'undefined') {
                return Promise.resolve({});
            }
            var appUrl = getAppUrl();
            if (!appUrl) {
                return Promise.resolve({});
            }

            var requested = [];
            (ids || []).forEach(function(id) {
                var key = String(id || '').trim();
                if (!key) return;
                // if (!Object.prototype.hasOwnProperty.call(productInfoCache, key)) {
                var cachedItem = productInfoCache[key];
                if (
                    !Object.prototype.hasOwnProperty.call(productInfoCache, key) ||
                    !cachedItem ||
                    typeof cachedItem.brand_name === 'undefined'
                ) {
                    requested.push(parseInt(key, 10));
                }
            });

            if (requested.length === 0) {
                return Promise.resolve(productInfoCache);
            }

            return new Promise(function(resolve) {
                $.post(
                    appUrl.replace(/\/$/, '') + "/showcase/preview/products", {
                        _token: getCsrfToken(),
                        ids: requested
                    },
                    function(data) {
                        if (data && typeof data === 'object') {
                            Object.keys(data).forEach(function(id) {
                                productInfoCache[String(id)] = data[id];
                            });
                        }
                        resolve(productInfoCache);
                    }
                ).fail(function() {
                    resolve(productInfoCache);
                });
            });
        }

        function normalizeFileUrl(file) {
            if (!file) {
                return null;
            }
            var url = file.file_url || file.file_name || file.file_path || file.file;
            if (!url) {
                return null;
            }
            if (isUrl(url)) {
                return url;
            }
            var appUrl = getAppUrl();
            if (appUrl) {
                return appUrl.replace(/\/$/, '') + '/' + String(url).replace(/^\//, '');
            }
            return url;
        }

        function resolveFileUrl(value) {
            if (!value) {
                return Promise.resolve(null);
            }
            if (isUrl(value)) {
                return Promise.resolve(value);
            }
            var first = String(value).split(',')[0].trim();
            if (!first) {
                return Promise.resolve(null);
            }
            if (Object.prototype.hasOwnProperty.call(fileUrlCache, first)) {
                return Promise.resolve(fileUrlCache[first]);
            }
            if (/\/|\.|uploads/i.test(first)) {
                var directUrl = normalizeFileUrl({
                    file_name: first
                });
                fileUrlCache[first] = directUrl;
                return Promise.resolve(directUrl);
            }
            return fetchFileInfo(first).then(function(files) {
                var url = files.length ? normalizeFileUrl(files[0]) : null;
                fileUrlCache[first] = url;
                return url;
            });
        }

        function getSelectedProductName(scope) {
            var select = scope.querySelector('select[name="product_id"]');
            if (select && select.value) {
                var option = select.options[select.selectedIndex];
                return option ? option.text : '';
            }

            var checked = scope.querySelector('.js-showcase-product:checked');
            if (checked) {
                var label = checked.closest('label');
                if (label) {
                    var name = label.querySelector('.showcase-product-name');
                    return name ? name.textContent.trim() : label.innerText.trim();
                }
            }
            return '';
        }

        function getSelectedProducts(scope) {
            var names = [];
            var checked = scope.querySelectorAll('.js-showcase-product:checked');
            checked.forEach(function(input) {
                var label = input.closest('label');
                if (!label) return;
                var name = label.querySelector('.showcase-product-name');
                names.push((name ? name.textContent : label.innerText).trim());
            });
            return names;
        }

        function getHashtags(scope) {
            var input = scope.querySelector('.js-hashtag-hidden');
            return input ? input.value : '';
        }

        function getShopInfo(preview, scope) {
            var select = scope.querySelector('select[name="seller_id"]');
            if (select && select.value) {
                var option = select.options[select.selectedIndex];
                return {
                    name: option ? option.text : 'Shop',
                    logo: ''
                };
            }
            return {
                name: preview.getAttribute('data-shop-name') || 'Shop',
                logo: preview.getAttribute('data-shop-logo') || ''
            };
        }

        function updatePreview(preview) {
            var type = preview.getAttribute('data-preview-type') || 'generic';
            var scope = preview.closest('form') || document;
            var content = preview.querySelector('.showcase-preview__content');
            var empty = preview.querySelector('.showcase-preview__empty');
            var mediaWrap = preview.querySelector('.showcase-preview__media');
            var titleEl = preview.querySelector('.showcase-preview__title');
            var subtitleEl = preview.querySelector('.showcase-preview__subtitle');
            var descEl = preview.querySelector('.showcase-preview__description');
            var cardsEl = preview.querySelector('.showcase-preview__cards');
            var cta = preview.querySelector('.showcase-preview__cta');
            var hashtagsEl = preview.querySelector('.showcase-preview__hashtags');
            var logoEl = preview.querySelector('.showcase-preview__logo');
            var nameEl = preview.querySelector('.showcase-preview__name');
            var brandEl = preview.querySelector('.showcase-preview__brand');
            var vitrinThumbs = preview.querySelector('.showcase-preview__vitrin-thumbs');
            var vitrinGrid = preview.querySelector('.showcase-preview__vitrin-grid');
            var vitrinProductsLabel = preview.querySelector('.showcase-preview__vitrin-products-label');
            var storyProductEl = preview.querySelector('.showcase-preview__story-product');

            var shop = getShopInfo(preview, scope);
            if (logoEl) {
                logoEl.innerHTML = shop.logo ?
                    '<img src="' + shop.logo + '" alt="' + shop.name + '">' :
                    (shop.name || 'S').trim().charAt(0);
            }
            if (nameEl) {
                nameEl.textContent = shop.name || 'Shop';
            }

            var hashtags = getHashtags(scope);
            if (hashtagsEl) {
                hashtagsEl.textContent = hashtags ? ('#' + hashtags.replace(/,\s*/g, ' #')) : '';
            }

            function setPreviewBrand(brandName) {
                if (!brandEl) {
                    return;
                }
                var normalizedBrand = (brandName || '').trim();
                preview.dataset.previewBrandName = normalizedBrand;
                brandEl.textContent = normalizedBrand;
                brandEl.style.display = normalizedBrand ? '' : 'none';
            }
            if (brandEl) {
                // brandEl.textContent = '';
                // brandEl.style.display = 'none';
                var persistedBrand = preview.dataset.previewBrandName || '';
                brandEl.textContent = persistedBrand;
                brandEl.style.display = persistedBrand ? '' : 'none';
            }

            function showContent(show) {
                if (show) {
                    empty.classList.add('d-none');
                    content.classList.remove('d-none');
                } else {
                    empty.classList.remove('d-none');
                    content.classList.add('d-none');
                }
            }

            var titleValue = '';
            var subtitleValue = '';
            var descriptionValue = '';
            var introValue = '';

            var titleGr = scope.querySelector('input[name="title_gr"]');
            var titleEn = scope.querySelector('input[name="title_en"]');
            var subtitleGr = scope.querySelector('input[name="subtitle_gr"]');
            var subtitleEn = scope.querySelector('input[name="subtitle_en"]');
            var descGr = scope.querySelector('textarea[name="description_gr"]');
            var descEn = scope.querySelector('textarea[name="description_en"]');
            var introGr = scope.querySelector('textarea[name="intro_gr"], input[name="intro_gr"]');
            var introEn = scope.querySelector('textarea[name="intro_en"], input[name="intro_en"]');

            titleValue = (titleGr && titleGr.value) || (titleEn && titleEn.value) || getSelectedProductName(scope);
            subtitleValue = (subtitleGr && subtitleGr.value) || (subtitleEn && subtitleEn.value) || '';
            descriptionValue = (descGr && descGr.value) || (descEn && descEn.value) || '';
            introValue = (introGr && introGr.value) || (introEn && introEn.value) || '';

            // Storefront title should not fallback to product title.
            if (
                type === 'vitrin' &&
                !((titleGr && titleGr.value) || (titleEn && titleEn.value))
            ) {
                titleValue = '';
            }

            if (type === 'collection') {
                var rawCards = Array.from(scope.querySelectorAll('.js-collection-item-card'));
                var cardsSnapshot = rawCards.map(function(card) {
                    var titleInput = card.querySelector(
                        'input[name*="[title_gr]"], input[name*="[title_en]"]');
                    var descInput = card.querySelector(
                        'textarea[name*="[description_gr]"], textarea[name*="[description_en]"]');
                    var imageInput = card.querySelector('input[name*="[cover_image]"]');
                    var productInput = card.querySelector('select[name*="[product_id]"]');
                    return {
                        title: titleInput ? titleInput.value : '',
                        desc: descInput ? descInput.value : '',
                        image: imageInput ? imageInput.value : '',
                        product_id: productInput && productInput.value ? String(productInput.value) : '',
                        product_name: productInput && productInput.value ? (productInput.options[
                            productInput.selectedIndex] ? productInput.options[productInput
                            .selectedIndex].text : '') : ''
                    };
                });

                var cardsWithImage = cardsSnapshot.filter(function(cardData) {
                    return !!cardData.image;
                });
                var shouldShow = cardsWithImage.length > 0;
                if (!shouldShow) {
                    showContent(false);
                    return;
                }

                if (cardsEl) {
                    var maxCards = 4;
                    var renderItems = cardsWithImage.slice(0, maxCards);
                    cardsEl.dataset.renderToken = String(Date.now());
                    var renderToken = cardsEl.dataset.renderToken;

                    while (cardsEl.children.length > renderItems.length) {
                        cardsEl.removeChild(cardsEl.lastElementChild);
                    }

                    renderItems.forEach(function(cardData, index) {
                        var cardEl = cardsEl.children[index];
                        if (!cardEl) {
                            cardEl = document.createElement('div');
                            cardEl.className = 'showcase-preview__collection-card';
                            cardEl.innerHTML = '' +
                                '<div class="showcase-preview__collection-media"></div>' +
                                '<div class="showcase-preview__card-title"></div>' +
                                '<div class="text-white-50 small"></div>' +
                                '<div class="showcase-preview__collection-product d-none"></div>' +
                                '<div class="showcase-preview__collection-footer">' +
                                '<button type="button" class="showcase-preview__collection-btn">' + T
                                .viewProduct + '</button>' +
                                '</div>';
                            cardsEl.appendChild(cardEl);
                        }

                        cardEl.dataset.index = String(index);
                        var mediaSlot = cardEl.querySelector('.showcase-preview__collection-media');
                        var titleSlot = cardEl.querySelector('.showcase-preview__card-title');
                        var descSlot = cardEl.querySelector('.text-white-50');
                        var productSlot = cardEl.querySelector('.showcase-preview__collection-product');

                        if (titleSlot) titleSlot.textContent = cardData.title || 'Collection item';
                        if (descSlot) descSlot.textContent = cardData.desc || '';

                        if (productSlot) {
                            productSlot.classList.toggle('d-none', !cardData.product_id);
                            productSlot.innerHTML = '';
                        }

                        resolveFileUrl(cardData.image).then(function(imageUrl) {
                            if (cardsEl.dataset.renderToken !== renderToken) {
                                return;
                            }
                            if (!mediaSlot) return;
                            var current = mediaSlot.dataset.mediaUrl || '';
                            var next = imageUrl || '';
                            if (current === next) {
                                return;
                            }
                            mediaSlot.dataset.mediaUrl = next;
                            mediaSlot.innerHTML = next ? '<img src="' + next + '" alt="">' : '';
                        });
                    });

                    var productIds = renderItems
                        .map(function(c) {
                            return c.product_id;
                        })
                        .filter(function(id) {
                            return !!id;
                        });
                    fetchProductInfo(productIds).then(function(cache) {
                        if (cardsEl.dataset.renderToken !== renderToken) {
                            return;
                        }
                        Array.from(cardsEl.children).forEach(function(cardEl, idx) {
                            var item = renderItems[idx];
                            if (!item || !item.product_id) return;
                            var info = cache[String(item.product_id)];
                            var slot = cardEl.querySelector(
                                '.showcase-preview__collection-product');
                            if (!slot) return;
                            if (!info) {
                                slot.classList.add('d-none');
                                return;
                            }
                            slot.classList.remove('d-none');
                            var productThumb = info.thumbnail_url || (info.photo_urls && info
                                .photo_urls.length ? info.photo_urls[0] : '');
                            slot.innerHTML = '' +
                                '<div class="product-row">' +
                                (productThumb ?
                                    '<div class="product-thumb"><img src="' + productThumb +
                                    '" alt=""></div>' :
                                    '<div class="product-thumb-placeholder"></div>') +
                                '<div class="product-name">' + (info.name || item.product_name ||
                                    '') + '</div>' +
                                '<div class="product-price">' + (info.price_html || '') + '</div>' +
                                '<button type="button" class="product-cta">' + T.addToCart +
                                '</button>' +
                                '</div>';
                        });
                        setPreviewBrand('');
                    });

                    cardsEl.classList.toggle('d-none', renderItems.length === 0);
                }
                var layout = preview.querySelector('.showcase-preview__collection-layout');
                var mainTitle = preview.querySelector('.showcase-preview__collection-main-title');
                var mainBy = preview.querySelector('.showcase-preview__collection-main-by');
                var mainDesc = preview.querySelector('.showcase-preview__collection-main-desc');
                var mainHashtags = preview.querySelector('.showcase-preview__collection-main-hashtags');
                var activeTitleEl = preview.querySelector('.showcase-preview__collection-active-title');
                var activeDescEl = preview.querySelector('.showcase-preview__collection-active-desc');
                var mainProductEl = preview.querySelector('.showcase-preview__collection-main-product');
                var mainSlide = preview.querySelector('.showcase-preview__collection-slide');
                var activeIndex = Number(preview.dataset.collectionActiveIndex || 0);
                if (activeIndex >= cardsWithImage.length) {
                    activeIndex = 0;
                }
                if (layout) {
                    layout.classList.remove('d-none');
                }
                if (mediaWrap) {
                    mediaWrap.classList.add('d-none');
                }
                if (titleEl) {
                    titleEl.classList.add('d-none');
                }
                if (subtitleEl) {
                    subtitleEl.classList.add('d-none');
                }
                if (descEl) {
                    descEl.classList.add('d-none');
                }
                if (cta) {
                    cta.style.display = 'none';
                }
                if (mainTitle) {
                    mainTitle.textContent = titleValue || 'Collection';
                }
                if (mainBy) {
                    mainBy.textContent = shop.name ? ('By - ' + shop.name) : '';
                }
                if (mainDesc) {
                    mainDesc.textContent = introValue || descriptionValue || '';
                }
                if (mainHashtags) {
                    mainHashtags.textContent = hashtags ? ('#' + hashtags.replace(/,\s*/g, ' #')) : '';
                }
                var activeCard = cardsWithImage[activeIndex] || cardsWithImage[0] || {};
                if (activeTitleEl) {
                    activeTitleEl.textContent = activeCard.title || 'Collection item';
                }
                if (activeDescEl) {
                    activeDescEl.textContent = activeCard.desc || '';
                }
                if (mainProductEl) {
                    mainProductEl.classList.toggle('d-none', !activeCard.product_id);
                    if (!activeCard.product_id) {
                        mainProductEl.innerHTML = '';
                    }
                }
                fetchProductInfo(activeCard.product_id ? [activeCard.product_id] : []).then(function(cache) {
                    if (!mainProductEl) {
                        return;
                    }
                    var info = activeCard.product_id ? cache[String(activeCard.product_id)] : null;
                    if (!info) {
                        mainProductEl.classList.add('d-none');
                        mainProductEl.innerHTML = '';
                        return;
                    }
                    mainProductEl.classList.remove('d-none');
                    var productThumb = info.thumbnail_url || (info.photo_urls && info.photo_urls.length ? info
                        .photo_urls[0] : '');
                    mainProductEl.innerHTML = '' +
                        '<div class="product-row">' +
                        (productThumb ?
                            '<div class="product-thumb"><img src="' + productThumb + '" alt=""></div>' :
                            '<div class="product-thumb-placeholder"></div>') +
                        '<div class="product-name">' + (info.name || activeCard.product_name || '') + '</div>' +
                        '<div class="product-price">' + (info.price_html || '') + '</div>' +
                        '<div class="product-icons"><span>&#x21AA;</span><span>&lt;/&gt;</span></div>' +
                        '</div>';
                });
                if (mainSlide) {
                    var activeImageValue = activeCard.image || '';
                    resolveFileUrl(activeImageValue).then(function(coverUrl) {
                        if (!coverUrl) {
                            mainSlide.innerHTML = '';
                            mainSlide.dataset.mediaUrl = '';
                            return;
                        }
                        mainSlide.dataset.mediaUrl = coverUrl;
                        var showArrows = cardsWithImage.length > 1;
                        mainSlide.innerHTML = '' +
                            (showArrows ?
                                '<button type="button" class="showcase-preview__collection-slide-nav showcase-preview__collection-slide-nav--prev" aria-label="Previous image">&#8249;</button>' :
                                '') +
                            '<img src="' + coverUrl + '" alt="">' +
                            (showArrows ?
                                '<button type="button" class="showcase-preview__collection-slide-nav showcase-preview__collection-slide-nav--next" aria-label="Next image">&#8250;</button>' :
                                '');

                        var prevBtn = mainSlide.querySelector(
                            '.showcase-preview__collection-slide-nav--prev');
                        var nextBtn = mainSlide.querySelector(
                            '.showcase-preview__collection-slide-nav--next');
                        if (prevBtn) {
                            prevBtn.onclick = function(event) {
                                event.stopPropagation();
                                var nextIndex = activeIndex <= 0 ? cardsWithImage.length - 1 :
                                    activeIndex - 1;
                                preview.dataset.collectionActiveIndex = String(nextIndex);
                                updatePreview(preview);
                            };
                        }
                        if (nextBtn) {
                            nextBtn.onclick = function(event) {
                                event.stopPropagation();
                                var nextIndex = activeIndex >= cardsWithImage.length - 1 ? 0 :
                                    activeIndex + 1;
                                preview.dataset.collectionActiveIndex = String(nextIndex);
                                updatePreview(preview);
                            };
                        }
                    });
                }
                if (cardsEl && cardsEl.children.length) {
                    Array.from(cardsEl.children).forEach(function(cardEl, index) {
                        cardEl.classList.toggle('is-active', index === activeIndex);
                        cardEl.onclick = function() {
                            preview.dataset.collectionActiveIndex = String(index);
                            updatePreview(preview);
                        };
                    });
                }
                showContent(true);
                return;
            }

            var mainVisualInput = scope.querySelector('input[name="main_visual"]');
            var coverInput = scope.querySelector('input[name="cover_image"]');
            var storyVideoInput = scope.querySelector('.story-video-input');
            var mediaValue = '';

            if (type === 'story' && storyVideoInput) {
                mediaValue = storyVideoInput.value || '';
            } else if (mainVisualInput && mainVisualInput.value) {
                mediaValue = mainVisualInput.value;
            } else if (coverInput && coverInput.value) {
                mediaValue = coverInput.value;
            }

            var snapshot = JSON.stringify({
                type: type,
                shop: shop,
                title: titleValue,
                subtitle: subtitleValue,
                description: descriptionValue,
                hashtags: hashtags,
                media: mediaValue,
                vitrinDetailOpen: preview.dataset.vitrinDetailOpen || '0',
                vitrinOverlayOpen: preview.dataset.vitrinOverlayOpen || '0',
                product: getSelectedProductName(scope),
                products: getSelectedProducts(scope),
                intro: introValue,
                collection: Array.from(scope.querySelectorAll('.js-collection-item-card')).map(function(
                    card) {
                    var t = card.querySelector(
                        'input[name*="[title_gr]"], input[name*="[title_en]"]');
                    var i = card.querySelector('input[name*="[cover_image]"]');
                    return {
                        title: t ? t.value : '',
                        image: i ? i.value : ''
                    };
                })
            });

            var currentMediaUrl = mediaWrap ? (mediaWrap.dataset.mediaUrl || '') : '';
            var resolvedPreviewMediaUrl = preview.dataset.previewResolvedMediaUrl || currentMediaUrl || '';
            if (preview.dataset.previewSnapshot === snapshot && !(mediaValue && currentMediaUrl === '')) {
                return;
            }
            preview.dataset.previewSnapshot = snapshot;

            if (titleEl) titleEl.textContent = titleValue || '';
            if (subtitleEl) subtitleEl.textContent = subtitleValue || '';
            if (descEl) descEl.textContent = descriptionValue || '';

            var shouldRefreshMedia = preview.dataset.previewMediaValue !== mediaValue || currentMediaUrl === '';
            var forceLaunchRender = false;
            if (type === 'launch') {
                var launchSelectionInput = scope.querySelector('[name="product_id"]');
                var launchSelectionValue = launchSelectionInput && launchSelectionInput.value ?
                    String(launchSelectionInput.value) :
                    '';
                if (!launchSelectionValue) {
                    var checkedLaunchInput = scope.querySelector(
                        'input[name="product_ids[]"]:checked, .js-showcase-product:checked');
                    launchSelectionValue = checkedLaunchInput && checkedLaunchInput.value ?
                        String(checkedLaunchInput.value) :
                        '';
                }
                if ((preview.dataset.launchSelectedId || '') !== launchSelectionValue) {
                    shouldRefreshMedia = true;
                    forceLaunchRender = true;
                }
                preview.dataset.launchSelectedId = launchSelectionValue;
            }

            if (shouldRefreshMedia) {
                preview.dataset.previewMediaValue = mediaValue;
                resolveFileUrl(mediaValue).then(function(mediaUrl) {
                    if (preview.dataset.previewMediaValue !== mediaValue) {
                        return;
                    }
                    if (!mediaWrap) return;
                    if (mediaWrap.dataset.mediaUrl === (mediaUrl || '') && !forceLaunchRender) {
                        return;
                    }
                    preview.dataset.previewResolvedMediaUrl = mediaUrl || '';
                    mediaWrap.dataset.mediaUrl = mediaUrl || '';
                    mediaWrap.innerHTML = '';
                    if (!mediaUrl) {
                        if (type !== 'vitrin') {
                            showContent(false);
                            return;
                        }
                    }
                    var isVideo = /\.(mp4|mov|webm|ogg)$/i.test(mediaUrl) || type === 'story';
                    if (type === 'launch') {
                        var coverInput = scope.querySelector('input[name="cover_image"]');
                        var coverValue = coverInput ? coverInput.value : '';
                        var thumbsValue = coverValue || mediaValue;
                        var shopLogoHtml = shop.logo ?
                            '<img src="' + shop.logo + '" alt="' + shop.name + '">' :
                            (shop.name || 'S').trim().charAt(0);
                        var selectedLaunchInputs = Array.from(scope.querySelectorAll(
                            'input[name="product_ids[]"]:checked, .js-showcase-product:checked'));
                        var launchSelectedId = '';
                        var productIdEl = scope.querySelector('[name="product_id"]');
                        if (productIdEl && productIdEl.value) {
                            launchSelectedId = String(productIdEl.value);
                        } else if (selectedLaunchInputs.length) {
                            launchSelectedId = selectedLaunchInputs[0].value ? String(selectedLaunchInputs[
                                0].value) : '';
                        }
                        var productName = getSelectedProductName(scope) || titleValue || 'Product Name';
                        var tagline = subtitleValue || T.elegantTagline;
                        var hashtagsText = hashtags ? ('#' + hashtags.replace(/,\s*/g, ' #')) : '';

                        resolveFileUrl(thumbsValue).then(function(thumbUrl) {
                            if (preview.dataset.previewMediaValue !== mediaValue) {
                                return;
                            }
                            fetchProductInfo(launchSelectedId ? [launchSelectedId] : []).then(
                                function(cache) {
                                    var info = launchSelectedId ? cache[String(
                                        launchSelectedId)] : null;
                                    // if (brandEl) {
                                    //     brandEl.textContent = info && info.brand_name ? info
                                    //         .brand_name : '';
                                    //     brandEl.style.display = brandEl.textContent ? '' :
                                    //         'none';
                                    // }
                                    setPreviewBrand(info && info.brand_name ? info.brand_name :
                                        '');
                                    var photoUrls = (info && info.photo_urls && info.photo_urls
                                        .length) ? info.photo_urls : [];
                                    var mainThumbs = photoUrls.length ?
                                        photoUrls :
                                        ((info && info.thumbnail_url) ? [info.thumbnail_url] : (
                                            thumbUrl ? [thumbUrl] : []));
                                    var sideThumbs = mainThumbs.slice(0, 4);

                                    var thumbHtml = function(u) {
                                        return u ?
                                            '<div class="showcase-preview__launch-thumb"><img src="' +
                                            u + '" alt=""></div>' :
                                            '<div class="showcase-preview__launch-thumb"><span class="showcase-preview__launch-side-placeholder"></span></div>';
                                    };
                                    var brandLogoHtml = shop.logo ?
                                        '<img src="' + shop.logo + '" alt="' + shop.name +
                                        '">' :
                                        (shop.name || 'B').trim().charAt(0);
                                    var launchInnerBrandName = (info && info.brand_name) ? info
                                        .brand_name : (shop.name || 'Brand Name');
                                    var launchDescription = descriptionValue || '';
                                    var launchProductThumb = (info && info.thumbnail_url) || (
                                        photoUrls.length ? photoUrls[0] : '') || (mainThumbs
                                        .length ? mainThumbs[0] : '') || (thumbUrl || '');
                                    var launchDescriptionHtml = launchDescription ?
                                        '<div class="showcase-preview__launch-description">' +
                                        launchDescription + '</div>' :
                                        '';
                                    var launchHashtagsHtml = hashtagsText ?
                                        '<div class="showcase-preview__launch-hashtags">' +
                                        hashtagsText + '</div>' :
                                        '';

                                    mediaWrap.innerHTML = '' +
                                        '<div class="showcase-preview__launch-card">' +
                                        '<div class="showcase-preview__launch-header">' +
                                        '<div class="showcase-preview__launch-shop">' +
                                        '<span class="shop-logo">' + shopLogoHtml + '</span>' +
                                        '<span>' + (shop.name || 'Shop Name') + '</span>' +
                                        '</div>' +
                                        '<button type="button" class="showcase-preview__launch-follow">' +
                                        T.follow + '</button>' +
                                        '</div>' +
                                        '<div class="showcase-preview__launch-media">' +
                                        '<div class="showcase-preview__launch-main">' +
                                        (isVideo ? '<video src="' + mediaUrl +
                                            '" muted autoplay loop playsinline></video>' :
                                            '<img src="' + mediaUrl + '" alt="">') +
                                        '<div class="showcase-preview__launch-overlay d-none"></div>' +
                                        '</div>' +
                                        '<div class="showcase-preview__launch-thumbs">' +
                                        sideThumbs.map(function(u) {
                                            return thumbHtml(u);
                                        }).join('') +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="showcase-preview__launch-brand">' +
                                        '<span class="brand-logo">' + brandLogoHtml +
                                        '</span>' +
                                        '<span class="showcase-preview__launch-brand-name">' + (
                                            launchInnerBrandName) + '</span>' +
                                        '</div>' +
                                        '<div class="showcase-preview__launch-title">' + (
                                            info && info.name ? info.name : productName) +
                                        '</div>' +
                                        '<div class="showcase-preview__launch-tagline">' +
                                        tagline + '</div>' +
                                        launchDescriptionHtml +
                                        launchHashtagsHtml +
                                        '<div class="showcase-preview__launch-product">' +
                                        (launchProductThumb ?
                                            '<div class="showcase-preview__launch-product-thumb"><img src="' +
                                            launchProductThumb + '" alt=""></div>' :
                                            '<div class="showcase-preview__launch-product-placeholder"></div>'
                                        ) +
                                        '<div class="showcase-preview__launch-product-meta">' +
                                        '<div class="showcase-preview__launch-product-name">' +
                                        (info && info.name ? info.name : productName) +
                                        '</div>' +
                                        '</div>' +
                                        '<span class="price">' + (info && info.price_html ? info
                                            .price_html : '&euro;0.00') + '</span>' +
                                        '<div class="showcase-preview__launch-actions"><span class="showcase-preview__launch-action">&#x21AA;</span><span class="showcase-preview__launch-action">&lt;/&gt;</span></div>' +
                                        '</div>' +
                                        '<button type="button" class="showcase-preview__launch-cart">' +
                                        T.addToCart + '</button>' +
                                        '</div>';

                                    var openBtn = mediaWrap.querySelector(
                                        '.showcase-preview__launch-open');
                                    var overlay = mediaWrap.querySelector(
                                        '.showcase-preview__launch-overlay');
                                    if (openBtn && overlay) {
                                        openBtn.onclick = function() {
                                            var isOpen = preview.dataset
                                                .launchOverlayOpen === '1';
                                            preview.dataset.launchOverlayOpen = isOpen ?
                                                '0' : '1';
                                            updatePreview(preview);
                                        };

                                        var isOpenNow = preview.dataset.launchOverlayOpen ===
                                            '1';
                                        overlay.classList.toggle('d-none', !isOpenNow);
                                        if (isOpenNow && info) {
                                            overlay.innerHTML = '' +
                                                '<div class="showcase-preview__launch-overlay-head">' +
                                                '<span>' + (shop.name || 'Shop') + '</span>' +
                                                '<button type="button" class="showcase-preview__launch-overlay-close">Close</button>' +
                                                '</div>' +
                                                '<div class="showcase-preview__launch-overlay-grid">' +
                                                '<div class="showcase-preview__launch-overlay-item">' +
                                                (info.thumbnail_url ? '<img src="' + info
                                                    .thumbnail_url + '" alt="">' :
                                                    '<img alt="">') +
                                                '<div class="showcase-preview__launch-overlay-body">' +

                                                '<div class="showcase-preview__launch-overlay-title">' +
                                                (info.name || '') + '</div>' +
                                                '<div class="showcase-preview__launch-overlay-row">' +
                                                '<span class="showcase-preview__launch-overlay-price">' +
                                                (info.price_html || '') + '</span>' +
                                                '<button type="button" class="showcase-preview__launch-overlay-buy">' +
                                                T.addToCart + '</button>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>';

                                            var closeBtn = overlay.querySelector(
                                                '.showcase-preview__launch-overlay-close');
                                            if (closeBtn) {
                                                closeBtn.onclick = function() {
                                                    preview.dataset.launchOverlayOpen = '0';
                                                    updatePreview(preview);
                                                };
                                            }
                                        } else {
                                            overlay.innerHTML = '';
                                        }
                                    }

                                    showContent(true);
                                });
                        });
                        return;
                    }
                    if (isVideo) {
                        mediaWrap.innerHTML = '<video src="' + mediaUrl +
                            '" muted controls playsinline></video>';
                    } else {
                        mediaWrap.innerHTML = '<img src="' + mediaUrl + '" alt="">';
                    }
                    if (type === 'vitrin') {
                        var current = mediaWrap.innerHTML;
                        mediaWrap.innerHTML = '' +
                            '<div class="showcase-preview__vitrin-media">' +
                            current +
                            '<div class="showcase-preview__vitrin-overlay d-none"></div>' +
                            '</div>';
                    }
                    showContent(true);
                });
            }

            if (type === 'vitrin') {
                var selectedInputs = Array.from(scope.querySelectorAll(
                    'input[name=\"product_ids[]\"]:checked, .js-showcase-product:checked'));
                var selectedIds = selectedInputs
                    .map(function(el) {
                        return (el && el.value) ? String(el.value) : '';
                    })
                    .filter(function(id) {
                        return !!id;
                    });
                var vitrinSelectionKey = selectedIds.join(',');
                if ((preview.dataset.vitrinSelectionKey || '') !== vitrinSelectionKey) {
                    preview.dataset.vitrinSelectionKey = vitrinSelectionKey;
                    preview.dataset.vitrinDetailOpen = '0';
                    preview.dataset.vitrinOverlayOpen = '0';
                }
                var isVitrinDetailOpen = preview.dataset.vitrinDetailOpen === '1';
                if (titleEl) {
                    if (isVitrinDetailOpen) {
                        titleEl.innerHTML =
                            '<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">' +
                            '<button type="button" class="btn btn-sm btn-light border js-vitrin-back-btn" style="font-size:11px;font-weight:700;padding:4px 10px;border-radius:999px;">' +
                            T.backToProducts +
                            '</button>' +
                            '<span>' + (titleValue || T.storefront) + '</span>' +
                            '</div>';
                    } else {
                        titleEl.textContent = titleValue || T.storefront;
                    }
                }
                if (subtitleEl) subtitleEl.textContent = '';
                if (cta) {
                    cta.textContent = T.viewStorefront;
                    cta.style.display = 'none';
                }
                var selectedFallbackInfos = selectedInputs.map(function(input) {
                    var label = input.closest('label');
                    var nameNode = label ? label.querySelector('.showcase-product-name') : null;
                    var idNode = label ? label.querySelector('strong[data-category-id]') : null;
                    return {
                        id: input && input.value ? String(input.value) : '',
                        name: nameNode ? nameNode.textContent.trim() : '',
                        code: idNode ? idNode.textContent.trim() : '',
                        thumbnail_url: '',
                        price_html: ''
                    };
                });

                function renderVitrinItems(items) {
                    var initialThumbs = [];
                    var vitrinMainMediaUrl = preview.dataset.previewResolvedMediaUrl || resolvedPreviewMediaUrl || '';
                    if (vitrinMainMediaUrl) {
                        initialThumbs.push({
                            id: 'cover',
                            thumbnail_url: vitrinMainMediaUrl,
                            media_url: vitrinMainMediaUrl,
                            is_main_media: true
                        });
                    }
                    items.forEach(function(info) {
                        initialThumbs.push(info);
                    });

                    if (vitrinThumbs) {
                        vitrinThumbs.classList.toggle('d-none', initialThumbs.length === 0);
                        vitrinThumbs.innerHTML = initialThumbs.map(function(info, index) {
                            var url = info.thumbnail_url || '';
                            var mediaUrl = info.media_url || url;
                            var isVideoThumb = !!(info.is_main_media && mediaUrl && /\.(mp4|mov|webm|ogg)$/i
                                .test(mediaUrl));
                            return '' +
                                '<button type="button" class="showcase-preview__vitrin-thumb" data-vitrin-index="' +
                                index + '">' +
                                (isVideoThumb ? '<video src="' + mediaUrl +
                                    '" muted playsinline preload="metadata"></video>' :
                                    (url ? '<img src="' + url + '" alt="">' :
                                    '<span class="showcase-preview__vitrin-thumb-placeholder"></span>')) +
                                '</button>';
                        }).join('');
                    }

                    if (vitrinProductsLabel) {
                        vitrinProductsLabel.classList.toggle('d-none', items.length === 0);
                    }

                    if (vitrinGrid) {
                        vitrinGrid.classList.toggle('d-none', items.length === 0);
                        vitrinGrid.innerHTML = items.map(function(info) {
                            var url = info.thumbnail_url || '';
                            var name = info.name || info.code || '';
                            var price = info.price_html || '';
                            return '' +
                                '<div class="showcase-preview__vitrin-item">' +
                                (url ? '<img src="' + url + '" alt="">' :
                                    '<div class="showcase-preview__vitrin-item-image-placeholder"></div>') +
                                '<div class="showcase-preview__vitrin-item-body">' +
                                '<div class="showcase-preview__vitrin-item-title">' + name + '</div>' +
                                '<div class="showcase-preview__vitrin-item-row">' +
                                '<span class="price">' + price + '</span>' +
                                '<button type="button" class="buy">' + T.addToCart + '</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }).join('');
                    }
                }

                renderVitrinItems(selectedFallbackInfos);

                fetchProductInfo(selectedIds).then(function(cache) {
                    var inlineBackBtn = titleEl ? titleEl.querySelector('.js-vitrin-back-btn') : null;
                    if (inlineBackBtn) {
                        inlineBackBtn.onclick = function() {
                            preview.dataset.vitrinDetailOpen = '0';
                            preview.dataset.vitrinOverlayOpen = '0';
                            updatePreview(preview);
                        };
                    }
                    var infos = selectedIds
                        .map(function(id) {
                            var apiInfo = cache[String(id)];
                            if (apiInfo) {
                                return apiInfo;
                            }
                            return selectedFallbackInfos.find(function(item) {
                                return String(item.id) === String(id);
                            });
                        })
                        .filter(function(info) {
                            return !!info;
                        });

                    renderVitrinItems(infos);
                    if (vitrinThumbs) {
                        Array.from(vitrinThumbs.querySelectorAll('[data-vitrin-index]')).forEach(function(
                            thumbBtn) {
                            thumbBtn.onclick = function() {
                                preview.dataset.vitrinDetailOpen = '1';
                                updatePreview(preview);
                            };
                        });
                    }
                    // if (brandEl) {
                    //     brandEl.textContent = infos.length && infos[0].brand_name ? infos[0].brand_name :
                    //         '';
                    //     brandEl.style.display = brandEl.textContent ? '' : 'none';
                    // }
                    setPreviewBrand(infos.length && infos[0].brand_name ? infos[0].brand_name : '');
                    if (cta) {
                        cta.onclick = function() {
                            if (!infos.length) return;
                            preview.dataset.vitrinOverlayOpen = preview.dataset.vitrinOverlayOpen ===
                                '1' ? '0' : '1';
                            updatePreview(preview);
                        };
                    }

                    var wrap = mediaWrap ? mediaWrap.querySelector('.showcase-preview__vitrin-media') :
                        null;
                    var overlay = wrap ? wrap.querySelector('.showcase-preview__vitrin-overlay') : null;
                    var open = preview.dataset.vitrinOverlayOpen === '1';
                    if (overlay) {
                        overlay.classList.toggle('d-none', !open || infos.length === 0);
                        if (open && infos.length) {
                            overlay.innerHTML = '' +
                                '<div class="showcase-preview__vitrin-overlay-head">' +
                                '<span>' + (shop.name || 'Shop') + '</span>' +
                                '<button type="button" class="showcase-preview__vitrin-overlay-close">Close</button>' +
                                '</div>' +
                                '<div class="showcase-preview__vitrin-overlay-grid">' +
                                infos.map(function(info) {
                                    var url = info.thumbnail_url || '';
                                    return '' +
                                        '<div class="showcase-preview__vitrin-overlay-item">' +
                                        (url ? '<img src="' + url + '" alt="">' : '<img alt="">') +
                                        '<div class="showcase-preview__vitrin-overlay-body">' +
                                        '<div class="showcase-preview__vitrin-overlay-title">' + (info
                                            .name || '') + '</div>' +
                                        '<div class="showcase-preview__vitrin-overlay-row">' +
                                        '<span class="showcase-preview__vitrin-overlay-price">' + (info
                                            .price_html || '') + '</span>' +
                                        '<button type="button" class="showcase-preview__vitrin-overlay-buy">' +
                                        T.addToCart + '</button>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                }).join('') +
                                '</div>';
                            var closeBtn = overlay.querySelector('.showcase-preview__vitrin-overlay-close');
                            if (closeBtn) {
                                closeBtn.onclick = function() {
                                    preview.dataset.vitrinOverlayOpen = '0';
                                    updatePreview(preview);
                                };
                            }
                        } else {
                            overlay.innerHTML = '';
                        }
                    }
                    if (infos.length || (preview.dataset.previewResolvedMediaUrl || '')) {
                        showContent(true);
                    } else {
                        showContent(false);
                    }
                });
            } else {
                if (cta) {
                    cta.textContent = T.viewProduct;
                }
                if (type === 'story') {
                    var storyProductIdEl = scope.querySelector('[name="product_id"]');
                    var storyProductId = storyProductIdEl && storyProductIdEl.value ? String(storyProductIdEl
                        .value) : '';
                    if (!storyProductEl) {
                        return;
                    }
                    if (!storyProductId) {
                        storyProductEl.classList.add('d-none');
                        storyProductEl.innerHTML = '';
                        return;
                    }
                    fetchProductInfo([storyProductId]).then(function(cache) {
                        var info = cache[String(storyProductId)];
                        if (!info) {
                            storyProductEl.classList.add('d-none');
                            storyProductEl.innerHTML = '';
                            return;
                        }
                        var storyProductThumb = info.thumbnail_url || (info.photo_urls && info.photo_urls
                            .length ? info.photo_urls[0] : '');
                        // if (brandEl) {
                        //     brandEl.textContent = info.brand_name || '';
                        //     brandEl.style.display = brandEl.textContent ? '' : 'none';
                        // }
                        setPreviewBrand(info.brand_name || '');
                        storyProductEl.classList.remove('d-none');
                        storyProductEl.innerHTML = '' +
                            (storyProductThumb ?
                                '<div class="showcase-preview__story-product-thumb"><img src="' +
                                storyProductThumb + '" alt=""></div>' :
                                '<div class="showcase-preview__story-product-placeholder"></div>') +
                            '<div class="showcase-preview__story-product-body">' +
                            '<div class="showcase-preview__story-product-name">' + (info.name ||
                                getSelectedProductName(scope) || '') + '</div>' +
                            '<div class="showcase-preview__story-product-price">' + (info.price_html ||
                                '') + '</div>' +
                            '</div>';
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var previews = document.querySelectorAll('.showcase-preview');
            previews.forEach(function(preview) {
                var type = preview.getAttribute('data-preview-type') || 'generic';
                var form = preview.closest('form') || document;
                var rafId = 0;
                var debounceTimer = 0;

                function scheduleUpdate() {
                    if (debounceTimer) {
                        clearTimeout(debounceTimer);
                    }
                    debounceTimer = setTimeout(function() {
                        if (rafId) {
                            cancelAnimationFrame(rafId);
                        }
                        rafId = requestAnimationFrame(function() {
                            updatePreview(preview);
                        });
                    }, 120);
                }

                updatePreview(preview);
                form.addEventListener('input', scheduleUpdate);
                form.addEventListener('change', scheduleUpdate);
                if (form) {
                    form.addEventListener('submit', function() {
                        updatePreview(preview);
                    });
                }
                if (type !== 'collection') {
                    setInterval(function() {
                        updatePreview(preview);
                    }, 1500);
                } else {
                    setInterval(function() {
                        updatePreview(preview);
                    }, 2000);
                }
            });
        });
    })();
</script>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/partials/showcase_preview.blade.php ENDPATH**/ ?>