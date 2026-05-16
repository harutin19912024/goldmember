<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Marks $model */

$this->title = Yii::t('app', 'Create '.$pageName);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $pageName), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'isGallery' => $isGallery,
        'pageName'=> $pageName
    ]) ?>

</div>
