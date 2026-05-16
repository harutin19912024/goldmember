<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tr_news".
 *
 * @property string $id
 * @property string $title
 * @property string $short_description
 * @property string $content
 * @property int $news_id
 * @property int $language_id
 *
 * @property News $news
 */
class TrNews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tr_news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['news_id', 'language_id'], 'integer'],
            [['title', 'short_description'], 'string', 'max' => 255],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'short_description' => Yii::t('app', 'Short Description'),
            'content' => Yii::t('app', 'Content'),
            'news_id' => Yii::t('app', 'News ID'),
            'language_id' => Yii::t('app', 'Language ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }
}
