<?php

use backend\models\Auction;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\AuctionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Auctions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-layout">
    <div class="tray tray-center">

        <div class="row">
            <div id="auction-form_cont" class="col-lg-2 col-sm-3">
                <?= Html::a('<span class="fa fa-plus pr5"></span> ' . Yii::t('app', 'Create Auction'), ['/auction/create'], ['class' => 'btn btn-system mb15']) ?>
            </div>
        </div>

        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_auction'
                        ],
                        'layout' => "{pager}\n{items}\n{pager}",
                        'rowOptions' => [
                            'role' => 'row',
                            'class' => 'odd'
                        ],
                        'summary' => true,
                        'options' => ['class' => 'br-r', 'id' => 'auction'],
                        'columns' => [
                            [
                                'attribute' => 'product_id',
                                'label' => Yii::t('app', 'Product'),
                                'format' => 'html',
                                'value' => function (Auction $model) {
                                    return $model->product
                                        ? Html::encode($model->product->title)
                                        : '<span class="text-muted">#' . $model->product_id . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'lot_number',
                                'label' => Yii::t('app', 'Lot'),
                            ],
                            [
                                'attribute' => 'start_date',
                                'format' => 'datetime',
                            ],
                            [
                                'attribute' => 'end_date',
                                'format' => 'datetime',
                            ],
                            [
                                'attribute' => 'start_price',
                                'format' => 'html',
                                'value' => function (Auction $model) {
                                    return $model->start_price
                                        ? '<strong>֏ ' . number_format($model->start_price, 0, '.', ',') . '</strong>'
                                        : '—';
                                },
                            ],
                            [
                                'label' => Yii::t('app', 'Status'),
                                'format' => 'html',
                                'value' => function (Auction $model) {
                                    $now   = time();
                                    $start = $model->start_date ? strtotime($model->start_date) : null;
                                    $end   = $model->end_date   ? strtotime($model->end_date)   : null;

                                    if (!$start) {
                                        return '<span class="label label-default">No date</span>';
                                    }
                                    if ($end && $now > $end) {
                                        return '<span class="label label-default">Ended</span>';
                                    }
                                    if ($now >= $start && ($end === null || $now <= $end)) {
                                        return '<span class="label label-danger">&#9679; Live</span>';
                                    }
                                    return '<span class="label label-warning">Upcoming</span>';
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}',
                                'contentOptions' => ['style' => 'white-space: normal;'],
                                'headerOptions' => ['style' => 'width: 9%;'],
                                'urlCreator' => function ($action, Auction $model, $key, $index, $column) {
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
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>

    </div>
</div>
