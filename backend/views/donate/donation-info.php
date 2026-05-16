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
<div class="donate-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'email',
            'phone',
            'amount',
            'message:ntext',
            ['class' => 'yii\grid\ActionColumn',
        'template' => '{edit}{delete}',
        'buttons' => [
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
