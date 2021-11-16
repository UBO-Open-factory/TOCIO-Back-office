<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\messageAlerte;
use app\components\tocioRegles;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;


// LISTE DE RÉPERTOIRES CONTENANT LE CODE ÉCRIT À LA MAIN-------------------------------------------
$l_TAB_Directory 	= ['assets', 'components', 'controllers', 'mail', 'models', 'views', 'migrations' ];
$nbLigneCode 		= [];
$nbLigneComment 	= [];
foreach( $l_TAB_Directory as $directoryName){
	// Scan directory
	$path = Yii::$app->getBasePath()."/".$directoryName;
	
	foreach (new DirectoryIterator($path) as $l_STR_File ){
		// We don't want to open some elements in that directory
		if($l_STR_File->isDot()) continue;
		
		// we got a file
		if( $l_STR_File->isFile() ){
			$fileName = $path."/".$l_STR_File;
			// Count non-null lines
			$nbLigneCode[$fileName]		= count(array_filter(file($fileName), "nonVide"));
			$nbLigneComment[$fileName]	= count(array_filter(file($fileName), "commentaire"));
		}
		
		// we got a sub-directory
		if( $l_STR_File->isDir() ){
			foreach (new DirectoryIterator($path."/".$l_STR_File) as $l_STR_SubFile ){
				// We don't want to open some elements in that sub-directory
				if($l_STR_SubFile->isDot()) continue;
				
				if( $l_STR_SubFile->isFile() ){
					$fileName = $path."/".$l_STR_File."/".$l_STR_SubFile;
					// Count non-null lines
					$nbLigneCode[$fileName]		= count(array_filter(file($fileName), "nonVide"));
					$nbLigneComment[$fileName]	= count(array_filter(file($fileName), "commentaire"));
				}
			}
		}
	}
}
function nonVide($elem){
	return strlen( trim($elem) ) > 1;
}
// Renvoie true si on a un commentaire dans $elem
function commentaire($elem){
	// Compact elem
	$data = trim(preg_replace('/[ ]{2,}|[\t]/', '', trim($elem)));
	
	// Si on a un commentaire
	if(strpos($data, "//") !== false) 	return true;
	if(strpos($data, "/**") !== false) 	return true;
	if(strpos($data, "*") !== false) 	return true;
	return false;
}
?>
<!-- Generation du graphique -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load("current", {packages:["corechart"]});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Task', 'Nombre'],
			['Code',	<?= array_sum($nbLigneCode);?>],
			['Commentaire',	<?= array_sum($nbLigneComment);?>],
        ]);

        var options = {
          backgroundColor: 'transparent',
          pieSliceText: 'label',
          textStyle:{color: '#FFFFFF'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
</script>











    <div class="jumbotron">
        <h1><?php echo Yii::$app->name;?> </h1>
        <p class="lead">
        	<?= Yii::$app->version?><br/>
        	<?php echo tocioRegles::widget(['regle' => 'tociodefinition']);?>
        </p>
    </div>
    
    
    <!-- ******************************************************************************************* -->
	<h1>Nombre de ligne de code</h1>
	<div class="row">
		<div class="col-sm-6">
			Ce site est écrit en PHP à l'aide du Framework Yii v<?= Yii::getVersion()?>. En plus des fichiers
			propre au Framework, il nécessite : 
			<ul>
				<li><?= count($nbLigneCode)?> fichiers</li>
				<li><?= array_sum($nbLigneCode)?> lignes de code. (lignes non vide, hein ;-)  )</li>
				<li>dont <?= array_sum($nbLigneComment)?> lignes de commentaire (soit <?php echo round(array_sum($nbLigneComment) / array_sum($nbLigneCode) * 100, 0) ;?>%).</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<div id="donutchart" style="width: 100%; height: 300px;"></div>
		</div>
	</div> 



	
    <!-- ******************************************************************************************* -->
	<h1>Version</h1>
	<p>Version courante de <i><?php echo Yii::$app->name;?></i> : <?= Yii::$app->version?>
	 



    <!-- ******************************************************************************************* -->
    <h1>Rêgles diverses</h1>
    <p>Afin d'assurer le bon fonctionnement du BackOffice de de <i><?php echo Yii::$app->name;?></i> certainnes rêgles ont été mise en place. Les voici :</p>
    <div class="row">
    	<div class="col-12">
    	<?php echo tocioRegles::widget(['regle' => 'all']);?>
    	</div>
    </div>
    
    
    <!-- ******************************************************************************************* -->
	<?php 
	/**
	 * Les lignes suivantes sont générées avec la commande :
		git log v1.3.2..HEAD --date=short --pretty=format:'<li>%ad <b>&bull;</b> %s</li>'
	 * 
	 */
	?>
	<h1>Modifications</h1>
	<h2>V 1.3.2</h2>
	<li>2021-11-16 <b>&bull;</b> Licences overview change</li>
	
	<h2>V 1.3.1</h2>
	<li>2021-09-07 <b>&bull;</b> Passage de la configuraiton du serveur SMTP dans le fichier config/params.php (qui n'est du coup plus versionné)</li>
<li>2021-09-07 <b>&bull;</b> Correction génération de code, le nom de la Grandeur apprait maintenant dans la légénde du graphique.</li>
<li>2021-09-01 <b>&bull;</b> Ajout du passage de variable pour initialiser un dataProvider dans la vue</li>
<li>2021-09-01 <b>&bull;</b> Ajout du controleur de generateur de code pour Grafana</li>
	
	<h2>V 1.3.0</h2>
	<li>2021-08-31 <b>&bull;</b> Générateur de code pour les graphiques Grafana</li>
	
	<h2>V 1.2.9</h2>
	<li>2021-06-30 <b>&bull;</b> Script d'import de fichier journaux locaux a partir du repertoire FTP</li>
<li>2021-06-29 <b>&bull;</b> Ajout d'une page pour visualiser les logs des imports automatique des fichiers CSV</li>
<li>2021-06-29 <b>&bull;</b> Script d'import de fichier journaux locaux a partir du repertoire FTP</li>
<li>2021-06-23 <b>&bull;</b> Upload d'un journal de mesure pour un ModuleID possible via la BAck Office</li>
	
	<h2>V 1.2.8</h2>
<li>2021-05-18 <b>&bull;</b> Les utilisateurs peuvent changer eux-même leur mot de passe</li>
<li>2021-04-30 <b>&bull;</b> Suppression de fichiers inutiles</li>
<li>2021-04-26 <b>&bull;</b> Utilisation du timezone défini dans le php.ini</li>
<li>2021-04-26 <b>&bull;</b> Fusion de la branche CSVFile</li>
<li>2021-04-26 <b>&bull;</b> Mis en place de l'export des données au format CSV</li>
<li>2021-04-14 <b>&bull;</b> Documentation de l'API pour pouvoir envoyer un fichier CSV</li>
<li>2021-04-14 <b>&bull;</b> Protection contre un champ 'file' vide.</li>
<li>2021-04-14 <b>&bull;</b> Upload d'un fichier par une méthode PUT via l'API</li>
<li>2021-06-23 <b>&bull;</b> Optimisation de l'insertion des données dans la base lorsqu'elles sont envoyées via un fichier CSV</li>
<li>2021-06-22 <b>&bull;</b> Changement de mode d'import de journaux CSV : Il est maintenant possible de ne plus formatter les données envoyées</li>
<li>2021-04-14 <b>&bull;</b> Documentation de l'API pour pouvoir envoyer un ficheir CSV</li>
<li>2021-04-14 <b>&bull;</b> Protection contre un champ 'file' vide.</li>

	
	<h2>V 1.2.7</h2>
	<li>2021-04-14 <b>&bull;</b> Documentation de l'API pour pouvoir envoyer un ficheir CSV</li>
<li>2021-04-14 <b>&bull;</b> Mise à jour du gitignore pour ne pas versionner le répertoire des uploads</li>
<li>2021-04-14 <b>&bull;</b> Ajout du parser pour pouvoir uploader un fichier via l'API en PUT</li>
<li>2021-04-14 <b>&bull;</b> On enregistre pas dans ElasticSearch s'il n'y a pas d'index de défini dans le fichier de configuration local config/(web_local.php)</li>
<li>2021-04-13 <b>&bull;</b> Missing namespace (Issue #8 https://github.com/UBO-Open-factory/TOCIO-Back-office/issues/8)</li>
<li>2021-04-08 <b>&bull;</b> Correction URL d'action dans le formulaire de la page site/export</li>
<li>2021-04-08 <b>&bull;</b> Mise à jour du fichier about</li>
<li>2021-04-08 <b>&bull;</b> Ajout de la prévisualisation des données avant l'export en CSV</li>
<li>2021-04-08 <b>&bull;</b> Simplification du formattage des colonnes lors de l'export CSV</li>
<li>2021-04-08 <b>&bull;</b> Filtrage de l'export sur un interval de temps en utilisant un date-picker</li>
<li>2021-04-08 <b>&bull;</b> Exportation d'un tableau croisé dynamique par identifiant réseau et par cumul de dates.</li>
<li>2021-04-07 <b>&bull;</b> Mise à jour de Yii en version 2.0.41.1</li>
<li>2021-04-07 <b>&bull;</b> Protection pour l'export CSV contre non cumul de valeur</li>
<li>2021-04-07 <b>&bull;</b> Export de Mesures au format CSV</li>
	
	<h2>V 1.2.7</h2>
	<li>2021-04-26 <b>&bull;</b>Mise à jour de Yii.</li>
<li>2021-04-26 <b>&bull;</b> Prise en compte du fuseau horaire pour le formattage des dates et de l'heure et affichage à la norme Française.</li>
<li>2021-04-26 <b>&bull;</b> Modification du footer</li>
<li>2021-04-14 <b>&bull;</b> Dump Mysl pour la structure de la BDD</li>
<li>2021-04-14 <b>&bull;</b> Mise à jour du fichier d'installation</li>
<li>2021-03-08 <b>&bull;</b> Mise à jour du Readme</li>
<li>2021-04-14 <b>&bull;</b> Documentation de l'API pour pouvoir envoyer un ficheir CSV</li>
<li>2021-04-14 <b>&bull;</b> Mise à jour du gitignore pour ne pas versionner le répertoire des uploads</li>
<li>2021-04-14 <b>&bull;</b> Ajout du parser pour pouvoir uploader un fichier via l'API en PUT</li>
<li>2021-04-14 <b>&bull;</b> On n'enregistre pas dans ElasticSearch s'il n'y a pas d'index de défini dans le fichier de configuration local config/(web_local.php)</li>
<li>2021-04-13 <b>&bull;</b> Missing namespace (Issue #8 https://github.com/UBO-Open-factory/TOCIO-Back-office/issues/8)</li>
<li>2021-04-08 <b>&bull;</b> Correction URL d'action dans le formulaire de la page site/export</li>
<li>2021-04-08 <b>&bull;</b> Ajout de la prévisualisation des données avant l'export en CSV</li>
<li>2021-04-08 <b>&bull;</b> Filtrage de l'export sur un interval de temps en utilisant un date-picker</li>
<li>2021-04-08 <b>&bull;</b> Exportation d'un tableau croisé dynamique par identifiant réseau et par cumul de dates.</li>
<li>2021-04-07 <b>&bull;</b> Mise à jour de Yii en version 2.0.41.1</li>
<li>2021-04-07 <b>&bull;</b> Un utilisateur anonyme peut faire un export CSV des données.</li>
	
	<h2>V 1.2.6</h2>
	<li>2021-03-04 <b>&bull;</b> Il est maintenant possible d'uploader un fichier journal de Mesures au format CSV.</li>
<li>2021-03-04 <b>&bull;</b> Suppression de fichiers innutiles.</li>
<li>2021-03-02 <b>&bull;</b> Bug 497 : L'affichage des timestamp dans les tables des Grandeurs affiche la bonne heure.</li>
	
	<h2>V 1.2.5</h2>
	<li>2021-02-12 <b>&bull;</b> Suppression du maximum dans la saisie d'un formattage d'une Grandeur (bug 491).</li>
<li>2021-02-12 <b>&bull;</b> Possibilité de supprimer une Grandeur si elle n'est utilisée par aucune donnée (bug 489).</li>
<li>2021-02-12 <b>&bull;</b> Lors de la génération du code Arduino et Python, il est maintenant possible d'utiliser un nom de Grandeur sans espace (bug 487).</li>
	
	<h2>V 1.2.4</h2>
	<li>2021-02-09 <b>&bull;</b> Affichage des tableaux de données avec l'ordre décroissant des dates d'enregistrement par défaut afin d'avoir la dernière valeur en début de page.</li>
<li>2021-02-09 <b>&bull;</b> Correction de bug : lors de la création d'un Module il faut affecter une Localisation.</li>
<li>2021-01-20 <b>&bull;</b> Creation d'un fichier de configuration locale pour éviter qu'il soit écrasé lors de mise a jour</li>
<li>2021-01-20 <b>&bull;</b> Nettoyage de fichiers suite à mise à jour de Yii</li>

	<h2>V 1.2.3</h2>
<li>2021-01-07 <b>&bull;</b> Mise à jour de Yii en version 2.0.40</li>
<li>2021-01-07 <b>&bull;</b> Corretion Bug 482 : Association d'un capteur impossible (en utilisant les boutons).</li>
	
	
	<h2>V 1.2.2</h2>
<li>2020-12-09 <b>&bull;</b> Mise à jour de Yii en version 2.0.39.3.</li>
<li>2020-12-09 <b>&bull;</b> Passage en version 1.2.2</li>
<li>2020-11-06 <b>&bull;</b> Correction Bug 475 : L'ordre d'ajout d'un Capteur dans un Module est conservé lorsque l'on modifie le nom d'un capteur.</li>
<li>2020-11-06 <b>&bull;</b> Correction Bug 476 : Redirection sur la page des modules après la modification d'un nom de Capteur.</li>
<li>2020-11-06 <b>&bull;</b> Correction Bug 747 : Le format du type (float) de la Grandeur ne permet pas d'insérer des valeur > 99.999</li>
<li>2020-11-06 <b>&bull;</b> Mise à jour de Yii</li>
<li>2020-11-06 <b>&bull;</b> Récupération des mises a jour faite sur le serveur de prod.</li>

	<h2>V 1.2.1</h2>
<li>2020-10-14 <b>&bull;</b> BUG 473 : L'ordre des capteurs dans la définition d'un Module est prise en compte lors de l'insertion d'une payload.</li>
	
	<h2>V 1.2.0</h2>
<li>2020-07-13 <b>&bull;</b> Affichage des graphiques des mesures sur la page d'accueil</li>
<li>2020-07-13 <b>&bull;</b> Affichage des valeurs de Grandeurs dans un graphique</li>
<li>2020-07-08 <b>&bull;</b> Utilisation de Apexcharts pour l'affichage des graphiques</li>
<li>2020-07-08 <b>&bull;</b> Mise à jour de YII2</li>
<li>2020-07-08 <b>&bull;</b> Modification de l'URL générale pour l'accès à swagger</li>
<li>2020-07-06 <b>&bull;</b> Générateur du code Python pour la Payload</li>
<li>2020-07-03 <b>&bull;</b> Implémentation de l'ingestion dans ElasticSearch</li>
<li>2020-07-03 <b>&bull;</b> Adaptation du code pour lire les formattages de Grandeur avec un point et non une virgule</li>
	
	<h2>V 1.1.3</h2>
<li>2020-07-03 <b>&bull;</b> Le formattage d'une Grandeur n'accèpte plus que les points (au lieux des virgules). Si le formattage ne contient pas de point, on le rajoute.</li>
<li>2020-07-03 <b>&bull;</b> Modification du fichier de description de l'API : Implémentation de la récupération d'un Capteur et de la liste des Capteurs</li>
<li>2020-06-22 <b>&bull;</b> Affichage du nom custom du Capteur dans les information de la Payload</li>
<li>2020-06-19 <b>&bull;</b> Modificaiton de l'URL de la requète AJAX de mise à jour de l'activation d'un Module</li>
	
	<h2>V 1.1.2</h2>
<li>2020-06-19 <b>&bull;</b> Correction Bug 466 : Affichage des coordonnées de la Localisation dans la liste déroulante des Localisation lors de la saisie d'un Module</li>
<li>2020-06-19 <b>&bull;</b> Changement des liens dans le pied de page (icone licence, dépot git)</li>
<li>2020-06-18 <b>&bull;</b> Correction Bug 454 : Sauvegarde de la saisi lors de la création d'un Module pour pouvoir la restaurer si on change de page</li>
<li>2020-06-18 <b>&bull;</b> Ajout d'un widget pour affichage des messages d'alerte envoyés par ->addFlash()</li>
<li>2020-06-18 <b>&bull;</b> Mise à jour de Yii (version 2.0.35)</li>
<li>2020-06-18 <b>&bull;</b> Correction Bug 463 : Modification de l'URL de redirection pour la requète AJAX de l'activation d'un Module</li>

	<h2>V 1.1.1</h2>
<li>2020-06-17 <b>&bull;</b> Activation / désactivation d'un module sans passer par l'affichage de son détail</li>
<li>2020-06-17 <b>&bull;</b> Modification du formattage des Grandeurs et renforcement du contrôle de saisie lors de la création d'une Granndeur</li>
<li>2020-05-18 <b>&bull;</b> Utilisation de SASS pour la copilation des SCSS.</li>
<li>2020-05-18 <b>&bull;</b> Eclaircissement de rêgles TOCIO.</li>
<li>2020-05-18 <b>&bull;</b> Affichage de la liste des rêgles de TOCIO.</li>
<li>2020-05-18 <b>&bull;</b> Correction URL pour la redirection après login successful</li>

	<h2>V 1.1.0</h2>
<li>2020-05-15 <b>&bull;</b> Suppression de la table utilisateurs_group et utilisation du système d'authentification Yii2.</li>
<li>2020-05-15 <b>&bull;</b> Correction des URLs pour les liens sur la page d'accueil et dans les formulaires de recherche.</li>
<li>2020-05-13 <b>&bull;</b> Possibilité de visualiser les données stockées dans les tables des mesures.</li>
<li>2020-05-13 <b>&bull;</b> Calcul du nombre de ligne développées dans le framework Yii</li>
<li>2020-05-13 <b>&bull;</b> Liste déroulante pour regrouper les outils de debug de TOCIO dans le menu</li>
<li>2020-05-13 <b>&bull;</b> Mise en place de la gestion des utilisateurs et des groupes</li>
<li>2020-05-11 <b>&bull;</b> Redirection vers la page d'accueil</li>
<li>2020-05-11 <b>&bull;</b> Modification de l'URL pour le retour sur le 'Home' lorsqu'on est derrière un proxy</li>
<li>2020-05-07 <b>&bull;</b> Gestion des Grandeurs : affichage du nombre de données dans la table des Mesures relative (et possibilité de supprimer la Grandeur si la table des Mesures correspondante est vide.</li>
<li>2020-05-06 <b>&bull;</b> Génération de la page 'About'</li>
<li>2020-05-05 <b>&bull;</b> Gestion du menu en fonction du groupe d'Utilisateur</li>
<li>2020-05-05 <b>&bull;</b> Ajout du tracking Matomoto</li>
<li>2020-05-05 <b>&bull;</b> Changement de la licence d'exploitation</li>
<li>2020-05-05 <b>&bull;</b> Authentification avec la tables des Utilisateurs</li>
<li>2020-05-02 <b>&bull;</b> Icon sur BugTracker</li>
<li>2020-05-02 <b>&bull;</b> Prise en compte de l'authentification pour l'affichage ou non des liens</li>
<li>2020-05-02 <b>&bull;</b> Habillage diverses</li>
<li>2020-05-02 <b>&bull;</b> Génération des varaibles pour le JavaScript</li>
<li>2020-05-02 <b>&bull;</b> Modificaiton de la génération des URLs</li>
<li>2020-05-02 <b>&bull;</b> Paramétrage du nom de l'application</li>
<li>2020-04-30 <b>&bull;</b> Mise à jour de Yii</li>
<li>2020-04-30 <b>&bull;</b> Mise à jour de la constuction des URLs dans le formulaires</li>
<li>2020-04-30 <b>&bull;</b> Affichage de la version courante de Yii</li>
<li>2020-04-30 <b>&bull;</b> Modification des action des forms pour prendre en compte le fait d'être derrière un proxy</li>
<li>2020-04-30 <b>&bull;</b> Utilisation du gestionnaire d'URL</li>
<li>2020-04-29 <b>&bull;</b> Mise en page est habillage CSS</li>
<li>2020-04-29 <b>&bull;</b> Correction du formattage des URLs d'accès à l'API</li>
	
	<h2>V 1.0.0</h2>
<li>2020-04-29 <b>&bull;</b> Mise à jour du footer avec une licence CC BY NC</li>
<li>2020-04-29 <b>&bull;</b> Mise à jour du Readme</li>
<li>2020-04-29 <b>&bull;</b> Problème avec les URLS</li>
<li>2020-04-29 <b>&bull;</b> Suppression de traces de debug JavaScript</li>
<li>2020-04-29 <b>&bull;</b> Passage des URLs de relative en absolus</li>
<li>2020-04-28 <b>&bull;</b> Problème de chemin dans la page des modules</li>
<li>2020-04-28 <b>&bull;</b> Page accueil pour mise ne prod</li>
<li>2020-04-28 <b>&bull;</b> Restructuration de la base</li>
<li>2020-04-28 <b>&bull;</b> Update CHANGELOG</li>
<li>2020-04-28 <b>&bull;</b> Add CHANGELOG</li>
<li>2020-04-28 <b>&bull;</b> Gestion des utilisateurs</li>
<li>2020-04-27 <b>&bull;</b> Gestion des redirections après les sauvegardes de modifications ou de créations</li>
<li>2020-04-27 <b>&bull;</b> Gestion des Localisations</li>
<li>2020-04-25 <b>&bull;</b> Décorations CSS</li>
<li>2020-04-24 <b>&bull;</b> Gestion des Grandeurs</li>
<li>2020-04-24 <b>&bull;</b> Formattage des libellés</li>
<li>2020-04-24 <b>&bull;</b> Gestion de la liste déroulante des Grandeurs pour la creation/maj d'un Capteur</li>
<li>2020-04-24 <b>&bull;</b> Fixe le menu des Capteurs sur le haut de la page lorsque l'on scroll</li>
<li>2020-04-23 <b>&bull;</b> Gestion des Capteurs</li>
<li>2020-04-23 <b>&bull;</b> Message disant que la payload n'est plus à jour lorsqu'on modifie lordre des capteurs</li>
<li>2020-04-23 <b>&bull;</b> Formattage du message d'alerte des boites TODO</li>
<li>2020-04-23 <b>&bull;</b> Affichage des Logs</li>
<li>2020-04-16 <b>&bull;</b> Edition d'un Capteur a partir de la page de la liste des Modules</li>
<li>2020-04-16 <b>&bull;</b> Merge branch 'master' of https://git.alex-design.fr/root/fablab.tocio.dataapi</li>
<li>2020-04-16 <b>&bull;</b> Delete .gitignore</li>
<li>2020-04-14 <b>&bull;</b> Modifications par double clique sur certain champ</li>
<li>2020-04-14 <b>&bull;</b> Chagement de l'odre des capteurs par drag'n drop. YEAH !</li>
<li>2020-04-10 <b>&bull;</b> Affichage des capteurs dans l'ordre de classement</li>
<li>2020-04-08 <b>&bull;</b> Mise à jour messages et règles TOCIO</li>
<li>2020-04-08 <b>&bull;</b> Affichage des logs</li>
<li>2020-04-08 <b>&bull;</b> Affichage des logs</li>
<li>2020-04-08 <b>&bull;</b> Prise en compte de l'activation ou non d'un module</li>
<li>2020-04-08 <b>&bull;</b> Affichage de la désactivation des Modules</li>
<li>2020-04-08 <b>&bull;</b> Gestion des Modules</li>
<li>2020-04-07 <b>&bull;</b> Ajout du dépliage/repliage des modules ou des capteurs</li>
<li>2020-04-06 <b>&bull;</b> Ajout d'un capteur dans un module</li>
<li>2020-04-06 <b>&bull;</b> Modificaiton de la strcture de la BDD</li>
<li>2020-04-03 <b>&bull;</b> Capteurs</li>
<li>2020-04-02 <b>&bull;</b> Ajout de valeurs provenant de Lora</li>
<li>2020-04-02 <b>&bull;</b> Ajout des Capteurs</li>
<li>2020-03-31 <b>&bull;</b> Merge branch 'master' of https://git.alex-design.fr/root/fablab.tocio.dataapi</li>
<li>2020-03-31 <b>&bull;</b> Modificaiton du gitgnore</li>
<li>2020-03-31 <b>&bull;</b> Affichage du tableau des modules en suivant les relations MySQL pour la liste des capateurs rattachés</li>
<li>2020-03-31 <b>&bull;</b> Update .gitignore</li>
<li>2020-03-30 <b>&bull;</b> Récupération de smesures d'un module</li>
<li>2020-03-30 <b>&bull;</b> Ajout des Grandeurs</li>
<li>2020-03-30 <b>&bull;</b> Ajout des Mesures</li>
<li>2020-03-30 <b>&bull;</b> Nettoyage fichiers</li>
<li>2020-03-30 <b>&bull;</b> Update APItocio.yaml</li>
<li>2020-03-30 <b>&bull;</b> Configuration de l'API</li>
<li>2020-03-30 <b>&bull;</b> Nettoyage fichiers innutiles</li>
<li>2020-03-30 <b>&bull;</b> Changement architecture</li>
<li>2020-03-30 <b>&bull;</b> Back up</li>
<li>2020-03-27 <b>&bull;</b> IHM</li>
<li>2020-03-27 <b>&bull;</b> Ajout de la redirection des tarces dans la base</li>
<li>2020-03-27 <b>&bull;</b> Découpage des trames reçues et insertion en base</li>
<li>2020-03-24 <b>&bull;</b> Formattage du code</li>
<li>2020-03-24 <b>&bull;</b> Modification de la table des module (suppression de l'ID et transformation de l'identifiant réseau en clé unique</li>
<li>2020-03-23 <b>&bull;</b> Back Office pour les Modules</li>
<li>2020-03-23 <b>&bull;</b> API pour les Grandeurs et les Capteurs</li>
<li>2020-03-22 <b>&bull;</b> Page accueil</li>
<li>2020-03-22 <b>&bull;</b> Ajout des grandeurs</li>
<li>2020-03-21 <b>&bull;</b> Suppression des tableGrandeurs</li>
<li>2020-03-21 <b>&bull;</b> Ajout des grandeurs</li>
<li>2020-03-21 <b>&bull;</b> Commit initial</li>


