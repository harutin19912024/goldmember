<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MetalPrices $model */
/** @var int $metalId */
/** @var int $currencyId */
/** @var int $rateStatus */
/** @var float $ratePrice */
/** @var array $apiPrices */
/** @var float $original_buy_price */
/** @var float $original_sell_price */
/** @var string|null $apiTimestamp */
/** @var array $existingByKarat */

$this->title = Yii::t('app', 'Set Metal Prices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Metal Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metal-prices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'metalId' => $metalId,
        'currencyId' => $currencyId,
        'apiPrices' => $apiPrices,
        'rateStatus' => $rateStatus,
        'ratePrice' => $ratePrice,
        'original_buy_price' => $original_buy_price,
        'original_sell_price' => $original_sell_price,
        'apiTimestamp' => $apiTimestamp,
        'existingByKarat' => $existingByKarat,
    ]) ?>

</div>
