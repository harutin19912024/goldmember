<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Language;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $short_description
 * @property integer $status
 * @property double $price
 * @property double $price_start
 * @property double $price_end
 * @property integer $category_id
 * @property string $created_date
 * @property string $updated_date
 * @property integer $brand_id
 * @property string $product_sku
 *
 * @property Brand $brand
 * @property Category $category
 * @property ProductAttribute[] $productAttributes
 * @property ProductImage[] $productImages
 */
class TrProduct extends ActiveRecord {

    const UPLOAD_MAX_COUNT = 10;

    public static $Extensions = ['jpg', 'png'];
    public $imageFiles;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tr_product';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['product_id', 'language_id'], 'integer'],
            [['title', 'short_description'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Product Title'),
            'short_description' => Yii::t('app', 'Short Description'),
            'description' => Yii::t('app', 'Description'),
            'product_id' => Yii::t('app', 'Product '),
            'language_id' => Yii::t('app', 'Language '),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
