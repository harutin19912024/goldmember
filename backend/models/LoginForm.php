<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.11.2016
 * Time: 14:55
 */

namespace backend\models;


class LoginForm extends \common\models\LoginForm
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Same as the parent check, but also rejects users whose role may not use
     * the admin panel (customers). Uses the same message as a wrong password so
     * the form doesn't reveal which accounts exist.
     */
    public function validatePassword($attribute, $params)
    {
        parent::validatePassword($attribute, $params);
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !in_array((int)$user->role, User::BACKEND_ROLES, true)) {
                $this->addError($attribute, 'Incorrect username or password.');
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