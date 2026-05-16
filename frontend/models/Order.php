<?php

namespace frontend\models;

//use backend\models\CustomerAddress;
use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $repairer_id
 * @property integer $customer_address_id
 * @property integer $status
 * @property string $created_date
 * @property string $updated_date
 * @property string $accepted_date
 * @property string $service_id
 * @property string $problem
 *
 * @property CustomerAddress $customerAddress
 * @property Customer $customer
 * @property Repairer $repairer
 * @property Service $service
 * @property Payment[] $payments
 * @property Review[] $reviews
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ordering';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_address_id', 'created_date', 'updated_date'], 'required'],
            [['customer_id', 'repairer_id', 'customer_address_id', 'status'], 'integer'],
            [['created_date', 'updated_date', 'accepted_date'], 'safe'],
			[['additional_info','order_info'], 'string', 'max' => 255],
            [['customer_address_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerAddress::className(), 'targetAttribute' => ['customer_address_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['repairer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Repairer::className(), 'targetAttribute' => ['repairer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'repairer_id' => Yii::t('app', 'Repairer ID'),
            'customer_address_id' => Yii::t('app', 'Customer Address ID'),
            'status' => Yii::t('app', 'Status'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'additional_info' => Yii::t('app', 'Additional Info'),
            'accepted_date' => Yii::t('app', 'Accepted Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerAddress()
    {
        return $this->hasOne(CustomerAddress::className(), ['id' => 'customer_address_id']);
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
    public function getRepairer()
    {
        return $this->hasOne(Repairer::className(), ['id' => 'repairer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['order_id' => 'id']);
    }

    public static function getRepairerHistory($repairer_id)
    {
        $arrayCustomerData = self::find()->where(['repairer_id' => $repairer_id])->orderBy('status asc')->asArray()->all();
        foreach ($arrayCustomerData as $key => $record) {
            $order = self::findOne($record['id']);
            unset($record['customer_id']);
            $record['customer_name'] = $order->customer->name;
            $record['customer_surname'] = $order->customer->surname;
            $record['customer_phone'] = $order->customer->phone;
            $CustomerAddress = \backend\models\CustomerAddress::findOne($record['customer_address_id']);
            unset($record['customer_address_id']);
            $record['lat'] = $CustomerAddress->lat;
            $record['lng'] = $CustomerAddress->long;
            $Repair = Repairs::findOne(['service_id' => $order->service_id]);
            unset($record['service_id']);
            if ($Repair->part_id == 0) {
                $arrayProductData = \frontend\models\Product::findById($Repair->product_id);
                $arrayProductData = $arrayProductData[0];
                $avater = ProductImage::getDefaultImageByProductId($arrayProductData['id']);
                $avater = array_values($avater);
                $arrayProductData['avatar'] = Yii::$app->urlManagerBackEnd->hostinfo . '/' . $avater[0];
                $arrayProductData['type'] = 1;
                unset($arrayProductData['status']);
                unset($arrayProductData['price_start']);
                unset($arrayProductData['price_end']);
                unset($arrayProductData['category_id']);
                unset($arrayProductData['created_date']);
                unset($arrayProductData['updated_date']);
                unset($arrayProductData['brand_id']);
                unset($arrayProductData['product_sku']);
                $record['product_details'] = $arrayProductData;
            } else {
                $arrayPartData = \frontend\models\ProductParts::findById($Repair->part_id);
                $arrayPartData = $arrayPartData[0];
                $avater = ProductImage::getDefaultImageByPartId($arrayPartData['id']);
                $avater = array_values($avater);
                $arrayPartData['avatar'] = Yii::$app->urlManagerBackEnd->hostinfo . '/' . $avater[0];
                $arrayPartData['type'] = 0;
                unset($arrayPartData['product_id']);
                $record['product_details'] = $arrayPartData;
            }
            $arrayCustomerData[$key] = $record;
        }
        return $arrayCustomerData;
    }

    public static function getUserHistoeyInfoById($customer_id)
    {
        $arrayCustomerData = self::find()->where(['customer_id' => $customer_id])->orderBy('status asc')->asArray()->all();
        foreach ($arrayCustomerData as $key => $record) {
            $order = self::findOne($record['id']);
            unset($record['customer_id']);
            unset($record['service_id']);
            unset($record['customer_address_id']);
            $record['repairer_name'] = ($order->repairer->name) ? $order->repairer->name : 'not value';
            $record['repairer_surname'] = ($order->repairer->surname) ? $order->repairer->surname : 'not value';
            $record['repairer_phone'] = ($order->repairer->phone) ? $order->repairer->phone : 'not value';
            $record['repairer_lat'] = ($order->repairer->lat) ? $order->repairer->lat : 'not value';
            $record['repairer_lng'] = ($order->repairer->long) ? $order->repairer->long : 'not value';
            $Repair = Repairs::findOne(['service_id' => $order->service_id]);
            unset($record['service_id']);
            if ($Repair->part_id == 0) {
                $arrayProductData = \frontend\models\Product::findById($Repair->product_id);
                $arrayProductData = $arrayProductData[0];
                $avater = ProductImage::getDefaultImageByProductId($arrayProductData['id']);
                $avater = array_values($avater);
                $arrayProductData['avatar'] = Yii::$app->urlManagerBackEnd->hostinfo . '/' . $avater[0];
                $arrayProductData['type'] = 1;
                unset($arrayProductData['status']);
                unset($arrayProductData['price_start']);
                unset($arrayProductData['price_end']);
                unset($arrayProductData['category_id']);
                unset($arrayProductData['created_date']);
                unset($arrayProductData['updated_date']);
                unset($arrayProductData['brand_id']);
                unset($arrayProductData['product_sku']);
                $record['product_details'] = $arrayProductData;
            } else {
                $arrayPartData = \frontend\models\ProductParts::findById($Repair->part_id);
                $arrayPartData = $arrayPartData[0];
                $avater = ProductImage::getDefaultImageByPartId($arrayPartData['id']);
                $avater = array_values($avater);
                $arrayPartData['avatar'] = Yii::$app->urlManagerBackEnd->hostinfo . '/' . $avater[0];
                $arrayPartData['type'] = 0;
                unset($arrayPartData['product_id']);
                $record['product_details'] = $arrayPartData;
            }
            $arrayCustomerData[$key] = $record;
        }
        return $arrayCustomerData;
    }

    public static function getAvailableorder($order_id)
    {
        $OrderObj = self::findOne($order_id);
        $arrayOrderData = [];
        $arrayOrderData['customer_name'] = $OrderObj->customer->name;
        $arrayOrderData['customer_surname'] = $OrderObj->customer->surname;
        $arrayOrderData['customer_phone'] = $OrderObj->customer->phone;
        $CustomerAddress = \backend\models\CustomerAddress::findOne($OrderObj->customer_address_id);
        $arrayOrderData['customer_lat'] = $CustomerAddress->lat;
        $arrayOrderData['customer_lng'] = $CustomerAddress->long;
        $Repair = Repairs::findOne(['service_id' => $OrderObj->service_id]);
        if ($Repair->part_id == 0) {
            $arrayProductData = \frontend\models\Product::findById($Repair->product_id);
            $arrayProductData = $arrayProductData[0];
            $ItemImage = ProductImage::getDefaultImageByProductId($arrayProductData['id']);
            $ItemImage = array_values($ItemImage);
            $arrayProductData['avatar'] = Yii::$app->urlManagerBackEnd->hostinfo . '/' . $ItemImage[0];
            $arrayProductData['type'] = 1;
            unset($arrayProductData['status']);
            unset($arrayProductData['price_start']);
            unset($arrayProductData['price_end']);
            unset($arrayProductData['category_id']);
            unset($arrayProductData['created_date']);
            unset($arrayProductData['updated_date']);
            unset($arrayProductData['brand_id']);
            unset($arrayProductData['product_sku']);
            $arrayOrderData['product_details'] = $arrayProductData;
        } else {
            $arrayPartData = \frontend\models\ProductParts::findById($Repair->part_id);
            $arrayPartData = $arrayPartData[0];
            $ItemImage = ProductImage::getDefaultImageByPartId($arrayPartData['id']);
            $ItemImage = array_values($ItemImage);
            $arrayPartData['avatar'] = Yii::$app->urlManagerBackEnd->hostinfo . '/' . $ItemImage[0];
            $arrayPartData['type'] = 0;
            unset($arrayPartData['product_id']);
            $arrayOrderData['product_details'] = $arrayPartData;
        }
        return $arrayOrderData;
    }
}
