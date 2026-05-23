<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $product backend\models\Product */
/* @var $isFaved bool */
/* @var $related backend\models\Product[] */

$adminUrl  = Yii::$app->params['adminUrl'] ?? '';
$mainImage = $product->image;
$allImages = $product->productImages;

if (empty($allImages) && $mainImage) {
    $allImages = [$mainImage];
}

$mainSrc = !empty($allImages) ? ($adminUrl . 'uploads/images/' . $allImages[0]->name) : null;

$this->title = Html::encode($product->title) . ' | ' . Yii::t('app', 'Goldmember');

$hasDiscount = !empty($product->another_price) && (float)$product->another_price > (float)$product->price;
?>

<!-- Hero Banner -->
<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100">
        <img class="w-100 h-100" src="/images/auction.png" alt="<?= Html::encode($product->title) ?>" height="400" width="1442"/>
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-8 col-12 px-0">
            <?php if ($product->category): ?>
                <span class="badge fs-6 mb-2 px-3 py-2" style="background:rgba(19,178,173,.15);color:#53E2D9;letter-spacing:1px;border:1px solid #13B2AD;">
                    <?= Html::encode($product->category->name) ?>
                </span>
            <?php endif ?>
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title">
                <?= Html::encode($product->title) ?>
            </h1>
            <?php if ($product->product_sku): ?>
                <span class="text-white small" style="opacity:.7;">
                    <?= Yii::t('app', 'SKU') ?>: <?= Html::encode($product->product_sku) ?>
                </span>
            <?php endif ?>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<?= $this->render('/site/partials/breadcrumb', [
    'prev'    => Yii::t('app', 'Products'),
    'prevUrl' => '/' . Yii::$app->language . '/product',
    'current' => Html::encode($product->title),
]) ?>

