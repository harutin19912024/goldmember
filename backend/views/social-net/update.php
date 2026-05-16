<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SocialNet */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Social Net',
]) . $model->social_type;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Social Nets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->social_type, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="social-net-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
