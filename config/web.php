<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name'=>'A + B',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => false,
            ],
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.user' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/user/messages',
                ],
                // ...
            ],
        ],
        'request' => [
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'w4i9DYOtYXQ2HrpQzJpueeZPUFVS8pe6',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'yii2mod\user\models\UserModel',
            'enableAutoLogin' => true,
            'on afterLogin' => function ($event) {
                $event->identity->updateLastLogin();
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
//            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => '',
                'password' => '',
                'port' => '587',
                'encryption' => 'tls',
            ],
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
        'db' => $db,
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                '/' => '/home',
                'logout' => '/site/logout',
                'projects' => '/projects/index',

                'projects-edit/<id:\d+>' => '/projects/edit',
                'project-images-size-list/<id:\d+>' => '/projects/project-images-size-list',
                'queue-list' => '/projects/queue-list',
                'generate-queue-projects/<id:\d+>' => '/projects/generate-queue-projects',
                'generate-queue-projects' => '/projects/generate-queue-projects',
                'project-images-list/<project_id:\d+>/<size_id:\d+>' => '/projects/project-images-list',
                'projects-update/<id:\d+>' => '/projects/update',
                'projects-delete/<id:\d+>' => '/projects/delete',
                'delete-project-image-ajax' => '/projects/project-images-list',


                'zipping/<id:\d+>' => '/projects/zipping',

                'backgrounds' => '/backgrounds/index',
                'project-image-generate/<id:\d+>' => '/projects/project-image-generate',

                'get-backgrounds-ajax' => '/backgrounds/get-backgrounds-ajax',
                'delete-background-image-ajax' => '/backgrounds/delete-background-image-ajax',
                'save-images-ajax' => '/projects/save-images-ajax',

            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
