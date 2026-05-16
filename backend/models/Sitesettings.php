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
class Sitesettings extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%sitesettings}}';
    }

   /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['meta_tag', 'meta_description', 'site_title', 'site_email', 'site_phone'], 'required'],
            [['meta_tag','meta_description', 'site_title', 'site_email', 'work_time', 'site_phone','address'], 'string', 'max' => 255],
			[['text1', 'text2','text3','text4'], 'string'],
			[['maintenance_mode'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'meta_tag' => Yii::t('app', 'Meta Tag'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'site_title' => Yii::t('app', 'Site Title'),
            'site_email' => Yii::t('app', 'Site Email'),
            'work_time' => Yii::t('app', 'Work Time'),
            'site_phone' => Yii::t('app', 'Site Phone'),
            'address' => Yii::t('app', 'Address'),
            'maintenance_mode' => Yii::t('app', 'Maintenance Mode'),
        ];
    }

    public static function find_One() {
        $language = Yii::$app->language;
        $query = (new Query());
        $query->select(['sitesettings.*']);
        $query->from('sitesettings');
        $rows = $query->all();
        return $rows;
    }

    public function updateDefaultTranslate($language_id) {
        $tr = TrSitesettings::findOne(['language_id' => $language_id, 'settings_id' => $this->id]);
        if (!$tr) {
            $tr = new TrSitesettings();
            $tr->setAttribute('language_id', $language_id);
            $tr->setAttribute('settings_id', $this->id);
        }
        $tr->setAttribute('logoText', $this->logoText);
        $tr->save();
        return true;
    }

}
