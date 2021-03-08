# Installation
```
mkdir data
cd data 
git clone https://git.alex-design.fr/root/fablab.tocio.dataapi.git ./
cd ..
sudo chown apache.apache -R data/ 
```
# Configuration du Back office de Tocio
Il est nécessaire de créer un fichier de configuration locale pour que le Back Office puisse fonctionner. Pour cela vous devez créer un fichier :
basic/config/web_local.php
contenant :
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
Avec : 
| Variable             | Fonction        |
| -------------------- | --------------- |
| @urlbehindproxy      | Si le Back office se trouve derrière un proxy, cette variable doit contenir la partie à supprimer dans les URL |
| @elasticsearchindex  | L'index sous lequel seront sauvegardés les documents dans Elastic search |
