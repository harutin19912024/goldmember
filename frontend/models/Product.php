<?php

namespace frontend\models;

use common\models\Language;
use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use common\models\Favorites;
use yii\data\ActiveDataProvider;
use backend\models\ProductsFilters;

//use backend\models\Category;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $short_description
 * @property integer $status
 * @property double $price
 * @property double $price_start
 * @property double $price_end
 * @property integer $category_id
 * @property string $created_date
 * @property string $updated_date
 * @property integer $brand_id
 * @property string $product_sku
 *
 * @property Brand $brand
 * @property Category $category
 * @property ProductAttribute[] $productAttributes
 * @property ProductParts[] $productParts
 */
class Product extends \common\models\Product {
    
    
    /**
     * Gets query for [[Model]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDamage()
    {
        return $this->hasOne(Damage::class, ['id' => 'damage_id']);
    }
    
     /**
     * Gets query for [[Model]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtColor()
    {
        return $this->hasOne(ExteriorColors::class, ['id' => 'exterior_color_id']);
    }
    
     /**
     * Gets query for [[Model]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEngine()
    {
        return $this->hasOne(Engines::class, ['id' => 'engine_id']);
    }

    public static function findList($filters, $params = null, $count = false) {
        $language = Yii::$app->language;
        $where = ['product.status'=>1];
        $query = (new Query());
        $query->select(['product.category_id as category_id', 'product.price as price', 'category.route_name as category_route_name', 'category.name as categoryName',
        'product_image.name as image', 'product.id', 'tr_product.title', 'product.route_name',
            'tr_product.short_description','tr_product.description']);
        $query->from('product');
        if (!empty($filters) && !empty($filters['id'])) {
            $where = array_merge($where, ['product.id' => $filters['id']]);
        }
        
        if (!empty($filters) && !empty($filters['best_offer'])) {
            $where = array_merge($where, ['product.best_offer' => $filters['best_offer']]);
        }
        
       

        $query->leftJoin('tr_product', 'product.id = tr_product.product_id');
        $query->leftJoin('product_image', 'product.id = product_image.product_id');
        $query->leftJoin('language', 'language.id = tr_product.language_id');
        $query->leftJoin('category', 'category.id = product.category_id');
        $query->where($where);
        if (!empty($filters) && !empty($filters['ids_attr'])) {
            $query->andWhere(['in', 'product.id', $filters['ids_attr']]);
        }
        if (!empty($filters) && !empty($filters['like'])) {
            $query->andFilterWhere([
                'or',
                    ['like', 'tr_product.name', $filters['like']],
                    ['like', 'product.name', $filters['like']],
            ]);
        }

        if (!empty($filters) && !empty($filters['category_ids'])) {
            $query->andWhere(['in', 'product.category_id', $filters['category_ids']]);
        }
        if (isset($filters['sort_by'])) {
            if ($filters['sort_by'] == 'SORT_ASC') {
                $query->orderBy(['product.price' => SORT_ASC]);
            } else {
                $query->orderBy(['product.price' => SORT_DESC]);
            }
        } else {
            $query->orderBy(['product.id' => SORT_DESC]);
        }
        if (isset($filters['limit'])) {
            $query->limit($filters['limit']);
        }
        if (isset($filters['offset'])) {
            $query->offset($filters['offset']);
        }
        $rows = $query->all();
        
        $arrData = self::makeArray($rows);
        //echo '<pre>';print_r($arrData);die;
        if ($count) {
            $arrData = count($arrData);
        }

        return $arrData;
    }

    public static function productsCount($filters) {
        $language = Yii::$app->language;
        $where = ['language.short_code' => $language, 'product_image.default_image_id' => '1'];
        $query = (new Query());
        $query->select(['product.id']);
        $query->from('product');
        if (!empty($filters) && !empty($filters['id'])) {
            $where = array_merge($where, ['product.id' => $filters['id']]);
        }

        $query->leftJoin('tr_product', 'product.id = tr_product.product_id');
        $query->leftJoin('product_image', 'product.id = product_image.product_id');
        $query->leftJoin('language', 'language.id = tr_product.language_id');
        if (!empty($filters) && !empty($filters['categories'])) {
            $query->leftJoin('category', 'category.id = product.category_id');
            $where['category.route_name'] = $filters['categories'];
        }
        $query->where($where);
        if (!empty($filters) && !empty($filters['price1']) && !empty($filters['price2'])) {
            $query->andWhere(['between', 'product.price', $filters['price1'], $filters['price2']]);
        }
        if (!empty($filters) && !empty($filters['commercial'])) {
            $query->andWhere(['product.commercial' => 1]);
        }
        if (!empty($filters) && !empty($filters['popular'])) {
            $query->andWhere(['product.popular' => 1]);
        }

        if (!empty($filters) && !empty($filters['cat_id'])) {
            $query->where(['product.category_id' => $filters['cat_id']]);
        }
        if (!empty($filters) && !empty($filters['ids_attr'])) {
            $query->andWhere(['in', 'product.id', $filters['ids_attr']]);
        }
        $query->orderBy(['product.ordering' => SORT_ASC]);
        $arrData = array();
        $rows = $query->all();

        //echo '<pre>';var_dump($rows);die;
        foreach ($rows as $key => $value) {
            if (!key_exists($value['id'], $arrData)) {
                $arrData[$value['id']] = $value;
            }
        }
        return $arrData;
    }

    public static function findProductInfo($product_id, $count = 1) {
        $language = Yii::$app->language;
        $where = ['language.short_code' => $language, 'product_image.default_image_id' => '1'];
        $query = (new Query());
        $query->select([ 'product.rate as rate', 'product.stock as stock', 'product.new_price as new_price', 'product.product_count as product_count', 'product.price as price', 'product_image.name as image', 'product.id', 'tr_product.name', 'product.route_name', 'tr_product.short_description', 'tr_product.description']);
        $query->from('product');
        $query->leftJoin('tr_product', 'product.id = tr_product.product_id');
        $query->leftJoin('product_image', 'product.id = product_image.product_id');
        $query->leftJoin('language', 'language.id = tr_product.language_id');
        $query->where(['product.id' => $product_id]);

        $query->orderBy(['product.ordering' => SORT_ASC]);
        $rows = $query->all();
        // $arrData = self::makeArray($rows);
        $arrPack = [];
        foreach ($rows as $key => $value) {
            $arrPack = array(
                'name' => $value['name'],
                'totalprice' => $count * $value['price'],
                'price' => $value['price'],
                'rate' => $value['rate'],
                'count' => $count,
            );
        }
        return $arrPack;
    }

    /**
     * @return array
     */
    public static function getShowInSliders() {
        $language = Yii::$app->language;
        $where = ['language.short_code' => $language, 'product_image.default_image_id' => '1', 'product.in_slider' => 1];
        $query = (new Query());
        $query->select(['product.sale as sale', 'product_image.name as image', 'product.rate as rate', 'product.category_id as category_id',
            'product.stock as stock', 'product.new_price as new_price', 'product.id', 'tr_product.name',
            'product.route_name', 'tr_product.short_description', 'tr_product.description', 'product.price']);
        $query->from('product');
        $query->leftJoin('tr_product', 'product.id = tr_product.product_id');
        $query->leftJoin('product_image', 'product.id = product_image.product_id');
        $query->leftJoin('language', 'language.id = tr_product.language_id');

        $query->where($where);
        $query->orderBy(['product.ordering' => SORT_ASC]);
        $rows = $query->all();

        return $rows;
    }

