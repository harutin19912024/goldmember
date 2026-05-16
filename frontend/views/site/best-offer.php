<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Slider;

/* @var $this yii\web\View */
/* @var $products array */
/* @var $pages yii\data\Pagination */
/* @var $favoritedIds int[] */

$sliders = Slider::find()->where(['status' => 1])->all();

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Best Offer');
?>

<!-- Carousel hero — standalone section, NOT wrapped in .main-banner -->
<?php if (!empty($sliders)): ?>
<section class="best-offer-hero">
    <div id="bestOfferCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($sliders as $i => $slider): ?>
                <button type="button"
                        data-bs-target="#bestOfferCarousel"
                        data-bs-slide-to="<?= $i ?>"
                        <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                        aria-label="Slide <?= $i + 1 ?>">
                </button>
            <?php endforeach ?>
        </div>

        <div class="carousel-inner">
            <?php foreach ($sliders as $i => $slider): ?>
                <?php $imgPath = Yii::$app->params['adminUrl'] . 'uploads/images/slider/' . $slider->id . '/' . $slider->path ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <img src="<?= Html::encode($imgPath) ?>"
                         class="d-block w-100"
                         alt="<?= Html::encode($slider->title ?: 'Slide ' . ($i + 1)) ?>"
                         loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
                    <?php if ($slider->title || $slider->short_description): ?>
                    <div class="carousel-caption d-none d-md-block">
                        <?php if ($slider->title): ?>
                            <h5><?= Html::encode($slider->title) ?></h5>
                        <?php endif ?>
                        <?php if ($slider->short_description): ?>
                            <p><?= Html::encode($slider->short_description) ?></p>
                        <?php endif ?>
                    </div>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#bestOfferCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bestOfferCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
<?php endif ?>

<!-- Page intro + breadcrumb — single unified section -->
<section class="page-intro-section">
    <div class="container">
        <div class="row justify-content-center text-center mb-3">
            <div class="col-lg-8">
                <h1 class="page-intro-title"><?= Yii::t('app', 'Best Offer') ?></h1>
                <p class="page-intro-sub"><?= Yii::t('app', 'Make the decision in the best interest of the products.') ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="/<?= Yii::$app->language ?>">
                                <i class="bi bi-house-door me-1"></i><?= Yii::t('app', 'Home') ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= Yii::t('app', 'Best Offer') ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Products grid -->
<section class="container py-3 px-sm-0">
    <?php if (empty($products)): ?>
        <div class="text-center py-5">
            <i class="bi bi-tag text-muted" style="font-size:3rem;"></i>
            <p class="mt-3 text-muted"><?= Yii::t('app', 'No best offers available at the moment.') ?></p>
        </div>
    <?php else: ?>
    <ul class="row products px-0">
        <?php foreach ($products as $product): ?>
            <?php
            $imagePath = Yii::$app->params['adminUrl'] . 'uploads/images/' . $product['image'];
            $uid      = (int) $product['id'];
            $isFaved  = in_array($uid, $favoritedIds ?? []);
            ?>
            <li class="col-lg-4 col-sm-12 col-md-6 product-item">
                <div class="w-100 shadow h-100">
                    <a href="/<?= Yii::$app->language ?>/best-offer/<?= $uid ?>" aria-label="Visit detail" class="position-relative product-link">
                        <div class="position-relative product-img img-box">
                            <img src="<?= Html::encode($imagePath) ?>" alt="" class="img-fluid"
                                 onmouseover="zoomIn(<?= $uid ?>)"
                                 onmouseout="zoomOut(<?= $uid ?>)"
                                 id="zoomImage_<?= $uid ?>">
                            <button type="button"
                                    data-fav-id="<?= $uid ?>"
                                    aria-label="Toggle Favourite"
                                    title="<?= $isFaved ? Yii::t('app','Remove from favourites') : Yii::t('app','Add to favourites') ?>"
                                    class="bg-white-color button icon-button position-absolute zindex-offcanvas-backdrop<?= $isFaved ? ' faved' : '' ?>">
                                <i class="bi <?= $isFaved ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                            </button>
                        </div>
                        <div class="content">
                            <div class="description">
                                <h3 class="product-name"><?= Html::encode($product['name']) ?></h3>
                                <div class="d-flex justify-content-between">
                                    <span class="category"><?= Html::encode($product['categoryName']) ?></span>
                                    <div class="star-rating">
                                        <?php for ($s = 5; $s >= 1; $s--): ?>
                                            <input type="radio"
                                                   id="star<?= $s ?>_<?= $uid ?>"
                                                   name="rating_<?= $uid ?>"
                                                   value="<?= $s ?>" disabled>
                                            <label for="star<?= $s ?>_<?= $uid ?>">
                                                <i class="bi bi-star"></i>
                                                <i class="bi bi-star-fill"></i>
                                            </label>
                                        <?php endfor ?>
                                    </div>
                                </div>
                            </div>
                            <div class="deadline-more-box">
                                <div class="deadline-box"></div>
                                <a href="/<?= Yii::$app->language ?>/best-offer/<?= $uid ?>" class="button secondary-button view-more-button">
                                    <?= Yii::t('app', 'More') ?><i class="bi bi-eye ms-1"></i>
                                </a>
                                <?php if ($product['price']): ?>
                                    <span class="button primary-button">
                                        ֏ <?= number_format($product['price'], 0, '', '.') ?>
                                    </span>
                                <?php endif ?>
                            </div>
                        </div>
                    </a>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>
</section>

<!-- Pagination -->
<?php if ($pages->pageCount > 1): ?>
<section class="container pagination-section">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($pages->page > 0): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= Url::current(['page' => $pages->page]) ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif ?>

                    <?php for ($p = 0; $p < $pages->pageCount; $p++): ?>
                        <li class="page-item <?= $p === $pages->page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= Url::current(['page' => $p + 1]) ?>"><?= $p + 1 ?></a>
                        </li>
                    <?php endfor ?>

                    <?php if ($pages->page < $pages->pageCount - 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= Url::current(['page' => $pages->page + 2]) ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif ?>
                </ul>
            </nav>
        </div>
    </div>
</section>
<?php endif ?>
