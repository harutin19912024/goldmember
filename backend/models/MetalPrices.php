<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "metal_prices".
 *
 * @property int $id
 * @property int $metal_id
 * @property int $currency_id
 * @property int $karat
 * @property float $price
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Currencies $currency
 * @property Metals $metal
 */
class MetalPrices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metal_prices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metal_id', 'currency_id', 'karat'], 'required'],
            [['metal_id', 'currency_id', 'karat', 'rate_status'], 'integer'],
            [['sell_price', 'buy_price', 'original_buy_price', 'original_sell_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            //[['metal_id', 'currency_id', 'karat'], 'unique', 'targetAttribute' => ['metal_id', 'currency_id', 'karat']],
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
            'karat' => Yii::t('app', 'Karat'),
            'sell_price' => Yii::t('app', 'Sell Price'),
            'buy_price' => Yii::t('app', 'Buy Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
    
    /**
     * list of metals
     * @return array
     */
    public function getMetals() {
        $metals = Metals::find()->all();
        return ArrayHelper::map($metals, 'id', 'name');
    }
    
    /**
     * list of currencies
     * @return array
     */
    public function getCurrencies() {
        $currencies = Currencies::find()->all();
        return ArrayHelper::map($currencies, 'id', 'name');
    }
}
