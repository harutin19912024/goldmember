<?php

namespace backend\models;

use Yii;

/**
 * Translation model for table "tr_partners".
 * @property int    $id
 * @property string $title
 * @property string $short_description
 * @property int    $partners_id
 * @property int    $language_id
 */
class TrPartners extends \yii\db\ActiveRecord
{
    public static function tableName() { return 'tr_partners'; }

    public function rules()
    {
        return [
            [['short_description'], 'string'],
            [['partners_id', 'language_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'title'             => Yii::t('app', 'Title'),
            'short_description' => Yii::t('app', 'Short Description'),
            'partners_id'       => Yii::t('app', 'Partner'),
            'language_id'       => Yii::t('app', 'Language'),
        ];
    }

    public function getPartners(){ return $this->hasOne(Partners::class, ['id' => 'partners_id']); }
    public function getLanguage(){ return $this->hasOne(\common\models\Language::class, ['id' => 'language_id']); }
}
