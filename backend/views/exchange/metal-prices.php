<?php

use backend\models\Exchange;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\ExchangeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Metal Exchanges');
$this->params['breadcrumbs'][] = $this->title;
$metals = $model::AVAILABLE_METALS;
$currency = $model::AVAILABLE_CURRENCIES;
$range = 0;
$priceRange = Yii::$app->request->getQueryParam('priceRange');
if($priceRange) {
    $range = $priceRange;
}

$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="container">
     <?php $form = ActiveForm::begin(['method' => 'POST', 'action' => '/exchange/save-prices', 'id' => 'exchangeForm']); ?>
    <div class="row">
        <div class="col-lg-4 col-sm-3">
            <label><?= Yii::t('app', 'Select Metal') ?></label>
            <?=
            $form->field($exchangeForm, 'metal', ['template' => $template])->widget(Select2::className(), [
                'data' => $metals,
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select Metal'), 'value' => !empty(Yii::$app->request->getQueryParam('metal')) ? (int)Yii::$app->request->getQueryParam('metal') : ''],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => false,
                ],
                'pluginLoading' => false,
            ])->label(false)
            ?>
        </div>
        <div class="col-lg-4 col-sm-3">
            <label><?= Yii::t('app', 'Select Currency') ?></label>
            <?=
            $form->field($exchangeForm, 'currency', ['template' => $template])->widget(Select2::className(), [
                'data' => $currency,
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select Currency'), 'value' => (!empty(Yii::$app->request->getQueryParam('currency')) ) ? (int)Yii::$app->request->getQueryParam('currency') : ''],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => false,
                ],
                'pluginLoading' => false,
            ])->label(false)
            ?>
        </div>
        <div class="col-lg-4 col-sm-3" style="padding-top: 20px;">
            <button class="btn btn-info" id="search-exchange"><?= Yii::t('app', 'Search') ?></button>
        </div>
    </div>

<div class="table-layout">
    <div class="tray tray-center filter">
    <div class="panel-body">
        <div class="row">
            <?php if(!empty($prices['prices'])):?>
            <?php foreach ($prices['prices'] as $key=>$price):?>
                <div class="col-lg-1 col-md-2">
                    <label><?=$key?>:</label>
                    <input type="text" class="form-control prices" name="ExchangeForm[data][<?=$key?>]" value="<?=$price - $range?>">
                </div>
            <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>
    </div>
</div>

<div class="row float-left">
    <div class="col-lg-6">
        <label>Your Price</label>
        <input type="number" min="0.1" step="0.1" name="priceRange" id="priceRange" class="form-control" value = "<?php echo !empty(Yii::$app->request->getQueryParam('priceRange')) ? Yii::$app->request->getQueryParam('priceRange') : '' ?>" >
    </div>
</div>
    
 <div class="form-group">
     <button class="btn btn-info" id="recalculate" onclick="recalculatePrices()"><?=Yii::t('app', 'Recalculate')?></button>
        <?= Html::submitButton(Yii::t('app', 'Save Changes'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<?php
$this->registerJs("
function recalculatePrices(event) {
    event.preventDefault(); // Prevent form submission
    
    var priceRange = $('#priceRange').val();
    
    if (isNaN(priceRange) || priceRange <= 0) {
        alert('Enter a valid positive number');
        return;
    }

    $('.prices').each(function() {
        let currentValue = parseFloat($(this).val());
        if (!isNaN(currentValue)) {
            let newValue = (currentValue - priceRange).toFixed(4); // Round to 4 decimal places
            $(this).val(Math.max(newValue, 0)); // Prevent negative values
        }
    });
}

$('#search-exchange').on('click', function(e) {
    e.preventDefault();
    
    var metal = $('#exchangeform-metal').val(); 
    var currency = $('#exchangeform-currency').val(); 
    var priceRange = $('#priceRange').val();
    
    var queryParams = [];
    if (metal) queryParams.push('metal=' + encodeURIComponent(metal));
    if (currency) queryParams.push('currency=' + encodeURIComponent(currency));
    if (priceRange) queryParams.push('priceRange=' + encodeURIComponent(priceRange));

    var queryString = queryParams.length ? '?' + queryParams.join('&') : '';
    window.location.href = window.location.pathname + queryString;
});

// Attach event listener for recalculate button
$('#recalculate').on('click', recalculatePrices);

", \yii\web\View::POS_END);


?>
