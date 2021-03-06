# Install
If you need an ElasticSearch data base you need to install it by your own. 
This guide doesn't cover this part [you can refer to this official tutorial.](https://www.elastic.co/guide/en/elasticsearch/reference/current/install-elasticsearch.html)

## Files
```
mkdir data
cd data 
git clone https://github.com/UBO-Open-factory/TOCIO-Back-office.git ./
cd ..
sudo chown apache.apache -R data/ 
```
You have 2 main directories :
* APIDoc (the swagger API's description)
* backoffice (the Yii application)


## MySQL Data base
You can found the data base structure in the __basic/migrations__ directory. 
You need first to create a data base named _data_, then to import TOCIO's data base structure run:

```
cd basic
mysql -u root -p data < migrations/DataBaseStructure.sql

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
You need to configur 2 apache VirtualHost for :
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


# TOCIO Back Office Config
To save your local config, you need to create a __config/web_local.php__ file with this :
```
<?php
return [
    'name' => "TOCIO : Back Office",
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@urlbehindproxy' => "/",
        '@elasticsearchindex' => "model-tocio-event-large"
    ]
];
```
| Params               | Fonction        |
| -------------------- | --------------- |
| @urlbehindproxy      | If your TOCIO's back office is behind a revers-proxy you need this params to define your base server.(This part will be add in front of every URL that Yii2 generate.) |
| @elasticsearchindex  | This is the ElasticSearch index under wich your documents will be stored. If you don't use ELK, let this params to empty. |