    public static function getSeria($seria_id = null) {
        $seria = Seria::findOne($seria_id);
        if (!empty($seria)) {
            return $seria->name;
        }
        return '';
    }

    public static function getBrend($brand_id = null) {
        $brand = Brand::findOne($brand_id);
        if ($brand) {
            return $brand->name;
        }
        return '';
    }

    public static function getCategoryInfo($category_id = null) {
        $catinfo = Category::findOne($category_id);
        if ($catinfo) {
            return $catinfo->name;
        }
        return '';
    }

    public static function getPrCountByCategory($filters) {
        $ids = array();
        $query = (new Query());
        $query->select(['product.id']);
        $query->from('product');
        if (!empty($filters) && !empty($filters['cat_id'])) {
            $where['product.category_id'] = $filters['cat_id'];
        }
        $query->where($where);
        $rows = $query->all();
        foreach ($rows as $items) {
            $ids[] = $items['id'];
        }

        return $ids;
    }

    public static function findBestSeller($filter) {
        $language = Yii::$app->language;
        $where = ['language.short_code' => $language, 'product_image.default_image_id' => '1'];
        $where = array_merge($where, $filter);
        $query = (new Query());
        $query->select(['product.rate as rate', 'product.category_id as category_id',
            'product.price as price',
            'product.product_count as product_count', 'product_image.name as image',
            'product.id', 'tr_product.name', 'product.route_name', 'tr_product.short_description',
            'tr_product.description']);
        $query->from('product');
        $query->leftJoin('tr_product', 'product.id = tr_product.product_id');
        $query->leftJoin('product_image', 'product.id = product_image.product_id');
        $query->leftJoin('language', 'language.id = tr_product.language_id');
        $query->where($where);
        $query->orderBy(['product.ordering' => SORT_ASC]);
        $rows = $query->all();
        $arrData = self::makeArray($rows);
        return $arrData;
    }

