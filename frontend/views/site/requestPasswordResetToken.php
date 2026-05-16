<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->registerCssFile('@web/css/theme.css');
$this->registerCssFile('@web/css/admin-forms.css');

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="contact" class="contact section hero section accent-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2><?=Yii::t('app', 'Forgot Password')?></h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gx-lg-0 gy-4">

          <div class="col-lg-12" style="margin-bottom: 45px;">
              <?php $form = ActiveForm::begin([
                    'method' => 'post', // Specifies the HTTP method
                    'options' => [
                        'data-aos' => 'fade',
                        'data-aos-delay' => '100',
                        'class' => 'php-email-form', // Adds the class to the form
                    ],
              ]); ?>
                    <div class="panel-body p15">

                        <div class="alert alert-micro alert-border-left alert-info pastel alert-dismissable mn">
                            <i class="fa fa-info pr10"></i> Enter your
                            <b>Email</b> and instructions will be sent to you!
                        </div>

                    </div>
                    <div class="panel-footer bg-light p30">
                        <div class="row">
                            <div class="col-sm-12 pr30">
                                <div class="section">
                                    <?= $form->field($model, 'email',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => Yii::t('app', 'Email'), "class" => "form-control", 'required'=>'required'])
                                ->label(false) ?>
                                 
                                </div>
                                    <?= Html::submitButton('Reset', ['class' => 'btn btn-primary pull-right']) ?>
                                <?php ActiveForm::end(); ?>
                       
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->
