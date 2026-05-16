<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MetalPrices;

/**
 * MetalPricesSearch represents the model behind the search form of `backend\models\MetalPrices`.
 */
class MetalPricesSearch extends MetalPrices
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'metal_id', 'currency_id', 'karat'], 'integer'],
            [['sell_price', 'buy_price', 'original_buy_price', 'original_sell_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = MetalPrices::find();

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
            'metal_id' => $this->metal_id,
            'currency_id' => $this->currency_id,
            'karat' => $this->karat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