    public static function makeArray($rows) {
        $arrData = [];
        foreach ($rows as $row) {
            $arrData[$row['id']] = array(
                'category_route_name' => $row['category_route_name'],
                'categoryName' => $row['categoryName'],
                'name' => $row['title'],
                'price' => $row['price'],
                'sku' => isset($row['sku']) ? $row['sku'] : '',
                'image' => $row['image'],
                'category_id' => $row['category_id'],
                'id' => $row['id'],
                'route_name' => $row['route_name'],
                'short_description' => $row['short_description'],
                'description' => $row['description']
            );
        }

        return $arrData;
    }

    public static function getFavoritesByUser($user_id) {
        $favorite_product_ids = Favorites::find()->select(['product_id'])->where(['user_id' => $user_id])->asArray()->all();
        $favorite_product_ids = ArrayHelper::map($favorite_product_ids, 'product_id', 'product_id');
        $Products = array();
        if (!empty($favorite_product_ids)) {
            $Products = self::findList(['ids' => $favorite_product_ids]);
        }
        return $Products;
    }

    public static function getProductCountByInstallments() {
        return "0";
    }

    public static function getProductCountByGift() {
        return "0";
    }

    public static function getProductCountByDiscounts() {
        return "0";
    }
	
	
	/**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $showOnlyBrokerForbid = false) {	
	
		$language = Yii::$app->language;
		
		$query = (new Query());
        $query->select(['product.category_id as category_id',
            'product.product_sku as sku','product.status', 'product.product_count as product_count', 'product.price as price', 
            'product_image.name as image', 'product.id as id', 'tr_product.name', 'product.route_name', 
            'tr_product.short_description','tr_product.description','product.created_date', 'product.new as new', 'product.address as address']);
        $query->from('product');
		
		$query->leftJoin('tr_product', 'product.id = tr_product.product_id');
        $query->leftJoin('product_image', 'product.id = product_image.product_id');
        $query->leftJoin('language', 'language.id = tr_product.language_id');
		
		$query->where(['language.short_code' => $language,'product.is_allow_to_show'=>1,'product.status'=>1]);


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

        if(count($arrayOfAttr)) {
            if(!count($ids)) $ids = [1];

            $query->andFilterWhere(['in', 'product.id', $ids]);
        }


        if(isset($params['price-from']) && $params['price-from'] > 0) {
            $query->andFilterWhere(['>=', 'product.price', $params['price-from']]);
        }

        if(isset($params['price-to']) && $params['price-to'] > 0) {
            $query->andFilterWhere(['<=', 'product.price', $params['price-to']]);
        }



        if(isset($params['ProductAddress'])) {
            if(isset($params['ProductAddress']['state'])) {
                $state = States::findOne($params['ProductAddress']['state']);
                $query->andFilterWhere(['like', 'state', $state['name']]);
            }

            if(isset($params['ProductAddress']['city'])) {
                $city = Cities::find()->where(['in', 'id', $params['ProductAddress']['city']])->asArray()->all();

                $cities = [];
                foreach ($city as $k => $v) {
                    $cities[] = $v['name'];
                }

                if(count($city)) {
                    $query->andFilterWhere(['in', 'city', $cities]);
                }
            }

            if(isset($params['ProductAddress']['address']) && !empty($params['ProductAddress']['address'])) {
                $address = ProductAddress::find()->where(['in', 'address_id', $params['ProductAddress']['address']])->asArray()->all();

                $productIds = [];
                foreach ($address as $k => $v) {
                    $productIds[] = $v['product_id'];
                }
				
                if(count($productIds)) {
                    $query->andFilterWhere(['in', 'id', $productIds]);
                }
            }
        }

        if(isset($params['addr_1']) && !empty($params['addr_1'])) {
            $query->andFilterWhere(['=', 'addr_1', $params['addr_1']]);
        }

        if(isset($params['addr_2']) && !empty($params['addr_2'])) {
            $query->andFilterWhere(['=', 'addr_2', $params['addr_2']]);
        }

        if(isset($params['city']) && !empty($params['city'])) {
            $query->andFilterWhere(['like', 'city', $params['city']]);
        }

        if(isset($params['state']) && !empty($params['state'])) {
            $query->andFilterWhere(['like', 'state', $params['state']]);
        }

        if(isset($params['product_sku']) && !empty($params['product_sku'])) {
			$query->where(['product_sku'=> $params['product_sku']]);
        }

        if(isset($params['Product']) && !empty($params['Product']['category_id'])) {
            $query->andFilterWhere(['=', 'category_id', $params['Product']['category_id']]);
        }

        if(isset($params['Product']) && (!empty($params['Product']['sub_category']) || $params['Product']['sub_category'] === '0')) {
            $query->andFilterWhere(['=', 'sub_category', $params['Product']['sub_category']]);
        }
		

        
        $query->orderBy(['created_date' => SORT_DESC]);
		$rows = $query->all();
        $arrData = self::makeArray($rows);

        return $arrData;
		
       // echo $query->createCommand()->sql;die;
    }

}
