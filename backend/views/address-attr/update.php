<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AddressAttr */

$this->title = 'Update Address Attr: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Address Attrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="address-attr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
	  'addressAttr' => $addressAttr,
    ]) ?>

</div>
