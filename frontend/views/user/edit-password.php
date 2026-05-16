<?php
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model frontend\models\EditPassword */
?>

<?php $form = ActiveForm::begin() ?>
<?= $form->field($model,'old_password')?>
<?= $form->field($model,'new_password')?>
<?= $form->field($model,'confirm_password')?>
<?php ActiveForm::end() ?>