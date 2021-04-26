<?php
return [
		'class' => 'yii\db\Connection',
		'dsn' => 'mysql:host=localhost;dbname=data',
		'username' => 'userdata',
		'password' => '5EFEMzGZALn8nYBV',
		'charset' => 'utf8',

		// Timezone pour Paris
		'on afterOpen' => function ( $event ) {
			// set 'Europe/Paris' timezone
			$event->sender->createCommand( "SET time_zone='+02:00';" )->execute();
		}
		// Schema cache options (for production environment)
// 'enableSchemaCache' => true,
// 'schemaCacheDuration' => 60,
// 'schemaCache' => 'cache',
];
