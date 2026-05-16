<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Sitesettings */

$this->title = Yii::t('app', 'Home Page Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Home Page Settings'), 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sitesettings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
