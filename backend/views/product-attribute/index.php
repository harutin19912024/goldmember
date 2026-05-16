<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductAttributeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Product Attributes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Product Attribute'), ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-hover display dataTable no-footer',
            'id' => 'tbl_product-attribute'
        ],
        'filterRowOptions' => [
            'role' => "row",
        ],
        'rowOptions' => [
            'role' => "row",
            'class' => 'odd'
        ],
        'summary' => false,
        'options' => ['class' => 'br-r',],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            ['attribute' => 'attributess',
                'label' =>'Attribute',
                'value' => 'attributess.name',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search'
                ],
            ],
//            'attribute_id',
            ['attribute' => 'product',
                'value' => 'product.name',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search'
                ],
            ],
//            'product_id',
            ['attribute' => 'value',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search'
                ],
            ],
//            'value',
//            'created_date',
            // 'updated_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
