<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%slider}}".
 *
 * @property string $id
 * @property string $title
 * @property string $short_description
 * @property string $link
 * @property integer $status
 */
class Slider extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%slider}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
           // [['status'], 'required'],
            [['status'], 'integer'],
            [['title','path', 'short_description', 'link'], 'string', 'max' => 255],
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
            'link' => Yii::t('app', 'Link'),
            'path' => Yii::t('app', 'Path'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

}
