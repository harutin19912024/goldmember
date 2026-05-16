<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MetalPrices $model */

$this->title = Yii::t('app', 'Create Metal Prices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Metal Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metal-prices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'apiPrices' => $apiPrices,
        'rateStatus' => $rateStatus,
        'ratePrice' => $ratePrice,
        'buy' => $original_buy_price,
        'sell' => $original_sell_price,
    ]) ?>

</div>
