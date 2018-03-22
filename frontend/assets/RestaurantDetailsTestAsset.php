<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class RestaurantDetailsTestAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/restaurant-details-test.css',
        'css/button.css',
        'css/food-details-img-slider.css',
        'css/ribbon.css',
    ];
    public $js = [
        'js/food-modal.js',
        'js/report-modal.js',
		'js/scrolltop.js',
        'js/restaurant-details.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

