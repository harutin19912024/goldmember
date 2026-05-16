<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $products array   keyed by product id, from Product::getFavoritesByUser() */
/* @var $favoritedIds int[] */

$adminUrl = Yii::$app->params['adminUrl'] ?? '';

?>

<?php if (empty($products)): ?>
    <div class="text-center py-5">
        <i class="bi bi-heart" style="font-size:3rem;color:#13B2AD;opacity:.4;"></i>
        <p class="text-muted mt-3 mb-1"><?= Yii::t('app', 'No favourites yet.') ?></p>
        <p class="text-muted small"><?= Yii::t('app', 'Tap the heart icon on any product to save it here.') ?></p>
        <a href="/<?= Yii::$app->language ?>/search" class="btn btn-sm mt-2"
           style="background:#13B2AD;color:#fff;border-radius:20px;padding:6px 20px;">
            <?= Yii::t('app', 'Browse Products') ?>
        </a>
    </div>
<?php else: ?>
    <div class="row g-3" id="fav-grid">
        <?php foreach ($products as $productId => $product): ?>
            <?php
                $imgSrc = !empty($product['image'])
                    ? $adminUrl . 'uploads/images/' . $product['image']
                    : null;
            ?>
            <div class="col-6 col-md-4" id="fav-card-<?= $productId ?>">
                <div class="card border-0 shadow-sm h-100 position-relative" style="border-radius:10px;overflow:hidden;">

                    <!-- Heart button — data-fav-profile triggers card removal on un-fave -->
                    <button type="button"
                            data-fav-id="<?= $productId ?>"
                            data-fav-profile="1"
                            title="<?= Yii::t('app', 'Remove from favourites') ?>"
                            class="position-absolute top-0 end-0 m-2 border-0 rounded-circle d-flex align-items-center justify-content-center faved"
                            style="width:30px;height:30px;background:rgba(255,255,255,.9);z-index:3;cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,.15);">
                        <i class="bi bi-heart-fill" style="color:#13B2AD;font-size:.95rem;"></i>
                    </button>

                    <?php if ($imgSrc): ?>
                        <a href="<?= Url::to(['/product/product', 'id' => $productId]) ?>">
                            <img src="<?= Html::encode($imgSrc) ?>"
                                 class="card-img-top"
                                 alt="<?= Html::encode($product['name'] ?? '') ?>"
                                 style="height:160px;object-fit:cover;">
                        </a>
                    <?php else: ?>
                        <a href="<?= Url::to(['/product/product', 'id' => $productId]) ?>"
                           class="d-flex align-items-center justify-content-center bg-light"
                           style="height:160px;">
                            <i class="bi bi-image text-muted" style="font-size:2rem;"></i>
                        </a>
                    <?php endif; ?>

                    <div class="card-body py-2 px-3">
                        <h6 class="card-title mb-1" style="font-size:.87rem;">
                            <a href="<?= Url::to(['/product/product', 'id' => $productId]) ?>"
                               class="text-dark text-decoration-none">
                                <?= Html::encode($product['name'] ?? '') ?>
                            </a>
                        </h6>
                        <?php if (!empty($product['price'])): ?>
                            <span class="fw-bold" style="color:#13B2AD;font-size:.9rem;">
                                <?= number_format((float)$product['price'], 0, '.', ',') ?> AMD
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
