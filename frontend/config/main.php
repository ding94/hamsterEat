<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'Delivery Food In Medini | HamsterEat',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'zh',
    'sourceLanguage'=>'en',
    'modules' => [
        'Restaurant' => [
            'class' => 'frontend\modules\Restaurant\Restaurant',
        ],
        'Delivery' => [
            'class' => 'frontend\modules\delivery\delivery',
        ],
        'UserPackage' => [
            'class' => 'frontend\modules\UserPackage\Package',
        ],
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'i18n' => [
            'translations' => [
                'cart' => ['class' => 'yii\i18n\DbMessageSource',],
                'checkout' => ['class' => 'yii\i18n\DbMessageSource',],
                'company' => ['class' => 'yii\i18n\DbMessageSource',],
                'layouts' => ['class' => 'yii\i18n\DbMessageSource',],
                'memberpoint-h' => ['class' => 'yii\i18n\DbMessageSource',],
                'news' => ['class' => 'yii\i18n\DbMessageSource',],
                'notification' => ['class' => 'yii\i18n\DbMessageSource',],
                'order' => ['class' => 'yii\i18n\DbMessageSource',],
                'payment' => ['class' => 'yii\i18n\DbMessageSource',],
                'price' => ['class' => 'yii\i18n\DbMessageSource',],
                'rating' => ['class' => 'yii\i18n\DbMessageSource',],
                'report' => ['class' => 'yii\i18n\DbMessageSource',],
                'site' => ['class' => 'yii\i18n\DbMessageSource',],
                'ticket' => ['class' => 'yii\i18n\DbMessageSource',],
                'topup' => ['class' => 'yii\i18n\DbMessageSource',],
                'topup-h' => ['class' => 'yii\i18n\DbMessageSource',],
                'user' => ['class' => 'yii\i18n\DbMessageSource',],
                'vouchers' => ['class' => 'yii\i18n\DbMessageSource',],
                'withdraw' => ['class' => 'yii\i18n\DbMessageSource',],
                'withdraw-h' => ['class' => 'yii\i18n\DbMessageSource',],
                'm-delivery' => ['class' => 'yii\i18n\DbMessageSource',],
                'm-restaurant' => ['class' => 'yii\i18n\DbMessageSource',],
                'm-userpackage' => ['class' => 'yii\i18n\DbMessageSource',],

                'food' => ['class' => 'yii\i18n\DbFoodSource',],
                'food-sel' => ['class' => 'yii\i18n\DbFoodSelSource',],

            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
