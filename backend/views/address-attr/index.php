<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AddressAttrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Address Attrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-attr-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Address Attr', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'attr_name',
            'attr_value',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
