<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\MetalPrices $model */
/** @var yii\widgets\ActiveForm $form */

$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
?>

<div class="exchange-rates-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="col-md-3">
        <?=
        $form->field($model, 'currency_id', ['template' => $template])->widget(Select2::className(), [
            'data' => $model->currencies,
            'language' => Yii::$app->language,
            'options' => ['placeholder' => Yii::t('app', 'Select Currency'), 
            'value' => (!empty(Yii::$app->request->getQueryParam('currency_id')) ) ? (int)Yii::$app->request->getQueryParam('currency_id') : 1],
            'disabled' => true,
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => false,
            ],
            'pluginLoading' => false,
        ])
        ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'buy_rate')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'sell_rate')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'style' => 'margin-top: 25px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
