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
        'css/Slider.css',
        'css/user.css',
        'css/sidenav.css',
		'style.css',
		'bubble.css',
        'css/footer.css',
    ];
    public $js = [
	'js/he.js',
    'js/PhotoSlider.js',
    'js/FlashTimer.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

