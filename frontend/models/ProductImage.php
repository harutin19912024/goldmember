<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product_image".
 *
 * @property integer $id
 * @property string $name
 * @property integer $product_id
 * @property integer $default_image_id
 * @property string $created_date
 * @property string $updated_date
 * @property integer $type
 */
class ProductImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'created_date', 'updated_date'], 'required'],
            [['product_id', 'default_image_id', 'type'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'product_id' => Yii::t('app', 'Product ID'),
            'default_image_id' => Yii::t('app', 'Default Image ID'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'type' => Yii::t('app', 'Type'),
        ];
    }
    /**
     * @param $productId
     * @return array
     */
    public static function getImagesByProductId($productId)
    {
        $data = self::find()->where(['product_id' => $productId, 'type'=>1])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'name');
    }

    /**
     * @param $productId
     * @return array
     */
    public static function getDefaultImageByProductId($productId)
    {
        $data = self::find()->where(['product_id' => $productId, 'type'=>1, 'default_image_id'=>1])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'name');
    }
    /**
     * @param $productId
     * @return array
     */
    public static function getImagesByPartId($productId)
    {
        $data = self::find()->where(['product_id' => $productId, 'type'=>0])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'name');
    }

    /**
     * @param $productId
     * @return array
     */
    public static function getDefaultImageByPartId($productId)
    {
        $data = self::find()->where(['product_id' => $productId, 'type'=>0, 'default_image_id'=>1])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'name');
    }
}
