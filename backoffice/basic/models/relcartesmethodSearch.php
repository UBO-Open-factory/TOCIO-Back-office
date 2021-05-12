<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\relcartesmethod;

/**
 * relcartesmethodSearch represents the model behind the search form of `app\models\relcartesmethod`.
 */
class relcartesmethodSearch extends relcartesmethod
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_carte', 'id_method'], 'integer'],
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
        $query = relcartesmethod::find();

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
            'id_carte' => $this->id_carte,
            'id_method' => $this->id_method,
        ]);

        return $dataProvider;
    }
}
