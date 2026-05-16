<?php

namespace backend\models;

use Yii;
use backend\models\States;
use backend\models\Cities;
use backend\models\Address;

/**
 * This is the model class for table "product_address".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $city_id
 * @property integer $state_id
 * @property integer $address_id
 *
 * @property Address $address
 */
class ProductAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'city_id', 'state_id', 'address_id'], 'integer'],
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
            'city_id' => Yii::t('app', 'City ID'),
            'state_id' => Yii::t('app', 'State ID'),
            'address_id' => Yii::t('app', 'Address ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(States::className(), ['id' => 'state_id']);
    }
	
	public static function getCityByAddressId($address_id) 
	{
		$city_id = self::find()->where(['address_id' => $address_id])->one();
		if ($city_id) {
			return $city_id->city_id;
		}
		return '';
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity($address_id = null)
    {
        
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    public function getStates()
    {
        $state = States::find()->asArray()->all();
        $states = [];
        foreach ($state as $category) {
            $states[$category['id']] = $category['name'];
        }
        return $states;
    }

    public function getCities()
    {
        $state = Cities::find()->orderBy(['ordering' => SORT_ASC])->asArray()->all();
        $states = [];
        foreach ($state as $category) {
            $states[$category['id']] = $category['name'];
        }
        return $states;
    }

    public function getAddresses($cityId = null)
    {
		if(!is_null($cityId)) {
			$address = Address::find()->where(['city_id'=>$cityId])->asArray()->all();
		} else {
			$address = Address::find()->asArray()->all();
		}
        $addressList = [];
        foreach ($address as $addr) {
            $addressList[$addr['id']] = $addr['address'];
        }
        return $addressList;
    }
}
