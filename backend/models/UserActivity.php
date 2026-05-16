<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_activity".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $session_id
 * @property string $ip_address
 * @property string $last_activity
 * @property string $created_at
 *
 * @property User $user
 */
class UserActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['session_id', 'ip_address'], 'required'],
            [['last_activity', 'created_at'], 'safe'],
            [['session_id', 'ip_address'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'session_id' => Yii::t('app', 'Session ID'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'last_activity' => Yii::t('app', 'Last Activity'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
