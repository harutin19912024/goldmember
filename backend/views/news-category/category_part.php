<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(['id' => 'categoryPjaxtbl']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-striped table-hover display dataTable no-footer',
            'id' => 'tbl_category'
        ],
        'filterRowOptions' => [
            'role' => "row"
        ],
        'rowOptions' => [
            'role' => "row",
            'class' => 'odd ui-state-default'
        ],
        'summary' => false,
        'options' => ['class' => 'br-r', 'id' => 'category'],
        'columns' => [
    //            ['class' => 'yii\grid\SerialColumn'],
    //            'id',
            ['attribute' => 'name',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search'
                ],
            ],
            ['attribute' => 'short_description',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search'
                ],
            ],
            ['attribute' => 'description',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search'
                ],
            ],
            // 'created_date',
            // 'updated_date',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{edit}{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash" style="color: #666666"></span>', $url, [
                                    'title' => 'Delete',
                                    'aria-label' => 'Delete',
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
    ]);?>
<?php Pjax::end() ?>