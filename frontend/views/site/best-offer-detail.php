<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $product backend\models\Product */
/* @var $isFaved bool */

$adminUrl  = Yii::$app->params['adminUrl'] ?? '';
$mainImage = $product->image;
$mainSrc   = $mainImage ? $adminUrl . 'uploads/images/' . $mainImage->name : null;
$allImages = $product->productImages;

$this->title = Html::encode($product->title) . ' | ' . Yii::t('app', 'Best Offer');

?>

<!-- Hero Banner -->
<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100">
        <img class="w-100 h-100" src="/images/auction.png" alt="Best Offer" height="400" width="1442"/>
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-6 col-12 px-0">
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title">
                <?= Html::encode($product->title) ?>
            </h1>
            <span class="badge fs-6 mb-4 px-3 py-2" style="background:#D4A017;color:#000;letter-spacing:1px;">
                ★ <?= Yii::t('app', 'Best Offer') ?>
            </span>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<?= $this->render('partials/breadcrumb', [
    'prev'    => Yii::t('app', 'Best Offer'),
    'prevUrl' => '/' . Yii::$app->language . '/best-offer',
    'current' => Html::encode($product->title),
]) ?>

<!-- Main content -->
<section class="container py-4 px-sm-0">
    <div class="row g-4">

        <!-- ===== LEFT: Images + details ===== -->
        <div class="col-lg-5 col-12">
            <div class="shadow rounded overflow-hidden">

                <!-- Main image / carousel -->
                <?php if (!empty($allImages)): ?>
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($allImages as $i => $img): ?>
                                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                    <img src="<?= Html::encode($adminUrl . 'uploads/images/' . $img->name) ?>"
                                         class="d-block w-100"
                                         alt="<?= Html::encode($product->title) ?>"
                                         style="max-height:420px;object-fit:cover;">
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
                    </div>
                <?php elseif ($mainSrc): ?>
                    <img src="<?= Html::encode($mainSrc) ?>"
                         alt="<?= Html::encode($product->title) ?>"
                         class="img-fluid w-100"
                         style="max-height:420px;object-fit:cover;">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:320px;">
                        <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                    </div>
                <?php endif ?>

                <!-- Details panel -->
                <div class="p-4">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <h4 class="mb-0 fw-bold"><?= Html::encode($product->title) ?></h4>
                            <?php if ($product->category): ?>
                                <span class="text-muted small"><?= Html::encode($product->category->name) ?></span>
                            <?php endif ?>
                        </div>
                        <!-- Favourite button -->
                        <button type="button"
                                data-fav-id="<?= $product->id ?>"
                                title="<?= $isFaved ? Yii::t('app','Remove from favourites') : Yii::t('app','Add to favourites') ?>"
                                class="border-0 rounded-circle d-flex align-items-center justify-content-center<?= $isFaved ? ' faved' : '' ?>"
                                style="width:40px;height:40px;background:#f5f5f5;cursor:pointer;flex-shrink:0;">
                            <i class="bi <?= $isFaved ? 'bi-heart-fill' : 'bi-heart' ?>"
                               style="font-size:1.2rem;color:<?= $isFaved ? '#13B2AD' : '#aaa' ?>;"></i>
                        </button>
                    </div>

                    <hr>

                    <dl class="row mb-0 small">
                        <?php if ($product->product_sku): ?>
                            <dt class="col-5 text-muted"><?= Yii::t('app', 'SKU') ?></dt>
                            <dd class="col-7"><?= Html::encode($product->product_sku) ?></dd>
                        <?php endif ?>

                        <?php if ($product->material): ?>
                            <dt class="col-5 text-muted"><?= Yii::t('app', 'Material') ?></dt>
                            <dd class="col-7"><?= Html::encode($product->material->name) ?></dd>
                        <?php endif ?>

                        <?php if ($product->fineness): ?>
                            <dt class="col-5 text-muted"><?= Yii::t('app', 'Fineness') ?></dt>
                            <dd class="col-7"><?= Html::encode($product->fineness) ?></dd>
                        <?php endif ?>

                        <?php if ($product->weight): ?>
                            <dt class="col-5 text-muted"><?= Yii::t('app', 'Weight') ?></dt>
                            <dd class="col-7"><?= Html::encode($product->weight) ?> g</dd>
                        <?php endif ?>

                        <?php if ($product->color): ?>
                            <dt class="col-5 text-muted"><?= Yii::t('app', 'Color') ?></dt>
                            <dd class="col-7"><?= Html::encode($product->color) ?></dd>
                        <?php endif ?>

                        <?php if ($product->gemstone): ?>
                            <dt class="col-5 text-muted"><?= Yii::t('app', 'Gemstone') ?></dt>
                            <dd class="col-7"><?= Html::encode($product->gemstone) ?></dd>
                        <?php endif ?>

                        <?php if ($product->state): ?>
                            <dt class="col-5 text-muted"><?= Yii::t('app', 'Condition') ?></dt>
                            <dd class="col-7"><?= Html::encode($product->state) ?></dd>
                        <?php endif ?>

                        <?php if ($product->country): ?>
                            <dt class="col-5 text-muted"><?= Yii::t('app', 'Country') ?></dt>
                            <dd class="col-7"><?= Html::encode($product->country) ?></dd>
                        <?php endif ?>
                    </dl>

                    <?php if (!empty($product->short_description)): ?>
                        <hr>
                        <p class="small text-muted mb-0"><?= Html::encode(strip_tags($product->short_description)) ?></p>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <!-- ===== RIGHT: Pricing + actions ===== -->
        <div class="col-lg-7 col-12">
            <div class="shadow rounded overflow-hidden h-100">

                <!-- Price header -->
                <div class="p-4" style="background:linear-gradient(135deg,#0d2020 0%,#0a1818 100%);">
                    <p class="text-muted mb-1" style="color:#53E2D9!important;font-size:.8rem;letter-spacing:1px;text-transform:uppercase;">
                        <?= Yii::t('app', 'Best Offer Price') ?>
                    </p>
                    <div class="d-flex align-items-baseline gap-3">
                        <span class="fw-bold" style="font-size:2.4rem;color:#D4A017;">
                            ֏ <?= number_format((float)$product->price, 0, '', '.') ?>
                        </span>
                        <?php if (!empty($product->another_price) && $product->another_price != $product->price): ?>
                            <span class="text-decoration-line-through text-muted" style="font-size:1.1rem;">
                                ֏ <?= number_format((float)$product->another_price, 0, '', '.') ?>
                            </span>
                        <?php endif ?>
                    </div>
                    <span class="badge mt-2 px-3 py-2" style="background:#D4A017;color:#000;font-size:.8rem;letter-spacing:1px;">
                        ★ <?= Yii::t('app', 'Best Offer') ?>
                    </span>
                </div>

                <!-- Description -->
                <?php if (!empty($product->description)): ?>
                    <div class="p-4 border-bottom">
                        <h6 class="fw-semibold mb-2" style="color:#13B2AD;">
                            <?= Yii::t('app', 'Description') ?>
                        </h6>
                        <div class="text-muted" style="font-size:.92rem; line-height:1.7;">
                            <?= strip_tags($product->description) ?>
                        </div>
                    </div>
                <?php endif ?>

                <!-- Why choose this -->
                <div class="p-4 border-bottom">
                    <h6 class="fw-semibold mb-3" style="color:#13B2AD;">
                        <?= Yii::t('app', 'Why choose this product?') ?>
                    </h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2"><i class="bi bi-check-circle me-2" style="color:#13B2AD;"></i><?= Yii::t('app', 'Certified gold quality') ?></li>
                        <li class="mb-2"><i class="bi bi-check-circle me-2" style="color:#13B2AD;"></i><?= Yii::t('app', 'Secure purchase guarantee') ?></li>
                        <li class="mb-2"><i class="bi bi-check-circle me-2" style="color:#13B2AD;"></i><?= Yii::t('app', 'Trusted by Goldmember.am') ?></li>
                        <li><i class="bi bi-check-circle me-2" style="color:#13B2AD;"></i><?= Yii::t('app', 'Fast delivery across Armenia') ?></li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="p-4 d-flex flex-column gap-3">
                    <a href="tel:+37494261606" class="button primary-button d-flex align-items-center justify-content-center gap-2"
                       style="text-decoration:none;padding:14px 20px;font-size:1rem;">
                        <i class="bi bi-telephone-fill"></i>
                        <?= Yii::t('app', 'Call to Order') ?>
                    </a>
                    <a href="<?= Url::to(['/site/contact']) ?>" class="button secondary-button d-flex align-items-center justify-content-center gap-2"
                       style="text-decoration:none;padding:14px 20px;font-size:1rem;">
                        <i class="bi bi-envelope"></i>
                        <?= Yii::t('app', 'Send Enquiry') ?>
                    </a>
                    <a href="/<?= Yii::$app->language ?>/best-offer" class="text-center text-muted small mt-1"
                       style="text-decoration:none;">
                        ← <?= Yii::t('app', 'Back to Best Offers') ?>
                    </a>
                </div>

            </div>
        </div>

    </div>
</section>

<style>
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
</style>
