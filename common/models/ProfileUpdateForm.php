<?php

namespace common\models;


use yii\base\Model;
use yii\db\ActiveQuery;
use Yii;
use yii\helpers\ArrayHelper;

class ProfileUpdateForm extends Model
{
//    public $email;
    public $username;
    public $currentPassword;

//    const SCENARIO_PROFILE = 'profile';
    /**
     * @var User
     */
    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'currentPassword' => Yii::t('app', 'Current Password'),

        ];
    }

    public function init()
    {
//        $this->email = $this->_user->email;   //emaili hamar
        $this->username = $this->_user->username;
        parent::init();
    }

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            [
                'username',
                'unique',
                'targetClass' => User::className(),
                'message' => Yii::t('app', 'ERROR_USERNAME_EXISTS'),
                'filter' => ['<>', 'id', $this->_user->id],
            ],
            ['currentPassword', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 100],
            ['currentPassword', 'currentPassword'],

            /*emaili hamar e
             *  ['email', 'required'],
             ['email', 'email'],
             [
                 'email',
                 'unique',
                 'targetClass' => User::className(),
                 'message' => Yii::t('app', 'ERROR_EMAIL_EXISTS'),
                 'filter' => ['<>', 'id', $this->_user->id],
             ],
             ['email', 'string', 'max' => 255],*/
        ];
    }

    public function currentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'ERROR_WRONG_CURRENT_PASSWORD'));
            }
        }
    }

    public function update()
    {
        if ($this->validate()) {
            $user = $this->_user;
//            $user->email = $this->email;
            
            $user->username = $this->username;
            return $user->save();
        } else {
            return false;
        }
    }
}