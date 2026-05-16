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
class Homepage extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%homepage}}';
    }

   /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['title'], 'string', 'max' => 255],
			[['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description')
            ];
    }

    public static function find_One() {
        $language = Yii::$app->language;
        $query = (new Query());
        $query->select(['homepage.*']);
        $query->from('homepage');
        $rows = $query->all();
        return $rows;
    }

    public function updateDefaultTranslate($language_id) {
        $tr = TrHomepage::findOne(['language_id' => $language_id, 'homepage_id' => $this->id]);
        if (!$tr) {
            $tr = new TrSitesettings();
            $tr->setAttribute('language_id', $language_id);
            $tr->setAttribute('homepage_id', $this->id);
        }
        $tr->setAttribute('title', $this->title);
        $tr->setAttribute('description', $this->description);
        $tr->save();
        return true;
    }

}
