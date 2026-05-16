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
class ProductsDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['name', 'value'], 'required'],
            [['show_front'], 'integer'],
            [['name','value'], 'string', 'max' => 255]
        ];
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
            'name',
            'value',
            'product_id',
            'show_front',
			'created_date',
            'updated_date'
        ];
        foreach ($data as $item => $value) {
			if(!empty($value['name']) && !empty($value['value'])){
				 $rows[] = [
					'name' => isset($value['name'])?$value['name']:'',
					'value' => isset($value['value'])?$value['value']:'',
					'product_id' => $product_id,
					'show_front' => (isset($value['show_front']) && $value['show_front']=='on')?1:0,
					'created_date' => date("Y-m-d H:i:s"),
					'updated_date' => date("Y-m-d H:i:s")
				];
			}
           
        }

        $result = Yii::$app->db->createCommand()->batchInsert(self::tableName(), $columns, $rows)->execute();
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Detail Name'),
            'value' => Yii::t('app', 'Detail Value'),
        ];
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
}
