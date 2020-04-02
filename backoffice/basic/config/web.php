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
            'cookieValidationKey' => 'keW0Qn2MixxqzkfRxEWni-Frzm3VJoFim',
			
        	// Autorisation pour avoir du JSON en entrée (POST par exemple)	
        	'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
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
            'traceLevel' => YII_DEBUG ? 4 : 0,
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
        	'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
            		['class' => 'yii\rest\UrlRule', 'controller' => 'tramebrute'],
            		[
            				'class' => 'yii\rest\UrlRule',
            				'controller' => 'mesure',
            				'pluralize' => false,
            				'patterns' => [
            						'POST addlora' 		=> 'addlora',
            						'GET addlora' 		=> 'addloraget',
            						'GET add/<moduleid>/<mesures>' => 'add',
            						'GET get/<moduleid>' => 'get',
            				],
            		],
            		[
            				'class' => 'yii\rest\UrlRule',
            				'controller' => 'capteur',
            				'pluralize' => false,
            				'patterns' => [
            						'GET getcapteur/<id:\d+>' => 'getcapteur',
            						'GET,POST getcapteurs' => 'getcapteurs',
            				],
            		],
            		[
            				'class' => 'yii\rest\UrlRule',
            				'controller' => 'grandeur',
            				'pluralize' => false,
            				'patterns' => [
            						'GET getgrandeur/<id:\d+>' => 'getgrandeur',
            						'GET getgrandeurs' => 'getgrandeurs',
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
        //'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*'],
    	'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*'],
    	// 'allowedIPs' => ['*'],
    ];
}

return $config;
