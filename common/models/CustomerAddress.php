<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\Location;
use common\models\Customer;

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
 *
 * @property Customer $customer
 * @property Order[] $orders
 */
class CustomerAddress extends \yii\db\ActiveRecord
{
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
//            [['customer_id'], 'required'],
            [['customer_id', 'default_address'], 'integer'],
            [['long', 'lat'], 'number'],
            [['city', 'country', 'address', 'state'], 'string', 'max' => 50],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
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

    public function batchInsert($Addressis, $customer_id)
    {
//        var_dump($Addressis, $customer_id);die;
        $rows = [];
//        var_dump($customer_id); die();
        $columns = [
            'country',
            'state',
            'city',
            'address',
            'default_address',
            'long',
            'lat',
            'customer_id',

        ];

        foreach ($Addressis as $key => $address) {
            if($key == 'default'){
                $address['default_address']=1;
            }else{
                $address['default_address']=null;
            }

            $addr = $address['address'].' '.$address['city'];
            $Location =Location::getLatLngByAddress($addr, $address['country']);
            $address['long']=$Location['lng'];
            $address['lat']=$Location['lat'];
            $address['customer_id']=$customer_id;
            $rows[] = $address;
        }
        $result = Yii::$app->db->createCommand()->batchInsert(self::tableName(), $columns, $rows)->execute();
        return $result;
    }

    /**
     * @param $customer_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCustomerAddressByCustomerId($customer_id)
    {
       $result = self::find()->where(['customer_id' => $customer_id])->all();
        return $result;
    }

    /**
     * @param $data
     * @param $customer_id
     * @return bool
     */
    public function bachUpdate($data, $customer_id)
    {
        $DataCustommerAddressis = self::find()->where(['customer_id' => $customer_id])
            ->asArray()->all();
        if(empty($DataCustommerAddressis)){
            return  $this->batchInsert($data, $customer_id);
        }else{
            self::getDb()->createCommand()->
            delete(self::tableName(), ['customer_id' => $customer_id])
                ->execute();
           return $this->batchInsert($data, $customer_id);
        }
    }
}