<!-- Main content -->
<section class="container py-4 px-sm-0 product-detail-section">
    <div class="row g-4">

        <!-- ===== LEFT: Image gallery ===== -->
        <div class="col-lg-6 col-12">
            <div class="product-gallery shadow rounded overflow-hidden bg-white">
                <?php if (!empty($allImages)): ?>
                    <div id="productCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner bg-light">
                            <?php foreach ($allImages as $i => $img): ?>
                                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                    <img src="<?= Html::encode($adminUrl . 'uploads/images/' . $img->name) ?>"
                                         class="d-block w-100 product-gallery-main"
                                         alt="<?= Html::encode($product->title) ?>">
                                </div>
                            <?php endforeach ?>
                        </div>
                        <?php if (count($allImages) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        <?php endif ?>

                        <!-- Floating heart -->
                        <button type="button"
                                data-fav-id="<?= $product->id ?>"
                                title="<?= $isFaved ? Yii::t('app','Remove from favourites') : Yii::t('app','Add to favourites') ?>"
                                class="product-gallery-fav border-0 rounded-circle d-flex align-items-center justify-content-center<?= $isFaved ? ' faved' : '' ?>">
                            <i class="bi <?= $isFaved ? 'bi-heart-fill' : 'bi-heart' ?>"
                               style="font-size:1.3rem;color:<?= $isFaved ? '#13B2AD' : '#aaa' ?>;"></i>
                        </button>

                        <?php if ($hasDiscount): ?>
                            <span class="product-gallery-badge badge px-3 py-2">
                                <i class="bi bi-tag-fill me-1"></i><?= Yii::t('app', 'Sale') ?>
                            </span>
                        <?php endif ?>
                    </div>

                    <?php if (count($allImages) > 1): ?>
                        <div class="product-gallery-thumbs px-3 py-3 d-flex gap-2 flex-wrap border-top">
                            <?php foreach ($allImages as $i => $img): ?>
                                <button type="button"
                                        class="product-thumb<?= $i === 0 ? ' active' : '' ?>"
                                        data-bs-target="#productCarousel"
                                        data-bs-slide-to="<?= $i ?>">
                                    <img src="<?= Html::encode($adminUrl . 'uploads/images/' . $img->name) ?>"
                                         alt="thumbnail <?= $i + 1 ?>">
                                </button>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:420px;">
                        <i class="bi bi-image text-muted" style="font-size:4rem;"></i>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <!-- ===== RIGHT: Pricing + actions + specs ===== -->
        <div class="col-lg-6 col-12">
            <div class="product-info-card shadow rounded overflow-hidden">

                <!-- Price header -->
                <div class="product-price-head p-4">
                    <p class="mb-1 product-price-label">
                        <?= Yii::t('app', 'Price') ?>
                    </p>
                    <div class="d-flex align-items-baseline gap-3 flex-wrap">
                        <span class="product-price-main">
                            ֏ <?= number_format((float)$product->price, 0, '', '.') ?>
                        </span>
                        <?php if ($hasDiscount): ?>
                            <span class="text-decoration-line-through text-muted product-price-old">
                                ֏ <?= number_format((float)$product->another_price, 0, '', '.') ?>
                            </span>
                            <?php
                                $pct = round((1 - $product->price / $product->another_price) * 100);
                            ?>
                            <span class="badge product-discount-badge">
                                −<?= $pct ?>%
                            </span>
                        <?php endif ?>
                    </div>
                    <?php if ($product->status == 1): ?>
                        <div class="mt-3 d-flex align-items-center gap-2">
                            <span class="product-stock-dot"></span>
                            <span class="product-stock-text"><?= Yii::t('app', 'In stock') ?></span>
                        </div>
                    <?php endif ?>
                </div>

                <!-- Specs -->
                <div class="p-4 border-bottom">
                    <h6 class="product-section-title mb-3">
                        <i class="bi bi-clipboard-data me-2"></i><?= Yii::t('app', 'Specifications') ?>
                    </h6>
                    <div class="row g-3">
                        <?php
                        $specs = [
                            ['SKU', $product->product_sku],
                            ['Material', $product->material ? $product->material->name : null],
                            ['Fineness', $product->fineness],
                            ['Weight', $product->weight ? Html::encode($product->weight) . ' g' : null],
                            ['Color', $product->color],
                            ['Gemstone', $product->gemstone],
                            ['Condition', $product->state],
                            ['Country', $product->country],
                        ];
                        foreach ($specs as [$label, $value]):
                            if (!$value) continue;
                        ?>
                            <div class="col-sm-6">
                                <div class="product-spec-item">
                                    <div class="product-spec-label"><?= Yii::t('app', $label) ?></div>
                                    <div class="product-spec-value"><?= Html::encode(strip_tags((string)$value)) ?></div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <!-- Description -->
                <?php if (!empty($product->description) || !empty($product->short_description)): ?>
                    <div class="p-4 border-bottom">
                        <h6 class="product-section-title mb-3">
                            <i class="bi bi-card-text me-2"></i><?= Yii::t('app', 'Description') ?>
                        </h6>
                        <div class="product-description">
                            <?php if (!empty($product->description)): ?>
                                <?= strip_tags($product->description, '<p><br><strong><em><ul><ol><li>') ?>
                            <?php else: ?>
                                <p><?= Html::encode(strip_tags($product->short_description)) ?></p>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>

                <!-- Why choose / trust -->
                <div class="p-4 border-bottom">
                    <h6 class="product-section-title mb-3">
                        <i class="bi bi-shield-check me-2"></i><?= Yii::t('app', 'Why choose this product?') ?>
                    </h6>
                    <ul class="list-unstyled mb-0 product-trust-list">
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i><?= Yii::t('app', 'Certified gold quality') ?></li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i><?= Yii::t('app', 'Secure purchase guarantee') ?></li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i><?= Yii::t('app', 'Trusted by Goldmember.am') ?></li>
                        <li><i class="bi bi-check-circle-fill me-2"></i><?= Yii::t('app', 'Fast delivery across Armenia') ?></li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="p-4 d-flex flex-column gap-3">
                    <a href="tel:+37494261606" class="button primary-button d-flex align-items-center justify-content-center gap-2 product-action-btn">
                        <i class="bi bi-telephone-fill"></i>
                        <?= Yii::t('app', 'Call to Order') ?>
                    </a>
                    <a href="<?= Url::to(['/site/contact']) ?>" class="button secondary-button d-flex align-items-center justify-content-center gap-2 product-action-btn">
                        <i class="bi bi-envelope"></i>
                        <?= Yii::t('app', 'Send Enquiry') ?>
                    </a>
                    <a href="/<?= Yii::$app->language ?>/product" class="text-center text-muted small mt-1 product-back-link">
                        ← <?= Yii::t('app', 'Back to Products') ?>
                    </a>
                </div>

            </div>
        </div>

    </div>

    <!-- ===== Related products ===== -->
    <?php if (!empty($related)): ?>
        <div class="row mt-5 pt-3">
            <div class="col-12 mb-3">
                <h3 class="product-related-title">
                    <?= Yii::t('app', 'You may also like') ?>
                </h3>
            </div>
            <?php foreach ($related as $rel):
                $relImg = $rel->image;
                $relSrc = $relImg ? $adminUrl . 'uploads/images/' . $relImg->name : null;
            ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= Url::to(['/product/detail', 'id' => $rel->id]) ?>" class="text-decoration-none">
                        <div class="card h-100 product-related-card border-0 shadow-sm">
                            <?php if ($relSrc): ?>
                                <img src="<?= Html::encode($relSrc) ?>"
                                     class="card-img-top"
                                     alt="<?= Html::encode($rel->title) ?>"
                                     style="height:200px;object-fit:cover;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                                    <i class="bi bi-image text-muted" style="font-size:2.5rem;"></i>
                                </div>
                            <?php endif ?>
                            <div class="card-body">
                                <h6 class="card-title text-dark mb-1"><?= Html::encode($rel->title) ?></h6>
                                <?php if ($rel->price): ?>
                                    <div class="product-related-price">
                                        ֏ <?= number_format((float)$rel->price, 0, '', '.') ?>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</section>

<style>
.product-detail-section { color: #1a1a1a; }

/* Gallery */
.product-gallery { background: #fff; }
.product-gallery-main { max-height: 500px; object-fit: contain; padding: 1rem; }
.product-gallery-fav {
    position: absolute; top: 16px; right: 16px;
    width: 44px; height: 44px;
    background: rgba(255,255,255,.95);
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
    z-index: 5; cursor: pointer;
}
.product-gallery-fav:hover { transform: scale(1.05); }
.product-gallery-badge {
    position: absolute; top: 16px; left: 16px;
    background: #D4A017; color: #000;
    font-size: .85rem; letter-spacing: 1px;
    z-index: 5;
}

/* Thumbnails */
.product-gallery-thumbs { background: #fafafa; }
.product-thumb {
    width: 64px; height: 64px;
    padding: 0; border: 2px solid transparent;
    border-radius: 6px; overflow: hidden;
    background: #fff; cursor: pointer;
    transition: border-color .15s ease;
}
.product-thumb img { width: 100%; height: 100%; object-fit: cover; }
.product-thumb:hover { border-color: #13B2AD; }
.product-thumb.active { border-color: #D4A017; }

/* Info card */
.product-info-card { background: #fff; }

/* Price header */
.product-price-head {
    background: linear-gradient(135deg, #0d2020 0%, #0a1818 100%);
    color: #fff;
}
.product-price-label {
    color: #53E2D9 !important;
    font-size: .75rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}
.product-price-main {
    font-size: 2.6rem;
    font-weight: 700;
    color: #D4A017;
    line-height: 1.1;
}
.product-price-old {
    font-size: 1.15rem;
}
.product-discount-badge {
    background: #c0392b;
    color: #fff;
    font-size: .85rem;
    padding: .35rem .6rem;
    letter-spacing: .5px;
}
.product-stock-dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: #2ecc71;
    box-shadow: 0 0 0 4px rgba(46,204,113,.2);
    animation: stockPulse 2s ease-in-out infinite;
}
@keyframes stockPulse {
    0%,100% { box-shadow: 0 0 0 4px rgba(46,204,113,.2); }
    50%     { box-shadow: 0 0 0 7px rgba(46,204,113,.05); }
}
.product-stock-text {
    color: #fff;
    font-size: .9rem;
    font-weight: 500;
}

/* Sections */
.product-section-title {
    color: #13B2AD;
    font-weight: 600;
    text-transform: uppercase;
    font-size: .85rem;
    letter-spacing: 1px;
}

/* Specs grid */
.product-spec-item {
    padding: .75rem 1rem;
    background: #fafafa;
    border-left: 3px solid #D4A017;
    border-radius: 4px;
}
.product-spec-label {
    color: #888;
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 2px;
}
.product-spec-value {
    color: #1a1a1a;
    font-weight: 600;
    font-size: .95rem;
}

/* Description */
.product-description {
    color: #555;
    font-size: .94rem;
    line-height: 1.75;
}
.product-description p { margin-bottom: .6rem; }

/* Trust list */
.product-trust-list { color: #555; font-size: .92rem; }
.product-trust-list .bi-check-circle-fill { color: #13B2AD; }

/* Actions */
.product-action-btn {
    text-decoration: none;
    padding: 14px 20px;
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: .5px;
}
.product-back-link { text-decoration: none !important; }
.product-back-link:hover { color: #13B2AD !important; }

/* Related */
.product-related-title {
    color: #1a1a1a;
    font-weight: 700;
    border-bottom: 2px solid #D4A017;
    padding-bottom: .5rem;
    display: inline-block;
}
.product-related-card {
    transition: transform .2s ease, box-shadow .2s ease;
}
.product-related-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,.12) !important;
}
.product-related-price {
    color: #13B2AD;
    font-weight: 700;
    font-size: 1rem;
}

@media (max-width: 991px) {
    .product-price-main { font-size: 2rem; }
    .product-gallery-main { max-height: 360px; }
}
</style>
