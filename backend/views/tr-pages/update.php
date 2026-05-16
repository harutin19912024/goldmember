<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TrPages */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tr Pages',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tr Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tr-pages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
