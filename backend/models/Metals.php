<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "metals".
 *
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property MetalPrices[] $metalPrices
 */
class Metals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'symbol'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['symbol'], 'string', 'max' => 10],
            [['name'], 'unique'],
            [['symbol'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'symbol' => Yii::t('app', 'Symbol'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[MetalPrices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetalPrices()
    {
        return $this->hasMany(MetalPrices::class, ['metal_id' => 'id']);
    }
}
