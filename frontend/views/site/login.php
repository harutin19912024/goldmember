<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\growl\Growl;

/* @var $this yii\web\View */
/* @var $model common\models\LoginForm */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Login');

$notvalid = Yii::$app->session->getFlash('notvalid');
if ($notvalid) {
    echo Growl::widget([
        'type'          => Growl::TYPE_SUCCESS,
        'title'         => '',
        'icon'          => 'fa fa-check-square-o',
        'body'          => $notvalid,
        'showSeparator' => true,
        'delay'         => 0,
        'pluginOptions' => ['showProgressbar' => false, 'placement' => ['from' => 'top', 'align' => 'right']],
    ]);
}

$error = Yii::$app->session->getFlash('error');
if ($error) {
    echo Growl::widget([
        'type'          => Growl::TYPE_DANGER,
        'title'         => '',
        'icon'          => 'fa fa-exclamation-triangle',
        'body'          => $error,
        'showSeparator' => true,
        'delay'         => 1000,
        'pluginOptions' => ['showProgressbar' => false, 'placement' => ['from' => 'top', 'align' => 'right']],
    ]);
}

$warning = Yii::$app->session->getFlash('warning');
if ($warning) {
    echo Growl::widget([
        'type'          => Growl::TYPE_WARNING,
        'title'         => '',
        'icon'          => 'fa fa-exclamation-circle',
        'body'          => $warning,
        'showSeparator' => true,
        'delay'         => 1000,
        'pluginOptions' => ['showProgressbar' => false, 'placement' => ['from' => 'top', 'align' => 'right']],
    ]);
}
?>

<section class="login-page">

    <!-- ── Left decorative panel ── -->
    <div class="login-panel-left">
        <div class="login-brand">
            <img src="/images/icons/logo.svg" alt="Goldmember">
            <span class="login-brand-name">Goldmember</span>
        </div>

        <h1 class="login-hero-title">
            Your Gateway to<br>
            <span>Exclusive Gold</span><br>
            Auctions
        </h1>

        <p class="login-hero-sub">
            <?= Yii::t('app', 'Join thousands of members who trust Goldmember for buying, selling, and investing in precious metals.') ?>
        </p>

        <ul class="login-features">
            <li>
                <span class="login-feature-icon"><i class="bi bi-graph-up-arrow"></i></span>
                <?= Yii::t('app', 'Live gold & silver price tracking') ?>
            </li>
            <li>
                <span class="login-feature-icon"><i class="bi bi-hammer"></i></span>
                <?= Yii::t('app', 'Exclusive live auction access') ?>
            </li>
            <li>
                <span class="login-feature-icon"><i class="bi bi-shield-check"></i></span>
                <?= Yii::t('app', 'Trusted & transparent marketplace') ?>
            </li>
            <li>
                <span class="login-feature-icon"><i class="bi bi-newspaper"></i></span>
                <?= Yii::t('app', 'Market news & price analysis') ?>
            </li>
        </ul>
    </div>

    <!-- ── Right form panel ── -->
    <div class="login-panel-right">
        <div class="login-form-card">

            <h2 class="login-form-heading"><?= Yii::t('app', 'Welcome back') ?></h2>
            <p class="login-form-sub">
                <?= Yii::t('app', 'Enter your credentials to access your account.') ?>
            </p>

            <?php $form = ActiveForm::begin([
                'action'  => '/' . Yii::$app->language . '/site/login',
                'options' => ['class' => 'w-100'],
            ]) ?>

                <!-- Username -->
                <div class="login-field">
                    <label><?= Yii::t('app', 'Username') ?></label>
                    <div class="login-input-wrap">
                        <i class="bi bi-person field-icon"></i>
                        <?= $form->field($model, 'username', ['template' => '{input}{error}'])
                            ->textInput([
                                'placeholder' => Yii::t('app', 'Enter your username'),
                                'autocomplete' => 'username',
                                'class' => '',
                            ])
                            ->label(false) ?>
                    </div>
                </div>

                <!-- Password -->
                <div class="login-field">
                    <label><?= Yii::t('app', 'Password') ?></label>
                    <div class="login-input-wrap">
                        <i class="bi bi-lock field-icon"></i>
                        <?= $form->field($model, 'password', ['template' => '{input}{error}'])
                            ->passwordInput([
                                'placeholder' => Yii::t('app', 'Enter your password'),
                                'id'           => 'login-password-input',
                                'autocomplete' => 'current-password',
                                'class'        => '',
                            ])
                            ->label(false) ?>
                        <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Show/hide password">
                            <i class="bi bi-eye" id="toggle-pwd-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="login-options">
                    <a href="/<?= Yii::$app->language ?>/forgot-password" class="login-forgot">
                        <?= Yii::t('app', 'Forgot password?') ?>
                    </a>
                </div>

                <button type="submit" class="login-submit-btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i><?= Yii::t('app', 'Login') ?>
                </button>

            <?php ActiveForm::end() ?>

            <div class="login-divider"><span><?= Yii::t('app', 'New to Goldmember?') ?></span></div>

            <div class="login-register-row">
                <p><?= Yii::t('app', 'Create a free account and start exploring exclusive auctions.') ?></p>
                <a href="/<?= Yii::$app->language ?>/register" class="login-register-btn">
                    <?= Yii::t('app', 'Create an Account') ?>
                </a>
            </div>

        </div>
    </div>

</section>

<?php
$this->registerJs("
function togglePassword() {
    var inp  = document.getElementById('login-password-input');
    var icon = document.getElementById('toggle-pwd-icon');
    if (!inp) return;
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
", \yii\web\View::POS_END);
?>
