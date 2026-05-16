<?php

namespace backend\models;

use Yii;
use backend\models\ProductsDetails;
use backend\models\Category;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string|null $title
 * @property float|null $price
 * @property string|null $fineness
 * @property string|null $weight
 * @property string|null $color
 * @property string|null $state
 * @property string|null $country
 * @property int|null $material_id
 * @property string|null $gemstone
 * @property string|null $description
 * @property string $short_description
 * @property int $category_id
 * @property int|null $rateing
 * @property string|null $product_sku
 * @property int|null $ordering
 * @property string|null $route_name
 * @property int|null $popular
 * @property int|null $best_offer
 * @property float|null $another_price
 * @property int|null $status
 * @property string $created_date
 * @property string|null $updated_date
 *
 * @property Material $material
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
        // return [
        //     [['title', 'short_description', 'category_id'], 'required'],
        //     [['description', 'fineness', 'weight', 'color', 'state', 'country','material', 'gemstone', 'product_sku'], 'string'],
        //     [['status', 'category_id','ordering', 'popular', 'rateing'], 'integer'],
        //     [['price'], 'number'],
        //     [['created_date', 'updated_date'], 'safe'],
        //     [['title', 'short_description', 'product_sku', 'route_name',  'state'], 'string', 'max' => 250],
        //     [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        //     [['route_name'], 'unique'],
        //     [['popular'], 'default', 'value' => 0],
        // ];
        
        return [
            [['title', 'category_id'], 'required'],
            [['price', 'another_price'], 'number'],
            [['material_id', 'category_id', 'rateing', 'ordering', 'popular', 'best_offer', 'status', 'is_auction'], 'integer'],
            [['description'], 'string'],
            [['short_description', 'category_id'], 'required'],
            [['created_date', 'updated_date'], 'safe'],
            [['title'], 'string', 'max' => 25],
            [['fineness', 'weight', 'color', 'state', 'country', 'gemstone', 'short_description', 'product_sku', 'route_name'], 'string', 'max' => 255],
            [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => Material::class, 'targetAttribute' => ['material_id' => 'id']],
            [['route_name'], 'unique'],
            [['popular'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'price' => Yii::t('app', 'Price'),
            'fineness' => Yii::t('app', 'Fineness'),
            'weight' => Yii::t('app', 'Weight'),
            'color' => Yii::t('app', 'Color'),
            'state' => Yii::t('app', 'State'),
            'country' => Yii::t('app', 'Country'),
            'material_id' => Yii::t('app', 'Material ID'),
            'gemstone' => Yii::t('app', 'Gemstone'),
            'description' => Yii::t('app', 'Description'),
            'short_description' => Yii::t('app', 'Short Description'),
            'category_id' => Yii::t('app', 'Category ID'),
            'rateing' => Yii::t('app', 'Rateing'),
            'product_sku' => Yii::t('app', 'Product Sku'),
            'ordering' => Yii::t('app', 'Ordering'),
            'route_name' => Yii::t('app', 'Route Name'),
            'popular' => Yii::t('app', 'Popular'),
            'best_offer' => Yii::t('app', 'Best Offer'),
            'another_price' => Yii::t('app', 'Another Price'),
            'status' => Yii::t('app', 'Status'),
            'is_auction' => Yii::t('app', 'For Auction'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
        ];
    }

   
    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
    
    /**
     * Gets query for [[Material]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Material::class, ['id' => 'material_id']);
    }
    
    public function getImage() 
    {
        return $this->hasOne(ProductImage::class, ['product_id' => 'id'])->andWhere(['default_image_id' => 1]);
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
    public function getTrProducts()
    {
        return $this->hasMany(Product::className(), ['product_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation($languageId)
    {
        return $this->hasOne(TrProduct::className(), ['product_id' => 'id'])
        ->andOnCondition(['language_id' => $languageId]);;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id'])->where(['type' => 1]);
    }

    /**
     * list of categories
     * @return array
     */
    public function getAllCategories()
    {
        $Categories = Category::find()->orderBy('ordering ASC')->all();
        return ArrayHelper::map($Categories, 'id', 'name');
    }
    
    /**
     * list of categories
     * @return array
     */
    public function getAllMaterials()
    {
        $materials = Material::find()->orderBy('id ASC')->all();
        return ArrayHelper::map($materials, 'id', 'name');
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


    public function getImages($product_id)
    {
        $images = ProductImage::find()->where(['product_id' => $product_id, 'type' => 1])->asArray()->all();
        return ArrayHelper::map($images, 'id', 'name');
    }

    public function DeleteData()
    {
        $ProdImages = $this->getImages($this->id);
        foreach ($ProdImages as $item => $prodImage) {
            if (file_exists(Yii::$app->basePath . '/web/uploads/images/' . $prodImage)) {
                unlink(Yii::$app->basePath . '/web/uploads/images/' . $prodImage);
                unlink(Yii::$app->basePath . '/web/uploads/images/thumbnail/' . $prodImage);
            }
            ProductImage::findOne($item)->delete();
        }
        $tr_products = TrProduct::findAll(['product_id' => $this->id]);
        $productsDetails = ProductsDetails::findAll(['product_id' => $this->id]);
        foreach ($tr_products as $trporudtc) {
            $trporudtc->delete();
        }
        foreach ($productsDetails as $details) {
            $details->delete();
        }
        ConnectedProducts::deleteAll('product_id = :id', [':id' => $this->id]);
        return $this->delete();
    }

    public static function getImagesToFront($product_id, $class = '', $alt = '', $path = false)
    {
        $params = [
            'class' => 'img-responsive ' . $class,
            'alt' => $alt,
            'data-cloudzoom' => "
					  zoomPosition:'inside',
					  zoomOffsetX:0,
					  zoomFlyOut:false,
					  variableMagnification:false,
					  disableZoom:'auto',
					  touchStartDelay:100,
					  propagateGalleryEvent:true
					  "
        ];

        $images = ProductImage::find()->where(['product_id' => $product_id, 'type' => 1, 'default_image_id' => 1])->asArray()->all();
        $dotParts = explode('.', @$images[0]['name']);
        if ($class == 'image-zoom') {
            $params['data-zoom-image'] = Yii::$app->params['adminUrl'] . 'uploads/images/' . $images[0]['name'];
        }

        if (!isset($dotParts[count($dotParts) - 1])) {
            throw new \yii\web\HttpException(404, 'Image must have extension');
        }
        if ($path) {
            return Yii::$app->params['adminUrl'] . 'uploads/images/' . @$images[0]['name'];
        } else {
            return Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . @$images[0]['name'], $params);
        }
    }

    public static function getProductImagesSecondry($product_id, $class = '', $alt = '')
    {
        $params = [
            'class' => 'img-responsive ' . $class,
            'alt' => $alt,
            'data-cloudzoom' => "
								 zoomPosition:'inside',
								 zoomOffsetX:0,
								 zoomFlyOut:false,
								 variableMagnification:false,
								 disableZoom:'auto',
								 touchStartDelay:100,
								 propagateGalleryEvent:true
								 "
        ];

        $images = ProductImage::find()->where(['product_id' => $product_id, 'type' => 1])->asArray()->all();
        $imagesHTML = ['tag' => [], 'url' => []];
        foreach ($images as $image) {
            $dotParts = explode('.', @$image['name']);
            if ($class == 'image-zoom') {
                $params['data-zoom-image'] = Yii::$app->params['adminUrl'] . 'uploads/images/' . $image['name'];
            }

            if (!isset($dotParts[count($dotParts) - 1])) {
                throw new \yii\web\HttpException(404, 'Image must have extension');
            }
            $imagesHTML['tag'][] = Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . @$image['name'], $params);
            $imagesHTML['url'][] = Yii::$app->params['adminUrl'] . 'uploads/images/' . @$image['name'];
        }
        return $imagesHTML;
    }


    public static function getProductImagesSliderView($product_id, $class = '', $alt = '')
    {
        $params = [
            'class' => 'img-responsive ' . $class,
            'alt' => $alt
        ];

        $images = ProductImage::find()->where(['product_id' => $product_id, 'type' => 1, 'folder' => null])->asArray()->all();
        $imagesHTML = ['tag' => [], 'url' => []];
        foreach ($images as $image) {
            $dotParts = explode('.', @$image['name']);

            if (!isset($dotParts[count($dotParts) - 1])) {
                throw new \yii\web\HttpException(404, 'Image must have extension');
            }
            $imagesHTML['tag'][] = Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/sliderPath/' . @$image['name'], $params);
            $imagesHTML['url'][] = Yii::$app->params['adminUrl'] . 'uploads/images/sliderPath/' . @$image['name'];
        }
        return $imagesHTML;
    }


    public static function getImagesToFrontThumb($product_id, $class = '', $alt = '')
    {
        $params = [
            'class' => 'img-responsive ' . $class,
            'alt' => $alt,
        ];

        $images = ProductImage::find()->where(['product_id' => $product_id, 'type' => 1, 'default_image_id' => 1])->asArray()->all();

        $dotParts = explode('.', @$images[0]['name']);

        if ($class == 'image-zoom') {
            $params['data-zoom-image'] = Yii::$app->params['adminUrl'] . 'uploads/images/' . $images[0]['name'];
        }

        if (!isset($dotParts[count($dotParts) - 1])) {
            throw new \yii\web\HttpException(404, 'Image must have extension');
        }

        return Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/thumbnail/' . @$images[0]['name'], $params);
    }

    public function updateDefaultTranslate($defaultLanguage)
    {
        $tr = TrProduct::findOne(['language_id' => $defaultLanguage, 'product_id' => $this->id]);

        if (!$tr) {
            $tr = new TrProduct();
            $tr->setAttribute('language_id', $defaultLanguage);
            $tr->setAttribute('product_id', $this->id);
        }
        $tr->setAttribute('title', $this->title);
        $tr->setAttribute('short_description', $this->short_description);
        $tr->setAttribute('description', $this->description);
        $tr->save();

        return true;
    }
}
