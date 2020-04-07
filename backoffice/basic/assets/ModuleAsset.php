<?php
/**
 * Ressource pour l'affichage des modules.
 * 
 * 	@file ModuleAsset.php
 * 
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ModuleAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/module/module.css',
    ];
    public $js = [
        'assets/module/module.js',
    ];
    public $depends = [
    		'\yii\web\JqueryAsset'
    ];
}
