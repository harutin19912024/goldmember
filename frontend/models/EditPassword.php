<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.09.2016
 * Time: 16:46
 */

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class EditPassword extends Model
{
    public $new_password;
    public $old_password;
    public $confirm_password;
    public $username;
    public $_user;

    public function rules()
    {
        return [
            [['new_password', 'old_password', 'confirm_password',], 'required'],
            [['new_password', 'old_password', 'confirm_password'], 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'old_password'],
            ['old_password', 'validatePassword']
//            [['email'], 'unique', 'targetClass'=>Customer::className(),'targetAttribute'=>'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'new_password' => Yii::t('app', 'New Password'),
            'old_password' => Yii::t('app', 'Old Password'),
            'confirm_password' => Yii::t('app', 'Confirm Password'),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function edit()
    {
        if ($this->validate()) {
            $this->getUser()->setPassword($this->new_password);
            $this->getUser()->update();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}