<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class RestaurantDefaultIndex2Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/restaurant-default-index2.css',
        'css/filter.css',
        'css/button.css',
        'css/food-details-img-slider.css',
        'css/ribbon.css',
    ];
    public $js = [
        'js/food-modal.js',
		'js/scrolltop.js',
        'js/filter.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

