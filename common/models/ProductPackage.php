<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_package".
 *
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property integer $product_id
 * @property string $art_num
 * @property integer $in_stock
 * @property integer $weight
 * @property double $price
 * @property string $create_date
 * @property string $update_date
 * @property string $default_package
 *
 * @property Product $product
 */
class ProductPackage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'product_id', 'art_num', 'weight'], 'required'],
            [['desc'], 'string'],
            [['product_id', 'in_stock', 'weight', 'default_package'], 'integer'],
            [['price'], 'number'],
            [['default_package'], 'default', 'value'=>0],
            [['create_date', 'update_date'], 'safe'],
            [['title', 'art_num'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Name',
            'desc' => 'Short Description',
            'product_id' => 'Product ID',
            'art_num' => 'Art. Num',
            'in_stock' => 'Status',
            'weight' => 'Weight',
            'price' => 'Price',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'default_package' => 'Default Package',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
