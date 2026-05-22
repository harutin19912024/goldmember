<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TrNewsCategory $model */

?>

<div class="tr-newscategory-form">

    <?php $form = ActiveForm::begin([
        'action' => ['/tr-news-category/update'],
        'id' => 'trnewscategoryupdate',
    ]); ?>

    <div class="clearfix"></div>
    <div class="form-group">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(false) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'short_description')->textInput(['maxlength' => true])->label(false) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6])->label(false) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'route_name')->textInput(['maxlength' => true])->label(false) ?>
        </div>

        <?= $form->field($model, 'language_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'category_id')->hiddenInput()->label(false) ?>

        <div class="col-md-6">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'type' => 'button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
