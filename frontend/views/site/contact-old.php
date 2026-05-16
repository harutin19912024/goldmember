<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->registerCssFile('@web/css/admin-forms.css');
$this->registerCssFile('@web/css/theme.css');
$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>

    <div class="row">
        <div class="admin-form mw500 center-block mt30">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <div class="panel mb25">
                <div class="panel-heading">
                    <div class="section pn mn row">
                        <div class="col-md-4 center-block">
                            Contactez Nous
                        </div>
                    </div>
                </div>
                <div class="panel-body p20 pb10">
                    <div class="tab-content pn br-n admin-form">
                        <div class="col-md-12">
                            <?= $form->field($model, 'subject',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-percent" class="field prepend-icon">
                                    {input}<label for="repairer-percent" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
                                ->textInput(['placeholder' => 'Subject'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'name',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-name" class="field prepend-icon">
                                    {input}<label for="repairer-name" class="field-icon"><i class="fa fa-user"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Name'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'surname',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-username" class="field prepend-icon">
                                    {input}<label for="repairer-surname" class="field-icon"><i class="fa fa-user"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Surname'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'email',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-email" class="field prepend-icon">
                                    {input}<label for="repairer-email" class="field-icon"><i class="fa fa-envelope"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Emal'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'phone',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-phone" class="field prepend-icon">
                                    {input}<label for="repairer-phone" class="field-icon"><i class="fa fa-phone"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Phone'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'country',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-country" class="field prepend-icon">
                                    {input}<label for="repairer-country" class="field-icon"><i class="fa fa-globe"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Country'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'city',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-city" class="field prepend-icon">
                                    {input}<label for="repairer-city" class="field-icon"><i class="fa fa-building-o"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'City'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'state',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-state" class="field prepend-icon">
                                    {input}<label for="repairer-state" class="field-icon"><i class="fa fa-globe"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'State'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'address',
                                ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-address" class="field prepend-icon">
                                    {input}<label for="repairer-address" class="field-icon"><i class="fa fa-map-marker"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Address'])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pull-right">
                                <?= Html::submitButton('Submit', ['class' => 'btn', 'name' => 'contact-button']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <?php ActiveForm::end(); ?>
    </div>
</div>

</div>
