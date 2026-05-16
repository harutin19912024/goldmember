<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\TrCategory;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_descritpion
 * @property string $description
 * @property integer $ordering
 * @property integer $route_name
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
                [['name', 'route_name'], 'required'],
                [['description', 'route_name'], 'string'],
                [['ordering', 'parent_id', 'opened'], 'integer'],
                [['created_date', 'updated_date'], 'safe'],
                [['name', 'short_description', 'route_name'], 'string', 'max' => 250],
                [['name', 'route_name'], 'filter', 'filter' => "trim"],
                [['name', 'route_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'short_description' => Yii::t('app', 'Short Description'),
            'description' => Yii::t('app', 'Description'),
            'ordering' => Yii::t('app', 'Ordering'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'route_name' => Yii::t('app', 'Route Name'),
            'parent_id' => Yii::t('app', 'Parent'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation($languageId)
    {
        return $this->hasOne(TrCategory::className(), ['category_id' => 'id'])
        ->andOnCondition(['language_id' => $languageId]);;
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
            $subUpdateOrderingQuery .= ' WHEN ' . $data['id'] . ' THEN ' . "'{$data['ordering']}'";
        }
        $updateQuery .= $subUpdateOrderingQuery . ' END';
        return self::getDb()->createCommand($updateQuery)->execute();
    }

    public function getTranslated($field) {
        $language = Yii::$app->language;
        if ($language == 'en') {
            return $this->$field;
        } else {
            
        }
    }

    public function updateDefaultTranslate($defaultLanguage) {
        $tr = TrCategory::findOne(['language_id' => $defaultLanguage, 'category_id' => $this->id]);
        if (!$tr) {
            $tr = new TrCategory();
        }
        $tr->setAttribute('name', $this->name);
        $tr->setAttribute('description', $this->description);
        $tr->setAttribute('short_description', $this->short_description);
        $tr->setAttribute('language_id', $defaultLanguage);
        $tr->setAttribute('category_id', $this->id);
        $tr->save();
        return true;
    }

}
