<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="table-layout">
    <div class="tray tray-center">
        <div class="row">
            <div id="customer-form_cont" class="col-lg-2 col-sm-3">
                <?= Html::a('<span class="fa fa-plus pr5"></span> ' . Yii::t('app', 'Create Customer'), ['/customer/create'], ['class' => 'btn btn-system mb15']) ?>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_customer'
                        ],
                        'layout' => "{pager}\n{items}\n{pager}",
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => true,
                        'options' => ['class' => 'br-r', 'id' => 'customer'],
                        'columns' => [
                            ['attribute' => 'name'],
                            ['attribute' => 'surname'],
                            ['attribute' => 'email',
                                'format' => 'email',
                            ],
                            ['attribute' => 'phone'],
                            ['attribute' => 'status',
                                'contentOptions' => function ($model) {
                                    if ($model->status == 0) {
                                        return ['class' => "label list-status label-rounded label-danger"];
                                    } elseif ($model->status == 1) {
                                        return ['class' => "label list-status label-rounded label-system"];
                                    }
                                },
                                'value' => function ($model) {
                                    if ($model->status == 0) {
                                        return Yii::t('app', "Pasive");
                                    } else {
                                        return Yii::t('app', "Active");
                                    }
                                },
                            ],
                            ['attribute' => 'user',
                                'value' => 'user.username',
                            ],
                            ['attribute' => 'address',
                                'value' => function ($model) {
                                    $address = $model->getFullAddress($model->id);
                                    $addr = array_filter(array_values($address));
                                    return implode(', ', $addr);
                                },
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}',
                                'contentOptions' => ['style' => 'white-space: normal;'],
                                'headerOptions' => ['style' => 'width: 9%;'],
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
