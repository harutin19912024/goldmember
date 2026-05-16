<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model backend\models\TrSlider */

?>
<div class="tr-slider-form">
    <?php $form = ActiveForm::begin(['action' => ['/tr-slider/update'], 'id' => 'trSliderUpdate']); ?>

    <label style="font-size:18px;color:#0a0e1b;">
        <?= Yii::t('app', 'Slider') ?>: <?= Html::encode($model->slider->title ?? '#' . $model->slider_id) ?>
    </label>
    <div class="clearfix mb10"></div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
        </div>
    </div>

    <?= $form->field($model, 'language_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'slider_id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
