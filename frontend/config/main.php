<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'), 
        require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);
$rulesProduct = [];
$Rules = [];
 /* if (file_exists(__DIR__ . "/routes.json")) {
    $rules = file_get_contents(__DIR__ . '/routes.json');
} 
*/
if (file_exists(__DIR__ . "/product-routes.json")) {
    $rulesProduct = file_get_contents(__DIR__ . '/product-routes.json');
}
//$Rules = json_decode($rules, true);
$RulesProduct = json_decode($rulesProduct, true);
$Rules = array_merge_recursive($Rules, $RulesProduct);
/* echo "<pre>";
  var_dump($RulesProduct, 1);die; */
$urlSeg = explode('/', $_SERVER['REQUEST_URI']);

array_push($Rules, ['pattern' => '', 'route' => 'site/index']);
array_push($Rules, ['pattern' => 'search', 'route' => 'product/index']);
array_push($Rules, ['pattern' => 'change-language/<language:\w+>', 'route' => 'site/change-language']);
array_push($Rules, ['pattern' => 'more-details', 'route' => 'site/more-details']);
array_push($Rules, ['pattern' => 'signup', 'route' => 'site/signup']);
array_push($Rules, ['pattern' => 'search-by-vin', 'route' => 'product/search-by-vin']);
array_push($Rules, ['pattern' => 'news', 'route' => 'site/news-list']);
array_push($Rules, ['pattern' => 'news/<id:\d+>', 'route' => 'site/news']);
array_push($Rules, ['pattern' => 'news/<category:[a-zA-Z0-9\-]+>', 'route'   => 'site/news-by-category']);
array_push($Rules, ['pattern' => 'auction/<auctionId:\d+>', 'route' => 'product/auction']);
array_push($Rules, ['pattern' => '/about-us', 'route' => 'site/about-us']);
array_push($Rules, ['pattern' => '/contact', 'route' => 'site/contact']);
array_push($Rules, ['pattern' => 'login', 'route' => 'site/login']);
array_push($Rules, ['pattern' => 'logout', 'route' => 'site/logout']);
array_push($Rules, ['pattern' => 'register', 'route' => 'site/signup']);
array_push($Rules, ['pattern' => 'forgot-password', 'route' => 'site/request-password-reset']);
array_push($Rules, ['pattern' => '/filter-product', 'route' => 'product/filter-product']);
array_push($Rules, ['pattern' => 'product/favorite', 'route' => 'product/favorite']);
array_push($Rules, ['pattern' => 'product/order', 'route' => 'product/order']);


array_push($Rules, ['pattern' => '/power-of-pany', 'route' => 'site/donate']);
array_push($Rules, ['pattern' => '/power-of-pany', 'route' => 'site/power-of-pany']);
array_push($Rules, ['pattern' => '/auction', 'route' => 'site/auction']);
array_push($Rules, ['pattern' => '/best-offer', 'route' => 'site/best-offer']);

//$Rules = $Rules.['POST <tag>' => 'product/index'];
//echo "<pre>"; print_r($Rules);die;
//var_dump($Rules, 2); die;
$db = require(__DIR__ . '/../../common/config/db-local.php');
return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'language' => 'am',
    'sourceLanguage' => 'am',
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'site/index',
    'bootstrap' => ['visitorTracker'],
    'components' => [
        'visitorTracker' => ['class' => 'common\components\VisitorTracker'],
        'db' => $db,
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => '!SomeRandomString@',
            'enableCookieValidation' => true,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
           'class' => 'yii\web\DbSession',
           'writeCallback' => function ($session) {
               return [
                   'user_id' => Yii::$app->user->id,
                   'last_write' => time(),
               ];
           },
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                    [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['am', 'en'],
            'enableDefaultLanguageUrlCode' => false,
            'enableLanguagePersistence' => false,
            'rules' => $Rules,
        ]
    ],
    'params' => $params,
];
