<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Language;
use backend\models\SubCategory;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_descritpion
 * @property string $description
 * @property integer $ordering
 * @property string $created_date
 * @property string $updated_date
 *
 * @property Attribute[] $attributes
 * @property Product[] $products
 */
class Category extends \common\models\Category {

    /**
     * @inheritdoc
     */
    public static function findList() {
        $language = Yii::$app->language;
        
        
        // $categories = (new \yii\db\Query())
        //     ->select([
        //         'c.id AS category_id',
        //         'trc.name AS category_name',
        //         'p.id AS product_id',
        //         'trp.title AS product_name'
        //     ])
        //     ->from(['c' => 'category'])
        //     ->innerJoin(['trc' => 'tr_category'], 'trc.category_id = c.id')
        //     ->leftJoin(['p' => 'product'], 'p.category_id = c.id')
        //     ->leftJoin(['trp' => 'tr_product'], 'trp.product_id = p.id')
        //     ->leftJoin('language', 'language.id = trc.language_id')
        //     ->where(['language.short_code' => $language])
        //     ->all();
        
        
        $rows = (new \yii\db\Query())
                ->select(['category.id', 'category.parent_id', 'category.opened', 'tr_category.name', 'category.route_name'])
                ->from('category')
                ->leftJoin('tr_category', 'category.id = tr_category.category_id')
                ->leftJoin('language', 'language.id = tr_category.language_id')
                ->where(['language.short_code' => $language])
                ->orderBy(['category.ordering' => SORT_ASC])
                ->all();
        return $rows;
    }

    public static function findParentList() {
        $language = Yii::$app->language;
		$category = [];
		$categoryTree = [];
        $rows = (new \yii\db\Query())
                ->select(['category.id', 'category.name', 'category.path', 'category.opened', 'category.route_name'])
                ->from('category')
                // ->leftJoin('tr_category','category.id = tr_category.category_id')
                // ->leftJoin('language','language.id = tr_category.language_id')
                // ->where(['language.short_code' => $language])
                ->where(['category.opened' => 1])
                ->orderBy(['category.ordering' => SORT_ASC])
                ->all();		
		foreach($rows as $cat) {
			if(!SubCategory::find()->where(['category_id'=>$cat['id']])->count()) {
				$category[$cat['id']] = $cat['name'];
			}
		}	
        return $category;
    }

    public static function getCatRout($category_id) {
        $category = self::findOne($category_id);
        if ($category) {
            return $category->route_name;
        }
        return "";
    }

    public static function getCategoryRouteName($category_id) {
        $language = Language::find()->where(['short_code'=>Yii::$app->language])->one();
        $rows = (new \yii\db\Query())
                ->select(['tr_category.route_name as name'])
                ->from('category')
                ->leftJoin('tr_category', 'category.id = tr_category.category_id')
                ->leftJoin('language', 'language.id = tr_category.language_id')
                ->where(['tr_category.language_id' => $language->id])
                ->where(['category.id' => $category_id])
                ->one();
        return ucfirst($rows['name']);
    }
	
	public static function getCategoryName($category_id) {
        $language = Language::find()->where(['short_code'=>Yii::$app->language])->one();
        $rows = (new \yii\db\Query())
                ->select(['tr_category.name as name'])
                ->from('category')
                ->leftJoin('tr_category', 'category.id = tr_category.category_id')
                ->leftJoin('language', 'language.id = tr_category.language_id')
                ->where(['tr_category.language_id' => $language->id])
                ->where(['category.id' => $category_id])
                ->one();
        return $rows['name'];
    }
}
