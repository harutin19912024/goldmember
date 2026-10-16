<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Files $model */
/** @var int $isGallery */
/** @var string $pageName */

$this->title = Yii::t('app', 'Update {page}: {name}', [
    'page' => Yii::t('app', $pageName),
    'name' => $model->title ?: $model->path,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $pageName), 'url' => [$isGallery ? 'gallery' : 'video']];
$this->params['breadcrumbs'][] = ['label' => $model->title ?: $model->path, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="media-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'isGallery' => $isGallery
    ]) ?>

</div>
