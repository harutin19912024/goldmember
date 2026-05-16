<?php
use yii\helpers\Html;
$partUrl = isset($uri) ? '/' . ltrim($uri, '/') : '';
$prevHref = isset($prevUrl) ? $prevUrl : ('/' . Yii::$app->language . $partUrl);
?>
<section class="container breadcrumb-container py-2 px-sm-0">
    <div class="row px-0">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= Html::encode($prevHref) ?>">
                            <i class="bi bi-house-door"></i><?= Yii::t('app', $prev) ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= Yii::t('app', $current) ?></li>
                </ol>
            </nav>
        </div>
    </div>
</section>