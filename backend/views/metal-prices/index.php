<?php

use backend\models\MetalPrices;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\MetalPricesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Metal Prices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-layout">
    <div class="tray tray-center">
        <div class="row">
            <div id="metal-prices-form_cont" class="col-lg-2 col-sm-3">
                <?= Html::a('<span class="fa fa-plus pr5"></span> ' . Yii::t('app', 'Create Metal Prices'), ['/metal-prices/create', 'currency_id' => 1, 'metal_id' => 1], ['class' => 'btn btn-system mb15']) ?>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_metal_prices'
                        ],
                        'layout' => "{pager}\n{items}\n{pager}",
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => true,
                        'options' => ['class' => 'br-r', 'id' => 'metal-prices'],
                        'columns' => [
                            ['attribute' => 'metal_id',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return "<span>" . $model->metal->name . "</span>";
                                },
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
                            ['attribute' => 'currency_id',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return "<span>" . $model->currency->name . "</span>";
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
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}',
                                'contentOptions' => ['style' => 'white-space: normal;'],
                                'headerOptions' => ['style' => 'width: 9%;'],
                                'urlCreator' => function ($action, MetalPrices $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                },
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-edit"></span>' . Yii::t('app', 'Edit'), $url, [
                                            'title' => Yii::t('app', 'Edit'),
                                            'aria-label' => 'Edit',
                                            'data-key' => $model->id,
                                            'class' => 'btn btn-info btn-xs fs12 br2 ml5'
                                        ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>' . Yii::t('app', 'Delete'), $url, [
                                            'title' => Yii::t('app', 'Delete'),
                                            'aria-label' => Yii::t('app', 'Delete'),
                                            'data-confirm' => 'Are you sure! You whant delete this item?',
                                            'data-method' => 'post',
                                            'data-pjax' => '0',
                                            'data-key' => $model->id,
                                            'class' => 'btn btn-danger btn-xs fs12 br2 ml5'
                                        ]);
                                    },
                                ]
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
