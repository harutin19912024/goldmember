<?php

namespace frontend\models;

use Yii;
use common\models\User;
use api\models\DeviceTokens;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $phone
 * @property integer $status
 * @property integer $user_id
 * @property string $last_ip
 * @property string $created_date
 * @property string $updated_date
 *
 * @property User $user
 * @property CustomerAddress[] $customerAddresses
 * @property Order[] $orders
 * @property Payment[] $payments
 * @property CustomerCard $customerCard
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'user_id'], 'integer'],
            [['created_date', 'updated_date'], 'required'],
            [['created_date', 'updated_date'], 'safe'],
            [['name', 'surname', 'email', 'phone', 'last_ip'], 'string', 'max' => 50],
            [['email'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'surname' => Yii::t('app', 'Surname'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'status' => Yii::t('app', 'Status'),
            'user_id' => Yii::t('app', 'User ID'),
            'last_ip' => Yii::t('app', 'Last Ip'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerAddresses()
    {
        return $this->hasMany(CustomerAddress::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCard()
    {
        return $this->hasOne(CustomerCard::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['customer_id' => 'id']);
    }

    public static function findDeviseToken($id)
    {
        $user_id =self::findOne($id)->user_id;
        $Devices = DeviceTokens::find()->where(['user_id'=>$user_id])->asArray()->all();
        return ArrayHelper::map($Devices,'id','device_token');
    }

    public function getDefaultAddress($customer_id)
    {
        return CustomerAddress::find()->where(['customer_id'=>$customer_id, 'default_address'=>1])->one();
    }
}
