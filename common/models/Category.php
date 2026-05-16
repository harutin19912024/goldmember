<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

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
 * @property TrCategory[] $trCategories
 */
class Category extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['description','route_name'], 'string'],
            [['ordering'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['name', 'short_description','route_name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'short_description' => Yii::t('app', 'Short Descritpion'),
            'description' => Yii::t('app', 'Description'),
            'ordering' => Yii::t('app', 'Ordering'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'route_name'=>Yii::t('app', 'Name In route'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributes($names = NULL, $except = Array()) {
        return $this->hasMany(Attribute::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts() {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrCategories() {
        return $this->hasMany(TrCategory::className(), ['category_id' => 'id']);
    }

    /**
     * list of categories
     * @return array
     */
    public function getAllCategories() {
        $Categories = Category::find()->all();
        return ArrayHelper::map($Categories, 'id', 'name');
    }

    /**
     * @param $AllData
     * @return int
     */
    public function bachUpdate($AllData) {
        $updateQuery = "UPDATE `category` SET ";
        $subUpdateOrderingQuery = '`ordering` = CASE `id` ';
        foreach ($AllData as $item => $data) {
            $subUpdateOrderingQuery .=' WHEN ' . $data['id'] . ' THEN ' . "'{$data['ordering']}'";
        }
        $updateQuery.=$subUpdateOrderingQuery . ' END';
        return self::getDb()->createCommand($updateQuery)->execute();
    }

    public function getTranslated($field){
        $language = Yii::$app->language;
       if($language=='en'){
           return $this->$field;
       }else{

       }
    }

}
