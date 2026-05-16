<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model backend\models\TrPartners */

?>
<div class="tr-partners-form">
    <?php $form = ActiveForm::begin(['action' => ['/tr-partners/update'], 'id' => 'trPartnersUpdate']); ?>

    <label style="font-size:18px;color:#0a0e1b;">
        <?= Yii::t('app', 'Partner') ?>: <?= Html::encode($model->partners->title ?? '#' . $model->partners_id) ?>
    </label>
    <div class="clearfix mb10"></div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'short_description')->textarea(['rows' => 5]) ?>
        </div>
    </div>

    <?= $form->field($model, 'language_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'partners_id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
