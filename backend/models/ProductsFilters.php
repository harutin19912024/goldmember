<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "products_filters".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $filter_id
 * @property string $value
 */
class ProductsFilters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_filters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'filter_id','attribute_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'filter_id' => Yii::t('app', 'Filter ID'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
}
