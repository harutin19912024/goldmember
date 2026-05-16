<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property integer $id
 * @property integer $discount
 * @property integer $user_id
 * @property string $expirationDate
 * @property string $duration
 * @property string $code
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount', 'user_id', 'code'], 'required'],
            [['discount', 'user_id'], 'integer'],
            [['expirationDate', 'duration'], 'safe'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'discount' => Yii::t('app', 'Discount'),
            'user_id' => Yii::t('app', 'User ID'),
            'expirationDate' => Yii::t('app', 'Expiration Date'),
            'duration' => Yii::t('app', 'Duration'),
            'code' => Yii::t('app', 'Code'),
        ];
    }
}
