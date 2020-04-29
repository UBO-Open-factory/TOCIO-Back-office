<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-lg ',
        ],
    ]);
    // Si l'user est authentifié
    $identity = Yii::$app->user->identity;	// null si non authentifié
    // SI ON EST AUTHENTIFIE -----------------------------------------------------------------------
    if( $identity != null){
	    echo Nav::widget([
	        'options' => ['class' => 'navbar-nav navbar-right'],
	        'items' => [
	            ['label' => 'Home', 'url' => ['/site/index'], 'linkOptions' => ['class' => 'nav-link'] ],
	        	['label' => 'Modules', 'url' => ['/module/index'], 'linkOptions' => ['class' => 'nav-link'] ],
	        	['label' => 'Capteurs', 'url' => ['/capteur/index'], 'linkOptions' => ['class' => 'nav-link'] ],
	        	['label' => 'Grandeurs', 'url' => ['/grandeur/index'], 'linkOptions' => ['class' => 'nav-link'] ],
	        	['label' => 'Localisation de modules', 'url' => ['/localisationmodule/index'], 'linkOptions' => ['class' => 'nav-link'] ],
	        	['label' => 'Traces de débug', 'url' => ['/log/index'], 'linkOptions' => ['class' => 'nav-link'] ],
	//         	['label' => 'Users', 'url' => ['/utilisateurs/index'], 'linkOptions' => ['class' => 'nav-link'] ],
	//         	['label' => 'Contact', 'url' => ['/site/contact'], 'linkOptions' => ['class' => 'nav-link'] ],
	            Yii::$app->user->isGuest ? (
	            		['label' => 'Login', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'nav-link pull-right'] ]
	            ) : (
	                '<li>'
	                . Html::beginForm(['/site/logout'], 'post')
	                . Html::submitButton(
	                    'Logout (' . Yii::$app->user->identity->username . ')',
	                    ['class' => 'btn btn-link logout']
	                )
	                . Html::endForm()
	                . '</li>'
	            )
	        ],
	    ]);
	// ANONYMOUS -----------------------------------------------------------------------------------
    } else  {
    	echo Nav::widget([
    			'options' => ['class' => 'navbar-nav navbar-right'],
    			'items' => [
    					['label' => 'Home', 'url' => ['/site/index'], 'linkOptions' => ['class' => 'nav-link'] ],
    					Yii::$app->user->isGuest ? (
    							['label' => 'Login', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'nav-link pull-right'] ]
    							) : (
    									'<li>'
    									. Html::beginForm(['/site/logout'], 'post')
    									. Html::submitButton(
    											'Logout (' . Yii::$app->user->identity->username . ')',
    											['class' => 'btn btn-link logout']
    											)
    									. Html::endForm()
    									. '</li>'
    									)
    			],
    			]);
    }
    NavBar::end();
    ?>
   	<div class="arcEnCiel"></div>
    <div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container row Licence">
        <div class="col">
        	<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a>
        </div>
        <div class="col-8 text-center">
	        Ce site est mise à disposition selon les termes de la <br/><a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">Licence Creative Commons Attribution - Pas d’Utilisation Commerciale 4.0 International</a>.
        </div >
        <div class="col"><a href="https://github.com/">Dépot GitHub</a></div>
        <div class="col pull-right"><?= Yii::powered() ?></div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
