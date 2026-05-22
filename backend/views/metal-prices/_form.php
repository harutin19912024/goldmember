<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

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

$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
?>

<div class="metal-prices-form admin-form">

    <?= Html::a(Yii::t('app', 'Back to list'), ['/metal-prices/index'], ['class' => 'btn btn-primary mb15']) ?>

    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $msg): ?>
        <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?>">
            <?= Html::encode($msg) ?>
        </div>
    <?php endforeach; ?>

    <div class="panel sort-disable mb50">
        <div class="panel-heading">
            <span class="panel-title"><?= Yii::t('app', $model->isNewRecord ? 'Set Metal Prices for Today' : 'Update Metal Price') ?></span>
        </div>
        <div class="panel-body" style="display:block;">

            <?php $form = ActiveForm::begin(['action' => ['create']]); ?>

            <?= $form->field($model, 'rate_status')->hiddenInput(['value' => $rateStatus])->label(false) ?>
            <?= $form->field($model, 'rate_price')->hiddenInput(['value' => $ratePrice])->label(false) ?>

            <div class="row">
                <div class="col-md-5">
                    <?= $form->field($model, 'metal_id', ['template' => $template])->widget(Select2::className(), [
                        'data' => $model->metals,
                        'language' => Yii::$app->language,
                        'options' => ['id' => 'mp-metal-id', 'placeholder' => Yii::t('app', 'Select Metal')],
                        'pluginOptions' => ['allowClear' => false, 'multiple' => false],
                        'pluginLoading' => false,
                    ])->label(Yii::t('app', 'Metal')) ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'currency_id', ['template' => $template])->widget(Select2::className(), [
                        'data' => $model->currencies,
                        'language' => Yii::$app->language,
                        'options' => ['id' => 'mp-currency-id', 'placeholder' => Yii::t('app', 'Select Currency')],
                        'pluginOptions' => ['allowClear' => false, 'multiple' => false],
                        'pluginLoading' => false,
                    ])->label(Yii::t('app', 'Currency')) ?>
                </div>
                <div class="col-md-2" style="margin-top:25px;">
                    <button type="button" id="fetch-latest-btn" class="btn btn-info" style="width:100%;">
                        <i class="fa fa-refresh"></i> <?= Yii::t('app', 'Fetch latest from API') ?>
                    </button>
                </div>
            </div>

            <div id="karat-grid-container">
                <?= $this->render('_karat-grid', [
                    'model' => $model,
                    'apiPrices' => $apiPrices,
                    'existingByKarat' => $existingByKarat,
                    'apiTimestamp' => $apiTimestamp,
                    'original_buy_price' => $original_buy_price,
                    'original_sell_price' => $original_sell_price,
                ]) ?>
            </div>

            <div class="row" style="margin-top:20px;">
                <div class="col-md-4">
                    <label><?= Yii::t('app', 'Apply uniform markup (added to every price)') ?></label>
                    <input type="number" step="0.01" id="priceRange" class="form-control" placeholder="e.g. 1.50">
                </div>
                <div class="col-md-2" style="margin-top:25px;">
                    <button type="button" class="btn btn-default" id="calculate-prices" style="width:100%;">
                        <?= Yii::t('app', 'Apply markup') ?>
                    </button>
                </div>
                <div class="col-md-6" style="margin-top:25px; text-align:right;">
                    <?= Html::submitButton('<i class="fa fa-save"></i> ' . Yii::t('app', 'Save prices'),
                        ['class' => 'btn btn-success btn-lg', 'id' => 'save-prices-btn']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$csrf = Yii::$app->request->csrfToken;
$csrfParam = Yii::$app->request->csrfParam;
$urlGet = \yii\helpers\Url::to(['/metal-prices/get-prices']);
$urlFetch = \yii\helpers\Url::to(['/metal-prices/fetch-latest']);
$this->registerJs(<<<JS
(function () {
    function applyMarkup() {
        var v = parseFloat(document.getElementById('priceRange').value);
        if (isNaN(v)) { alert('Enter a valid number'); return; }
        document.querySelectorAll('.prices-sell, .prices-buy').forEach(function (el) {
            var cur = parseFloat(el.value);
            if (!isNaN(cur)) {
                var n = Math.max(0, +(cur + v).toFixed(4));
                el.value = n;
            }
        });
    }

    function refreshGrid() {
        var metalId = document.getElementById('mp-metal-id').value;
        var currencyId = document.getElementById('mp-currency-id').value;
        if (!metalId || !currencyId) return;
        var c = document.getElementById('karat-grid-container');
        c.innerHTML = '<div class="text-center" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-2x"></i></div>';
        \$.get('$urlGet', {metal_id: metalId, currency_id: currencyId}, function (html) {
            c.innerHTML = html;
        }).fail(function () {
            c.innerHTML = '<div class="alert alert-danger">Failed to load karat grid.</div>';
        });
    }

    function fetchLatest() {
        var btn = document.getElementById('fetch-latest-btn');
        var orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Fetching...';
        \$.ajax({
            url: '$urlFetch',
            method: 'POST',
            data: {'$csrfParam': '$csrf'},
            dataType: 'json'
        }).done(function (res) {
            if (res.success) {
                var lines = ['Fetched at ' + res.timestamp + ':'];
                for (var m in res.prices) lines.push('  ' + m + ': $' + res.prices[m] + '/oz');
                alert(lines.join('\\n'));
                refreshGrid();
            } else {
                alert('Fetch failed: ' + (res.error || 'unknown'));
            }
        }).fail(function (xhr) {
            alert('Fetch failed: HTTP ' + xhr.status);
        }).always(function () {
            btn.disabled = false;
            btn.innerHTML = orig;
        });
    }

    \$(document).on('change', '#mp-metal-id, #mp-currency-id', refreshGrid);
    document.getElementById('calculate-prices').addEventListener('click', applyMarkup);
    document.getElementById('fetch-latest-btn').addEventListener('click', fetchLatest);
})();
JS
, \yii\web\View::POS_READY);
?>
