<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    
    'modules' => [
        'finance' => [
            'class' => 'app\modules\finance\finance',
        ],
        'order' => [
            'class' => 'backend\modules\Order\order',
        ],
        'restaurant' => [
            'class' => 'backend\modules\Restaurant\restaurant',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'i18n' => [
            'translations' => [
                'common' => ['class' => 'common\translation\DbSentencesTranslate',],
                'food' => ['class' => 'common\translation\DbSentencesTranslate',],
            ],
        ],
        'frontendAuthManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
            //these configuratio allow to rename the auth table  
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'adminitem_child',
            'ruleTable' => 'auth_rule',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
            //these configuratio allow to rename the auth table  
            'itemTable' => 'admin_auth_item',
            'assignmentTable' => 'admin_auth_assignment',
            'itemChildTable' => 'admin_auth_item_child',
            'ruleTable' => 'admin_auth_rule',
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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

        'urlManagerFrontEnd'=>[
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/imageLocation',
        ],

        'urlManagerBackEnd'=>[
            'class' => 'yii\web\urlManager',
            'baseUrl' => './../../frontend/web/imageLocation/',
        ],
        
         /*'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
		*/
    ],

    /*
     *deny all guest to view page
    */
    'as beforeRequest' =>[
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'actions' => ['login'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'denyCallback' => function () {
            return Yii::$app->response->redirect(['site/login']);
        },
    ],
    
    'params' => $params,
];
