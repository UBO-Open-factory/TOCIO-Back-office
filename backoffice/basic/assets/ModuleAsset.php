<?php
/**
 * Fichier de déclaration des ressources pour la page des Modules.
 * 
 * 	@file ModuleAsset.php
 * @author : Alexandre PERETJATKO (APE)
 * @version 16 avr. 2020	: APE	- Création. 
 * 
 */

namespace app\assets;

use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@urlbehindproxy';
    public $css = [
        'assets/module/module.scss',
    ];
    public $js = [
        'assets/config.js',
        'assets/module/module.js',
    ];
    public $depends = [
    		'\yii\web\JqueryAsset'
    ];
}
