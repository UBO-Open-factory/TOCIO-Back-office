<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Module;

/**
 * ModuleSearch represents the model behind the search form of `app\models\Module`.
 */
class ModuleSearch extends Module
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identifiantReseau', 'nom', 'description'], 'safe'],
            [['idLocalisationModule', 'actif'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    // ---------------------------------------------------------------------------------------------
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Module::find();

        // add conditions that should always apply here

        // RELATION LOCALISATION MODULE POUR AFFICHER L ENOM DE LA LOCALISATION PLUTOT QUE SON ID
        // @see https://www.yiiframework.com/doc/guide/2.0/fr/output-data-widgets ( section "Travail avec des relations entre modèles" )
        $query->joinWith(['localisationModule AS localisationModule']);
        
        
        // POUVOIR TRIER SELON LA LOCALISATION DU MODULE EN TOUTE LETTRES (table locallisationModule)
        $dataProvider->sort->attributes['localisationModule.description'] = [
        		'asc' => ['localisationModule.description' => SORT_ASC],
        		'desc' => ['localisationModule.description' => SORT_DESC],
        ];
        
        
        
        // RELATION MODULE-CAPTEUR POUR AFFICHER LE NOM DES CAPTEURS LIÉS
        $query->joinWith(['relModulecapteur as relModulecapteur']);
        $query->joinWith(['idCapteurs as idCapteurs']);

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => ['pageSize' => 15,],
        	'sort' => ['defaultOrder' => ['nom' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idLocalisationModule' => $this->idLocalisationModule,
            'actif' => $this->actif,
        ]);

        $query->andFilterWhere(['like', 'identifiantReseau', $this->identifiantReseau])
            ->andFilterWhere(['like', 'nom', $this->nom])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
