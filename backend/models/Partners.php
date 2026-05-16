<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "partners".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $short_description
 * @property string $path
 * @property int|null $ordering
 */
class Partners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['short_description'], 'string'],
            [['path'], 'required'],
            [['ordering'], 'integer'],
            [['title', 'path'], 'string', 'max' => 255],
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
            'path' => Yii::t('app', 'Path'),
            'ordering' => Yii::t('app', 'Ordering'),
        ];
    }
}
