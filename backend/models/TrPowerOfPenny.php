<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tr_power_of_penny".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $content
 * @property int $power_of_penny_id
 * @property int $language_id
 */
class TrPowerOfPenny extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tr_power_of_penny';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'content'], 'default', 'value' => null],
            [['content'], 'string'],
            [['power_of_penny_id', 'language_id'], 'required'],
            [['power_of_penny_id', 'language_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'content' => Yii::t('app', 'Content'),
            'power_of_penny_id' => Yii::t('app', 'Power Of Penny ID'),
            'language_id' => Yii::t('app', 'Language ID'),
        ];
    }

}
