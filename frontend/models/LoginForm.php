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
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            // Try by email first (customer-based login), then fall back to username
            $this->_user = \common\models\User::findByEmail($this->username)
                        ?: \common\models\User::findByUsername($this->username);
        }

        return $this->_user;
    }
}