<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "connected_products".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $conn_product_id
 */
class ConnectedProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'connected_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'conn_product_id'], 'required'],
            [['product_id', 'conn_product_id'], 'integer'],
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
            'conn_product_id' => Yii::t('app', 'Conn Product ID'),
        ];
    }
}
