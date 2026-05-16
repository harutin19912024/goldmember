<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "social_net".
 *
 * @property integer $id
 * @property string $name
 * @property string $icon_path
 * @property integer $active
 */
class SocialNet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_net';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link','social_type', 'active'], 'required'],
            [['active'], 'integer'],
            [['link','social_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'link' => Yii::t('app', 'Link'),
            'active' => Yii::t('app', 'Active'),
            'social_type' => Yii::t('app', 'Social Type'),
        ];
    }
}
