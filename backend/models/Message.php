<?php

namespace backend\models;

use Yii;
use backend\models\SourceMessage;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 *
 * @property SourceMessage $id0
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => SourceMessage::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Keyword'),
            'language' => Yii::t('app', 'Language'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }
    
    public function getTranslation($modelID,$languageCode){
        $translation = Message::find()->where(['id'=>$modelID,'language'=>$languageCode])->one();
        if(!empty($translation)){
            return $translation->translation;
        }else{
            return "";
        }

    }
    public function getTranslationSource($modelID){
        $translation = SourceMessage::find()->where(['id'=>$modelID])->one();
        if(!empty($translation)){
            return $translation->message;
        }else{
            return "";
        }

    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }
}
