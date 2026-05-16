<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerAddress */
/* @var $form ActiveForm */
?>
<?php foreach ($data as $key => $value): ?>
    <?php if ($value['default_address'] == 1) {
        $legend = 'Default Address';
        $cont_class = 'b_0';
        $cont_id = 'default-address';
        $input_name = 'default';
        $key ='';
    } else {
        $legend = 'Address' . $key;
        $cont_class = 'b_' . $key;
        $cont_id = '';
        $input_name = 'address' . $key . '';
    }
    foreach($countries as $country){
        $countryArray[$country['name']] = $country['name'];
    }
    foreach($states as $state){
        $statesArray[$state['name']] = $state['name'];
    }
    ?>
    <div class="<?= $cont_class?> br_" id="<?= $cont_id ?>">
        <div class="section-divider mb40">
            <span><?= $legend ?></span>
        </div>
        <div class="row">
            <div class="col-md-8  center-block">
                <div class="section">
                    <div class="col-md-6">
                        <div class="form-group field-customeraddress-country">
<!--                            --><?//=
//                            $form->field($customerAddress_model, 'country',
//                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
//                                                {input}</label>{error}</div>'
//                                ]
//                            )->widget(Select2::classname(), [
//
//                                'data' => $countryArray,
//                                'language' => Yii::$app->language,
//                                'options' => ['placeholder' => Yii::t('app', 'Select a Country') . '...', 'name'=>'CustomerAddress['.$input_name.'][country]'],
//                                'pluginOptions' => [
//                                    'allowClear' => true
//                                ],
//                            ])->label(false);
//                            ?>
                            <input type="text" id="customeraddress-country<?=$key?>" class="form-control"
                                   name="" placeholder="Country" value="<?= $value['country'] ?>">
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group field-customeraddress-country">
<!--                            --><?//=
//                            $form->field($customerAddress_model, 'state',
//                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
//                                                {input}</label>{error}</div>'
//                                ]
//                            )->widget(Select2::classname(), [
//
//                                'data' => $statesArray,
//                                'language' => Yii::$app->language,
//                                'options' => ['placeholder' => Yii::t('app', 'Select a Country') . '...', 'name'=>'CustomerAddress['.$input_name.'][state]'],
//                                'pluginOptions' => [
//                                    'allowClear' => true
//                                ],
//                            ])->label(false);
//                            ?>

                            <input type="text" id="customeraddress-state<?=$key?>" class="form-control"
                                   name="CustomerAddress[<?= $input_name ?>][state]" placeholder="State" value="<?= $value['state'] ?>">
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 center-block">
                <div class="section">
                    <div class="col-md-4">
                        <div class="form-group field-customeraddress-country">
                            <input type="text" id="customeraddress-sity<?=$key?>" class="form-control"
                                   name="CustomerAddress[<?= $input_name ?>][city]" placeholder="City" value="<?= $value['city'] ?>">
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group field-customeraddress-country">
                            <input type="text" id="customeraddress-address<?=$key?>" class="form-control"
                                   name="CustomerAddress[<?= $input_name ?>][address]" placeholder="Address" value="<?= $value['address'] ?>">
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

