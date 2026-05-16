<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\Language;

/**
 * This is the model class for table "attribute".
 *
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property string $created_date
 * @property string $updated_date
 * @property integer $category_id
 *
 * @property Category $category
 * @property ProductAttribute[] $productAttributes
 */
class TrHomepage extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tr_homepage';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title'], 'required'],
            [['homepage_id', 'language_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['homepage_id'], 'exist', 'skipOnError' => true, 'targetClass' => Homepage::className(), 'targetAttribute' => ['homepage_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'language_id' => Yii::t('app', 'Language ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributes($names = NULL, $except = Array()) {
        return $this->hasOne(Homepage::className(), ['id' => 'homepage_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage() {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
	
	public function updateDefaultTranslate($homepageid) {
        $tr = self::findOne(['language_id' => 2, 'homepage_id' => $homepageid]);

        if (!$tr) {
            $tr = new self();
            $tr->setAttribute('language_id', 2);
            $tr->setAttribute('homepage_id', $homepageid);
        }
		
        $tr->setAttribute('title', $this->title);
        $tr->setAttribute('description', $this->description);
        $tr->save();
        return true;
    }

}
