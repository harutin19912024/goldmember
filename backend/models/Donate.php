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
class Donate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'donate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['bank_name', 'bank_account'], 'string', 'max' => 255],
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
