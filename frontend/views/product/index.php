<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Favorites;

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Products');
$adminUrl = Yii::$app->params['adminUrl'] ?? '';

$favoritedIds = [];
if (!Yii::$app->user->isGuest) {
    $favoritedIds = Favorites::find()
        ->select('product_id')
        ->where(['user_id' => Yii::$app->user->id])
        ->column();
}

?>

<div id="product-listing" class="mt-4 mb-5">
    <div class="container">

        <div class="row mb-3">
            <div class="col-12">
                <h2><?= Yii::t('app', 'Products') ?></h2>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted"><?= Yii::t('app', 'No products found.') ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <?php
                        $imgSrc = !empty($product['image'])
                            ? $adminUrl . 'uploads/images/' . $product['image']
                            : null;
                        $isFaved = in_array($product['id'], $favoritedIds);
                    ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 product-card shadow-sm border-0 position-relative">

                            <!-- Favourite button -->
                            <button type="button"
                                    data-fav-id="<?= $product['id'] ?>"
                                    title="<?= $isFaved ? Yii::t('app', 'Remove from favourites') : Yii::t('app', 'Add to favourites') ?>"
                                    class="position-absolute top-0 end-0 m-2 border-0 rounded-circle d-flex align-items-center justify-content-center<?= $isFaved ? ' faved' : '' ?>"
                                    style="width:32px;height:32px;background:rgba(255,255,255,0.92);z-index:2;cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,.15);">
                                <i class="bi <?= $isFaved ? 'bi-heart-fill' : 'bi-heart' ?>"
                                   style="color:<?= $isFaved ? '#13B2AD' : '#aaa' ?>;font-size:1rem;"></i>
                            </button>

                            <?php if ($imgSrc): ?>
                                <a href="<?= Url::to(['/product/detail', 'id' => $product['id']]) ?>">
                                    <img src="<?= Html::encode($imgSrc) ?>"
                                         class="card-img-top product-card-img"
                                         alt="<?= Html::encode($product['name'] ?? '') ?>"
                                         style="object-fit:cover;height:200px;">
                                </a>
                            <?php else: ?>
                                <a href="<?= Url::to(['/product/detail', 'id' => $product['id']]) ?>"
                                   class="d-flex align-items-center justify-content-center bg-light"
                                   style="height:200px;">
                                    <i class="bi bi-image text-muted" style="font-size:2.5rem;"></i>
                                </a>
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-1">
                                    <a href="<?= Url::to(['/product/detail', 'id' => $product['id']]) ?>"
                                       class="text-dark text-decoration-none">
                                        <?= Html::encode($product['name'] ?? '') ?>
                                    </a>
                                </h6>
                                <?php if (!empty($product['short_description'])): ?>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?= Html::encode(mb_strimwidth(strip_tags($product['short_description']), 0, 60, '…')) ?>
                                    </p>
                                <?php endif; ?>
                                <div class="mt-auto d-flex align-items-center justify-content-between">
                                    <?php if (!empty($product['price'])): ?>
                                        <span class="fw-bold" style="color:#13B2AD;">
                                            <?= number_format((float)$product['price'], 0, '.', ',') ?> AMD
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>
