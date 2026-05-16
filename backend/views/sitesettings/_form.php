<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\Sitesettings */
/* @var $form yii\widgets\ActiveForm */
 if (!$model->isNewRecord) {
	//$model->site_phone = json_decode($model->site_phone);
} 
?>

<div class="sitesettings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'meta_tag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site_email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?> 
	
    <?= $form->field($model, 'text1')->textInput(['maxlength' => true]) ?> 
    <?= $form->field($model, 'text2')->textInput(['maxlength' => true]) ?> 

    <?= $form->field($model, 'work_time')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'site_phone')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'maintenance_mode')->checkbox(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>