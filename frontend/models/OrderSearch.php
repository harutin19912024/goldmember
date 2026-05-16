<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Order;

/**
 * OrderSearch represents the model behind the search form about `frontend\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'repairer_id', 'customer_address_id', 'status', 'service_id'], 'integer'],
            [['created_date', 'updated_date', 'accepted_date', 'problem'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
    public function search($params, $user_id, $role)
    {
        $query = Order::find();
        if($role == User::CUSTOMER){
            $query = $query->where(['customer_id'=>$user_id]);
        }elseif ($role == User::REPAIRER){
            $query = $query->where(['repairer_id'=>$user_id]);
        }

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
            'customer_id' => $this->customer_id,
            'repairer_id' => $this->repairer_id,
            'customer_address_id' => $this->customer_address_id,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'updated_date' => $this->updated_date,
            'accepted_date' => $this->accepted_date,
            'service_id' => $this->service_id,
        ]);

        $query->andFilterWhere(['like', 'problem', $this->problem]);

        return $dataProvider;
    }
}
