<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductAttribute */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => [
            'class' => 'table table-striped table-hover display table-bordered dataTable no-footer',
            'id' => 'tbl_product'
        ],
        'template' =>'<tr class="odd" role="row"><th scope="row">{label}</th><td>{value}</td></tr>',
        'attributes' => [
//            'id',
//            'attribute_id',
            [
                'attribute' => 'attribute',
                'label' => 'Attribute',
                'value' =>$model->attributess->name
            ],
//            'product_id',
            [
                'attribute' => 'product',
                'label' => 'Product',
                'value' =>$model->product->name
            ],
            'value',
//            'created_date',
//            'updated_date',
        ],
    ]) ?>

</div>
