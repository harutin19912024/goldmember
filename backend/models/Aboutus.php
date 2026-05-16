<?php

namespace backend\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%aboutus}}".
 *
 * @property string $id
 * @property string $title
 * @property string $short_description
 * @property string $description
 */
class Aboutus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%aboutus}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_description'], 'required'],
            [['description', 'left_text'], 'string'],
            [['title', 'short_description'], 'string', 'max' => 700],
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
            'short_description' => Yii::t('app', 'Short Description'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
    
    public static function find_One(){
        $language = Yii::$app->language;
        $where = ['language.short_code' => $language];
        $query = (new Query());
        $query->select(['tr_aboutus.*',]);
        $query->from('aboutus');
        $query->leftJoin('tr_aboutus', 'aboutus.id = tr_aboutus.aboutus_id');
        $query->leftJoin('language', 'language.id = tr_aboutus.language_id');
        $query->where($where);
       // $query->offset(1);
        $query->orderBy(['aboutus.id' => SORT_DESC]);
        $query->limit(1);
        $rows = $query->all();
        //$arrData = self::makeArray($rows);
        return $rows;
    }
}
