<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>

<div class="table-responsive">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
            'id' => 'tbl_inbox'
        ],
        'filterRowOptions' => [
            'role' => "row",
        ],
        'rowOptions' => [
            'role' => "row",
            'class' => 'odd'
        ],
        'summary' => false,
        'columns' => [
            ['attribute' => 'title',

            ],

            // 'status',
            ['attribute' => 'status',
                'contentOptions' => function ($model) {
                    if ($model->status == 0) {
                        return ['class' => "text-danger"];
                    } elseif ($model->status == 1) {
                        return ['class' => "text-success"];
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
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}{answer}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span style="color: #666" class="glyphicon glyphicon-eye-open"></span>',
                            $url,
                            [
                                'title' => Yii::t('app', 'View'),
                                'aria-label' => Yii::t('app', 'View'),
                                'data-pjax' => '0',
                                'class' => 'btn btn-info btn-xs fs12 br2 ml5',
                            ]);
                    },
                    'answer'=>function($url, $model){
                        return Html::a('<span style="color: #666" class="glyphicon glyphicon-arrow-up"></span>',
                            $url,
                            [
                                'title' => Yii::t('app', 'Answer'),
                                'aria-label' => Yii::t('app', 'Answer'),
                                'data-pjax' => '0',
                                'class' => 'btn btn-info btn-xs fs12 br2 ml5',
                            ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-xs fs12 br2 ml5',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>


