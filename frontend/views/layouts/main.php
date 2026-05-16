<?php
/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use kartik\growl\Growl;
use frontend\models\Service;
use frontend\models\Product;
use common\models\Language;
use common\models\Customer;
use yii\helpers\Url;
use frontend\models\Pages;
use backend\models\Sitesettings;
use backend\models\Homepage;
use backend\models\SocialNet;
use yii\authclient\widgets\AuthChoice;
use backend\models\News;

$settings = Sitesettings::find_One();
$homepage = Homepage::find_One();
$action = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;

$languages = Language::find()->asArray()->all();
$currentLang = Yii::$app->language; // e.g. 'am' or 'en'
$language = Language::find()->where(['short_code' => $currentLang])->asArray()->one();

if (empty($language) || !isset($language['short_code'])) {
    $this->registerJs("window.location.href = '/en';");
}

$socialLinks = SocialNet::find()->all();

$currentUrl = trim($_SERVER['REQUEST_URI']);

// Build a language-switch URL for each language that keeps the user on the current page.
// Current URL may start with /en/... or just /... (default AM).
// Strip existing language prefix, then prepend the target language prefix.
$currentPath = preg_replace('#^/(en|am)(/|$)#', '/', $currentUrl);  // strip any /en/ or /am/
$currentPath = '/' . ltrim($currentPath, '/');

// Both languages now have explicit URL prefix (enableDefaultLanguageUrlCode = true)
$langSwitchUrls = [];
foreach ($languages as $_lang) {
    $code = $_lang['short_code'];
    $langSwitchUrls[$code] = '/' . $code . ($currentPath === '/' ? '' : $currentPath);
}
//echo "<pre>"; print_r($currentUrl);die;
$com = strcmp($currentUrl, "/site/index");
$staticPages = Pages::findList(['type' => 0]);
$staticPagesFooter = Pages::findList(['position' => 1]);
$session = Yii::$app->session;

$newses = News::find()->where(['running_line' => 1])->limit(15)->all();
AppAsset::register($this);

// Compute real favorites count for logged-in users
$favCount = 0;
if (!Yii::$app->user->isGuest) {
    $favCount = (int) \common\models\Favorites::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->count();
}

// Inject globals for favorites.js
$this->registerJs(
    'window.GM_CSRF = ' . json_encode(Yii::$app->request->getCsrfToken()) . ';' .
    'window.GM_LOGGED_IN = ' . (Yii::$app->user->isGuest ? 'false' : 'true') . ';' .
    'window.GM_LANG = ' . json_encode(Yii::$app->language) . ';',
    \yii\web\View::POS_HEAD
);
$this->registerJsFile('/js/favorites.js', ['position' => \yii\web\View::POS_END, 'depends' => \frontend\assets\AppAsset::class]);

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?=$settings[0]['meta_tag']?>">
    <meta name="keywords" content="<?=$settings[0]['meta_description']?>">
    <meta name="author" content="John Doe">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?php if($this->title == ''):?><?=$settings[0]['site_title']?><?php else:?><?=$this->title?><?php endif;?></title>
    <link href="/img/favicon.ico" rel="icon">
    <link href="/img/apple-touch-icon.png" rel="apple-touch-icon">
    
    <!-- Fonts -->
    <link rel="preload" href="/fonts/roboto/Roboto-Light.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/roboto/Roboto-Regular.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/roboto/Roboto-Medium.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/roboto/Roboto-Bold.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/roboto/Roboto-Thin.woff2" as="font" type="font/woff2" crossorigin="anonymous">

    <link rel="preload" href="/fonts/inter/Inter-Thin.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/inter/Inter-Light.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/inter/Inter-Regular.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/inter/Inter-Medium.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/inter/Inter-SemiBold.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/inter/Inter-Bold.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <?php $this->head() ?>
</head>

<body class="index-page">
<div id="loader">
    <div class="spinner">
        <img src="/images/photo_5375441271239340276_y.jpg" alt="Loading" class="spinner-image">
    </div>
</div>

