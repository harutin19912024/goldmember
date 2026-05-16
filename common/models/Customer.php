<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\User;
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
            [['name', 'surname', 'email'], 'required'],
            [['created_date', 'updated_date'], 'safe'],
            [['name', 'surname', 'phone', 'last_ip'], 'string', 'max' => 50],
            ['email', 'unique'],
            ['email', 'email'],
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

    /**
     * @return array
     */
    public function getUsers()
    {
        $users =User::find()->asArray()->all();
        return ArrayHelper::map($users, 'id', 'username');
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFullAddress($id)
    {
        $address = self::findBySql('SELECT customer_address.country,'.
         'customer_address.state, '.
         'customer_address.city, '.
         'customer_address.address from customer_address '.
          'WHERE customer_address.customer_id = '.$id)->asArray()->all();
        return (isset($address[0])) ? $address[0] : [];
    }
    
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
    
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'auth_token' => $token,
        ]);
    }

    public function getDefaultAddress($id)
    {
        return CustomerAddress::find()->where(['customer_id'=>$id])->asArray()->all();
    }
}
