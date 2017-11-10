<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class DeliveryLocationAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/delivery-location.css',
        'css/drop-down-mobile.css',
    ];
    public $js = [
        // 'js/cart.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

