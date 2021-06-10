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
//     public $baseUrl = '@web';
    public $baseUrl = '@urlbehindproxy';
    public $css = [
		'css/bootstrap-slate.min.css',
		'css/site.css',
		'css/button.css',
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
}
