<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Cities;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Address */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$city = Cities::find()->asArray()->all();
$cities = [];
foreach ($city as $category) {
    $cities[$category['id']] = $category['name'];
}
?>
<div class="address-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?=
    $form->field($model, 'city_id')->widget(Select2::classname(), [
	  'data' => $cities,
	  'language' => Yii::$app->language,
	  'options' => [
		'placeholder' => Yii::t('app', 'Select Parent') . '...',
	  ],
	  'pluginOptions' => [
		'allowClear' => true,
		'multiple' => false,
	  ],
	  'pluginLoading' => false,
    ])->label(Yii::t('app', 'Choose City'));
    ?>

    <div class="form-group">
	  <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
