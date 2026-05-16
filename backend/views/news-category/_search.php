<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'category-search',
    ]); ?>
    <div class="section row">

        <?php //echo $form->field($model, 'id') ?>
        <div class="col-md-12">
            <?= $form->field($model, 'name',
                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-lat" class="field prepend-icon">
                    {input}<label for="repairer-lat" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>'])
                ->textInput(['maxlength' => true, 'placeholder' =>Yii::t('app','Name')])->label(false) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'short_description',
                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-lat" class="field prepend-icon">
               {input}<label for="repairer-lat" class="field-icon"><i class="fa fa-comment-o"></i></label></label>{error}</div>'])
                ->textInput(['maxlength' => true, 'placeholder' =>Yii::t('app','Short Description')])->label(false) ?>
        </div>

    </div>
    <hr class="short">
    <div class="section">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-default btn-sm ph25']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
