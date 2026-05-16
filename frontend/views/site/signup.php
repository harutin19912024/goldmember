<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\growl\Growl;

/* @var $this yii\web\View */
/* @var $model frontend\models\SignupForm */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Register');

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

$success = Yii::$app->session->getFlash('success');
if ($success) {
    echo Growl::widget([
        'type'          => Growl::TYPE_SUCCESS,
        'title'         => '',
        'icon'          => 'fa fa-check-square-o',
        'body'          => $success,
        'showSeparator' => true,
        'delay'         => 0,
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
            Join the World of<br>
            <span>Precious Metals</span><br>
            & Live Auctions
        </h1>

        <p class="login-hero-sub">
            <?= Yii::t('app', 'Create your free account in seconds and gain access to exclusive live gold and silver auctions.') ?>
        </p>

        <ul class="login-features">
            <li>
                <span class="login-feature-icon"><i class="bi bi-person-check"></i></span>
                <?= Yii::t('app', 'Free account, no hidden fees') ?>
            </li>
            <li>
                <span class="login-feature-icon"><i class="bi bi-camera-video"></i></span>
                <?= Yii::t('app', 'Watch & bid in live video auctions') ?>
            </li>
            <li>
                <span class="login-feature-icon"><i class="bi bi-graph-up-arrow"></i></span>
                <?= Yii::t('app', 'Real-time gold & silver prices') ?>
            </li>
            <li>
                <span class="login-feature-icon"><i class="bi bi-bell"></i></span>
                <?= Yii::t('app', 'Auction alerts & market news') ?>
            </li>
        </ul>

        <!-- Steps -->
        <div class="login-steps">
            <div class="login-step">
                <span class="login-step-num">1</span>
                <span><?= Yii::t('app', 'Create account') ?></span>
            </div>
            <div class="login-step-arrow"><i class="bi bi-arrow-right"></i></div>
            <div class="login-step">
                <span class="login-step-num">2</span>
                <span><?= Yii::t('app', 'Browse auctions') ?></span>
            </div>
            <div class="login-step-arrow"><i class="bi bi-arrow-right"></i></div>
            <div class="login-step">
                <span class="login-step-num">3</span>
                <span><?= Yii::t('app', 'Bid & win') ?></span>
            </div>
        </div>
    </div>

    <!-- ── Right form panel ── -->
    <div class="login-panel-right login-panel-right--wide">
        <div class="login-form-card login-form-card--wide">

            <h2 class="login-form-heading"><?= Yii::t('app', 'Create your account') ?></h2>
            <p class="login-form-sub">
                <?= Yii::t('app', 'Fill in your details below to get started.') ?>
            </p>

            <?php $form = ActiveForm::begin([
                'action'  => '/' . Yii::$app->language . '/site/signup',
                'method'  => 'post',
                'options' => ['class' => 'w-100'],
            ]) ?>

                <div class="login-form-grid">

                    <!-- First Name -->
                    <div class="login-field">
                        <label><?= Yii::t('app', 'First Name') ?></label>
                        <div class="login-input-wrap">
                            <i class="bi bi-person field-icon"></i>
                            <?= $form->field($model, 'name', ['template' => '{input}{error}'])
                                ->textInput([
                                    'placeholder'  => Yii::t('app', 'e.g. John'),
                                    'autocomplete' => 'given-name',
                                    'class'        => '',
                                ])->label(false) ?>
                        </div>
                    </div>

                    <!-- Last Name -->
                    <div class="login-field">
                        <label><?= Yii::t('app', 'Last Name') ?></label>
                        <div class="login-input-wrap">
                            <i class="bi bi-person field-icon"></i>
                            <?= $form->field($model, 'surname', ['template' => '{input}{error}'])
                                ->textInput([
                                    'placeholder'  => Yii::t('app', 'e.g. Doe'),
                                    'autocomplete' => 'family-name',
                                    'class'        => '',
                                ])->label(false) ?>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="login-field">
                        <label><?= Yii::t('app', 'Username') ?></label>
                        <div class="login-input-wrap">
                            <i class="bi bi-at field-icon"></i>
                            <?= $form->field($model, 'username', ['template' => '{input}{error}'])
                                ->textInput([
                                    'placeholder'  => Yii::t('app', 'Choose a username'),
                                    'autocomplete' => 'username',
                                    'class'        => '',
                                ])->label(false) ?>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="login-field">
                        <label><?= Yii::t('app', 'Email') ?></label>
                        <div class="login-input-wrap">
                            <i class="bi bi-envelope field-icon"></i>
                            <?= $form->field($model, 'email', ['template' => '{input}{error}'])
                                ->input('email', [
                                    'placeholder'  => Yii::t('app', 'you@example.com'),
                                    'autocomplete' => 'email',
                                    'class'        => '',
                                ])->label(false) ?>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="login-field">
                        <label><?= Yii::t('app', 'Password') ?></label>
                        <div class="login-input-wrap">
                            <i class="bi bi-lock field-icon"></i>
                            <?= $form->field($model, 'password', ['template' => '{input}{error}'])
                                ->passwordInput([
                                    'placeholder'  => Yii::t('app', 'Min. 6 characters'),
                                    'id'           => 'signup-password',
                                    'autocomplete' => 'new-password',
                                    'class'        => '',
                                ])->label(false) ?>
                            <button type="button" class="toggle-password" onclick="togglePwd('signup-password','icon-pwd')" aria-label="Show/hide password">
                                <i class="bi bi-eye" id="icon-pwd"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="login-field">
                        <label><?= Yii::t('app', 'Confirm Password') ?></label>
                        <div class="login-input-wrap">
                            <i class="bi bi-lock-fill field-icon"></i>
                            <?= $form->field($model, 'confirm_password', ['template' => '{input}{error}'])
                                ->passwordInput([
                                    'placeholder'  => Yii::t('app', 'Repeat your password'),
                                    'id'           => 'signup-confirm-password',
                                    'autocomplete' => 'new-password',
                                    'class'        => '',
                                ])->label(false) ?>
                            <button type="button" class="toggle-password" onclick="togglePwd('signup-confirm-password','icon-cpwd')" aria-label="Show/hide password">
                                <i class="bi bi-eye" id="icon-cpwd"></i>
                            </button>
                        </div>
                    </div>

                </div><!-- /.login-form-grid -->

                <!-- Password strength hint -->
                <p class="login-hint">
                    <i class="bi bi-info-circle me-1"></i>
                    <?= Yii::t('app', 'Password must be at least 6 characters long.') ?>
                </p>

                <button type="submit" class="login-submit-btn">
                    <i class="bi bi-person-plus me-2"></i><?= Yii::t('app', 'Create Account') ?>
                </button>

            <?php ActiveForm::end() ?>

            <div class="login-divider"><span><?= Yii::t('app', 'Already have an account?') ?></span></div>

            <div class="login-register-row">
                <a href="/<?= Yii::$app->language ?>/login" class="login-register-btn">
                    <i class="bi bi-box-arrow-in-right me-1"></i><?= Yii::t('app', 'Log In') ?>
                </a>
            </div>

        </div>
    </div>

</section>

<?php
$this->registerJs("
function togglePwd(inputId, iconId) {
    var inp  = document.getElementById(inputId);
    var icon = document.getElementById(iconId);
    if (!inp) return;
    if (inp.type === 'password') {
        inp.type  = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        inp.type  = 'password';
        icon.className = 'bi bi-eye';
    }
}
", \yii\web\View::POS_END);
?>
