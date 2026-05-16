<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "metal_price_real".
 *
 * @property int $id
 * @property int|null $metal_id
 * @property int|null $currency_id
 * @property string $created_date
 * @property string $request_data
 * @property string|null $request_error
 *
 * @property Currencies $currency
 * @property Metals $metal
 */
class MetalPriceReal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metal_price_real';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metal_id', 'currency_id'], 'integer'],
            [['created_date'], 'safe'],
            [['request_data'], 'required'],
            [['request_data', 'request_error'], 'string'],
            [['metal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metals::class, 'targetAttribute' => ['metal_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currencies::class, 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'metal_id' => Yii::t('app', 'Metal ID'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'created_date' => Yii::t('app', 'Created Date'),
            'request_data' => Yii::t('app', 'Request Data'),
            'request_error' => Yii::t('app', 'Request Error'),
        ];
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currencies::class, ['id' => 'currency_id']);
    }

    /**
     * Gets query for [[Metal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetal()
    {
        return $this->hasOne(Metals::class, ['id' => 'metal_id']);
    }
}
