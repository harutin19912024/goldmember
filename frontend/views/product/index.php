<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Products');

$adminUrl = Yii::$app->params['adminUrl'] ?? '';

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
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 product-card shadow-sm border-0">
                            <?php
                                $imgSrc = !empty($product['image'])
                                    ? $adminUrl . 'uploads/images/' . $product['image']
                                    : '/frontend/web/img/no-image.png';
                            ?>
                            <a href="<?= Url::to(['/product/product', 'id' => $product['id']]) ?>">
                                <img src="<?= Html::encode($imgSrc) ?>"
                                     class="card-img-top product-card-img"
                                     alt="<?= Html::encode($product['name'] ?? '') ?>"
                                     style="object-fit:cover;height:200px;">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-1">
                                    <a href="<?= Url::to(['/product/product', 'id' => $product['id']]) ?>"
                                       class="text-dark text-decoration-none">
                                        <?= Html::encode($product['name'] ?? '') ?>
                                    </a>
                                </h6>
                                <?php if (!empty($product['short_description'])): ?>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?= Html::encode(mb_strimwidth($product['short_description'], 0, 60, '…')) ?>
                                    </p>
                                <?php endif; ?>
                                <div class="mt-auto">
                                    <?php if (!empty($product['price'])): ?>
                                        <span class="fw-bold text-success">
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
