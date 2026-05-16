<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Currencies $model */
/** @var yii\widgets\ActiveForm $form */

$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
?>

<div class="currencies-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-md-4">
         <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-4">
         <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-4">
         <?= $form->field($model, 'symbol')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
