<?php

namespace backend\models;

use Yii;

/**
 * Translation model for table "tr_blog".
 * @property int    $id
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property int    $blog_id
 * @property int    $language_id
 */
class TrBlog extends \yii\db\ActiveRecord
{
    public static function tableName() { return 'tr_blog'; }

    public function rules()
    {
        return [
            [['description'], 'string'],
            [['blog_id', 'language_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 500],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'title'             => Yii::t('app', 'Title'),
            'short_description' => Yii::t('app', 'Short Description'),
            'description'       => Yii::t('app', 'Description'),
            'blog_id'           => Yii::t('app', 'Blog'),
            'language_id'       => Yii::t('app', 'Language'),
        ];
    }

    public function getBlog()    { return $this->hasOne(Blog::class,    ['id' => 'blog_id']); }
    public function getLanguage(){ return $this->hasOne(\common\models\Language::class, ['id' => 'language_id']); }
}
