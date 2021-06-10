<?php
/**
 * Fichier de déclaration des ressources pour la page des Cartes.
 * 
 * 	@file ModuleAsset.php
 * @author : Alexandre PERETJATKO (APE)
 * 		 - Kilian LE GALL (KLG)
 * 	@version 23 avr. 2020	: APE	- Création.
 * 	@version 27 mai 2021	: KLG	- adaptation pour les cartes
 * 
 */

namespace app\assets;

use yii\web\AssetBundle;

class MethodIndexAsset extends AssetBundle {
    public $basePath = '@webroot';
    //     public $baseUrl = '@web';
    public $baseUrl = '@urlbehindproxy';
//     public $css = [
//         'assets/capteur/capteur.css',
//     ];
    public $js = [
        'assets/config.js',
        'assets/method/methodIndex.js',
    ];
    public $depends = [
    		'\yii\web\JqueryAsset'
    ];

}
