<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Customer;

/* @var $this yii\web\View */
/* @var $model backend\models\MessageSystemSearch */
/* @var $form yii\widgets\ActiveForm */

$users = Customer::find()->asArray()->all();
$usersArray = [];

    foreach($users as $value){
        $usersArray[$value['id']] = $value['name'];
    }
?>

<div class="message-system-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?= $form->field($model, 'title') ?>

    <?=
    $form->field($model, 'recipient_user_id',
        ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                                {input}</label>{error}</div>'
        ])->widget(Select2::classname(), [

        'data' => $usersArray,
        'language' => Yii::$app->language,
        'options' => ['placeholder' => Yii::t('app', 'Select a User') . '...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label(false);
    ?>
    <?= $form->field($model, 'send_at') ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
