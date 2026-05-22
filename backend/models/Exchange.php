<?php

namespace backend\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%sitesettings}}".
 *
 * @property string $id
 * @property string $logoText
 * @property string $facebook
 * @property string $google
 * @property string $youtube
 */
class Exchange extends \yii\db\ActiveRecord {

    const AVAILABLE_METALS = [
        1 => 'XAU',
        2 => 'XAG',
        3 => 'XPT',
        4 => 'XPD',
    ];

    const AVAILABLE_METALS_NAME = [
        self::AVAILABLE_METALS[1] => 'GOLD',
        self::AVAILABLE_METALS[2] => 'SILVER',
        self::AVAILABLE_METALS[3] => 'PLATINUM',
        self::AVAILABLE_METALS[4] => 'PALLADIUM',
    ];

    const AVAILABLE_CURRENCIES = [
        1 => 'USD',
        2 => 'EUR',
        3 => 'AMD',
        4 => 'RUB',
    ];

    const GOLD_PURITIES = [
        '999' => 99.9,  // 99.9% pure (24K)
        '995' => 99.5,  // 99.5% pure
        '958' => 95.8,  // 95.8% pure (23K)
        '585' => 58.5,  // 58.5% pure (14K)
        '916' => 91.6,  // 91.6% pure (22K)
        '900' => 90.0,  // 90.0% pure (21.6K)
        '875' => 87.5,  // 87.5% pure (21K)
        '750' => 75.0,  // 75.0% pure (18K)
        '500' => 50.0,  // 50.0% pure (12K)
        '417' => 41.7,  // 41.7% pure (10K)
        '375' => 37.5,  // 37.5% pure (9K)
        '333' => 33.3,  // 33.3% pure (8K)
        '250' => 25.0,  // 25.0% pure (6K)
    ];

    const SILVER_PURITIES = [
        '999' => 99.9,  // Fine silver
        '958' => 95.8,  // Britannia
        '925' => 92.5,  // Sterling
        '900' => 90.0,  // Coin silver
        '800' => 80.0,
    ];

    const PLATINUM_PURITIES = [
        '999' => 99.9,
        '950' => 95.0,
        '900' => 90.0,
        '850' => 85.0,
    ];

    const PALLADIUM_PURITIES = [
        '999' => 99.9,
        '950' => 95.0,
        '500' => 50.0,
    ];

    public static function getPuritiesByMetal($metalId)
    {
        switch ((int)$metalId) {
            case 2: return self::SILVER_PURITIES;
            case 3: return self::PLATINUM_PURITIES;
            case 4: return self::PALLADIUM_PURITIES;
            case 1:
            default: return self::GOLD_PURITIES;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%exchange}}';
    }

   /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sell', 'buy'], 'required'],
            [['name', 'sell', 'buy', 'original_sell','original_buy'], 'string', 'max' => 255],
            [['created_date', 'updated_date', 'exchange_enum', 'data'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Exchange Name'),
            'buy' => Yii::t('app', 'Buy'),
            'original_buy' => Yii::t('app', 'Orignial Buy'),
            'original_sell' => Yii::t('app', 'Exchange Sell'),
            'sell' => Yii::t('app', 'Sell'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            ];
    }

}
