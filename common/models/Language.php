<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "language".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_code
 * @property integer $ordering
 * @property integer $is_default
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['ordering', 'is_default'], 'integer'],
            [['name', 'short_code'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'short_code' => Yii::t('app', 'Short Code'),
            'ordering' => Yii::t('app', 'Ordering'),
            'is_default' => Yii::t('app', 'Is Default'),
        ];
    }

    /**
     * @param $short_code
     * @return bool|int
     */
    public static function isDefault($short_code)
    {
        $Language = self::findOne(['short_code'=>$short_code]);
        if($Language->is_default == 1 && $Language){
            return true;
        }elseif($Language->is_default != 1 && $Language){
            return $Language->id;
        }
        return false;
    }
}
