alsihop DB
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=103.6.198.180;dbname=alishopm_xmail;',
            'username' => 'alishopm_alishop',
            'password' => 'SGshopmy123',
            'emulatePrepare'=>true,
            'charset' => 'utf8',
        ],
         'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.alishop.my',
                'username' => '_mainaccount@alishop.my',
                'password' => 'SGshopmy123',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
    ],
];

hamsterEat DB config

Username: hamstere
Password: Hamster123

<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=103.6.198.51;dbname=hamstere_hamsterEat;',
            'username' => 'hamstere_admin',
            'password' => 'SGshopmy123',
            'emulatePrepare'=>true,
            'charset' => 'utf8',
        ],
         'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.hamstereat.my',
                'username' => '_mainaccount@hamstereat.my',
                'password' => 'Hamster123',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
    ],
];

'request' => [
            'csrfParam' => '_csrf-backend',
            'class' => 'common\components\Request',
            'web'=> '/hamsterEat/backend/web',
            'adminUrl' => '/admin'
],

'request' => [
           'class' => 'common\components\Request',
           'web'=> '/hamsterEat/frontend/web',
           'csrfParam' => '_csrf-frontend',
 ],

https://stackoverflow.com/questions/26525320/enable-clean-url-in-yii2
https://sochinda.wordpress.com/2015/10/08/yii2-htaccess-how-to-hide-frontendweb-and-backendweb-completely/

