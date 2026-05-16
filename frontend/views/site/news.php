<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $news \backend\models\News */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Html::encode($news->title);

$adminUrl   = Yii::$app->params['adminUrl'] ?? '';
$primaryImg = $news->newsImagesPrimary;
$imagePath  = $primaryImg
    ? $adminUrl . 'uploads/images/news/' . $news->id . '/' . $primaryImg->name
    : null;

?>

<!-- Page Title -->
<div class="page-title">
    <div class="heading">
        <div class="container">
            <div class="row d-flex justify-content-center text-center">
                <div class="col-lg-8">
                    <h1><?= Html::encode($news->title) ?></h1>
                    <p class="text-muted">
                        <i class="bi bi-clock"></i>
                        <?= date('M j, Y', strtotime($news->created_date)) ?>
                        <?php if ($news->category): ?>
                            &nbsp;·&nbsp;
                            <a href="<?= Url::to(['/site/news-by-category', 'category' => $news->category->route_name]) ?>"
                               class="text-muted">
                                <?= Html::encode($news->category->name) ?>
                            </a>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <nav class="breadcrumbs">
        <div class="container">
            <ol>
                <li><a href="/<?= Yii::$app->language ?>"><?= Yii::t('app', 'Home') ?></a></li>
                <li><a href="<?= Url::to(['/site/news-list']) ?>"><?= Yii::t('app', 'News') ?></a></li>
                <li class="current"><?= Html::encode(mb_strimwidth($news->title, 0, 50, '…')) ?></li>
            </ol>
        </div>
    </nav>
</div><!-- End Page Title -->

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <section id="blog-details" class="blog-details section">
                <article class="article">

                    <?php if ($imagePath): ?>
                        <div class="post-img mb-4">
                            <img src="<?= Html::encode($imagePath) ?>"
                                 alt="<?= Html::encode($news->title) ?>"
                                 class="img-fluid rounded">
                        </div>
                    <?php endif; ?>

                    <div class="content mb-4">
                        <?= $news->content ?>
                    </div>

                    <?php if (!empty($news->video_url)): ?>
                        <div class="mb-4">
                            <h5><?= Yii::t('app', 'Video') ?></h5>
                            <div><?= $news->video_url ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($news->source_url)): ?>
                        <p class="text-muted small">
                            <?= Yii::t('app', 'Source') ?>:
                            <a href="<?= Html::encode($news->source_url) ?>" target="_blank" rel="noopener">
                                <?= Html::encode($news->source_url) ?>
                            </a>
                        </p>
                    <?php endif; ?>

                </article>
            </section>

            <!-- Back to News -->
            <div class="mt-4">
                <a href="<?= Url::to(['/site/news-list']) ?>" class="btn btn-outline-dark">
                    ← <?= Yii::t('app', 'Back to News') ?>
                </a>
            </div>

        </div>
    </div>
</div>
