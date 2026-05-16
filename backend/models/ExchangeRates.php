<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "exchange_rates".
 *
 * @property int $id
 * @property int $from_currency_id
 * @property int $to_currency_id
 * @property float $rate
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Currencies $fromCurrency
 * @property Currencies $toCurrency
 */
class ExchangeRates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exchange_rates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['currency_id', 'sell_rate', 'buy_rate'], 'required'],
            [['currency_id'], 'integer'],
            [['sell_rate', 'buy_rate'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'currency_id' => Yii::t('app', 'Currency'),
            'sell_rate' => Yii::t('app', 'Sell Rate'),
            'buy_rate' => Yii::t('app', 'Buy Rate'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[FromCurrency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currencies::class, ['id' => 'currency_id']);
    }
    
    /**
     * list of metals
     * @return array
     */
    public function getCurrencies() {
        $currencies= Currencies::find()->all();
        return ArrayHelper::map($currencies, 'id', 'name');
    }

}
