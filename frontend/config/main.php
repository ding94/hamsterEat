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
                'common' => ['class' => 'common\translation\DbSentencesTranslate',],
                'cart' => ['class' => 'common\translation\DbSentencesTranslate',],
                'checkout' => ['class' => 'common\translation\DbSentencesTranslate',],
                'company' => ['class' => 'common\translation\DbSentencesTranslate',],
                'discount' => ['class' => 'common\translation\DbSentencesTranslate',],
                'guide' => ['class' => 'common\translation\DbSentencesTranslate',],
                'layouts' => ['class' => 'common\translation\DbSentencesTranslate',],
                'memberpoint-h' => ['class' => 'common\translation\DbSentencesTranslate',],
                'news' => ['class' => 'common\translation\DbSentencesTranslate',],
                'notification' => ['class' => 'common\translation\DbSentencesTranslate',],
                'order' => ['class' => 'common\translation\DbSentencesTranslate',],
                'payment' => ['class' => 'common\translation\DbSentencesTranslate',],
                'rating' => ['class' => 'common\translation\DbSentencesTranslate',],
                'report' => ['class' => 'common\translation\DbSentencesTranslate',],
                'site' => ['class' => 'common\translation\DbSentencesTranslate',],
                'ticket' => ['class' => 'common\translation\DbSentencesTranslate',],
                'topup' => ['class' => 'common\translation\DbSentencesTranslate',],
                'topup-h' => ['class' => 'common\translation\DbSentencesTranslate',],
                'user' => ['class' => 'common\translation\DbSentencesTranslate',],
                'vouchers' => ['class' => 'common\translation\DbSentencesTranslate',],
                'withdraw' => ['class' => 'common\translation\DbSentencesTranslate',],
                'withdraw-h' => ['class' => 'common\translation\DbSentencesTranslate',],
                'm-delivery' => ['class' => 'common\translation\DbSentencesTranslate',],
                'm-restaurant' => ['class' => 'common\translation\DbSentencesTranslate',],
                'm-userpackage' => ['class' => 'common\translation\DbSentencesTranslate',],
                'faq' => ['class' => 'common\translation\DbSentencesTranslate',],
                'food' => ['class' => 'common\translation\DbSentencesTranslate',],
                //'food-sel' => ['class' => 'common\translation\DbFoodSelSource',],

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
        
         'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                 'list-of-restaurants' => 'Restaurant/default/index',
                'list-of-food' => 'Restaurant/default/show-by-food',
            ],
        ],
        
    ],
    'params' => $params,
];
