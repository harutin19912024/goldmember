<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SocialNet */

$this->title = Yii::t('app', 'Create Social Net');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Social Nets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-net-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
