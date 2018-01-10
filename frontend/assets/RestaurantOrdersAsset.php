<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class RestaurantOrdersAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/restaurant-orders.css',
        'css/drop-down-mobile.css',
		'css/filter.css',
        'css/button.css',
    ];
    public $js = [
        // 'js/cart.js',
	  'js/scrolltop.js',
      'js/check.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

