<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tr_category".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $short_description
 * @property string $route_name
 * @property int $category_id
 * @property int $language_id
 *
 * @property NewsCategory $category
 */
class TrNewsCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tr_news_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'short_description'], 'required'],
            [['description'], 'string'],
            [['category_id', 'language_id'], 'integer'],
            [['name', 'short_description', 'route_name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'description' => Yii::t('app', 'Description'),
            'short_description' => Yii::t('app', 'Short Description'),
            'route_name' => Yii::t('app', 'Route Name'),
            'category_id' => Yii::t('app', 'Category ID'),
            'language_id' => Yii::t('app', 'Language ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(NewsCategory::className(), ['id' => 'category_id']);
    }
}
