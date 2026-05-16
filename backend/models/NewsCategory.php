<?php

namespace backend\models;

use Yii;
use common\models\Language;

/**
 * This is the model class for table "news_category".
 *
 * @property int $id
 * @property string $name
 * @property string $route_name
 * @property string $description
 * @property string $short_description
 * @property int $parent_id
 * @property int $ordering
 * @property int $opened
 * @property string $created_date
 * @property string $updated_date
 *
 * @property News[] $news
 * @property TrCategory[] $trCategories
 */
class NewsCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'route_name'], 'required'],
            [['description'], 'string'],
            [['parent_id', 'ordering', 'is_top'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['name', 'route_name', 'short_description'], 'string', 'max' => 255],
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
            'route_name' => Yii::t('app', 'Route Name'),
            'description' => Yii::t('app', 'Description'),
            'short_description' => Yii::t('app', 'Short Description'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'ordering' => Yii::t('app', 'Ordering'),
            'is_top' => Yii::t('app', 'Is Top'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrCategories()
    {
        return $this->hasMany(TrCategory::className, ['category_id' => 'id']);
    }

    /**
     * list of categories
     * @return array
     */
    public function getAllCategories()
    {
        $Categories = Category::find()->all();
        return ArrayHelper::map($Categories, 'id', 'name');
    }

    /**
     * @param $AllData
     * @return int
     */
    public function bachUpdate($AllData)
    {
        $updateQuery = "UPDATE `category` SET ";
        $subUpdateOrderingQuery = '`news_category` = CASE `id` ';
        foreach ($AllData as $item => $data) {
            $subUpdateOrderingQuery .= ' WHEN ' . $data['id'] . ' THEN ' . "'{$data['ordering']}'";
        }
        $updateQuery .= $subUpdateOrderingQuery . ' END';
        return self::getDb()->createCommand($updateQuery)->execute();
    }

    public function getTranslated($field)
    {
        $language = Yii::$app->language;
        if ($language == 'en') {
            return $this->$field;
        } else {

        }
    }

    public function updateDefaultTranslate()
    {
        $defaultLanguage = Language::find()->where(['is_default'=>1])->one();
        $tr = TrNewsCategory::findOne(['language_id' => $defaultLanguage->id, 'category_id' => $this->id]);
        if (!$tr) {
            $tr = new TrNewsCategory();
        }

        $tr->setAttribute('name', $this->name);
        $tr->setAttribute('description', $this->description);
        $tr->setAttribute('short_description', $this->short_description);
        $tr->setAttribute('language_id', $defaultLanguage->id);
        $tr->setAttribute('route_name', $this->route_name);
        $tr->setAttribute('category_id', $this->id);
        $tr->save();
        return true;
    }
}
