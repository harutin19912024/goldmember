<?php

use backend\models\Donate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\DonateSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Donations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-layout">
    <div class="tray tray-center">
        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_donation'
                        ],
                        'layout' => "{pager}\n{items}\n{pager}",
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => true,
                        'options' => ['class' => 'br-r', 'id' => 'donation'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name',
                            'email',
                            'phone',
                            'amount',
                            'message:ntext',
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
