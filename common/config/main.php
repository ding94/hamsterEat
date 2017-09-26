<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
         'formatter' => [
            'dateFormat' => 'php:d-M-Y',
            'datetimeFormat' => 'php:Y-m-d H:i:s',
            'timeFormat' => 'php:H:i:s',
            'timeZone' => 'Asia/Kuala_Lumpur',
        ],
    ],
];
