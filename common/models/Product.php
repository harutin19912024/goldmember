<?php

namespace common\models;

use Yii;
use common\models\Brand;
use common\models\Category;
use common\models\ProductPackage;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use backend\models\ProductImage;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $ordering
 *
 * @property ProductAttribute[] $productAttributes
 * @property ProductParts[] $productParts
 * @property TrProduct[] $trProducts
 */
class Product extends \yii\db\ActiveRecord
{

    const UPLOAD_MAX_COUNT = 10;

    public static $Extensions = ['jpg', 'png'];
    public $imageFiles;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'category_id', 'route_name'], 'required'],
            [['description', 'route_name'], 'string'],
            [['status', 'category_id'], 'integer'],
            [['price'], 'number'],
            [['created_date', 'updated_date'], 'safe'],
            [['name', 'short_description', 'product_sku', 'route_name'], 'string', 'max' => 250],
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
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'short_description' => Yii::t('app', 'Short Description'),
            'status' => Yii::t('app', 'Status'),
            'price' => Yii::t('app', 'Price'),
            'category_id' => Yii::t('app', 'Category ID'),
            'rate' => Yii::t('app', 'Rate'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'brand_id' => Yii::t('app', 'Brand ID'),
            'product_sku' => Yii::t('app', 'Product Sku'),
            'route_name' => Yii::t('app', 'Name In route'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
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
        return $this->hasMany(ProductAttribute::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductParts()
    {
        return $this->hasMany(ProductParts::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrProducts()
    {
        return $this->hasMany(Product::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id'])->where(['type' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPackage()
    {
        return $this->hasMany(ProductPackage::className(), ['product_id' => 'id']);
    }

    /**
     * list of categories
     * @return array
     */
    public function getAllCategories()
    {

        $Categories = Category::find()->where([])->all();
        return ArrayHelper::map($Categories, 'id', 'name');
    }

    public function getAllBrands()
    {
        $Brands = Brand::find()->all();
        return ArrayHelper::map($Brands, 'id', 'name');
    }
    
    /**
     * @param $product_id
     * @return array
     */
    public function getDefaultImage($product_id)
    {
        $result = ProductImage::find()->where(['product_id' => $product_id, 'default_image_id' => 1, 'type' => 1])->asArray()->all();
//        var_dump(ArrayHelper::map($result,'default_image_id','name'));die;
        return ArrayHelper::map($result, 'default_image_id', 'name');
    }

    public static function getImages($product_id)
    {
        $images = ProductImage::find()->where(['product_id' => $product_id, 'type' => 1])->asArray()->all();
        return ArrayHelper::map($images, 'id', 'name');
    }

    public function DeleteData($id)
    {
        $ProdImages = $this->getImages($id);
        foreach ($ProdImages as $item => $prodImage) {
            ProductImage::findOne($item)->delete();
            if (file_exists(Yii::$app->basePath . '/web/' . $prodImage)) {
                unlink(Yii::$app->basePath . '/web/' . $prodImage);
            }
        }
        $Parts = new ProductParts();
        $ProductParts = $Parts->getProductParts($id);
        $PartIds = ArrayHelper::map($ProductParts, 'id', 'id');
        foreach ($PartIds as $partId) {
            $PartImages = ArrayHelper::map($Parts::getImageByPartId($partId), 'id', 'name');
            foreach ($PartImages as $image => $partImage) {
                ProductImage::findOne($image)->delete();
                if (file_exists(Yii::$app->basePath . '/web/' . $partImage)) {
                    unlink(Yii::$app->basePath . '/web/' . $partImage);
                }
            }
            $Parts::findOne($partId)->delete();
        }
        return $this->delete();
    }

    public static function getImagesToFront($product_id, $class = '')
    {
        $params = [
            'class' => 'img-responsive ' . $class,
            'alt' => '',
        ];
        $images = ProductImage::find()->where(['product_id' => $product_id, 'type' => 1, 'default_image_id' => 1])->asArray()->all();
        $dotParts = "";
        if (!empty($images)) {
            $dotParts = explode('.', $images[0]['name']);
            if ($class == 'image-zoom') {
                $params['data-zoom-image'] = Yii::$app->params['adminUrl'] . '/uploads/images/' . $images[0]['name'];
            }
            return Html::img(Yii::$app->params['adminUrl'] . '/uploads/images/' . $images[0]['name'], $params);
        } else {
            return Html::img(Yii::$app->params['adminUrl'] . '/img/default.png');
        }

        if (!isset($dotParts[count($dotParts) - 1])) {

        } else {

        }
    }

    public function updateDefaultTranslate()
    {
        $tr = TrProduct::findOne(['language_id' => 1, 'product_id' => $this->id]);

        if (!$tr) {
            $tr = new TrProduct();
            $tr->setAttribute('language_id', 1);
            $tr->setAttribute('product_id', $this->id);
        }
        $tr->setAttribute('name', $this->name);
        $tr->setAttribute('short_description', $this->short_description);
        $tr->setAttribute('description', $this->description);
        $tr->save();

        return true;
    }

    public function isFavorite($user_id)
    {
        $isFavorite = Favorites::findOne(['user_id' => $user_id, 'product_id' => $this->id]);
        if ($isFavorite) {
            return true;
        }

        return false;
    }
    
}
