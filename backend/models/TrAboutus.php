<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%tr_aboutus}}".
 *
 * @property string $id
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property integer $aboutus_id
 * @property integer $lanugage_id
 */
class TrAboutus extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%tr_aboutus}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'short_description', 'description', 'aboutus_id', 'language_id'], 'required'],
            [['description'], 'string'],
            [['aboutus_id', 'language_id'], 'integer'],
            [['title', 'short_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'short_description' => Yii::t('app', 'Short Description'),
            'description' => Yii::t('app', 'Description'),
            'aboutus_id' => Yii::t('app', 'Aboutus ID'),
            'language_id' => Yii::t('app', 'Lanugage ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbout() {
        return $this->hasOne(Aboutus::className(), ['id' => 'aboutus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage() {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

}
