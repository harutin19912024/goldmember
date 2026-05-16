<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1><?= Html::encode($this->title) ?></h1>

</div>

<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-hover display dataTable no-footer',
            'id' => 'message'
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

            ['attribute' => 'language',
                'filterInputOptions' => [
                    'class' => 'form-control',
                ],
            ],
            ['attribute' => 'translation',
                'filterInputOptions' => [
                    'class' => 'form-control',
                ],
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open" style="color: #666666"></span>',
                            $url,
                            [
                                'title' => 'Update',
                                'aria-label' => 'Update',
                                'data-pjax' => '0',
                                'class' => 'btn btn-info btn-xs fs12 br2 ml5'
                            ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash" style="color: #666666"></span>',
                            $url,
                            [
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
    ]); ?>
</div>
