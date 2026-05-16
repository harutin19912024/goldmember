<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 31.08.2016
 * Time: 16:51
 */

namespace frontend\models;

use Yii;
use yii\base\DynamicModel;
use yii\base\Model;

class Address extends Model
{
    public $name;
    public $email;
    public $addr1;
    public $strnumb;
    public $country;
    public $city;
    public $state;
    public $phone;
    public $zip;
    public $addr_id;

    public function rules()
    {
        return [
            [['country', 'city', 'addr1',  'state',], 'required'],
            [['name', 'city', 'country', 'state', 'phone', 'email', 'zip'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['addr_id', 'strnumb'], 'integer'],
//            [['email'], 'unique', 'targetClass'=>Customer::className(),'targetAttribute'=>'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'city' => Yii::t('app', 'City'),
            'country' => Yii::t('app', 'Country'),
            'addr1' => Yii::t('app', 'Street'),
            'addr2' => Yii::t('app', 'Address2'),
            'state' => Yii::t('app', 'State'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'zip' => Yii::t('app', 'Zip'),
        ];
    }
}