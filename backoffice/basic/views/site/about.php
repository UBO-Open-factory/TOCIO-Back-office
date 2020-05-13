<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\messageAlerte;
use app\components\tocioRegles;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;

$l_TAB_Directory 	= ['assets', 'components', 'controllers', 'mail', 'models', 'views' ];
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












    <div class="jumbotron">
        <h1><?php echo Yii::$app->name;?> </h1>
        <p class="lead">
        	<?= Yii::$app->version?><br/>
        	<?php echo tocioRegles::widget(['regle' => 'tociodefinition']);?>
        </p>
    </div>
    
    
    <!-- ******************************************************************************************* -->
	<h1>Nombre de ligne de code</h1>
	Ce site est écrit en PHP à l'aide du Framework Yii v<?= Yii::getVersion()?>. En plus des fichiers
	propre au Framework, il nécessite :  
	<ul>
		<li><?= count($nbLigneCode)?> fichiers</li>
		<li><?= array_sum($nbLigneCode)?> lignes de code. (lignes non vide, hein ;-)  )</li>
		<li>dont <?= array_sum($nbLigneComment)?> lignes de commentaire (soit <?php echo round(array_sum($nbLigneComment) / array_sum($nbLigneCode) * 100, 0) ;?>%).</li>
	</ul>



	
    <!-- ******************************************************************************************* -->
	<h1>Version</h1>
	<p>Version courante de <i><?php echo Yii::$app->name;?></i> : <?= Yii::$app->version?>
	 



    <!-- ******************************************************************************************* -->
	<?php 
	/**
	 * Les lignes suivantes sont générées avec la commande :
		git log v1.1..HEAD --date=short --pretty=format:'<li>%ad <b>&bull;</b> %s</li>' >> views/site/about.php
	 * 
	 */
	?>
	<h1>Modifications</h1>
	<h2>V 1.1.0</h2>
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
<li>2020-05-02 <b>&bull;</b> Icon sur mon BugTracker</li>
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
<li>2020-03-30 <b>&bull;</b> COnfiguraiton de l'API</li>
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


