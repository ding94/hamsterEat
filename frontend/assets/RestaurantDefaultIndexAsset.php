<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class RestaurantDefaultIndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/restaurant-default-index.css',
        'css/filter.css',
        'css/button.css',
    ];
    public $js = [
        // 'js/PhotoSlider.js',
        'js/filter.js',
        'js/scrolltop.js',
		'js/add-modal.js',
        'js/halal-status.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