<!--<div id="loader">-->
<!--    <img src="/images/photo_5375441271239340276_y.jpg" alt="Loading" class="loader-image">-->
<!--</div>-->

<?php $this->beginBody() ?>
<style>
.running-line {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: black;
            color: white;
            padding: 10px 0;
            overflow: hidden;
            white-space: nowrap;
        }
        .running-line .marquee {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 10s linear infinite;
        }
        @keyframes marquee {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(-100%);
            }
        }
/*#loader {*/
/*    position: fixed;*/
/*    z-index: 9999;*/
/*    top: 0;*/
/*    left: 0;*/
/*    width: 100%;*/
/*    height: 100%;*/
/*    background-color: #fff;*/
/*    display: flex;*/
/*    justify-content: center;*/
/*    align-items: center;*/
/*}*/

/*.spinner {*/
/*    border: 8px solid #f3f3f3;*/
/*    border-top: 8px solid #3498db;*/
/*    border-radius: 50%;*/
/*    width: 80px;*/
/*    height: 80px;*/
/*    animation: spin 1s linear infinite;*/
/*}*/

/*@keyframes spin {*/
/*    0% { transform: rotate(0deg); }*/
/*    100% { transform: rotate(360deg); }*/
/*}*/

/*#loader {*/
/*    position: fixed;*/
/*    z-index: 9999;*/
/*    top: 0;*/
/*    left: 0;*/
/*    width: 100%;*/
/*    height: 100%;*/
/*    background-color: #000;
/*    display: flex;*/
/*    justify-content: center;*/
/*    align-items: center;*/
/*}*/

/*.loader-image {*/
/*    max-width: 200px;*/
/*    width: 40vw;*/
/*    height: auto;*/
/*    animation: fadeIn 1.2s ease-in-out;*/
/*    border-radius: 12px;*/
/*}*/

/*@keyframes fadeIn {*/
/*    0% { opacity: 0; transform: scale(0.9); }*/
/*    100% { opacity: 1; transform: scale(1); }*/
/*}*/

/*#loader {*/
/*    position: fixed;*/
/*    z-index: 9999;*/
/*    top: 0;*/
/*    left: 0;*/
/*    width: 100%;*/
/*    height: 100%;*/
/*    background-color: #000;*/
/*    display: flex;*/
/*    justify-content: center;*/
/*    align-items: center;*/
/*}*/

/*.spinner {*/
/*    width: 150px;*/
/*    height: 150px;*/
/*    border: 10px solid #444;*/
/*    border-top: 10px solid #00f0ff;*/
/*    border-radius: 50%;*/
/*    animation: spin 1s linear infinite;*/
/*    display: flex;*/
/*    justify-content: center;*/
/*    align-items: center;*/
/*    position: relative;*/
/*    background-color: #000;*/
/*}*/

/*.spinner-image {*/
/*    position: absolute;*/
/*    width: 80px;*/
/*    height: 80px;*/
/*    object-fit: contain;*/
/*    z-index: 1;*/
/*    border-radius: 8px;*/
/*}*/

/*@keyframes spin {*/
/*    0% { transform: rotate(0deg); }*/
/*    100% { transform: rotate(360deg); }*/
/*}*/


