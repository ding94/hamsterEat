<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
         'formatter' => [
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:Y-m-d H:i:s',
            'timeFormat' => 'php:H:i:s',
            'timeZone' => 'Asia/Kuala_Lumpur',
        ],
        // bug for dynamic form
        'assetManager' => [
            'bundles' => [
                'wbraganca\dynamicform\DynamicFormAsset' => [
                    'sourcePath' => '@frontend/web/js',
                    'js' => [
                        'yii2-dynamic-form.js'
                    ],
                ],
            ],
            //'appendTimestamp' => true,
            //'forceCopy' => true,
        ],

    ],
   

];
