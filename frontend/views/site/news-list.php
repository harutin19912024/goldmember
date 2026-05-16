<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $news \backend\models\News[] */
/* @var $categories \backend\models\NewsCategory[] */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'News');
$adminUrl    = Yii::$app->params['adminUrl'] ?? '';

?>

<!-- Page Title -->
<div class="page-title">
    <div class="heading">
        <div class="container">
            <div class="row d-flex justify-content-center text-center">
                <div class="col-lg-8">
                    <h1><?= Yii::t('app', 'News') ?></h1>
                    <p class="text-muted"><?= Yii::t('app', 'Latest updates from the gold market') ?></p>
                </div>
            </div>
        </div>
    </div>
    <nav class="breadcrumbs">
        <div class="container">
            <ol>
                <li><a href="/<?= Yii::$app->language ?>"><?= Yii::t('app', 'Home') ?></a></li>
                <li class="current"><?= Yii::t('app', 'News') ?></li>
            </ol>
        </div>
    </nav>
</div><!-- End Page Title -->

<section id="news-listing" class="section my-4">
    <div class="container">

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

    </div>
</section>
