<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {

    public $username;
    public $email;
    public $password;
    public $rememberMe = true;
    protected $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['password'], 'required'],
            [['username', 'email'], 'safe'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

//    /**
//     * Logs in a user using the provided username and password.
//     *
//     * @return boolean whether the user is logged in successfully
//     */
//    public function loginAdmin()
//    {
//        return Yii::$app->user->login($this->getAdmin(), $this->rememberMe ? 3600 * 24 * 30 : 0);
//
//    }

    public function login() {
        if ($this->getUser()) {
            if ($this->validate()) {
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            }else{
                return false;
            } 
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        if (!empty($this->_user)) {
            return $this->_user;
        } elseif(User::$notverified){
            return false;
        }else{
             return false;
        }
    }
    
    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmailVerify($email)
    {
        $authkey = Yii::$app->security->generateRandomString() . '_' . time();
        $customer = Customer::findOne(['email' => $email]);
        $customer->auth_token = $authkey;
        if($customer->save()){
            $url =Yii::$app->params['baseUrlHome'].'/site/login?authkey='.$authkey;
            $message = "Hello $customer->name!<br/><br/>
            Please go to this <a href='$url'>link</a> to verify your email";
            return Yii::$app
                            ->mailer
                            ->compose('email-layout', ['content' => $message])
                            ->setFrom(['admin-odenson@test.com' => Yii::$app->name])
                            ->setTo($email)
                            ->setSubject("Verify Email")
                            ->send();
        }
    }

//    protected function getAdmin()
//    {
//        if ($this->_user === null) {
//            $this->_user = User::findByUsername($this->username);
//        }
//
//        return $this->_user;
//    }
}
