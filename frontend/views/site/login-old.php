<?php

/* @var $this \yii\web\View */
/* @var $model \common\models\LoginForm */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\authclient\widgets\AuthChoice;
use kartik\growl\Growl;
?>
<section id="content">
    <div class="container box sign">
        <div class="row">
            <?php
                $mess = Yii::$app->session->getFlash('notvalid');
                if (isset($mess) && $mess) {
                    echo Growl::widget([
                        'type' => Growl::TYPE_SUCCESS,
                        'title' => '',
                        'icon' => 'fa fa-check-square-o',
                        'body' => $mess,
                        'showSeparator' => true,
                        'delay' => 0,
                        'pluginOptions' => [
                            'showProgressbar' => false,
                            'placement' => [
                                'from' => 'top',
                                'align' => 'right',
                            ]
                        ]
                    ]);
                }
                ?>
            <?php
        $error = Yii::$app->session->getFlash('error');

        if (isset($error) && $error) {
            echo Growl::widget([
                'type' => Growl::TYPE_DANGER,
                'title' => '',
                'icon' => 'fa fa-exclamation-triangle',
                'body' => $error,
                'showSeparator' => true,
                'delay' => 1000,
                'pluginOptions' => [
                    'showProgressbar' => false,
                    'placement' => [
                        'from' => 'top',
                        'align' => 'right',
                    ]
                ]
            ]);
        }
        ?>
            <h2>Sign in or Sign up Odenschew.com</h2>
            <div class="col-md-6">
                <div class="row">
                    <div class="sign-wrap">
                        <h4 class="text-center">Sign In</h4>
                        <?php $form = ActiveForm::begin(['action'=>'/'.Yii::$app->language.'/site/login','options' => ['class'=>'form-signin']]) ?>
                        
                        <div class="form-group">
                            <i class="material-icons">mail</i>
                            <label for="inputEmail" class="sr-only">Email</label>
                            <?= $form->field($model, 'email',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(["placeholder" => "Email",'type'=>'email','id'=>'inputEmail', "class" => "form-control",'required'=>true])
                                ->label(false) ?>
                        </div>

                        <div class="form-group">
                            <i class="material-icons">lock</i>
                            <label for="inputPassword" class="sr-only">Password</label>
                            <?= $form->field($model, 'password',
                                ['template' => '{input}{label}{error}'])
                                ->passwordInput(["placeholder" => "Password", "class" => "form-control",'id'=>'inputPassword','required'=>true])
                                ->label(false) ?>
                        </div>

                        <div class="checkbox">
                            <label class="control control--checkbox">Remember me
                                <input type="checkbox" name="prd-brand"  />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <?php echo Html::submitButton(Yii::t('app','Sign in'),['class'=>'btn btn-lg btn-primary btn-block']) ?>
                        <?php ActiveForm::end() ?>
                        <div class="social-sign">
                            <h3>SIGN IN WITH SOCIAL MEDIA</h3>
                            <div class="inline">
                                <?php
                                $authAuthChoice = AuthChoice::begin([
                                    'baseAuthUrl' => ['site/auth'],
                                    'options' => [
                                        'class' => ['text-center']
                                    ]
                                ]);
                                ?>
                                <?php foreach ($authAuthChoice->getClients() as $key => $client): ?>

                                    <?php echo $authAuthChoice->clientLink($client, '<span class="icon"><i class="fa fa-' . $key . '" aria-hidden="true"></i>' . ucfirst($key) . '</span>')?>
                                <?php endforeach; ?>
                                <div class="clear"></div>
                                <?php AuthChoice::end(); ?>
                            </div>
                        </div>
                        <div class="forget">
                            <?php echo Html::a('Forgot password?', ['/site/request-password-reset']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="sign-wrap">
                        <h4 class="text-center">Sign Up</h4>
                        <?php $formSignup = ActiveForm::begin(['action'=>'/'.Yii::$app->language.'/site/signup','options' => ['class'=>'form-signin']]) ?>
                        <input type="hidden"  value="<?php echo $modelSignup->verifyToken?>" name="verifyToken" />
                        <div class="form-group">
                            <i class="material-icons">person</i>
                            <label for="inputFName" class="sr-only">First Name</label>
                            <?= $formSignup->field($modelSignup, 'name',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(["placeholder" => "First Name",'id'=>'inputFName', "class" => "form-control",'required'=>true])
                                ->label(false) ?>
                        </div>

                        <div class="form-group">
                            <i class="material-icons">person</i>
                            <label for="inputLName" class="sr-only">Last Name</label>
                            <?= $formSignup->field($modelSignup, 'surname',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(["placeholder" => "Last Name",'id'=>'inputLName', "class" => "form-control",'required'=>true])
                                ->label(false) ?>
                            </div>

                        <div class="form-group">
                            <i class="material-icons">mail</i>
                            <label for="inputEmail" class="sr-only">Email</label>
                            <?= $formSignup->field($modelSignup, 'email',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(["placeholder" => "Enter email",'type'=>'email','id'=>'inputEmail', "class" => "form-control"])
                                ->label(false) ?>
                            </div>

                        <div class="form-group">
                            <i class="material-icons">lock</i>
                            <label for="inputPassword" class="sr-only">Password</label>
                            <?= $formSignup->field($modelSignup, 'password',
                                ['template' => '{input}{label}{error}'])
                                ->passwordInput(["placeholder" => "Password", "class" => "form-control ex-input", 'required'=>true])
                                ->label(false) ?>
                            </div>
                        <div class="form-group">
                            <i class="material-icons">lock</i>
                            <label for="confirmPassword" class="sr-only">Confirm Password</label>
                            <?= $formSignup->field($modelSignup, 'confirm_password',
                                ['template' => '{input}{label}{error}'])
                                ->passwordInput(["placeholder" => "Confirm Password", "class" => "form-control", 'required'=>true])
                                ->label(false) ?>
                        </div>
                        <div class="valid-box">
                            <div class="popover-content"></div>
                            <span>Password must be at least 6 character, contain one alpha character and one numeric character</span>
                        </div>

                        <div class="checkbox">
                            <label class="control control--checkbox">Sign Up to our newsletter
                                <input type="checkbox" name="prd-brand"  />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

