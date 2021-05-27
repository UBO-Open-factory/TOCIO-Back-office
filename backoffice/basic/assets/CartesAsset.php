<?php
/**
 * Fichier de déclaration des ressources pour la page des Cartes.
 * 
 * 	@file ModuleAsset.php
 * @author : Alexandre PERETJATKO (APE) - Kilian LE GALL (adaptation pour les cartes)
 * 	@version 23 avr. 2020	: APE	- Création.
 * 
 */

namespace app\assets;

use yii\web\AssetBundle;

class CartesAsset extends AssetBundle {
    public $basePath = '@webroot';
    //     public $baseUrl = '@web';
    public $baseUrl = '@urlbehindproxy';
//     public $css = [
//         'assets/capteur/capteur.css',
//     ];
    public $js = [
        'assets/config.js',
        'assets/cartes/cartes.js',
    ];
    public $depends = [
    		'\yii\web\JqueryAsset'
    ];

}
