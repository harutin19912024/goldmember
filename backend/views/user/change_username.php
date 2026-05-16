<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\user */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $this->title = Yii::t('app', 'Change_Login');?>
<div class="user-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?=$form->field($user, 'username')->textInput() ?>

            <?=$form->field($user, 'currentPassword')->passwordInput() ?>
        </div>
    </div>

    
    <div class="form-group">
        <?= Html::submitButton( Yii::t('app','Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>