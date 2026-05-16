<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\tagsinput\TagsinputWidget;
/* @var $this yii\web\View */
/* @var $model backend\models\Sitesettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sitesettings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text1')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'text2')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'text3')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'text4')->textarea(['maxlength' => true]) ?>

   

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
	<?php echo $this->registerJs("
            CKEDITOR.replace('sitesettings-text1'); 
            CKEDITOR.replace('sitesettings-text2'); 
            CKEDITOR.replace('sitesettings-text3'); 
            CKEDITOR.replace('sitesettings-text4'); 
"); ?>