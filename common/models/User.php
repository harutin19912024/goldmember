<?php

namespace common\models;

use frontend\models\Repairer;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_token
 * @property string $auth_key
 * @property integer $role
 * @property integer $created
 * @property integer $updated
 * @property string $password write-only password
 * @property string $api_key
 *
 * @property Customer $customer
 * @property Repairer $repairer
 */
class User extends ActiveRecord implements IdentityInterface {

//    const STATUS_DELETED = 0;
//    const STATUS_ACTIVE = 10;
    const ADMIN = 0;
    const SCENARIO_PROFILE = 'profile';
    public static $notverified = false;
    public $favoritesCount = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => new Expression('NOW()'),
            ],
        ];
    }
	
	 /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password' => Yii::t('app', 'Password'),
            'role' => Yii::t('app', 'Role'),
            'password_token' => Yii::t('app', 'Password Token'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
            'api_key' => Yii::t('app', 'Api Key'),
            'user_number' => Yii::t('app', 'User Number'),
            'phone_number' => Yii::t('app', 'Phone Number'),
        ];
    }

//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            ['status', 'default', 'value' => self::STATUS_ACTIVE],
//            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
//        ];
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer() {
        return self::hasOne(Customer::className(), ['user_id' => 'id']);
    }
	
	 public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_PROFILE => ['username', 'email'],
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepairer() {
        return self::hasOne(Repairer::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['device_token' => $token]);
    }

    public function getDeviceToken() {
        return $this->hasMany(DeviceTokens::className(), ['user_id' => 'id']);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static
     */
    public static function findByEmail($email) {
        $customer = Customer::findOne(['email' => $email]);
        if (!empty($customer->user_id)) {
            return self::findOne(['id' => $customer->user_id]);
        } elseif (!empty($customer)) {
            self::$notverified = true;
            return false;
        }else{
            return false;
        }
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_token = null;
    }

    /**
     * @param $data
     * return array of user data like ['username' =>'username', 'password'=>'password'] if success
     * and false if
     * @return array|bool false
     */
    public function addUser($data) {
        $this->generateAuthKey();
        $this->username = $data->name . time();
        $password = Yii::$app->security->generateRandomString(6);
        $this->setPassword($password);
        $this->role = 20;
        if ($this->save()) {
            return ['username' => $this->username, 'password' => $password, 'user_id' => $this->id];
        }
        return false;
    }

    public function getDefaultAddress($customer_id) {
       // $customer  = \frontend\models\Customer::find()->where(['user_id'=>$user_id])->one();
        return CustomerAddress::find()->where(['customer_id' => $customer_id, 'default_address' => 1])->one();
    }

}
