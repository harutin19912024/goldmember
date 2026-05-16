<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Product;
use backend\models\ProductsFilters;
use common\models\Language;

/**
 * ProductSearch represents the model behind the search form about `backend\models\Product`.
 */
class ProductSearch extends Product {

    public $category;
    public $brand;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'status', 'category_id', 'rateing', 'price'], 'integer'],
            [['name', 'description', 'short_description', 'created_date', 'updated_date', 'product_sku'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params, $showOnlyBrokerForbid = false) {		
		 if($showOnlyBrokerForbid) {
			$query = Product::find()->where(['forbid'=>1]);
		} else {
			$query = Product::find();
		} 

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['product_sku', 'price']]
        ]);


        $this->load($params);


        $arrayOfAttr = [];

        if((isset($params['size-from']) || isset($params['size-to'])) && (!empty($params['size-from']) || !empty($params['size-to']))) {
            $arrayOfAttr[] = 3;
        }

        if(isset($params['Attribute']['name']) && !empty($params['Attribute']['name'])) {
            $arrayOfAttr[] = 14;
        }

        if(isset($params['Attribute']['category_id']) && !empty($params['Attribute']['category_id'])) {
            $arrayOfAttr[] = 1;
        }

        if(isset($params['Attribute']['path']) && !empty($params['Attribute']['path'])) {
            $arrayOfAttr[] = 2;
        }

        if((isset($params['floor-from']) || isset($params['floor-to'])) && (!empty($params['floor-from']) || !empty($params['floor-to']))) {
            $arrayOfAttr[] = 4;
        }

        if((isset($params['land-size-from']) || isset($params['land-size-to'])) && (!empty($params['land-size-from']) || !empty($params['land-size-to']))) {
            $arrayOfAttr[] = 40;
        }

        $ids = [];

        if(count($arrayOfAttr)) {
            $productFilters = ProductsFilters::find()->where(['in', 'attribute_id', $arrayOfAttr])->asArray()->all();

            $groupedFilters = [];
            foreach ($productFilters as $k => $v) {
                if(!isset($groupedFilters[$v['product_id']])) $groupedFilters[$v['product_id']] = [];

                $val = !$v['filter_id'] ? $v['value'] : $v['filter_id'];

                $groupedFilters[$v['product_id']][$v['attribute_id']] = $val;
            }
            foreach ($groupedFilters as $k => $v) {
                
                if(isset($params['size-from']) && !empty($params['size-from'])) {
                    if(!isset($v[3]) || $v[3] < $params['size-from']) continue;
                }

                if(isset($params['size-to']) && !empty($params['size-to'])) {
                    if(!isset($v[3]) || $v[3] > $params['size-to']) continue;
                }

                if(isset($params['land-size-from']) && !empty($params['land-size-from'])) {
                    if(!isset($v[40]) || $v[40] < $params['land-size-from']) continue;
                }

                if(isset($params['land-size-to']) && !empty($params['land-size-to'])) {
                    if(!isset($v[40]) || $v[40] > $params['land-size-to']) continue;
                }

                if(isset($params['Attribute']['name']) && !empty($params['Attribute']['name'])) {
                    if(!isset($v[14])) {
                        continue;
                    }

                    $found = false;
                    foreach ($params['Attribute']['name'] as $key => $attr) {
                        if($v[14] == $attr) {
                            $found = true;
                        }
                    }

                    if(!$found) {
                        continue;
                    }
                }

                if(isset($params['Attribute']['category_id']) && !empty($params['Attribute']['category_id'])) {
                    if(!isset($v[1])) {
                        continue;
                    }

                    $found = false;
                    foreach ($params['Attribute']['category_id'] as $key => $attr) {
                        if($v[1] == $attr) {
                            $found = true;
                        }
                    }

                    if(!$found) {
                        continue;
                    }
                }

                if(isset($params['Attribute']['path']) && !empty($params['Attribute']['path'])) {
                    if(!isset($v[2])) {
                        continue;
                    }

                    $found = false;
                    foreach ($params['Attribute']['path'] as $key => $attr) {
                        if($v[2] == $attr) {
                            $found = true;
                        }
                    }

                    if(!$found) {
                        continue;
                    }
                }

                if(isset($params['floor-from']) && !empty($params['floor-from'])) {
                    if(!isset($v[4]) || $v[4] < $params['floor-from']) continue;
                }

                if(isset($params['floor-to']) && !empty($params['floor-to'])) {
                    if(!isset($v[4]) || $v[4] > $params['floor-to']) continue;
                }
                $ids[] = $k;
            }

        }


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(count($arrayOfAttr)) {
            if(!count($ids)) $ids = [1];

            $query->andFilterWhere(['in', 'id', $ids]);
        }

         if(isset($params['product_sku']) && !empty($params['product_sku'])) {
            //$query->andFilterWhere(['like', 'product_sku', '%'.$params['product_sku']]);
			//$query->where(['product_sku'=> $params['product_sku']]);
			$query->where(['like', 'product_sku', '%'.$params['product_sku'] , false]);
        } else {


        if(isset($params['price-from']) && $params['price-from'] > 0) {
            $query->andFilterWhere(['>=', 'price', $params['price-from']]);
        }

        if(isset($params['price-to']) && $params['price-to'] > 0) {
            $query->andFilterWhere(['<=', 'price', $params['price-to']]);
        }
       
        if(isset($params['Product']) && !empty($params['Product']['category_id'])) {
            $query->andFilterWhere(['=', 'category_id', $params['Product']['category_id']]);
        }

        if(isset($params['Product']) && (!empty($params['Product']['sub_category']) || $params['Product']['sub_category'] === '0')) {
            $query->andFilterWhere(['=', 'sub_category', $params['Product']['sub_category']]);
        }
		
        if(isset($params['Product']) && (!empty($params['Product']['status']) || $params['Product']['status'] === '0')) {
            $query->andFilterWhere(['=', 'status', (int)$params['Product']['status']]);
        }
        
        
        if(isset($params['sort']) && ( $params['sort'] == 'price' || $params['sort'] == '-price')) {
            $query->orderBy(['price'=>($params['sort'] == 'price') ? SORT_DESC : SORT_ASC]);
        } else {
            $query->orderBy(['created_date' => SORT_DESC]);
        }
        
}

        return $dataProvider;
    }

}
