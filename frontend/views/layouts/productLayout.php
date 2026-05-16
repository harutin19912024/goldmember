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
use common\models\Language;
use yii\helpers\Url;


$languages = Language::find()->asArray()->all();

$action = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;

$session = Yii::$app->session;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<?php

$languege = Language::find()->where(['short_code' => Yii::$app->language])->asArray()->all();
$isDefaultLanguage = $languege[0]['is_default'];
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="description" content="">
    <meta name="author" content="BrainFors">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="https://cdn.socket.io/socket.io-1.3.5.js"></script>
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
<header role="banner" aria-label="Heading">
    <div class="header">
        <div class="container">
            <div class="row">
                <?=
                $this->render('top_menu', [
                    'controller'=>$controller,
                    'action'=>$action,
                    'languages'=>$languages
                ]);
                ?>
            </div>
        </div>
    </div>
    
    <div class="cart-overlay">
<!--        --><?//=
//        $this->render('top_basket', [
//
//        ]);
//        ?>
    </div> <!--end container -->
</header>
<div id="container">
    <?php echo $content ?>
</div>
<section id="subscribe">
    <?=
    $this->render('subscribe', [

    ]);
    ?>
</section>

<footer role="contentinfo" aria-label="Footer">
    <?=
    $this->render('footer', [

    ]);
    ?>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
