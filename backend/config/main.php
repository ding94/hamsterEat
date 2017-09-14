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
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
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
            'baseUrl' => '/hamsterEat/frontend/web',
        ],

        'urlManagerBackEnd'=>[
            'class' => 'yii\web\urlManager',
            'baseUrl' => './../../frontend/web',
        ]
        /*
        'urlManager' => [
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
