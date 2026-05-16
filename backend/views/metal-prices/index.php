<?php

use backend\models\MetalPrices;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\MetalPricesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Metal Prices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metal-prices-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Metal Prices'), ['create?currency_id=1&metal_id=1'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['attribute' => 'metal_id',
                'format' => 'html',
				'value' => function ($model) {
                    return "<span>".$model->metal->name."</span>";
                },
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search'
                ],
            ],
            ['attribute' => 'currency_id',
                'format' => 'html',
				'value' => function ($model) {
                    return "<span>".$model->currency->name."</span>";
                },
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search'
                ],
            ],
            'karat',
            'sell_price',
            'original_sell_price',
            'buy_price',
            'original_buy_price',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MetalPrices $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
