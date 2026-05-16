<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Attribute */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Back to Attributes'), ['index',], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="panel">
        <div class="panel-body">
    <?= DetailView::widget([
        'model' => $model,
        'options' => [
            'class' => 'table table-striped table-hover display table-bordered dataTable no-footer',
            'id' => 'tbl_product'
        ],
        'template' => '<tr class="odd" role="row"><th scope="row">{label}</th><td>{value}</td></tr>',
        'attributes' => [
//            'id',
//            'type',
            [
                'attribute' => 'type',
                'label' => 'Attribute Type',
                'value' => $model->type
            ],
//            'name',
            [
                'attribute' => 'name',
                'label' => 'Attribute Name',
                'value' => $model->name
            ],
//            'created_date',
//            'updated_date',
            [
                'attribute' => 'category',
                'label' => 'Category',
                'value' => $model->category->name
            ]
        ],
    ]) ?>
        </div>
    </div>
</div>