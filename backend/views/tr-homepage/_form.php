<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TrBrand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-brand-form">

    <?php
    $form = ActiveForm::begin([
                'action' => ['/tr-homepage/update'],
                'id' => 'trhomepageupdate',
    ]);
    ?>

    <div class="clearfix"></div>
    <div class="form-group">
        <div class="col-md-12">

        <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(false) ?>
        </div>
		 <div class="col-md-12">

        <?= $form->field($model, 'description')->textarea(['maxlength' => true])->label(false) ?>
        </div>
        <?= $form->field($model, 'language_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'homepage_id')->hiddenInput()->label(false) ?>

        <div class="col-md-6">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'type' => 'button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
