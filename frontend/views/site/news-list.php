<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $news \backend\models\News[] */
/* @var $categories \backend\models\NewsCategory[] */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'News');
$adminUrl    = Yii::$app->params['adminUrl'] ?? '';

?>

<!-- Hero Banner -->
<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100">
        <img class="w-100 h-100" src="/images/news-banner.svg" alt="News Banner" height="400" width="1442"/>
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-5 col-12 px-0">
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title">
                <?= Yii::t('app', 'Gold Market News') ?>
            </h1>
            <p class="text-content fs-4 pb-1 white-color fw-normal mb-5 h-base">
                <?= Yii::t('app', 'Stay ahead with the latest gold prices, market trends and precious metals insights.') ?>
            </p>
        </div>
    </div>
</section>

<!-- Sub-heading -->
<section class="container breadcrumb-container py-2 px-sm-0">
    <div class="row px-0">
        <div class="col-sm-12">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1><?= Yii::t('app', 'Latest Updates') ?></h1>
                        <p class="mb-0"><?= Yii::t('app', 'News and analysis from the gold and precious metals market.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<?= $this->render('partials/breadcrumb', ['prev' => 'Home', 'prevUrl' => '/' . Yii::$app->language, 'current' => 'News']) ?>

<!-- News Grid -->
<section class="container py-4 px-sm-0">

    <?php if (!empty($categories)): ?>
        <div class="mb-4">
            <a href="<?= Url::to(['/site/news-list']) ?>"
               class="btn btn-sm btn-outline-dark me-1">
                <?= Yii::t('app', 'All') ?>
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= Url::to(['/site/news-by-category', 'category' => $cat->route_name]) ?>"
                   class="btn btn-sm btn-outline-secondary me-1">
                    <?= Html::encode($cat->name) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($news)): ?>
        <div class="alert alert-info">
            <?= Yii::t('app', 'No news articles available at this time.') ?>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($news as $article): ?>
                <?php
                    $primaryImg = $article->newsImagesPrimary;
                    $imgSrc = $primaryImg
                        ? $adminUrl . 'uploads/images/news/' . $article->id . '/' . $primaryImg->name
                        : null;
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm news-card">

                        <?php if ($imgSrc): ?>
                            <a href="<?= Url::to(['/site/news', 'id' => $article->id]) ?>">
                                <img src="<?= Html::encode($imgSrc) ?>"
                                     class="card-img-top"
                                     alt="<?= Html::encode($article->title) ?>"
                                     style="height:200px;object-fit:cover;">
                            </a>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-clock"></i>
                                <?= date('M j, Y', strtotime($article->created_date)) ?>
                                <?php if ($article->category): ?>
                                    &nbsp;·&nbsp;
                                    <a href="<?= Url::to(['/site/news-by-category', 'category' => $article->category->route_name]) ?>"
                                       class="text-muted">
                                        <?= Html::encode($article->category->name) ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <h5 class="card-title">
                                <a href="<?= Url::to(['/site/news', 'id' => $article->id]) ?>"
                                   class="text-dark text-decoration-none">
                                    <?= Html::encode($article->title) ?>
                                </a>
                            </h5>

                            <?php if (!empty($article->short_description)): ?>
                                <p class="card-text text-muted flex-grow-1">
                                    <?= Html::encode(mb_strimwidth(strip_tags($article->short_description), 0, 120, '…')) ?>
                                </p>
                            <?php endif; ?>

                            <div class="mt-auto pt-2">
                                <a href="<?= Url::to(['/site/news', 'id' => $article->id]) ?>"
                                   class="btn btn-sm btn-outline-dark">
                                    <?= Yii::t('app', 'Read more') ?> →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>