#loader {
    position: fixed;
    z-index: 9999;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.spinner {
    width: 150px;
    height: 150px;
    border: 10px solid #444;
    border-top: 10px solid #00f0ff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

.spinner-image {
    width: 80px;
    height: 80px;
    object-fit: contain;
    z-index: 1;
    border-radius: 8px;
    pointer-events: none;
    /* Image stays still */
    animation: none !important;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.language-dropdown .dropdown-item {
    position: relative;
    padding-left: 38px; /* space for flag */
    line-height: 24px;
}

/* Flag base */
.language-dropdown .dropdown-item::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 50%;
    width: 20px;
    height: 14px;
    transform: translateY(-50%);
    background-size: cover;
    background-position: center;
    border-radius: 2px;
}

.lang-am::before { background-image: url('https://flagcdn.com/w20/am.png'); }
.lang-en::before { background-image: url('https://flagcdn.com/w20/gb.png'); }
.lang-de::before { background-image: url('https://flagcdn.com/w20/de.png'); }
.lang-fr::before { background-image: url('https://flagcdn.com/w20/fr.png'); }
.lang-es::before { background-image: url('https://flagcdn.com/w20/es.png'); }
.lang-ru::before { background-image: url('https://flagcdn.com/w20/ru.png'); }


</style>


<!--  &lt;!&ndash; SECTION HEADER &ndash;&gt;-->
<header class="mb-auto header">
    <div class="bg-black py-1">
        <div class="row py-2 px-sm-0 mx-auto d-flex justify-content-between white-color flex-wrap container">
            <ul class="d-flex gap-3 col-md-9 col-6 fw-light contact-box px-0 mb-0 ps-2 ps-sm-0 ">
                <li class="me-md-4"><a href="mailto:<?=$settings[0]['site_email']?>"> <i class="bi bi-envelope-fill pe-1"></i><span><?=$settings[0]['site_email']?></span></a>
                </li>
                <?php foreach(explode(',', $settings[0]['site_phone']) as $phone):?>
                <li class="me-md-4"><a href="tel:<?=$phone?>"> <i class="bi bi-telephone-fill pe-1"></i> <span><?=$phone?></span></a></li>
                <?php endforeach;?>
            </ul>
            <ul class="px-0 d-flex col-md-3 col-6 justify-content-end justify-content-md-start mt-0 mb-0 justify-content-md-end mb-0">
                <?php foreach($socialLinks as $social):?>
                    <li class="social-button"><a href="<?=$social->link?>" class="social" target="_blank"> <i class="bi bi-<?=$social->social_type?>"></i> </a></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <div class="bg-secondary-color black-color">
        <div class="row mx-auto py-2 px-sm-0 d-flex justify-content-between container header-action-box">
            <div class="w-auto px-0">
                <a href="/<?=Yii::$app->language?>" class="logo">
                    <img src="/images/icons/logo.svg" alt="Logo" height="32" width="32">
                    <span>Goldmember</span>
                </a>
            </div>
            <ul class="d-flex col-4 gap-2 justify-content-end px-0 mb-0">
                <li class="mr-2">
                    <a href="/<?= Yii::$app->language ?>/user/profile#favorites-pane"
                       class="user-link bg-white-color d-flex"
                       title="<?= Yii::t('app', 'My Favourites') ?>">
                        <i class="bi bi-heart"></i>
                        <span class="count bg-primary-color white-color fav-count">
                            <?= $favCount ?>
                        </span>
                    </a>
                </li>
<!--                <li class="mr-2">-->
<!--                    <a href="/--><?php //=Yii::$app->language?><!--" class="user-link bg-white-color d-flex">-->
<!--                        <i class="bi bi-cart3"></i>-->
<!--                        <span class="count bg-primary-color white-color">3</span>-->
<!--                    </a>-->
<!--                </li>-->
                <li class="mr-2">
                    <a href="/<?=Yii::$app->language?>/<?php if(Yii::$app->user->isGuest):?>login<?php else:?>user/profile<?php endif;?>" class="user-link bg-white-color d-flex">
                        <i class="bi bi-person"></i>
                        <span><?php if(Yii::$app->user->isGuest):?><?=Yii::t('app','Login')?><?php else:?><?=Yii::t('app','Welcome')?>, <?= Yii::$app->user->identity->customer->name ?><?php endif;?></span>
                    </a>
                </li>
                <?php if(!Yii::$app->user->isGuest):?>
                <li>
                    <a href="/<?=Yii::$app->language?>/logout" class="user-link bg-white-color d-flex">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
                <?php endif;?>
            </ul>
        </div>
    </div>
    
    <div class="bg-white-color black-color">
            <div class="container ps-sm-0 ps-2 pe-0">
                <button class="navbar-toggler d-md-none d-flex collapsed ps-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>
                
            <div class="row d-flex flex-column flex-md-row pt-md-0 pt-6 pb-md-0 pb-6 justify-content-center  justify-content-md-between collapse navbar-collapse navbar-mobile"
                    id="navbarNav">
                 <button class="navbar-toggler d-md-none d-flex collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">

                        <i class="bi bi-x-lg"></i>
                    </button>
                <ul class="d-flex col-md-6 cold-12 py-0 ps-md-0 ps-3 flex-column flex-md-row">
                    <li class="nav-item<?php if ($currentUrl == ''): ?> active <?php endif ?>">
                        <a href="/<?=Yii::$app->language?>" class="nav-item-link gray-color">
                            <?= Yii::t('app', 'Home'); ?>
                        </a>
                    </li>
                    <li class="nav-item<?php if ($currentUrl == ''): ?> active <?php endif ?>">
                        <a href="/<?=Yii::$app->language?>/about-us" class="nav-item-link gray-color">
                            <?= Yii::t('app', 'About Us'); ?>
                        </a>
                    </li>
                    <li class="nav-item<?php if (strpos($currentUrl, 'news') === 0): ?> active <?php endif ?>">
                        <a href="/<?= Yii::$app->language ?>/news" class="nav-item-link gray-color">
                            <?= Yii::t('app', 'News'); ?>
                        </a>
                    </li>
                    <li class="nav-item<?php if ($currentUrl == ''): ?> active <?php endif ?>">
                        <a class="nav-item-link gray-color" href="#contact-us">
                            <?= Yii::t('app', 'Contact'); ?>
                        </a>
                    </li>
                </ul>
                <div class="dropdown w-auto mt-5 mt-md-0 ps-md-3 ps-0 me-md-0 me-auto">
                    <button class="btn primary-color dropdown-toggle pe-md-0" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?= strtoupper($currentLang) ?>
                    </button>
                    <ul class="dropdown-menu language-dropdown">
                        <?php foreach ($languages as $lang): ?>
                            <li>
                                <a class="dropdown-item lang-<?= $lang['short_code'] ?><?= $lang['short_code'] === $currentLang ? ' active' : '' ?>"
                                   href="<?= Html::encode($langSwitchUrls[$lang['short_code']]) ?>">
                                    <?= strtoupper($lang['short_code']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .marquee-container {
            display: flex;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            background-color: #f8f9fa; /* Adjust as needed */
            width: 100%;
        }
        
        .marquee-wrapper {
            display: flex;
            min-width: 200%; /* Ensures seamless transition */
            animation: marquee 15s linear infinite;
        }
        
        /* Pause animation on hover */
        .marquee-container:hover .marquee-wrapper {
            animation-play-state: paused;
        }
        
        .marquee-text {
            display: flex;
            gap: 50px; /* Space between news items */
        }
        
        /* Keyframes for Infinite Scrolling */
        @keyframes marquee {
            from {
                transform: translateX(0%);
            }
            to {
                transform: translateX(-50%);
            }
        }
    </style>

    <div class="marquee-container">
        <div class="marquee-wrapper">
            <div class="marquee-text">
                <?php foreach($newses as $news): ?>
                    <a href="<?= Yii::$app->language ?>/news/<?= $news->id ?>" target="_blank"><?= $news->title ?></a> |
                <?php endforeach; ?>
            </div>
            <!-- Duplicate to ensure a seamless loop -->
            <div class="marquee-text">
                <?php foreach($newses as $news): ?>
                    <a href="<?= Yii::$app->language ?>/news/<?= $news->id ?>" target="_blank"><?= $news->title ?></a> |
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="bg-white-color white-line"></div>
</header>
 

<main class="main">
    <?php echo $content ?>
    <?= $this->render('/site/chat'); ?>
</main>

<?= $this->render('footer', ['settings' => $settings, 'currentUrl' => $currentUrl, 'socialLinks' => $socialLinks]); ?>

<script>
    window.addEventListener("load", function () {
        const loader = document.getElementById("loader");
        if (loader) {
            loader.style.transition = "opacity 0.5s ease";
            loader.style.opacity = 0;
            setTimeout(() => loader.style.display = "none", 500);
        }
    });
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
