<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
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
 *
 * @property Product $product
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
            [['product_id'], 'required'],
            [['product_id', 'default_image_id', 'type'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['name','folder'], 'string', 'max' => 255],
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
            'type' => Yii::t('app','Type'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_date', 'updated_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_date'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id'])->where(['type'=>1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPart()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id'])->where(['type'=>0]);
    }

    /**
     * @param $paths
     * @param $product_id
     * @param $default_image
     * @return bool
     */
    public function multiSave($paths, $product_id, $default_image,$img_type, $resized = 1)
    {
        if(!empty($paths)){
            $data =[];
			$i =0;
            foreach ($paths as $key => $value){
                if($key == $default_image){
                    $data[] =[
                        'name'=>$value,
                        'product_id'=>$product_id,
                        'default_image_id'=>1,
                        'type'=>$img_type,
                        'resized'=>$resized,
                    ];
                }else{
                    $data[] =[
                        'name'=>$value,
                        'product_id'=>$product_id,
                        'default_image_id'=>0,
                        'type'=>$img_type,
                        'resized'=>$resized,
                    ];
                }
				$i++;
            }
            
            Yii::$app->db->createCommand()
                ->batchInsert(
                    'product_image', ['name', 'product_id', 'default_image_id', 'type','resized'], $data
                )
                ->execute();
            return true;
        }
        return false;
    }
	
	public function multiUpdate($productId) 
	{
		self::getDb()->createCommand("UPDATE product_image SET resized = 1 WHERE product_id = $productId")->execute();
	}

    /**
     * @param $productId
     * @return array
     */
    public static function getImageByProductId($productId)
    {
        $data = self::find()->where(['product_id' => $productId, 'type'=>1,'folder'=>null])->asArray()->all();
		return $data;
        //return ArrayHelper::map($data, 'id', 'name');
    }
	/**
     * @param $productId
     * @return array
     */
    public static function getImageByProductIdOther($productId)
    {
        $data = self::find()->where(['product_id' => $productId, 'type'=>1])->andWhere(['not', ['folder' => null]])->asArray()->all();
		return $data;
        //return ArrayHelper::map($data, 'id', 'name');
    }
	
	public static function getImageColorById($image_id)
    {
        $data = self::find()->where(['id' => $image_id])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'color_id');
    }

    /**
     * @param $productId
     * @return array
     */
    public static function getDefaultImageIdByProductId($productId)
    {
        $data = self::find()->where(['product_id' => $productId, 'default_image_id' => 1, 'type'=>1])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'id');
    }

    public static function updatDefaultImage($new_id, $old_id)
    {
        self::getDb()->createCommand("UPDATE product_image SET default_image_id = 1 WHERE id = $new_id")->execute();
        self::getDb()->createCommand("UPDATE product_image SET default_image_id = 0 WHERE id = $old_id")->execute();
        return true;
    }
}
