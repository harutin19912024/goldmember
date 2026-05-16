<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Product as BackProduct;
use yii\db\Query;

class Brand extends \common\models\Brand
{
    /**
     * @inheritdoc
     */
    public static function findList($brand_id = null,$filter = []){
        $language = Yii::$app->language;
		if(!is_null($brand_id)){
			$rows = (new \yii\db\Query())
            ->select(['brand.*'])
            ->from('brand')
            ->leftJoin('tr_brand','brand.id = tr_brand.brand_id')
            ->leftJoin('language','language.id = tr_brand.language_id')
            ->where(['language.short_code' => $language,'brand.id'=>$brand_id])
            ->orderBy(['brand.name'=>SORT_ASC])
            ->all();
		}else{
			$query = (new Query());
            $query->select(['brand.*']);
            $query->from('brand');
            $query->leftJoin('tr_brand','brand.id = tr_brand.brand_id');
            $query->leftJoin('language','language.id = tr_brand.language_id');
            $query->where(['language.short_code' => $language]);
			if(!empty($filter)){
				if($filter['letter'] == 'rus'){
					$query->andWhere(['brand.is_russian'=>1]);
				}else{
					$query->andWhere(['LIKE','brand.name' , $filter['letter'].'%',false]);
				}
				
			}
            $query->orderBy(['brand.name'=>SORT_ASC]);
            $rows = $query->all();
		}
        
        return $rows;
    }
    
    public static function getProductCountByBrand($brand_id,$count = false){
		if($count){
			return BackProduct::find()->where(['brand_id'=>$brand_id])->count();
		}else{
			return BackProduct::find()->where(['brand_id'=>$brand_id])->asArray()->all();
		}
    }
    
}