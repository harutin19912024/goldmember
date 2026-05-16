<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700',
        'css/site.css',
        'css/skin/default_skin/css/theme.css',
        'css/admin-forms.css',
        'js/plugins/magnific/magnific-popup.css',
        'js/plugins/slick/slick.css',
        'js/plugins/daterange/daterangepicker.css',
        'js/plugins/datepicker/css/bootstrap-datetimepicker.css',
        'css/filInput.css',
        'css/fonts/font-awesome/font-awesome.css',
        'css/fonts/glyphicons-pro/glyphicons-pro.css',
        'css/multi-select.css',
        'css/multi-select.dev.css',
		"css/bootstrap-tagsinput.css",
		"https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css",
    ];
    public $js = [
        'js/jquery-ui.min.js',
        'js/plugins/moment/moment.min.js',
        'js/plugins/highcharts/highcharts.js',
        'js/plugins/sparkline/jquery.sparkline.min.js',
        'js/plugins/circles/circles.js',
        'js/plugins/jvectormap/jquery.jvectormap.min.js',
        'js/plugins/mixitup/jquery.mixitup.min.js',
        'js/plugins/jvectormap/assets/jquery-jvectormap-us-lcc-en.js',
        'js/plugins/magnific/jquery.magnific-popup.js',
        'js/plugins/holder/holder.min.js',
        'js/plugins/daterange/daterangepicker.js',
        'js/plugins/datepicker/js/bootstrap-datetimepicker.js',
        'js/utility/utility.js',
        'js/demo/demo.js',
        'js/main.js',
        'js/demo/widgets.js',
        'js/plugins/canvasbg/canvasbg.js',
        'js/plugins/slick/slick.js',
        'js/plugins/jquerymask/jquery.maskedinput.min.js',
//        'js/plugins/select2/select2.min.js',
        'js//plugins/validate/jquery.validate.js',
        'js//plugins/validate/additional-methods.js',
        'js/custom.js',
        'js/product_view.js',
        'js/product_parts.js',
        'js/plugins/ckfinder/ckfinder.js',
        "js/plugins/ckeditor/ckeditor.js",
        "js/plugins/pnotify/pnotify.js",
        "js/checkBoxActions.js",
        "js/jquery.quicksearch.js",
        "js/jquery.multi-select.js",
        "js/bootstrap-tagsinput.js",
        "https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js",

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
