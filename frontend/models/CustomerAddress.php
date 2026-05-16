<?php

namespace frontend\models;

use common\models\Countries;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customer_address".
 *
 * @property integer $id
 * @property string $city
 * @property string $country
 * @property string $address
 * @property string $state
 * @property integer $customer_id
 * @property string $long
 * @property string $lat
 * @property integer $default_address
 * @property integer $zip
 *
 * @property Customer $customer
 * @property Order[] $orders
 */
class CustomerAddress extends ActiveRecord
{
    public $str_num;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['customer_id', 'default_address'], 'integer'],

            [['zip'], 'string', 'max'=>20 ],
            [['city', 'country', 'address', 'state',], 'string', 'max' => 50],
            [['country'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'city' => Yii::t('app', 'City'),
            'country' => Yii::t('app', 'Country'),
            'address' => Yii::t('app', 'Address'),
            'state' => Yii::t('app', 'State'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'long' => Yii::t('app', 'Long'),
            'lat' => Yii::t('app', 'Lat'),
            'zip' => Yii::t('app', 'Zip Code'),
            'default_address' => Yii::t('app', 'Default Address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_address_id' => 'id']);
    }

    public static function getFullAddressesByCustomerId($customer_id)
    {
        $data = self::findBySql("SELECT id, country, state, city, address FROM customer_address WHERE customer_id = $customer_id")
            ->asArray()->all();
        $addresses = ArrayHelper::index($data, 'id');
        foreach ($addresses as $key => $address){
            unset($address['id']);
            $addresses[$key]=implode(', ', $address);
        }
        return $addresses;
    }

    /**
     * @param $customer_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCustomerAddressByCustomerId($customer_id)
    {
        $result = self::find()->where(['customer_id' => $customer_id])->asArray()->all();
        return $result;
    }
}
