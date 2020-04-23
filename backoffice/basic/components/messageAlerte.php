<?php
namespace app\components;
use yii\base\Widget;

/**
 * 
 */


class messageAlerte extends Widget{
	public $message;
	public $type;
	private $TAB_Type = array('light','warning',"danger","success","info","primary","secondary", "todo");
	
	public function init(){
		parent::init();
		
		if( $this->message === null){
			$this->message = "";
			$this->type = "";
		} 
		
		// si le type ne fait pas partie des type prédéfini d'affichage dans Boostrap
		if (!in_array(strtolower($this->type), $this->TAB_Type)){
			$this->type = "";
		} else {
			$this->type = strtolower($this->type);
		}
	}
	
	/**
	 * Affichage du message.
	 * {@inheritDoc}
	 * @see \yii\base\Widget::run()
	 */
	public function run(){
		if( $this->message == "" or $this->type == "" ){
			return "";
		}
		switch( $this->type ) {
			case "secondary":
			case "primary":
			case "light":
				$l_STR_TitreBoite = "";
				break;
			case "todo":
				$this->type = "danger";
				$l_STR_TitreBoite = "A faire";
				break;
				
			default :
				$l_STR_TitreBoite = ucfirst($this->type);
		}
		
		
		return "<div class='alert alert-dismissible alert-".$this->type."'>
		  <button type='button' class='close' data-dismiss='alert'>&times;</button>
		  <h4 class='alert-heading'><i class='glyphicon glyphicon-comment'></i> ".$l_STR_TitreBoite."</h4>
		  <p class='mb-0'>".$this->message."</p>
		</div>";
	}
}
?>