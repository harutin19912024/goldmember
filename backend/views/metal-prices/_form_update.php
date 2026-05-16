<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\MetalPrices $model */
/** @var yii\widgets\ActiveForm $form */

$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
?>

<div class="metal-prices-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-3">
        <?=
        $form->field($model, 'metal_id', ['template' => $template])->widget(Select2::className(), [
            'data' => $model->metals,
            'language' => Yii::$app->language,
            'options' => ['placeholder' => Yii::t('app', 'Select Metal'),
            'value' => (!empty(Yii::$app->request->getQueryParam('metal_id')) ) ? (int)Yii::$app->request->getQueryParam('metal_id') : 1],
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
        <?= $form->field($model, 'karat')->textInput(['disabled' => true])?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row">
        <div class="col-lg-6" style="margin-top: 25px;">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
