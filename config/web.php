<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'timeZone' => 'Europe/Paris',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'keW0Qn2MixxqzkfRxEWniFrzm3VJoFim',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

		// le composant "log" traite les messages avec un horodatage (timestamp).
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
				[
					'class' => 'yii\log\DbTarget',
					'levels' => ['error'],
					'categories' => ['tocio'],
				],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            		[
            				'class' => 'yii\rest\UrlRule',
            				'controller' => 'mesure',
            				'pluralize' => false,
            				'patterns' => [
            						'GET add/<moduleid>/<mesures>' => 'add',
            				],
            		],
            		[
            				'class' => 'yii\rest\UrlRule',
            				'controller' => 'capteur',
            				'pluralize' => false,
            				'patterns' => [
            						'GET getcapteur/<id:\d+>' => 'getcapteur',
            				],
            		],
            		[
            				'class' => 'yii\rest\UrlRule',
            				'controller' => 'grandeur',
            				'pluralize' => false,
            				'patterns' => [
            						'GET getgrandeur/<id:\d+>' => 'getgrandeur',
            				],
            		],
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
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['*'],
    ];
}

return $config;
