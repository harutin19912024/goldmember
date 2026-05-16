<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $css = [
        "css/lib/bootstrap.min.css",
        "css/lib/bootstrap-icons-min.css",
        "css/global.css"
    ];
    public $baseUrl = '@web';
    public $js = [
        "vendor/bootstrap/js/bootstrap.bundle.min.js",
        "js/canvasbg.js",
        "js/carusel.js",
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

}
