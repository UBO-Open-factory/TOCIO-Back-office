<?php
/**
 * Fichier de déclaration des ressources pour la page des Modules.
 * 
 * 	@file ModuleAsset.php
 * @author : Alexandre PERETJATKO (APE)
 * 		 - Kilian LE GALL (KLG)
 * @version 16 avr. 2020	: APE	- Création.
 * @version 27 mai 2021	: KLG	- adaptation pour les cartes 
 */

namespace app\assets;

use yii\web\AssetBundle;

class ModuleGenCodeAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@urlbehindproxy';
    public $css = [
        'assets/module/module.scss',
    ];
    public $js = [
        'assets/config.js',
        'assets/module/generation/Ajax.js',
        'assets/module/generation/gen_main.js',
        'assets/module/generation/gen_include.js',
        'assets/module/generation/gen_pin.js',
        'assets/module/generation/gen_declaration.js',
        'assets/module/generation/gen_setup.js',
        'assets/module/generation/gen_reading.js',
        'assets/module/generation/gen_color.js',
    ];
    public $depends = [
    		'\yii\web\JqueryAsset'
    ];
}
