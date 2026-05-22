<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\Donate $model */
/** @var yii\widgets\ActiveForm $form */

if (!$model->isNewRecord) {
    $formId = 'donateUpdate';
    $action = '/donate/update?id=' . $model->id;
} else {
    $formId = 'donateCreate';
    $action = '/donate/create';
}
?>

<div class="donate-form admin-form">
    <?= Html::a(Yii::t('app', 'Back to list'), ['/donate/index'], ['class' => 'btn btn-primary mb15']) ?>

    <?php $form = ActiveForm::begin([
        'action' => [$action],
        'id' => $formId,
    ]); ?>

    <div class="panel sort-disable mb50" id="p2" data-panel-color="false"
         data-panel-fullscreen="false" data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title"><?= Yii::t('app', 'Donate Account') ?></span>
            <span style="float:left;" class="panel-controls">
                <a href="#" class="panel-control-loader"></a>
                <a href="#" style="margin-left:5px" class="panel-control-collapse"></a>
            </span>
        </div>

        <div class="panel-body" style="display: block;">
            <div class="tab-content pn br-n admin-form">
                <div class="section row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'bank_name',
                            ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-university" aria-hidden="true"></i></label></label>{error}</div>'])
                            ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Bank Name')])->label(false) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'bank_account',
                            ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></label></label>{error}</div>'])
                            ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Bank Account')])->label(false) ?>
                    </div>
                </div>
                <div class="section row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'description',
                            ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-comments-o" aria-hidden="true"></i></label></label>{error}</div>'])
                            ->textarea(['rows' => 6, 'placeholder' => Yii::t('app', 'Description')])->label(false) ?>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                        [
                            'class' => $model->isNewRecord ? 'btn btn-sm btn-primary pull-right ' : 'btn btn-sm btn-success pull-right',
                            'id' => $formId,
                            'type' => 'button'
                        ]) ?>
                    <?php if (!$model->isNewRecord) {
                        echo Html::a(Yii::t('app', 'Reset'), Url::to('/' . Yii::$app->language . '/donate/index', true), ['class' => 'btn btn-default btn-sm ph25 reste-button pull-right']);
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
