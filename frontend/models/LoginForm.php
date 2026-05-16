<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.11.2016
 * Time: 15:16
 */

namespace frontend\models;

class LoginForm extends \common\models\LoginForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = \common\models\User::findByEmail($this->username);
        }

        return $this->_user;
    }
}