<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use common\models\Countries;
use common\models\States;

$countries = Countries::find()->asArray()->all();
$countryArray = [];
foreach($countries as $country){
    $countryArray[$country['name']] = $country['name'];
}
$states = States::find()->asArray()->all();

$this->title = 'Signup';
?>
<section class="select-block login-block">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                <div class="login-heading">
                    <h2>login here</h2>
                </div>
                <div class="login-inner">

                    <?php $form = ActiveForm::begin() ?>
                    <div class="login-inner">
                        <div class="section">
                            <?= $form->field($model, 'username',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Enter username", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($model, 'name',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Enter First name", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($model, 'surname',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Enter Last name", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($model, 'email',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Enter email", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($modelCustomer, 'company_name',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Company name", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?=
                            $form->field($modelCustomeraddress, 'country')->widget(Select2::classname(), [
                                'data' => $countryArray,
                                'language' => Yii::$app->language,
                                'options' => ['placeholder' => Yii::t('app', 'Select a Country') . '...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label(false);
                            ?>
                        </div>
                        <div class="section">
                            <?= $form->field($modelCustomeraddress, 'address',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Address", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($modelCustomeraddress, 'zip',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Zip code", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($modelCustomeraddress, 'city',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "City", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($modelCustomer, 'phone',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Phone", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($modelCustomer, 'mobile_phone',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(['autofocus' => true, "placeholder" => "Mobile Phone", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <!-- end section -->
                        <div class="section">
                            <?= $form->field($model, 'password',
                                ['template' => '{input}{label}{error}'])
                                ->passwordInput(["placeholder" => "Wanted password", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section">
                            <?= $form->field($model, 'confirm_password',
                                ['template' => '{input}{label}{error}'])
                                ->passwordInput(["placeholder" => "Confirm password", "class" => "log-int"])
                                ->label(false) ?>
                        </div>
                        <div class="section btn-login">
                            <?php echo Html::submitButton('Signup') ?>
                        </div>
                        <!-- end section -->
                        <!-- end .form-body section -->
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</section>
