<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Method;

/**
 * MethodSearch represents the model behind the search form of `app\models\Method`.
 */
class MethodSearch extends Method
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_capteur', 'id_carte'], 'integer'],
            [['nom_method', 'method_include', 'method_statement', 'method_setup', 'method_read'], 'safe'],
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
        $query = Method::find();

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
            'id_capteur' => $this->id_capteur,
            'id_carte' => $this->id_carte,
        ]);

        $query->andFilterWhere(['like', 'nom_method', $this->nom_method])
            ->andFilterWhere(['like', 'method_include', $this->method_include])
            ->andFilterWhere(['like', 'method_statement', $this->method_statement])
            ->andFilterWhere(['like', 'method_setup', $this->method_setup])
            ->andFilterWhere(['like', 'method_read', $this->method_read]);

        return $dataProvider;
    }
}
