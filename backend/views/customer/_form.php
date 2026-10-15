<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $customer_model backend\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$formId = $customer_model->isNewRecord ? 'customer-create-form' : 'customer-update-form';
$action = $customer_model->isNewRecord ? ['/customer/create'] : ['/customer/update', 'id' => $customer_model->id];
?>

<?php $form = ActiveForm::begin([
    'action' => $action,
    'id' => $formId,
]); ?>
    <div class="panel mb25">
        <div class="panel-heading">
            <span class="panel-title hidden-xs"><?php echo Yii::t('app','Add New Customer')?></span>
            <ul class="nav panel-tabs-border panel-tabs">
                <li class="active">
                    <a href="#tab1_1" data-toggle="tab"><?php echo Yii::t('app','Customer')?></a>
                </li>
            </ul>
        </div>
        <div class="panel-body p20 pb10">
            <div class="tab-content pn br-n admin-form">
                <div id="tab1_1" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="section">
                                <?= $form->field($customer_model, 'name',
                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                            {input}<label for="customer-name" class="field-icon"><i class="fa fa-user"></i></label></label>{error}</div>'
                                    ])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app','First Name')])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="section">
                                <?= $form->field($customer_model, 'surname',
                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                            {input}<label for="customer-name" class="field-icon"><i class="fa fa-user"></i></label></label>{error}</div>'
                                    ])
                                    ->textInput(['maxlength' => true, 'placeholder' =>  Yii::t('app','Last Name')])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="section">
                                <?= $form->field($customer_model, 'email',
                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                                {input}<label for="customer-name" class="field-icon"><i class="fa fa-envelope"></i></label></label>{error}</div>'
                                    ])->textInput(['maxlength' => true, 'placeholder' => Yii::t('app','Email')])->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="section">
                                <?= $form->field($customer_model, 'phone',
                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                            {input}<label for="customer-name" class="field-icon"><i class="fa fa-phone"></i></label></label>{error}</div>'
                                    ])->textInput(['maxlength' => true, 'placeholder' => Yii::t('app','Phone')])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="section" style="margin: 16px; float: none;">
                                <?= $form->field($customer_model, 'status')->widget(Select2::className(), [
                                    'data' => [Yii::t('app','Pasive'), Yii::t('app','Active')],
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app','Select Status ...')],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => false
                                    ],
                                ])->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                </div>
                <div class="form-group col-md-12">
                    <?= Html::submitButton($customer_model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $customer_model->isNewRecord ? 'btn btn-sm btn-primary pull-right ' : 'btn btn-sm btn-success pull-right ']) ?>
                </div>
            </div>
        </div>

        <?php // $form->field($customer_model, 'last_ip')->textInput(['maxlength' => true]) ?>

    </div>
<?php ActiveForm::end() ?>

