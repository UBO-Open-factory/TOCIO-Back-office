<?php

namespace app\controllers;

use Yii;
use app\models\Module;
use app\models\Relmodulecapteur;
use app\models\Capteur;
use app\models\Relcapteurgrandeur;
use app\models\Grandeur;
use app\models\Cartes;
use app\models\Relcartesmethod;
use app\models\method;

use app\models\CapteurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\filters\AccessControl;

/**
 * GenerationController implements the SELECT request for module arduino code generation.
 * créer un tableau de capteur pour une association module - carte
 * chaque capteur est aussi un tableau composé de son nom , ses différentes méthodes et son tableau de grandeur
 * le tableau de grandeur est quand à lui composé des grandeurs , leurs noms , leurs format et les méthodes d'accès
 * à ces grandeurs pour ce capteur
 * @param int $idmodule
 * @param string $nomcarte
 * @return string[] tab
 * @version 28 mai 2021
 */
class GenerationController extends Controller
{
    public function actionGetdata($idModule,$nomcarte)
    {
    	// Le retour sera au format JSON
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = array();
        $data = array();

        //on récupère tout les capteurs associé au module
        $model_relmodulecapteur = Relmodulecapteur::find()
                        ->where(["idModule" => $idModule])
                        ->orderBy(['ordre'=>SORT_ASC ])
                        ->all();

        //on récupère l'id de la carte sélectionné 
                        $model_carte_id = cartes::find()
                        ->where(["nom" => $nomcarte])
                        ->one();

        //on récupère toutes les méthodes associé à cette carte
        $model_cartesmethod = relcartesmethod::find()
                        ->where(['id_carte' => $model_carte_id["id"]])
                        ->all();

        foreach($model_relmodulecapteur as $model_relmodulecapteur1)
        {
            //variable pour déterminer si il faut ou non ajouter du code (au cas ou aucune méthode n'ai été trouvé)
            $var_method_finded = 0;
            foreach($model_cartesmethod as $model_cartesmethod1)
            {
                //on récupère le nom de la méthode et on le compare au nom du capteur
                $model_method = method::find()->where(['id' => $model_cartesmethod1['id_method']])->one();
                if(explode(" ",$model_relmodulecapteur1['nomcapteur'])[0] === explode("_",$model_method['nom_method'])[0])
                {
                    //on met la variable de vérification à 1
                    $var_method_finded = 1;
                    $data = array();

                    $data["nom_capteur"] = explode(" ",$model_relmodulecapteur1['nomcapteur'])[0];
                    $data["method_include"] = $model_method["method_include"];
                    $data["method_declaration"] = $model_method["method_statement"];
                    $data["method_setup"] = $model_method["method_setup"];
                    $data["grandeur"] = array();
                    $i = 0;
                    //ajout de toutes les grandeurs associé à un capteur ainsi que leur format et méthode d'accès
                    foreach(Relcapteurgrandeur::find()->where(["idCapteur" => $model_relmodulecapteur1["idCapteur"]])->all() as $Grandeur_finded)
                    {
                        $l_STR_READ_METHOD = array();
                        $l_STR_READ_METHOD[0] = explode(" ",Grandeur::find()->where(["id"=>$Grandeur_finded["idGrandeur"]])->one()["nature"])[0];
                        $l_STR_READ_METHOD[1] = explode("|CutBalise|",$model_method['method_read'])[$i];
                        $l_STR_READ_METHOD[2] = Grandeur::find()->where(["id"=>$Grandeur_finded["idGrandeur"]])->one()["formatCapteur"];
                        array_push($data["grandeur"],$l_STR_READ_METHOD);
                        $i++;
                    }
                    array_push($model,$data);
                }
            }
            //boucle pour ajouter du code si aucune méthode n'a été trouvé pour ce capteur
            if($var_method_finded == 0)
            {
                $var_method_finded = 1;
                $data = array();

                $data["nom_capteur"] = explode(" ",$model_relmodulecapteur1['nomcapteur'])[0];
                $data["method_include"] = "//No method find for " . explode(" ",$model_relmodulecapteur1['nomcapteur'])[0];
                $data["method_declaration"] = "//No method find for " . explode(" ",$model_relmodulecapteur1['nomcapteur'])[0];
                $data["method_setup"] = "//No method find for " . explode(" ",$model_relmodulecapteur1['nomcapteur'])[0];
                $data["grandeur"] = array();
                //ajout de toutes les grandeurs associé à un capteur ainsi que leur format et commentaires aux emplacement des méthodes d'accès
                foreach(Relcapteurgrandeur::find()->where(["idCapteur" => $model_relmodulecapteur1["idCapteur"]])->all() as $Grandeur_finded)
                {
                    $l_STR_READ_METHOD = array();
                    $l_STR_READ_METHOD[0] = explode(" ",Grandeur::find()->where(["id"=>$Grandeur_finded["idGrandeur"]])->one()["nature"])[0];
                    $l_STR_READ_METHOD[1] = "//No method find for " . explode(" ",$model_relmodulecapteur1['nomcapteur'])[0];
                    $l_STR_READ_METHOD[2] = Grandeur::find()->where(["id"=>$Grandeur_finded["idGrandeur"]])->one()["formatCapteur"];
                    array_push($data["grandeur"],$l_STR_READ_METHOD);
                }
                array_push($model,$data);
            }
        }
        //$model[1]["grandeur"][1][0]
		return $model;
    }
}
