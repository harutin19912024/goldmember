<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product_attribute".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property integer $product_id
 * @property string $value
 * @property string $created_date
 * @property string $updated_date
 *
 * @property Attribute $attributess
 * @property Product $product
 */
class ProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'product_id',], 'required'],
            [['attribute_id', 'product_id'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => TrAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => TrProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attribute_id' => Yii::t('app', 'Attribute'),
            'product_id' => Yii::t('app', 'Product'),
            'value' => Yii::t('app', 'Value'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
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
    public function getAttributess()
    {
        return $this->hasOne(TrAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(TrProduct::className(), ['id' => 'product_id']);
    }

    /**
     * list of products
     * @return array
     */
    public function getProducts()
    {
        $Products = Product::find()->all();
        return ArrayHelper::map($Products, 'id', 'name');
    }

    /**
     * list of attributes
     * @return array
     */
    public function getAllAttibutes()
    {
        $Attributes = Attribute::find()->all();
        return ArrayHelper::map($Attributes, 'id', 'name');
    }
	
	/**
     * list of attributes
     * @return array
     */
    public static function findAttributesForFrontend($productId)
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
                SELECT pf.product_id, pf.value,
                (SELECT a.name FROM attribute a WHERE pf.attribute_id = a.id ) as attribute,
                (SELECT a.name FROM attribute a WHERE pf.filter_id = a.id ) as filter
            FROM products_filters pf WHERE pf.product_id = :product_id
        ", [':product_id' => $productId]);

        $filters = $command->queryAll();
		
		return $filters;
    }

    /**
     * @param $data
     * @param $product_id
     * @return int|bool
     */
    public function batchInsert($data, $product_id)
    {
        $rows = [];
        $columns = [
            'attribute_id',
            'value',
            'product_id',
            'created_date',
            'updated_date',

        ];
        foreach ($data as $item => $value) {
				$rows[] = [
					'attribute_id' => $item,
					'value' => $value,
					'product_id' => $product_id,
					'created_date' => date("Y-m-d H:i:s"),
					'updated_date' => date("Y-m-d H:i:s"),
				];
        }

        $result = Yii::$app->db->createCommand()->batchInsert(self::tableName(), $columns, $rows)->execute();
        return $result;
    }

    /**
     * @param $product_id
     * @return array
     */
    public function getAttributesByProductId($product_id)
    {
        $attributes = self::find()->where(['product_id' => $product_id])->asArray()->all();
        return ArrayHelper::map($attributes, 'attribute_id', 'value');
    }

    public function saveData($data, $product_id)
    {

        $DataProductAttribute = self::find()->where(['product_id' => $product_id])->asArray()->all();

        if(empty($DataProductAttribute)){
            return  $this->batchInsert($data, $product_id);
        }else{
            self::getDb()->createCommand()->
            delete(self::tableName(), ['product_id' => $product_id])
                ->execute();
            return $this->batchInsert($data, $product_id);
        }
    }

    public function getAttibute($attribute_id)
    {
        $Attributes = TrAttribute::find()->where(['id'=>$attribute_id])->asArray()->all();
        return $Attributes;
    }
}
