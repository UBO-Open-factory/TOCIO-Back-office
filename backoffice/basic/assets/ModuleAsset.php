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
