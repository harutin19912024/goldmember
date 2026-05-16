<?php
/* @var $this \yii\web\View */
/* @var $model \common\models\LoginForm */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="keywords" content="HTML5 Bootstrap 3 Admin Template UI Theme"/>
        <meta name="description" content="AdminDesigns - A Responsive HTML5 Admin UI Framework">
        <meta name="author" content="AdminDesigns">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link rel="shortcut icon" href="img/fave.png">

        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    </head>
    <body class="external-page sb-l-c sb-r-c">
        <?php $this->beginBody() ?>
        <!-- Start: Main -->
        <div id="main" class="animated fadeIn">

            <!-- Start: Content-Wrapper -->
            <section id="content_wrapper">

                <!-- begin canvas animation bg -->
                <div id="canvas-wrapper">
                    <canvas id="demo-canvas"></canvas>
                </div>

                <!-- Begin: Content -->
                <section id="content">

                    <div class="admin-form theme-info" id="login1">

                        <div class="row mb15 table-layout">

                            <div class="col-xs-6 va-m pln">
                                <a href="javascript:void(0);" title="Return to Dashboard">
                                    <?= Html::img("@web/images/goldmember.jpg", ["title" => "Goldmember", 
                                    "class" => "img-responsive", 
                                    "style" => "width: 200px;margin-left: 110px;"]) ?>
                                </a>
                            </div>
                        </div>

                        <div class="panel panel-info mt10 br-n">
                            <!-- end .form-header section -->
                            <?php $form = ActiveForm::begin(["id" => "contact"]) ?>
                            <div class="panel-body bg-light p30">
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-6 pr30">
                                        <div class="section">
                                            <label for="username" class="field-label text-muted fs18 mb10">Username</label>
                                            <label for="username" class="field prepend-icon">
                                                <?=
                                                        $form->field($model, 'username', ['template' => '{input}{error}'])
                                                        ->textInput(['autofocus' => true, "placeholder" => "Enter username", "class" => "gui-input"])
                                                ?>
                                                <!--                                            <input type="text" name="LoginForm[username]" id="username"-->
                                                <!--                                                   class="gui-input" placeholder="">-->
                                                <label for="username" class="field-icon">
                                                    <i class="fa fa-user"></i>
                                                </label>
                                            </label>
                                        </div>
                                        <div class="section">
                                            <label for="username" class="field-label text-muted fs18 mb10">Password</label>
                                            <label for="password" class="field prepend-icon">
                                                <?=
                                                        $form->field($model, 'password', ['template' => '{input}{error}'])
                                                        ->passwordInput(["class" => "gui-input", "placeholder" => "Enter password"])
                                                ?>
                                                <label for="password" class="field-icon">
                                                    <i class="fa fa-lock"></i>
                                                </label>
                                            </label>
                                        </div>
                                        <!-- end section -->

                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                            </div>
                            <!-- end .form-body section -->
                            <div class="panel-footer clearfix p10 ph15">
                                <button type="submit" name="login-button" class="button btn-primary mr10 pull-left">Sign In</button>

                            </div>
                            <!-- end .form-footer section -->
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>

                </section>
                <!-- End: Content -->

            </section>
            <!-- End: Content-Wrapper -->

        </div>
        <?php $this->endBody() ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {

                "use strict";

                // Init Theme Core      
                Core.init();

                // Init Demo JS
                Demo.init();

                // Init CanvasBG and pass target starting location
                CanvasBG.init({
                    Loc: {
                        x: window.innerWidth / 2,
                        y: window.innerHeight / 3.3
                    },
                });

            });
        </script>
    </body>
</html>
<?php $this->endPage() ?>
    