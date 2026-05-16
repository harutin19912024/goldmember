<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message_system".
 *
 * @property integer $id
 * @property integer $sender_user_id
 * @property integer $recipient_user_id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property string $send_at
 */
class MessageSystem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message_system';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_user_id', 'recipient_user_id', 'title', 'content', 'status'], 'required'],
            [['sender_user_id', 'recipient_user_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['send_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sender_user_id' => Yii::t('app', 'Sender User ID'),
            'recipient_user_id' => Yii::t('app', 'Recipient User'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'send_at' => Yii::t('app', 'Send At'),
        ];
    }
}
