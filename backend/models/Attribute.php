<?php

namespace backend\models;

use Yii;
use backend\models\TrAttribute;
use backend\models\AttributeCategory;
use common\components\RuleHelper;
use yii\helpers\ArrayHelper;

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
 * @property TrAttribute[] $trAttributes
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
            [['type','parent_id','ordering','is_unity'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            //[['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'ordering' => Yii::t('app', 'Ordering'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
			'path' => Yii::t('app', 'Image'),
           // 'category_id' => Yii::t('app', 'Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasMany(AttributeCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAttributes()
    {
        return $this->hasMany(TrAttribute::className(), ['attribute_id' => 'id']);
    }
    public function updateDefaultTranslate(){
        $tr = TrAttribute::findOne(['language_id' => 1,'attribute_id'=>$this->id]);
        if(!$tr){
            $tr = new TrAttribute();
            $tr->setAttribute('language_id',1);
            $tr->setAttribute('attribute_id',$this->id);
        }
        $tr->setAttribute('name',$this->name);
        $tr->save();

        return true;
    }

	public function getParentAttributes(){
		$attributes = self::find()->where(['parent_id'=>null])->asArray()->all();
		return ArrayHelper::map($attributes, 'id', 'name');
	}

}
