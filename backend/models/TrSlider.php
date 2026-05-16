<?php

namespace backend\models;

use Yii;

/**
 * Translation model for table "tr_slider".
 * @property int    $id
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property int    $slider_id
 * @property int    $language_id
 */
class TrSlider extends \yii\db\ActiveRecord
{
    public static function tableName() { return 'tr_slider'; }

    public function rules()
    {
        return [
            [['description'], 'string'],
            [['slider_id', 'language_id'], 'integer'],
            [['title', 'short_description'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'title'             => Yii::t('app', 'Title'),
            'short_description' => Yii::t('app', 'Short Description'),
            'description'       => Yii::t('app', 'Description'),
            'slider_id'         => Yii::t('app', 'Slider'),
            'language_id'       => Yii::t('app', 'Language'),
        ];
    }

    public function getSlider()  { return $this->hasOne(Slider::class,  ['id' => 'slider_id']); }
    public function getLanguage(){ return $this->hasOne(\common\models\Language::class, ['id' => 'language_id']); }
}
