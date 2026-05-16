<?php

namespace backend\models;

use Yii;
use backend\models\TrPages;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $ordering
 * @property string $created_date
 * @property string $updated_date
 *
 * @property TrPages[] $trPages
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content'], 'string'],
            [['status', 'ordering','type','position','parent_id'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['title','short_description','route_name'], 'string', 'max' => 255],
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
            'route_name' => Yii::t('app', 'Rout Name'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'ordering' => Yii::t('app', 'Ordering'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrPages()
    {
        return $this->hasMany(TrPages::className(), ['pages_id' => 'id']);
    }

    public function updateDefaultTranslate(){
        $tr = TrPages::findOne(['language_id' => 1,'pages_id'=>$this->id]);
        if(!$tr){
            $tr = new TrPages();
            $tr->setAttribute('language_id',1);
            $tr->setAttribute('pages_id',$this->id);
        }
        $tr->setAttribute('title',$this->title);
        $tr->setAttribute('short_description',$this->short_description);
        $tr->setAttribute('content',$this->content);
        $tr->save();

        return true;
    }
	
	public function getParentPages(){
		$pages = self::find()->where(['parent_id'=>null])->asArray()->all();
		return ArrayHelper::map($pages, 'id', 'title');
	}
}
