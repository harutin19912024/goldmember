<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use common\models\Customer;
use common\models\CustomerAddress;
use Yii;
use common\components\Location;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $name;
    public $surname;
    public $email;
    public $password;
    public $confirm_password;
    public $verifyToken;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            ['username', 'trim'],
//            ['username', 'required'],
//            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
//            ['username', 'string', 'min' => 2, 'max' => 50],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 50],

            ['surname', 'trim'],
            ['surname', 'required'],
            ['surname', 'string', 'min' => 2, 'max' => 50],
            
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'max' => 50],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 50],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['confirm_password', 'required'],
            ['confirm_password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $customer = new Customer();
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->role = 20;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if($user->save()){
            $customer->name             = $this->name;
            $customer->surname          = $this->surname;
            $customer->email            = $this->email;
            $customer->user_id          = $user->id;
            $customer->last_ip          = \Yii::$app->request->userIP;
            $customer->social_user_name = '';
            $customer->auth_token       = '';
            $customer->save(false);
            return $user ;
        }
        return null;
    }
    
    public function getNewUser() {
        $user = new User();
        $user->username = $this->username;
        $user->role = 20;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if($user->save()){
            return $user;
        }
    }
}
