<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class RestaurantDetailsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/restaurant-details.css',
        'css/button.css',
        
    ];
    public $js = [
        'js/food-modal.js',
        'js/report-modal.js',
		'js/scrolltop.js',
        'js/mouseclick.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

