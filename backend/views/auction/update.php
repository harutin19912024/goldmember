<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Auction $model */

$this->title = Yii::t('app', 'Update Auction: {name}', [
    'name' => $model->product ? $model->product->title : '#' . $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product ? $model->product->title : '#' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?= $this->render('_form', ['model' => $model]) ?>
