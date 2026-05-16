<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id'=>'customersearch'
    ]); ?>

    <div class="section row">
        <div class="col-md-6">
            <?= $form->field($model, 'name',
                ['template' => '<label for="productsearch-price" class="field prepend-icon">{input}{label}{error}</label>'])
                ->textInput(['placeholder'=>'Name'])->label('<i class="fa fa-user"></i>', ['class' => 'field-icon']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'surname',
                ['template' => '<label for="productsearch-price" class="field prepend-icon">{input}{label}{error}</label>'])
                ->textInput(['placeholder'=>'Surname'])->label('<i class="fa fa-user"></i>', ['class' => 'field-icon']) ?>
        </div>
    </div>
    <div class="section row">
        <div class="col-md-6">
            <?= $form->field($model, 'email',
                ['template' => '<label for="productsearch-price" class="field prepend-icon">{input}{label}{error}</label>'])
                ->textInput(['placeholder'=>'Email'])->label('<i class="fa fa-envelope"></i>', ['class' => 'field-icon']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'phone',
                ['template' => '<label for="productsearch-price" class="field prepend-icon">{input}{label}{error}</label>'])
                ->textInput(['placeholder'=>'Phone'])->label('<i class="fa fa-phone"></i>', ['class' => 'field-icon']) ?>
        </div>
    </div>

    <div class="section row">
        <div class="col-md-12">
            <?= $form->field($model, 'status')->widget(Select2::className(), [
                'data' => ['Pasive', 'Active'],
                'language' => Yii::$app->language,
                'options' => ['placeholder' => 'Filter By Status'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => false
                ],
            ])->label(false) ?>
        </div>
    </div>
    <div class="section row">
        <div class="col-md-12">
    <?= $form->field($model, 'user_id')->widget(Select2::className(), [
        'data' => $model->getUsers(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => 'Filter By User'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => false
        ],
    ])->label(false) ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'last_ip') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'updated_date') ?>

    <hr class="short">
    <div class="section">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-default btn-sm ph25']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default btn-sm ph25']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
