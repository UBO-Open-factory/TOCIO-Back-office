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
        <div class="col-2">
        	<a href="https://github.com/">
        		<svg height="24" class="octicon octicon-mark-github" viewBox="0 0 16 16" version="1.1" width="24" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
        		Dépot GitHub
        	</a>
        </div>
        <div class="col pull-right"><?= Yii::powered() ?></div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
