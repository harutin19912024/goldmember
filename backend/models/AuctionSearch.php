<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Auction;

/**
 * AuctionSearch represents the model behind the search form of `backend\models\Auction`.
 */
class AuctionSearch extends Auction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'user_id'], 'integer'],
            [['start_date', 'end_date', 'video_link', 'lot_number', 'created_date', 'updated_at'], 'safe'],
            [['start_price'], 'number'],
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
        $query = Auction::find();

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
            'product_id' => $this->product_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_price' => $this->start_price,
            'user_id' => $this->user_id,
            'created_date' => $this->created_date,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'video_link', $this->video_link]);
        $query->andFilterWhere(['like', 'lot_number', $this->lot_number]);

        return $dataProvider;
    }
}
