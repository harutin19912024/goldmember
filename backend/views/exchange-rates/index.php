<?php

use backend\models\ExchangeRates;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\ExchangeRatesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Exchange Rates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exchange-rates-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Exchange Rates'), ['create?currency_id=1'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
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
            'sell_rate',
            'buy_rate',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ExchangeRates $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
