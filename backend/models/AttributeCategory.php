<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "attribute_category".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $attribute_id
 */
class AttributeCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attribute_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'attribute_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
        ];
    }
	
	/**
     * @param $data
     * @param $product_id
     * @return int|bool
     */
    public function batchInsert($data, $attribute_id)
    {
        $rows = [];
        $columns = [
            'attribute_id',
            'category_id'

        ];
        foreach ($data['category_id'] as $item => $value) {
            $rows[] = [
                'attribute_id' => $attribute_id,
                'category_id' => $value
            ];
        }

        $result = Yii::$app->db->createCommand()->batchInsert(self::tableName(), $columns, $rows)->execute();
        return $result;
    }
	
	 public function saveData($data, $attribute_id)
    {

        $categories = self::find()->where(['attribute_id' => $attribute_id])->asArray()->all();

        if(empty($categories)){
            return  $this->batchInsert($data, $attribute_id);
        }else{
            self::getDb()->createCommand()->
            delete(self::tableName(), ['attribute_id' => $attribute_id])
                ->execute();
            return $this->batchInsert($data, $attribute_id);
        }
    }
}
