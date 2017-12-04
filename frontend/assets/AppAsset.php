<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/sidenav.css',
        'css/footer.css',
        'css/popupbox.css',
        'css/button.css',
    ];
    public $js = [
	    'js/he.js',
        'js/FlashTimer.js',
        'js/feedback-modal.js',
        'js/login-modal.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

