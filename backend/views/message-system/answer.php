<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\Customer;

/* @var $this yii\web\View */
/* @var $model common\models\MessageSystem */
/* @var $form yii\widgets\ActiveForm */



$action = '/message-system/answer?id=' . $id;
$user = Customer::find()->where(['id' => $message['sender_user_id']])->asArray()->all();

?>

<div class="message-system-form">
    <?php $form = ActiveForm::begin([
        'action' => [$action],
        'id' => $formId,
    ]); ?>
    <div class="panel mb25">
        <div class="panel-heading">
            <span class="panel-title hidden-xs"><?php echo Yii::t('app','Answer to '.$user[0]['name'].' '.$user[0]['surname'])?></span>

        </div>
        <div class="panel-body p20 pb10">
            <div class="tab-content pn br-n admin-form">
                <div id="tab1_1" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section">
                                <?= $form->field($model, 'title',
                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                            {input}<label for="customer-name" class="field-icon"><i class="fa fa-user"></i></label></label>{error}</div>'
                                    ])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app','Message Title')])->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section">
                                <?= $form->field($model, 'content',
                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                            {input}<label for="customer-name" class="field-icon"><i class="fa fa-phone"></i></label></label>{error}</div>'
                                    ])->textarea(['rows' => 6, 'placeholder' =>  Yii::t('app','Message Body')])->label(false) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
