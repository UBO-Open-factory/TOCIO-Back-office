<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\models\User;

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
    <!-- Matomo -->
	<script type="text/javascript">
	  var _paq = window._paq || [];
	  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
	  _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
	  _paq.push(['trackPageView']);
	  _paq.push(['enableLinkTracking']);
	  (function() {
	    var u="https://outils.alex-design.fr/";
	    _paq.push(['setTrackerUrl', u+'matomo.php']);
	    _paq.push(['setSiteId', '18']);
	    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
	    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
	  })();
	</script>
	<noscript><p><img src="https://outils.alex-design.fr/matomo.php?idsite=18&amp;rec=1" style="border:0;" alt="" /></p></noscript>
	<!-- End Matomo Code -->
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    $menuItems = [];
    if( Yii::$app->user->can('createModule') ) {
    	$menuItems[] = ['label' => 'Modules', 'url' => ['/module/index'], 'linkOptions' => ['class' => 'nav-link'] ];
    }
    if( Yii::$app->user->can('createCapteur') ) {
    	$menuItems[] = ['label' => 'Capteurs', 'url' => ['/capteur/index'], 'linkOptions' => ['class' => 'nav-link'] ];
    }
    if( Yii::$app->user->can('createGrandeur') ) {
    	$menuItems[] = ['label' => 'Grandeurs', 'url' => ['/grandeur/index'], 'linkOptions' => ['class' => 'nav-link'] ];
    }
    if( Yii::$app->user->can('createLocalisation') ) {
    	$menuItems[] = ['label' => 'Localisations', 'url' => ['/localisationmodule/index'], 'linkOptions' => ['class' => 'nav-link'] ];
    }
    
	// OUTILS --------------------------------------------------------------------------------------
    if( ! Yii::$app->user->isGuest ) 
    {
        $menuGen['Gen'] = 
        [ 
            'label' => 'Generation',
            'linkOptions' => ['class' => 'nav-link'],
            'items' => 
            [ 
                    '<li class="dropdown-header">Outils de génération de code Arduino</li>',
                [ 'label' => 'Method', 'url' => ['/method/index'], 'linkOptions' => ['class' => 'nav-link'] ],
                [ 'label' => 'Cartes', 'url' => ['/cartes/index'], 'linkOptions' => ['class' => 'nav-link'] ],
            ],       
        ];
		$menuItems['Outils'] = 
        [ 
			'label' => 'Outils',
			'linkOptions' => ['class' => 'nav-link'],
			'items' => 
            [ 
					'<li class="dropdown-header">Outils de diagnostique divers</li>',
				[ 'label' => 'Traces de débug', 'url' => ['/log/index'], 'linkOptions' => ['class' => 'nav-link'] ],
				[ 'label' => 'Tables des données', 'url' => ['/grandeur/index'], 'linkOptions' => ['class' => 'nav-link'] ],
				[ 'label' => 'Exports de données', 'url' => ['/site/export'], 'linkOptions' => ['class' => 'nav-link'] ],
				[ 'label' => 'Import de journaux CSV', 'url' => ['/site/upload'], 'linkOptions' => ['class' => 'nav-link'] ],
                [ 'label' => 'Outils de génération de code'],
                [ 'label' => 'Generation' , 'linkOptions' => ['class' => 'nav-link'],'items' => 
                [
                        '<li class="dropdown-header">Outils de génération de code Arduino</li>',
                    [ 'label' => 'Method', 'url' => ['/method/index'], 'linkOptions' => ['class' => 'nav-link'] ],
                    [ 'label' => 'Cartes', 'url' => ['/cartes/index'], 'linkOptions' => ['class' => 'nav-link'] ],
                ],
                ],
			],       
		];

        
        
    } 
    else 
    {
    	$menuItems[] = 
        [
    			'label' => 'Outils',
    			'linkOptions' => ['class' => 'nav-link'],
    			'items' => 
                [
    					[ 'label' => 'Tables des données', 'url' => ['/grandeur/index'], 'linkOptions' => ['class' => 'nav-link'] ],
    					[ 'label' => 'Exports de données', 'url' => ['/site/export'], 'linkOptions' => ['class' => 'nav-link'] ],
    			],
    	];
    }
	
   	// UTILISATEURS --------------------------------------------------------------------------------
   	if( Yii::$app->user->can('createUser') ) {
   		$menuItems[] = ['label' => 'Users', 'url' => ['/utilisateur/index'], 'linkOptions' => ['class' => 'nav-link']]; 
   	}
   	$menuItems[] = ['label' => 'About', 'url' => ['/site/about'], 'linkOptions' => ['class' => 'nav-link'] ];
   	
   	
   	// LOGIN ---------------------------------------------------------------------------------------
   	if( Yii::$app->user->isGuest ) {
   		$menuItems[] = ['label' => 'Login', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'nav-link pull-right'] ];
   	} else {
   		$menuItems[] = '<li>'. Html::beginForm(['/site/logout'], 'post')
	                		 . Html::submitButton( 	'Logout (' . Yii::$app->user->identity->username . ')',
	                    							['class' => 'btn btn-link logout push-right']
	                							)
	                		. Html::endForm()
	                	. '</li>';
   	}
    	

    
    
    // CONSTRUCTION DU MENU ------------------------------------------------------------------------
    NavBar::begin(['brandLabel' => Yii::$app->name,
				    'brandUrl' => Url::base().Url::to("/"),
				    'options' => [
				    	'class' => 'navbar navbar-expand-lg ',
    				],
    			]);
    echo Nav::widget(['options' => ['class' => 'nav-pills'],
    				'items' => $menuItems,
    				'dropDownCaret' => "",
    				]);

    NavBar::end();
    ?>
   	<div class="arcEnCiel"></div>
    <div class="container">
		<?= Breadcrumbs::widget([
			'homeLink' => ['label' => "Home", 'url' =>  "@urlbehindproxy/"],
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container row Licence">
		<?php 
		if( defined('YII_ENV') ){
			echo "<div class='col'>Mode ".YII_ENV." </div>";
		}
		?>
        <div class="col">
        	<a rel="license" href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank"><img alt="Licence Creative Commons" style="border-width:0" src="<?php echo Url::to(['/assets/licence_CCbySA.png']); ?>" /></a><br />
        </div>
        <?php if( Yii::$app->user->isGuest ){ ?>
        <div class="col-6 text-center">
	        Ce site est mise à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Licence Creative Commons Attribution -  Partage dans les Mêmes Conditions 4.0 International</a>.
        </div >
        <?php }?>
        <div class="col">
        	<a href="https://github.com/UBO-Open-factory/TOCIO-Back-office" target="_blank">
        		<svg height="24" class="octicon octicon-mark-github" viewBox="0 0 16 16" version="1.1" width="24" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
        		Dépot
        	</a>
        </div>
        <?php if( !Yii::$app->user->isGuest ){ ?>
        <div class="col-2"><a href="https://github.com/UBO-Open-factory/TOCIO-Back-office/issues" target="bug">Ouvrir un bug</a></div>
        <?php }?>
        <div class="col pull-right"><?php echo Yii::powered()." ".Yii::getVersion() ?> </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
