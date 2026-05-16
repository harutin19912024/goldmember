<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Source Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Source Message'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="table-responsive">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-striped table-hover display dataTable no-footer',
                'id' => 'source-message'
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

                ['attribute' => 'category',
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'placeholder' => 'Search'
                    ],
                ],
                ['attribute' => 'message',
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'placeholder' => 'Search'
                    ],
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                                $link = "/message/update?id=" . $model->id;
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span> Edit', $link, [
                                            'title' => Yii::t('app', 'Update'),
                                            'aria-label' => Yii::t('app', 'Update'),
                                            //'data-confirm' =>Yii::t('app', 'Are you sure! You whant delete this item?'),
                                            //'data-method' =>'post',
                                            //'data-pjax' => '0',
                                            'data-key' => $model->id,
                                            'class' => 'btn btn-info btn-xs fs12 br2 ml5 pull-left'
                                ]);
                            },
                        'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> Delete',
                                            $url,
                                            [
                                                'title' => Yii::t('app', 'Delete'),
                                                'aria-label' => Yii::t('app', 'Delete'),
                                                'data-confirm' =>Yii::t('app', 'Are you sure! You whant delete this item?'),
                                                'data-method' =>'post',
                                                'data-pjax' => '0',
                                                'data-key' => $model->id,
                                                'class' => 'btn btn-danger btn-xs fs12 br2 ml5 pull-left'
                                            ]);
                                    },
                            ]
                        ],
                    ],
                ]);
                ?>
    </div>


</div>
