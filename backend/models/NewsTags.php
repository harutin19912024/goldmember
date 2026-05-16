<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "news_tags".
 *
 * @property int $id
 * @property int $news_id
 * @property int $tag_id
 */
class NewsTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'tag_id'], 'required'],
            [['news_id', 'tag_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'news_id' => Yii::t('app', 'News ID'),
            'tag_id' => Yii::t('app', 'Tag ID'),
        ];
    }

    public function getAllTags()
    {
        $tags = Tags::find()->where(['status'=>1])->all();
        return ArrayHelper::map($tags, 'id', 'name');
    }

    public static function findAssignedTags($newsId)
    {
        $newsTags = self::find()->where(['news_id'=>$newsId])->all();
        return ArrayHelper::map($newsTags, 'id', 'tag_id');
    }
}
