<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $customer_model backend\models\Customer */
/* @var $customerAddress_model backend\models\CustomerAddress */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
if (!$customer_model->isNewRecord) {
    $formId = 'customerUpdate';
    $action = '/customer/update?id=' . $customer_model->id;
    $addresses = $customerAddress_model->getCustomerAddressByCustomerId($customer_model->id);
} else {
    $formId = 'customerCreate';
    $action = '/customer/create';
}

foreach($countries as $country){
    $countryArray[$country['name']] = $country['name'];
}
foreach($states as $state){
    $statesArray[$state['name']] = $state['name'];
}
?>

<?php $form = ActiveForm::begin([
    'action' => [$action],
    'id' => $formId,
]); ?>
    <div class="panel mb25">
        <div class="panel-heading">
            <span class="panel-title hidden-xs"><?php echo Yii::t('app','Add New Customer')?></span>
            <ul class="nav panel-tabs-border panel-tabs">
                <li class="active">
                    <a href="#tab1_1" data-toggle="tab"><?php echo Yii::t('app','Customer')?></a>
                </li>
                <li>
                    <a href="#tab1_2" data-toggle="tab"><?php echo Yii::t('app','Customer Address')?></a>
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
                <div id="tab1_2" class="tab-pane">
                    <div class="addr">
                            <div class="b_0 br_" id="default-address">
                                <div class="section-divider mb40" id="spy1">
                                    <span>Default Address</span>
                                </div>

                                <div class="section row">
                                    <div class="col-md-8  center-block">
                                        <div class="">
                                            <div class="col-md-6">
                                                <?=
                                                $form->field($customerAddress_model, 'country',
                                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                                {input}</label>{error}</div>'
                                                    ])->widget(Select2::classname(), [

                                                    'data' => $countryArray,
                                                    'language' => Yii::$app->language,
                                                    'options' => ['placeholder' => Yii::t('app', 'Select a Country') . '...', 'name'=>'CustomerAddress[default][country]'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ])->label(false);
                                                ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?=
                                                $form->field($customerAddress_model, 'state',
                                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                                {input}</label>{error}</div>'
                                                    ])->widget(Select2::classname(), [

                                                    'data' => $statesArray,
                                                    'language' => Yii::$app->language,
                                                    'options' => ['placeholder' => Yii::t('app', 'Select a State') . '...', 'name'=>'CustomerAddress[default][sate]'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ])->label(false);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section row">
                                    <div class="col-md-8 center-block">
                                        <div class="">
                                            <div class="col-md-4">
                                                <?= $form->field($customerAddress_model, 'city',
                                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                                {input}<label for="customer-name" class="field-icon"><i class="fa fa-building-o"></i></label></label>{error}</div>'
                                                    ])
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app','City'),
                                                        'name' => 'CustomerAddress[default][city]',
                                                        'id'=>'customeraddress-city0'
                                                    ])->label(false) ?>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $form->field($customerAddress_model, 'address',
                                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                                {input}<label for="customer-name" class="field-icon"><i class="fa fa-map-marker"></i></label></label>{error}</div>'
                                                    ])
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app','Address'),
                                                        'name' => 'CustomerAddress[default][address]',
                                                        'id'=>'customeraddress-address0'
                                                    ])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="section row">
                        <div class="col-md-8 center-block">
                            <div class="col-md-12" style="padding: 0;">
                                <div class="col-md-2">
                                    <button type="button" id="add-address" class="btn btn-sm btn-primary"><?php echo Yii::t('app','Add Address')?>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="remove-address" class="btn btn-sm btn-primary">
                                        <?php echo Yii::t('app','Remove Address')?>
                                    </button>
                                </div>
                            </div>
                        </div>
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

<?php
echo $this->registerJs("
jQuery(document).ready(function () {
$('#customer-phone').mask('99-99-9999-9999');
$('#customersearch-phone').mask('+33 9 99 99 99 99');
})
") ?>