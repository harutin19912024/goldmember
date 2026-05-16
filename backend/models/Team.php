<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "team".
 *
 * @property int $id
 * @property string $fname
 * @property string $sname
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $image
 * @property string|null $profession
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fname', 'sname'], 'required'],
            [['fname', 'sname', 'email', 'phone', 'image', 'profession'], 'string', 'max' => 255],
           [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fname' => Yii::t('app', 'Fname'),
            'sname' => Yii::t('app', 'Sname'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'image' => Yii::t('app', 'Image'),
            'profession' => Yii::t('app', 'Profession'),
        ];
    }
}
