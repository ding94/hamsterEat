<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class FoodMenuAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/food-menu.css',
        'css/drop-down-mobile.css',
		'css/filter.css',
        'css/button.css',
    ];
    public $js = [
        'js/add-modal.js',
        'js/cart.js',
		'js/scrolltop.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

