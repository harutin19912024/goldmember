<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "attribute".
 *
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property string $created_date
 * @property string $updated_date
 * @property integer $category_id
 *
 * @property Category $category
 * @property ProductAttribute[] $productAttributes
 */
class Attribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'category_id'], 'integer'],
            [['name', 'created_date', 'updated_date'], 'required'],
            [['created_date', 'updated_date'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'category_id' => Yii::t('app', 'Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['attribute_id' => 'id']);
    }
	
	/**
     * @inheritdoc
     */
    public static function findList(){
        $language = Yii::$app->language;
        $rows = (new \yii\db\Query())
            ->select(['attribute.id', 'attribute.name'])
            ->from('attribute')
            ->leftJoin('tr_attribute','attribute.id = tr_attribute.attribute_id')
            ->leftJoin('language','language.id = tr_attribute.language_id')
            ->where(['language.short_code' => $language])
            ->orderBy(['attribute.ordering'=>SORT_ASC])
            ->all();
        return $rows;
    }
    
    /* public static function getProductCountByBrand($brand_id){
        return BackProduct::find()->where(['brand_id'=>$brand_id])->count();
    } */
}
