<?php
use yii\helpers\Html;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\Url;
use yii\grid\GridView;


$this->title = 'Grandeurs';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Générateur de code pour la table des mesures <?= Html::encode($tablename) ?></h1>

Code JSON pour l'intégration de la table de mesure : <?php echo $tablename?> ( <?php echo $title;?> ) dans Grafana.
<div class="code">
<pre>
<?php echo $code;?>
</pre>
</div>

<h2>Intégration dans Grafana</h2>
<p>Dans un dashboard existant:</p>
<ul>
<li>Afficher le dashboard : Menu de droite > Dashboards > <b>Manage</b></li>
<li>Sélectionner le dashboard</li>
<li>Menu haut à droite > <b>Icone Setting (engrenage)</b></li>
<li>Menu de droite : <b>JSON Model</b></li>
<li>Editer la section JSON nommée <i>pannels</i> en lui rajoutant le code généré ci-dessus.</li>
<li>Enregistrer les modifications.</li>
</ul> 


<h2>Générer le code JSON pour la table :</h2>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'nature',
            'formatCapteur',
            'type',
            'tablename',
        	['attribute' => 'NbDataTable',
        				'format' => 'html',
        				'label' => "Nb données stockées",
        				'value' => function($model){
				        				return $model->NbDataTable;
				        			}
        	],
        	['attribute' => 'Grafana JSON',
			'format' => 'html',
        	'label' => "",
        	'value' => function($model){
        					return '<a href="'.Url::toRoute("/codegenerator/grafana?id=".$model->id).'" title="Grafana : pannel\'s JSON definition"><i class="glyphicon glyphicon-export"></i></a>';
				        		}
        	],
        	['attribute' => 'Graphique',
			'format' => 'html',
        	'label' => "",
        	'value' => function($model){
        					return '<a href="'.Url::toRoute("/grandeur/graphique?id=".$model->id).'" title="Voir les données sous forme de graphique)"><i class="glyphicon glyphicon-stats"></i></a>';
				        		}
        	],
        	['attribute' => 'Detail',
        	'format' => 'html',
        	'label' => "",
        	'value' => function($model){
        	return '<a href="'.Url::toRoute("/grandeur/view?id=".$model->id."&sort=-timestamp").'" title="Voir les données sous forme de tableau)"><i class="glyphicon glyphicon-list"></i></a>';
				        			}
        	],
			['class' => 'yii\grid\ActionColumn',
        	'visibleButtons' => [
		      		'view' => false,
		        	'update' => false,
        			'delete' => function($model){
        							# if we got no value stored in the related table of data, we 
        							# display the delete button. 
			        				return $model->NbDataTable == 0;
			        			}],
        	],
        ],
    ]); ?>