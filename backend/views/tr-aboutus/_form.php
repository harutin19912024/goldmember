<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TrAttribute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-attribute-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $form = ActiveForm::begin([
                'action' => ['/tr-attribute/update'],
                'id' => 'trattributeupdate',
    ]);
    ?>

    <label style="font-size: 25px; color:#0a0e1b"> <?= $model->title; ?></label>
    <div class="clearfix"></div>
    <div class="form-group">
        <div class="col-md-12">

        <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(false) ?>
        </div>
        <div class="col-md-12">
                <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>
            </div>
        <div class="col-md-12">
                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
            </div>
        <?= $form->field($model, 'language_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'aboutus_id')->hiddenInput()->label(false) ?>

        <div class="col-md-6">
<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'type' => 'button']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>

</div>
