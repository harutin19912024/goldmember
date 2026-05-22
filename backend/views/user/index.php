<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$roleLabels = [
    0  => ['text' => Yii::t('app', 'Admin'),     'class' => 'label label-danger'],
    1  => ['text' => Yii::t('app', 'Broker'),    'class' => 'label label-warning'],
    20 => ['text' => Yii::t('app', 'Customer'),  'class' => 'label label-success'],
];
?>
<div class="table-layout">
    <div class="tray tray-center">

        <div class="row">
            <div id="user-form_cont" class="col-lg-2 col-sm-3">
                <?= Html::a('<span class="fa fa-plus pr5"></span> ' . Yii::t('app', 'Create User'),
                    ['/user/create'], ['class' => 'btn btn-system mb15']) ?>
            </div>
        </div>

        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id'    => 'tbl_user',
                        ],
                        'layout'      => "{pager}\n{items}\n{pager}",
                        'rowOptions'  => ['role' => 'row', 'class' => 'odd'],
                        'summary'     => true,
                        'options'     => ['class' => 'br-r', 'id' => 'user'],
                        'columns' => [
                            ['attribute' => 'id', 'headerOptions' => ['style' => 'width:6%;']],
                            'username',
                            'email:email',
                            [
                                'attribute' => 'role',
                                'format'    => 'html',
                                'value'     => function ($model) use ($roleLabels) {
                                    $r = $roleLabels[$model->role] ?? ['text' => $model->role, 'class' => 'label label-default'];
                                    return '<span class="' . $r['class'] . '">' . $r['text'] . '</span>';
                                },
                            ],
                            'phone_number',
                            [
                                'attribute' => 'created',
                                'format'    => ['datetime', 'php:Y-m-d H:i'],
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}{update}{delete}',
                                'headerOptions' => ['style' => 'width:14%;'],
                                'contentOptions' => ['style' => 'white-space: normal;'],
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . Yii::t('app', 'View'),
                                            $url,
                                            [
                                                'title'      => Yii::t('app', 'View'),
                                                'aria-label' => 'View',
                                                'data-key'   => $model->id,
                                                'class'      => 'btn btn-primary btn-xs fs12 br2 ml5',
                                            ]);
                                    },
                                    'update' => function ($url, $model) {
                                        if ((int)Yii::$app->user->identity->role === 1) return '';
                                        return Html::a('<span class="glyphicon glyphicon-edit"></span> ' . Yii::t('app', 'Edit'),
                                            $url,
                                            [
                                                'title'      => Yii::t('app', 'Edit'),
                                                'aria-label' => 'Edit',
                                                'data-key'   => $model->id,
                                                'class'      => 'btn btn-info btn-xs fs12 br2 ml5',
                                            ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        if ((int)Yii::$app->user->identity->role === 1) return '';
                                        // Don't let the admin delete themselves.
                                        if ((int)$model->id === (int)Yii::$app->user->id) return '';
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('app', 'Delete'),
                                            $url,
                                            [
                                                'title'        => Yii::t('app', 'Delete'),
                                                'aria-label'   => Yii::t('app', 'Delete'),
                                                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this user?'),
                                                'data-method'  => 'post',
                                                'data-pjax'    => '0',
                                                'data-key'     => $model->id,
                                                'class'        => 'btn btn-danger btn-xs fs12 br2 ml5',
                                            ]);
                                    },
                                ],
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
