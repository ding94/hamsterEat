<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class PriceConfigAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/add-modal.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'iutbay\yii2fontawesome\FontAwesomeAsset'
    ];
}

