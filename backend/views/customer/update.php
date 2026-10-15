<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $customer_model backend\models\Customer */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Customer',
]) . $customer_model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $customer_model->name, 'url' => ['view', 'id' => $customer_model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'customer_model' => $customer_model,
    ]) ?>

</div>
