<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zone_prices".
 *
 * @property integer $id
 * @property integer $zone_id
 * @property double $weight_from
 * @property double $weight_to
 * @property double $price
 *
 * @property Zones $zone
 */
class ZonePrices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zone_prices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['zone_id', 'weight_from', 'weight_to', 'price'], 'required'],
            [['zone_id'], 'integer'],
            [['weight_from', 'weight_to', 'price'], 'number'],
            [['zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zones::className(), 'targetAttribute' => ['zone_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zone_id' => 'Zone ID',
            'weight_from' => 'Weight From',
            'weight_to' => 'Weight To',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(Zones::className(), ['id' => 'zone_id']);
    }
}
