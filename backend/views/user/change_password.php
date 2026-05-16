<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use common\widgets\Alert;
/* @var $this yii\web\View */
/* @var $model common\models\user */    
/* @var $form yii\widgets\ActiveForm */
?>
<?php $this->title = Yii::t('app', 'Change Password');?>
<div class="user-form">

<?php $form = ActiveForm::begin([

    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
]); ?>

<div class="row">
    <div class="col-md-6">
        <?=$form->field($user, 'currentPassword')->passwordInput();?>
        <?= $form->field($user, 'newPassword')->passwordInput(); ?>
        <?php
        echo $form->field($user, 'newPasswordRepeat')->passwordInput();
        ?>
    </div>
</div>


<div class="form-group">
    <?= Html::submitButton( Yii::t('app','Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
</div>