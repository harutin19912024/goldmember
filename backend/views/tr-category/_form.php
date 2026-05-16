<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TrCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-category-form">

     <?php $form = ActiveForm::begin([
        'action' => ['/tr-category/update'],
        'id' => 'trcategoryUpdate',
    ]); ?>
    <label style="font-size: 25px; color:#0a0e1b">Category:<?= $model->category->name; ?></label>
    <div class="clearfix"></div>
    <div class="tab-content row">
        <div class="row">
            <div class="col-md-6">

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
            </div>
            <?= $form->field($model, 'language_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'category_id')->hiddenInput()->label(false) ?>

            <div class="col-md-6">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

