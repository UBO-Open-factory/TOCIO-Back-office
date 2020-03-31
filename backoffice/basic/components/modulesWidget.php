<?php
namespace app\components;

use yii\base\Widget;

class modulesWidget extends Widget
{
	public $dataProvider;
	public $columns;
	
	
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::init()
	 */
	public function init()	{
		parent::init();
		
		$models = array_values($this->dataProvider->getModels());


// TRACE DE DEBUG DEBUT____________________________________________________________________________________
ini_set( 'html_errors' , 0 );
echo "<pre>";
echo str_repeat("_", 80)."\n";
printf('Fichier : %s, Ligne : %s',__FILE__,__LINE__); echo "\nContenu de  \$models : ";
var_dump($models);
echo str_repeat("_", 80)."\n";
echo "</pre>";
// TRACE DE DEBUG FIN _______________________________________________________________________________________ 



	}
	
	public function run()
	{
		//return Html::encode($this->message);
	}
}
?>