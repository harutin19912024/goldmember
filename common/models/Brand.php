<?php

namespace common\models;

use Yii;
use common\models\Product;
use common\models\TrBrand;
/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property srting $name
 * @property integer $ordering
 * @property integer $status
 *
 * @property TrProduct[] $trProducts
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ordering','status'], 'integer'],
            [['name','status'], 'required'],
            [['website_link'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ordering' => Yii::t('app', 'Ordering'),
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'Name'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductsByBrand($brand_id)
    {
        return Product::find()->where(['brand_id'=>$brand_id])->count();
    }
    
    /**
     * @param $AllData
     * @return int
     */
    public function bachUpdate($AllData) {

        $updateQuery = "UPDATE `brand` SET ";
        $subUpdateOrderingQuery = '`ordering` = CASE `id` ';
        foreach ($AllData as $item => $data) {
            $subUpdateOrderingQuery .=' WHEN ' . $data['id'] . ' THEN ' . "'{$data['ordering']}'";
        }
        $updateQuery.=$subUpdateOrderingQuery . ' END';
        return self::getDb()->createCommand($updateQuery)->execute();
    }

    public function updateDefaultTranslate(){
        $tr = TrBrand::findOne(['language_id' => 1,'brand_id'=>$this->id]);
      if(!$tr){
          $tr = new TrBrand();

          $tr->setAttribute('language_id',1);
          $tr->setAttribute('brand_id',$this->id);

      }
        $tr->setAttribute('name',$this->name);
        $tr->save();
      return true;
    }
}
