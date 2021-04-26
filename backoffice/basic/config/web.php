<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/',
    'language' => 'fr-FR',
	'sourceLanguage' => 'fr-FR',
    'bootstrap' => [
        'log'
    ],
    'timeZone' => 'Europe/Paris',
    'version' => '1.2.6',
    'aliases' => [
        // Do not define anything here, but in web_local.php
    ],
    'components' => [
        // Gestion des ressources ( les assets )
        // On rajoute un timestamp pour que cette ressource ne soit pas mise en cache
        'assetManager' => [
            'baseUrl' => "@urlbehindproxy/assets/",
            'appendTimestamp' => true,
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'scss' => [
                        'css',
                        'sass {from} {to} --sourcemap --style=compressed'
                    ]
                ]
            ]
        ],
        // Gestion des authentifications par groupe d'accès.
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'keW0Qn2MixxqzkfRxEWni-Frzm3VJoFim',

            // Autorisation pour avoir du JSON en entrée (POST par exemple)
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache'
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 60 * 30,
            'identityCookie' => [
                'name' => '_User'
            ]
        ],
        'errorHandler' => [
            'errorAction' => '/site/error'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',

            'useFileTransport' => true,
            // if set to "true" -> send all mails to a file
            // if set to "false" -> send email to your transport configuration (you have to configure it)
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => 'tls',
                'host' => 'your_mail_server_host',
                'port' => 'your_smtp_port',
                'username' => 'your_username',
                'password' => 'your_password'
            ]
        ],

        // le composant "log" traite les messages avec un horodatage (timestamp).
        'log' => [
            'traceLevel' => YII_DEBUG ? 4 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => [
                        'error'
                    ],
                    'categories' => [
                        'tocio'
                    ]
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning'
                    ]
                ]
            ]
        ],
    	// Pour affichage par défault de la date et de l'heure.
    	'localtime'=>array(
    			'class'=>'LocalTime',
    	),
    	// Pour ubn formattage par défaut de la date et de l'heure
		'formatter' => [
    			'dateFormat' => 'dd/MM/yyyy',
    			'datetimeFormat' => 'dd/MM/yyyy hh:mm:ss',
    			'decimalSeparator' => '.',
    			'thousandSeparator' => ' ',
    			'currencyCode' => 'EUR',
    	],
        'db' => $db,
        'urlManager' => [
            'baseUrl' => "@urlbehindproxy",
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'tramebrute'
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'mesure',
                    'pluralize' => false,
                    'patterns' => [
                        'POST addlora' => 'addlora',
                        'GET addlora' => 'addloraget',
                        'GET add/<moduleid>/<mesures>' => 'add',
                        'GET get/<moduleid>' => 'get'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'capteur',
                    'pluralize' => false,
                    'patterns' => [
                        'GET getcapteur/<id:\d+>' => 'getcapteur',
                        'GET,POST getcapteurs' => 'getcapteurs'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'grandeur',
                    'pluralize' => false,
                    'patterns' => [
                        'GET getgrandeur/<id:\d+>' => 'getgrandeur',
                        'GET getgrandeurs' => 'getgrandeurs'
                    ]
                ]
            ]
        ]
    ],
    'params' => $params
];


// Merge config from other web.php config file
if( __DIR__ . '/web_local.php') {
    $config = array_merge($config, require __DIR__ . '/web_local.php');
}



if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        // 'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*'],
        'allowedIPs' => [
            '*'
        ]
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => [
            '127.0.0.1',
            '::1',
            '192.168.0.*'
        ]
        // 'allowedIPs' => ['*'],
    ];
}

return $config;
