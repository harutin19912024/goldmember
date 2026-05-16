<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\BodyTypes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="body-types-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?=
        $form->field($model, 'exchange_enum')->widget(Select2::className(), [
            'data' => ['usd' => 'USD', 'eur' => 'EUR', 'rub' =>'RUB', 'gold' => 'GOLD'],
            'language' => Yii::$app->language,
            'options' => ['placeholder' => Yii::t('app', 'Select Exchange'),'onchange' => 'addExchangeName()',], //'onchange'=>'getProductAttr(this.value,"'.Yii::$app->language.'")'
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => false,
            ],
            'pluginLoading' => false,
        ])->label(false)
        ?>

    <?= $form->field($model, 'buy')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sell')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'original_buy')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'original_sell')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->hiddenInput(['maxlength' => true])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
function addExchangeName(){
    $('#exchange-name').val($('#exchange-exhnage_enum option:selected').text())
}
</script>
