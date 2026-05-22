<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TrMaterial $model */
?>

<div class="tr-material-form">

    <?php $form = ActiveForm::begin([
        'action' => ['/tr-material/update'],
        'id' => 'trmaterialupdate',
    ]); ?>

    <div class="clearfix"></div>
    <div class="form-group">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(false) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'short_description')->textInput(['maxlength' => true])->label(false) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6])->label(false) ?>
        </div>

        <?= $form->field($model, 'language_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'material_id')->hiddenInput()->label(false) ?>

        <div class="col-md-6">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'type' => 'button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
