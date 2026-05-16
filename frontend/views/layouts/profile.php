<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use kartik\growl\Growl;
use frontend\models\Category;
use frontend\models\Service;
use frontend\models\Product;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <?php
    $mess = Yii::$app->session->getFlash('success');
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
    <div class="wrapper">
        <header class="header header-2 navbar-fixed-top">
            <div class="container-fluid">
                <div class="logo">
                    <?php echo Html::a(Html::img("@web/img/logo.png"), ['/']) ?>
                </div>
                <div class="nav_right pull-right">
                    <div class="header-icon">
                        <ul class="list-unstyled">
                            <li class="dropdown drop-box">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <?php if (Yii::$app->user->identity->role == \common\models\User::CUSTOMER): ?>
                                        <?php $name = Yii::$app->user->identity->customer->name;
                                        $surname = Yii::$app->user->identity->customer->surname;
                                        echo ucfirst($name) . ' ' . ucfirst($surname);
                                        ?>
                                    <?php elseif (Yii::$app->user->identity->role == \common\models\User::REPAIRER): ?>
                                        <?php $name = Yii::$app->user->identity->repairer->name;
                                        $surname = Yii::$app->user->identity->repairer->surname;
                                        echo ucfirst($name) . ' ' . ucfirst($surname);
                                        ?>
                                    <?php endif; ?>
                                    <span><i class="fa fa-angle-down" aria-hidden="true"></i></span></a>
                                <ul class="dropdown-menu list-unstyled dm-menu">
                                    <li>
                                        <?php echo Html::a("Edite Password", ['user/edit-password']) ?>
                                    </li>
                                    <li>
                                        <?php echo Html::a("Profile Image", ['user/profile-image']) ?>
                                    </li>
                                    <li>
                                        <a href="#">Something else here</a>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="#">Separated link</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <aside class="sidebar">
            <div class="side-inner">
                <div class="user-profile">
                    <a href="#">
                        <?php echo Html::img("@web/img/user-img.jpg") ?>
                    </a>
                    <h4>
                        <?php if (Yii::$app->user->identity->role == \common\models\User::CUSTOMER): ?>
                            <?php $name = Yii::$app->user->identity->customer->name;
                            $surname = Yii::$app->user->identity->customer->surname;
                            echo ucfirst($name) . ' ' . ucfirst($surname);
                            ?>
                        <?php elseif (Yii::$app->user->identity->role == \common\models\User::REPAIRER): ?>
                            <?php $name = Yii::$app->user->identity->repairer->name;
                            $surname = Yii::$app->user->identity->repairer->surname;
                            echo ucfirst($name) . ' ' . ucfirst($surname);
                            ?>
                        <?php endif; ?>
                    </h4>
                </div>
                <div class="holt-box">
                    <div class="process-inner clearfix">
                        <div class="clearfix">
                            <h6 class="pull-left">Your Profile</h6>
                            <h6 class="pull-right">20%</h6>
                        </div>
                        <div class="progress process-line" style="border-radius:30px">
                            <div class="progress-bar progress-bar-danger " role="progressbar" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100" style="width:20%;">
                            </div>
                        </div>
                        <div class="profile-dtl">
                            <ul class="list-unstyled">
                                <li><span><i class="fa fa-check" aria-hidden="true"></i></span>Add Credit Card</li>
                                <li><span><i class="fa fa-check" aria-hidden="true"></i></span>Verify Mobile</li>
                                <li class="active"><span><i class="fa fa-check" aria-hidden="true"></i></span>Verify
                                    Email Address
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab_panels">
                <ul class="list-unstyled clearfix">
                    <li class="<?php echo (Yii::$app->controller->action->id == 'profile') ? 'active' : '' ?>">
                        <?php echo Html::a("My Profile", \yii\helpers\Url::to(['user/profile'])) ?>
                    </li>
                    <li class="<?php echo (Yii::$app->controller->action->id == 'history') ? 'active' : '' ?>">
                        <?php echo Html::a("My Reparis", \yii\helpers\Url::to(['user/history'])) ?>
                    </li>
                    <li class="<?php echo (Yii::$app->controller->action->id == 'paymant') ? 'active' : '' ?>">
                        <a href="#">payment</a>
                    </li>
                </ul>
            </div>
        </aside>
        <main class="main">
            <div class="container-fluid">
                <div id="container">
                    <?php echo $content ?>
                </div>
            </div>
        </main>
    </div>

    <?php $this->endBody() ?>
    <script>
        $(window).scroll(function () {
            if ($(this).scrollTop() > 1) {
                $('header').addClass("sticky");
            }
            else {
                $('header').removeClass("sticky");
            }
        });
    </script>
    <script>
        if (navigator.appVersion.indexOf("Chrome/") != -1) {
        }
    </script>
    </body>
    </html>
<?php $this->endPage() ?>