<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SliderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sliders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-layout">
    <div class="tray tray-center">
        <!-- create new order panel -->
        <div class="row">
            <div id="slider-form_cont" class="col-lg-2 col-sm-3">
                <?= Html::a('<span class="fa fa-plus pr5"></span> ' . Yii::t('app', 'Create Slider'), ['/slider/create'], ['class' => 'btn btn-system mb15']) ?>
            </div>
        </div>
        <!-- recent orders table -->
        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_slider'
                        ],
                        'layout' => "{pager}\n{items}\n{pager}",
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => true,
                        'options' => ['class' => 'br-r', 'id' => 'slider'],
                        'columns' => [
                            ['attribute' => 'image',
                                'format' => 'html',
                                'label' => Yii::t('app', 'Image'),
                                'headerOptions' => ['style' => 'width: 5%;'],
                                'value' => function ($model) {
                                    $path = 'uploads/images/slider/' . $model->id . '/' . $model->path;
                                    return Html::img('/' . $path, ['style' => 'width: 40px !important']);
                                },
                            ],
                            ['attribute' => 'title',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return Html::a($model->title, 'javascript: void(0);', ['class' => 'link']);
                                },
                            ],
                            ['attribute' => 'status',
                                'contentOptions' => function ($model) {
                                    if ($model->status == 0) {
                                        return ['class' => "list-status label label-rounded label-info"];
                                    } elseif ($model->status == 1) {
                                        return ['class' => "list-status label label-rounded label-success"];
                                    }
                                },
                                'value' => function ($model) {
                                    if ($model->status == 0) {
                                        return Yii::t('app', "Unavailable");
                                    } elseif ($model->status == 1) {
                                        return Yii::t('app', "Available");
                                    }
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
                                ]
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
