<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "donate".
 *
 * @property int $id
 * @property string|null $bank_name
 * @property string|null $bank_account
 * @property string|null $description
 */
class DonationInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'donation_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 50],
            
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'max' => 50],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 50],
            
            ['amount', 'trim'],
            ['amount', 'required'],
            ['amount', 'number', 'min' => 10],

            ['message', 'string', 'min' => 6],
            [['created_date', 'updated_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_account' => Yii::t('app', 'Bank Account'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
