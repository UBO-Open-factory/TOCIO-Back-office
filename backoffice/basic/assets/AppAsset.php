<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'css/bootstrap-slate.min.css',
		'css/site.css',
    	'https://fonts.googleapis.com/css2?family=Raleway:wght@900&display=swap',
    ];
    public $cssOptions = [
    	'type' => 'text/css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
    
    
    //______________________________________________________________________________________________
    /**
     * Changement de la variable de baseURL pour la machine de prod.
     * {@inheritDoc}
     * @see \yii\web\AssetBundle::init()
     */
    public function init(){
    	parent::init();
    	
    	// Si on est pas en dev (donc probablement sur la machine de l'UBO derrière son proxie)
    	if ( YII_ENV != 'dev') {
    		$this->baseUrl = '@web/data/passerelle';
    	}
    }
}
