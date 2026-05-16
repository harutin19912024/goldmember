<?php

namespace common\models;

use Yii;
use yii\db\Query;


/**
 * This is the model class for table "zones".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $description
 *
 * @property ZoneCountries[] $zoneCountries
 * @property ZonePrices[] $zonePrices
 *
 */
class Zones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZoneCountries()
    {
        return $this->hasMany(ZoneCountries::className(), ['zone_id' => 'id']);
    }
    public static function findZones($countryId,$weight){

        $query = (new Query());
        $query->select('zones.*,zone_countries.country_id,zone_prices.price');
        $query->leftJoin('zone_countries','zone_countries.zone_id =zones.id');
        $query->leftJoin('zone_prices','zone_prices.zone_id=zones.id AND  (`zone_prices`.`weight_from` <= '.$weight. ' AND `zone_prices`.`weight_to` >'.$weight.')');
        $query->from('zones');
        $query->where(['zone_countries.country_id'=>$countryId]);
        $query->orderBy(['zones.type' => SORT_DESC]);
        $arrData=$query->all();
        return $arrData;

    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZonePrices()
    {
        return $this->hasMany(ZonePrices::className(), ['zone_id' => 'id']);
    }
}
