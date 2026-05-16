<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var $model frontend\models\CustomerAddress
 */
?>
<div class="col-md-12">
    <div class="col-md-12">
        <input type="text" class="input-inner" name="" id="searchText" placeholder="Edite Address"
               value="<?= $model->address . ', ' . $model->city . ', ' . $model->state . ', ' . $model->country ?>">
    </div>
</div>
<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'step-2-form frm-2 clearfix',
        'id' => 'save-address',

    ],
    'action' => '/customer/save-address'
]) ?>
<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
<div class="container-fluid">
    <div class="row pn">
        <?= $form->field($model, 'str_num')->hiddenInput([
            'id' => 'x_street_number',
        ])->label(false) ?>
        <?= $form->field($model, 'address')->hiddenInput([
            'id' => 'x_route',
        ])->label(false) ?>
        <?= $form->field($model, 'city')->hiddenInput([
            'id' => 'x_locality',
        ])->label(false) ?>
        <?= $form->field($model, 'state')->hiddenInput([
            'id' => 'x_administrative_area_level_1',
        ])->label(false) ?>
        <?= $form->field($model, 'country')->hiddenInput([
                'id' => 'x_country',
            ]
        )->label(false) ?>
        <?= $form->field($model, 'zip')->hiddenInput([
            'id' => 'x_postal_code',
        ])->label(false) ?>
    </div>
</div>
<div class="col-md-12">
    <div class="col-md-12" style="margin-bottom: 50px">
        <?php echo Html::submitButton("enregistrer l'adresse", ['class' => 'submit-btn']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>