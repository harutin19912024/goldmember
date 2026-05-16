<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php Pjax::begin(['id' => 'attrPjaxtbl']) ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table table-striped table-hover display dataTable no-footer',
        'id' => 'tbl_attr'
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
        ['attribute' => 'name',
            'filterInputOptions' => [
                'class' => 'form-control',
                'placeholder' => 'Search'
            ],
        ],
        ['attribute' => 'category',
            'value' => function ($model) {
                if (isset($model->category_id)) {
                    return $model->category->name;
                } else {
                    return 'Global';
                }
            },
            'filterInputOptions' => [
                'class' => 'form-control',
                'placeholder' => 'Search'
            ],
        ],
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    $url = '/' . Yii::$app->language . '/attribute/delete?id=' . $model->attribute_id;
                    return Html::a('<span class="glyphicon glyphicon-trash" style="color: #666666"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'aria-label' => Yii::t('app', 'Delete'),
                                'data-confirm' => Yii::t('app', 'Are you sure! You whant delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                                'data-key' => $model->attribute_id,
                                'class' => 'btn btn-danger btn-xs fs12 br2 ml5'
                    ]);
                },
                    ]
                ],
            ],
]);?>
<?php Pjax::end() ?>