# Install
If you need an ElasticSearch data base you need to install it by your own. 
This guide doesn't cover this part [you can refer to this official tutorial.](https://www.elastic.co/guide/en/elasticsearch/reference/current/install-elasticsearch.html)

This documentation explain how to configure Web server's. They will be accessible via URL :
| URL                   | Description     |
| --------------------- | --------------- |
| http://localhost:8888 | Backoffice to configure your TOCIO's hardware network |
| http://localhost      | REST API o access data from your TOCIO web server |

## Requirements
  - PHP 7.3 (doesn't work with PHP 8)
  - Mariadb or MySQL
  - Centos 8

## Files
```
cd /var/www/html
mkdir data
cd data 
git clone https://github.com/UBO-Open-factory/TOCIO-Back-office.git ./
cd /var/www/html
sudo chown apache.apache -R data/ 
sudo ln -s data/backoffice/basic/web/ Yii
sudo ln -s data/APIdoc/swagger-ui/dist/ default
```
You have 2 main directories :
* APIDoc (the swagger API's description)
* backoffice (the Yii application)


## MySQL Data base
First, create a database **data** with user **5EFEMzGZALn8nYBV** :
```
mysql -u root
CREATE DATABASE data  DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
grant usage on *.* to userdata@localhost identified by '5EFEMzGZALn8nYBV';
GRANT SELECT, INSERT, UPDATE, DELETE, DROP ON `data`.* TO 'userdata'@'localhost';
quit;
```

Then import structure and some data in database.
You can found the data base structure in the __basic/migrations__ directory. 
You need first to create a data base named _data_, then to import TOCIO's data base structure run:

```
cd data/backoffice/basic/
mysql -u root -p data < migrations/DataBaseStructure.sql
```
You have to populate your database with samples :
in __backoffice/basic__ directory :
```
php yii migrate
```


You have to create a user __userdata__ with the password __5EFEMzGZALn8nYBV__ that can access this data base and define it in __config/db.php__
```
<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=data',
    'username' => 'userdata',
    'password' => '5EFEMzGZALn8nYBV',
    'charset' => 'utf8',
];
```



## Apache
You need to set 2 apache VirtualHost for :
- Swagger API access (on port 80 or wathever)
- TOCIO BackOffice access (on port 8888 or wathever)

api-80.conf :
```
<VirtualHost *:80>
        ServerName YourHostName
        CustomLog "/var/log/httpd/default-access.log" combined
        ErrorLog "/var/log/httpd/default-error.log"

        DirectoryIndex index.html index.php
        DocumentRoot /var/www/html/default

        <Directory "/var/www/html/default/">
                Options -Indexes +MultiViews +FollowSymlinks

                DirectoryIndex index.html index.php
                AllowOverride All
                Require all granted
        </Directory>
</VirtualHost>
```
Yii-8888.conf :
```
Listen 8888
<VirtualHost *:8888>
        CustomLog /etc/httpd/logs/Yii-access.log common
        ErrorLog /etc/httpd/logs/Yii-error.log

        DocumentRoot /var/www/html/Yii/
        <Directory /var/www/html/Yii/>
                RewriteEngine on

                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteCond %{REQUEST_FILENAME} !-d
                RewriteRule . index.php

                RewriteRule ^index.php/ - [L,R=404]
                Header set Access-Control-Allow-Origin "*"
                DirectoryIndex index.php
                Options FollowSymLinks MultiViews
                AllowOverride All
                Require all granted
        </Directory>
</VirtualHost>
```

## Firewall
As you need the 8888 port, you have to open it in your firewall, with :
```
sudo firewall-cmd --permanent --zone=public --add-port=8888/tcp
sudo firewall-cmd --reload
```

# TOCIO Back Office Config
To save your local config, you need to create 2 files __params.php__ and __web_local.php__ in __basic/config/__ directory:
## __config/params.php__ ##
This file store everything about your mailer of TOCIO backoffice.
```
<?php
return [
		'adminEmail' => '<yourAdmin@email>',
		'senderEmail' => 'noreply@tocio.com',
		'senderName' => 'Admin TOCIO',
		
		// If you don't need SMTP, please change components.mailer.useFileTransport to true (in config/web.php)
		'SMTP_host' 	=> "YourHost",
		'SMTP_port' 	=> "YourPort",
		'SMTP_username'	=> "YourUserName",
		'SMTP_password' => "YourUserPassword",
];
```

## __config/web_local.php__ ##
This file store everything about your instance of TOCIO backoffice.
```
<?php
return [
    'name' => "TOCIO : Back Office",
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@urlbehindproxy' => "/",
	
	// If you don't need ElasticSearch, set next param to ""
        '@elasticsearchindex' => "model-tocio-event-large",
	
	// Absolute path to the directory where uploads will be stored
        '@CSVIimportDirectory' => "/web/downloads/",       
    ]
];
```
| Params               | Fonction        |
| -------------------- | --------------- |
| @urlbehindproxy      | If your TOCIO's back office is behind a revers-proxy you need this params to define your base server.(This part will be add in front of every URL that Yii2 generate.) |
| @elasticsearchindex  | This is the ElasticSearch index under wich your documents will be stored. If you don't use ELK, let this params to empty. |
| @CSVIimportDirectory | Absolute path to the directory where uploads will be stored. Path should be rootable from Yii (started with /web/....) |

Give access to the web server to this files :
```
sudo chown apache.apache config/web_local.php
sudo chown apache.apache config/params.php
```

## Configur the CSV import
TOCIO give you the ability to import CSV file from an ftp server. For that you need to execute __scripts_import_FTP/import.sh__ in a cron as shown bellow :
```
0/30 * * * * sh /var/www/html/data/backoffice/basic/scripts_import_FTP/import.sh
```
