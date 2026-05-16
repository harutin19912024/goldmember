<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "power_of_penny".
 *
 * @property int $id
 * @property string|null $content
 * @property string $video_url
 * @property string $name
 * @property int $status
 */
class PowerOfPenny extends \yii\db\ActiveRecord
{
    
    public static $Extensions = ['jpg', 'png'];
    public $imageFiles;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'power_of_penny';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 1],
            [['content'], 'string'],
            [['video_url', 'name'], 'required'],
            [['status'], 'integer'],
            [['video_url', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'video_url' => 'Video Url',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }

}
