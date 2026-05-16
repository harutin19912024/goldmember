<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TrNews */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tr News',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tr News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tr-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
