<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_broker'
                        ],
                        'filterRowOptions' => [
                            'role' => "row",
                        ],
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => false,
                        'options' => ['class' => 'br-r', 'id' => 'product'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
             'user_number',
            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{allow}{view}{update}{delete}',
                                'contentOptions' => ['style' => 'width:28%; white-space: normal;'],
                                'buttons' => [
                                    'allow' => function ($url, $model) {
										$allowClass = ($model->allow_create) ? 'btn-system' : 'btn-warning';
                                        return Html::a(Yii::t('app', 'Allow'), 'javascript:;', [
                                            'title' => Yii::t('app', 'Allow'),
                                            'aria-label' => 'Allow',
                                            'data-key' => $model->id,
                                            'id' => 'allowCreateProdutc-'.$model->id,
                                            'onclick' => 'allowToAddProdcut('.$model->id.','. !$model->allow_create.')',
                                            'class' => 'btn '.$allowClass.' btn-xs fs12 br2 ml5'
                                        ]);
                                    },
									'view' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-view"></span>' . Yii::t('app', 'View'), $url, [
                                            'title' => Yii::t('app', 'View'),
                                            'aria-label' => 'View',
                                            'data-key' => $model->id,
                                            'class' => 'btn btn-primary btn-xs fs12 br2 ml5'
                                        ]);
                                    },
                                    'update' => function ($url, $model) {
                                        if (\Yii::$app->user->identity->role == 1) return '';
                                        return Html::a('<span class="glyphicon glyphicon-edit"></span>' . Yii::t('app', 'Edit'), $url, [
                                            'title' => Yii::t('app', 'Edit'),
                                            'aria-label' => 'Edit',
                                            'data-key' => $model->id,
                                            'class' => 'btn btn-info btn-xs fs12 br2 ml5'
                                        ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        if (\Yii::$app->user->identity->role == 1) return '';
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