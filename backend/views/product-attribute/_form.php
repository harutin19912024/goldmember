<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductAttribute */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$template = '<div class="col-md-12">{label}<div class="col-md-8">{input}{error}</div></div>';
?>

<div class="product-attribute-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_id',
        ['template' => $template])->widget(Select2::className(), [
        'data' => $model->getProducts(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => 'Select Product ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' =>false,
        ],
    ])->label("Attribute", ['class' => 'col-md-1 control-label',]) ?>

    <?= $form->field($model, 'attribute_id',
        ['template' => $template])->widget(Select2::className(), [
        'data' => [],
        'language' => Yii::$app->language,
        'options' => ['placeholder' => 'Select Attribute ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' =>false,
        ],
    ])->label("Attribute", ['class' => 'col-md-1 control-label',]) ?>


    <?= $form->field($model, 'value', ['template' => $template])
        ->textInput()->label("Attribute Value", ['class' => 'col-md-1 control-label',]) ?>

    <?php //$form->field($model, 'created_date')->textInput() ?>

    <?php //$form->field($model, 'updated_date')->textInput() ?>

    <div class="clearfix"></div>
    <div class="form-group col-md-9">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-sm btn-primary pull-right ' : 'btn btn-sm btn-success pull-right ']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="clearfix"></div>
