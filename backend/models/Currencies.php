<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "currencies".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $symbol
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property ExchangeRates[] $exchangeRates
 * @property ExchangeRates[] $exchangeRates0
 * @property Currencies[] $fromCurrencies
 * @property MetalPrices[] $metalPrices
 * @property Currencies[] $toCurrencies
 */
class Currencies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currencies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50],
            [['symbol'], 'string', 'max' => 5],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'symbol' => Yii::t('app', 'Symbol'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[ExchangeRates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExchangeRates()
    {
        return $this->hasMany(ExchangeRates::class, ['from_currency_id' => 'id']);
    }

    /**
     * Gets query for [[ExchangeRates0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExchangeRates0()
    {
        return $this->hasMany(ExchangeRates::class, ['to_currency_id' => 'id']);
    }

    /**
     * Gets query for [[FromCurrencies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFromCurrencies()
    {
        return $this->hasMany(Currencies::class, ['id' => 'from_currency_id'])->viaTable('exchange_rates', ['to_currency_id' => 'id']);
    }

    /**
     * Gets query for [[MetalPrices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetalPrices()
    {
        return $this->hasMany(MetalPrices::class, ['currency_id' => 'id']);
    }

    /**
     * Gets query for [[ToCurrencies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getToCurrencies()
    {
        return $this->hasMany(Currencies::class, ['id' => 'to_currency_id'])->viaTable('exchange_rates', ['from_currency_id' => 'id']);
    }
}
