<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TrProduct */

$this->title = Yii::t('app', 'Create Tr Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tr Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
