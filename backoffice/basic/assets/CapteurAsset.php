<?php
/**
 * Fichier de déclaration des ressources pour la page des Capteur.
 * 
 * 	@file ModuleAsset.php
 * @author : Alexandre PERETJATKO (APE)
 * 	@version 23 avr. 2020	: APE	- Création.
 * 
 */

namespace app\assets;

use yii\web\AssetBundle;

class CapteurAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//     public $css = [
//         'assets/capteur/capteur.css',
//     ];
    public $js = [
        'assets/capteur/capteur.js',
    ];
    public $depends = [
    		'\yii\web\JqueryAsset'
    ];
}
