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
            [['id', 'idLocalisationModule', 'actif'], 'integer'],
            [['idCapteur', 'identifiantReseau', 'description', 'positionCapteur'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Module::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'idLocalisationModule' => $this->idLocalisationModule,
            'actif' => $this->actif,
        ]);

        $query->andFilterWhere(['like', 'idCapteur', $this->idCapteur])
            ->andFilterWhere(['like', 'identifiantReseau', $this->identifiantReseau])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'positionCapteur', $this->positionCapteur]);

        return $dataProvider;
    }
}
