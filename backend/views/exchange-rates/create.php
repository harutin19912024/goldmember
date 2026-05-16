<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\ExchangeRates $model */

$this->title = Yii::t('app', 'Create Exchange Rates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exchange Rates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exchange-rates-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'currencies' => $currencies,
    ]) ?>

</div>
