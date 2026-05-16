<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\MetalPrices $model */
/** @var yii\widgets\ActiveForm $form */

$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
$range = 0;
$priceRange = Yii::$app->request->getQueryParam('price-range');
if($priceRange) {
    $range = $priceRange;
}
?>

<div class="metal-prices-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'rate_status')->hiddenInput(['value' => $rateStatus])->label(false)?>
    <?= $form->field($model, 'rate_price')->hiddenInput(['value' => $ratePrice])->label(false)?>

    <div class="col-md-6">
        <?=
        $form->field($model, 'metal_id', ['template' => $template])->widget(Select2::className(), [
            'data' => $model->metals,
            'language' => Yii::$app->language,
            'options' => ['placeholder' => Yii::t('app', 'Select Metal'),
            'value' => (!empty(Yii::$app->request->getQueryParam('metal_id')) ) ? (int)Yii::$app->request->getQueryParam('metal_id') : 1],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => false,
            ],
            'pluginLoading' => false,
        ])->label(false)
        ?>
    </div>
    
    <div class="col-md-6">
        <?=
        $form->field($model, 'currency_id', ['template' => $template])->widget(Select2::className(), [
            'data' => $model->currencies,
            'language' => Yii::$app->language,
            'options' => ['placeholder' => Yii::t('app', 'Select Currency'), 
            'value' => (!empty(Yii::$app->request->getQueryParam('currency_id')) ) ? (int)Yii::$app->request->getQueryParam('currency_id') : 1],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => false,
            ],
            'pluginLoading' => false,
        ])->label(false)
        ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'original_buy_price')->textInput(['maxlength' => true, 'value' => $buy, 'class' => 'form-control prices'])->label(Yii::t('app', 'Original Buy Price')) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'original_sell_price')->textInput(['maxlength' => true, 'value' => $sell, 'class' => 'form-control prices'])->label(Yii::t('app', 'Original Sell Price')) ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2><?=Yii::t('app', 'Sell Prices')?></h2>
            <?php if(!empty($apiPrices['prices'])):?>
                <?php foreach ($apiPrices['prices'] as $key=>$price):?>
                    <?php 
                        $value = $price['sell'] - $range;
                    ?>
                    <div class="col-md-1">
                        <?= $form->field($model, 'karat[]')->hiddenInput(['value' => $key])->label(false)?>
                        <?= $form->field($model, 'sell_price[]')->textInput(['maxlength' => true, 'value' => $price['sell'], 'class' => 'form-control prices'])->label($key) ?>
                        <?= $form->field($model, 'original_sell_price[]')->hiddenInput(['maxlength' => true, 'value' => $price['sell'], 'class' => 'form-control'])->label($key) ?>
                    </div>
                <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <h2><?=Yii::t('app', 'Buy Prices')?></h2>
            <?php if(!empty($apiPrices['prices'])):?>
                <?php foreach ($apiPrices['prices'] as $key=>$price):?>
                    <?php 
                        $value = $price['buy'] - $range;
                    ?>
                    <div class="col-md-1">
                        <?= $form->field($model, 'buy_price[]')->textInput(['maxlength' => true, 'value' => $price['buy'], 'class' => 'form-control prices'])->label($key) ?>
                        <?= $form->field($model, 'original_buy_price[]')->hiddenInput(['maxlength' => true, 'value' => $price['buy'], 'class' => 'form-control'])->label($key) ?>
                    </div>
                <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <label>Your Price</label>
            <input type="number" step="0.1" name="priceRange" id="priceRange" class="form-control" value = "<?php echo !empty(Yii::$app->request->getQueryParam('priceRange')) ? Yii::$app->request->getQueryParam('priceRange') : '' ?>" >
        </div>
        <div class="col-lg-6" style="margin-top: 25px;">
            <button class="btn btn-info" id="calculate-prices">Calculate</button>
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("
    
function calculatePrices(event) {
    event.preventDefault(); // Prevent form submission

    var priceRange = parseFloat($('#priceRange').val());

    if (isNaN(priceRange)) {
        alert('Enter a valid number');
        return;
    }

    $('.prices').each(function() {
        let currentValue = parseFloat($(this).val());
        if (!isNaN(currentValue)) {
            let newValue = (currentValue + priceRange).toFixed(4); // Round to 4 decimal places
            newValue = Math.max(parseFloat(newValue), 0); // Prevent negative values
            $(this).val(newValue);
        }
    });
}

    
    function updateUrl() {
        var metalId = $('#".Html::getInputId($model, 'metal_id')."').val();
        var currencyId = $('#".Html::getInputId($model, 'currency_id')."').val();
        
        var baseUrl = window.location.origin + window.location.pathname;
        var newUrl = baseUrl + '?currency_id=' + encodeURIComponent(currencyId) + '&metal_id=' + encodeURIComponent(metalId);

        window.location.href = newUrl;
    }

    // Attach event listeners
    $('#".Html::getInputId($model, 'metal_id')."').on('change', updateUrl);
    $('#".Html::getInputId($model, 'currency_id')."').on('change', updateUrl);
    $('#calculate-prices').on('click', calculatePrices);
", \yii\web\View::POS_READY);
?>
