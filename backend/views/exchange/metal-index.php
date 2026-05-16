<?php

use backend\models\BodyTypes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\BodyTypesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Exchanges');
$this->params['breadcrumbs'][] = $this->title;
?>
  <h1><?= Html::encode($this->title) ?></h1>
  
<div class="table-layout">
    <div class="tray tray-center filter">
        <!-- create new order panel -->
        <div id="category-form_cont">
            <?= Html::a('<span class="fa fa-plus pr5"></span>' . Yii::t('app', 'Create Todays Rate'), ['exchange/create'], [
                'class' => 'btn btn-system mb15'
            ]) ?>

        </div>
        <!-- recent orders table -->
        <div class="panel">
            <div class="panel-body pn">
                <div class="table-responsive">
    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table table-striped admin-form table-hover display dataTable no-footer',
            'id' => 'tbl_category'
        ],
        'filterRowOptions' => [
            'role' => "row"
        ],
        'rowOptions' => [
            'role' => "row",
            'class' => 'odd ui-state-default'
        ],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
'name',
             'buy',
             'sell',
             'original_buy',
             'original_sell',
['attribute' => 'created_date',
				'value' => function ($model) {
					return date('Y-m-d', strtotime($model->created_date));
				},
			],
            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>'.Yii::t('app', 'Delete'),
                                            $url,
                                            [
                                                'title' => Yii::t('app', 'Delete'),
                                                'aria-label' =>Yii::t('app', 'Delete'),
                                                'data-confirm' => 'Are you sure! You whant delete this item?',
                                                'data-method' => 'post',
                                                'data-pjax' => '0',
                                                'data-key' => $model->id,
                                                'class' => 'btn btn-danger btn-xs fs12 br2 ml5'
                                            ]);
                                    },
                                    'update' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>'.Yii::t('app', 'Edit'),
                                            $url,
                                            [
                                                'title' => Yii::t('app', 'Edit'),
                                                'aria-label' => Yii::t('app', 'Edit'),
                                                //'onclick' => 'openRateModal(); return false;',
                                                //'data-confirm' => 'Are you sure! You whant delete this item?',
                                                //'data-method' => 'post',
                                                'data-pjax' => '0',
                                                'data-key' => $model->id,
                                                'class' => 'btn btn-info btn-xs fs12 br2 ml5'
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

