<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tablemesure;

/**
 * TablemesureSearch represents the model behind the search form of `app\models\Tablemesure`.
 */
class TablemesureSearch extends Tablemesure
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'valeur', 'posX', 'posY', 'posZ'], 'integer'],
            [['timestamp', 'identifiantModule'], 'safe'],
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
        $query = Tablemesure::find();

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
            'timestamp' => $this->timestamp,
            'valeur' => $this->valeur,
            'posX' => $this->posX,
            'posY' => $this->posY,
            'posZ' => $this->posZ,
        ]);

        $query->andFilterWhere(['like', 'identifiantModule', $this->identifiantModule]);

        return $dataProvider;
    }
}
