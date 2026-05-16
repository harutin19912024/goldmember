<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "news_images".
 *
 * @property int $id
 * @property string $name
 * @property int $news_id
 * @property int $default_image_id
 * @property string $created_date
 * @property string $updated_date
 * @property int $type
 * @property int $resized
 *
 * @property News $news
 */
class NewsImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'default_image_id', 'type', 'resized'], 'integer'],
            [['created_date', 'updated_date'], 'required'],
            [['created_date', 'updated_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'news_id' => Yii::t('app', 'News ID'),
            'default_image_id' => Yii::t('app', 'Default Image ID'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'type' => Yii::t('app', 'Type'),
            'resized' => Yii::t('app', 'Resized'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className, ['id' => 'news_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsPart()
    {
        return $this->hasOne(News::className, ['id' => 'news_id'])->where(['type' => 0]);
    }

    /**
     * @param $paths
     * @param $news_id
     * @param $default_image
     * @return bool
     */
    public function multiSave($paths, $news_id, $default_image, $img_type)
    {
        if (!empty($paths)) {
            $data = [];
            $i = 0;
            foreach ($paths as $key => $value) {
                if ($key == $default_image) {
                    $data[] = [
                        'name' => $value,
                        'news_id' => $news_id,
                        'default_image_id' => 1,
                        'type' => $img_type,
                    ];
                } else {
                    $data[] = [
                        'name' => $value,
                        'news_id' => $news_id,
                        'default_image_id' => 0,
                        'type' => $img_type,
                    ];
                }
                $i++;
            }
            Yii::$app->db->createCommand()
                ->batchInsert(
                    'news_images', ['name', 'news_id', 'default_image_id', 'type'], $data
                )
                ->execute();
            return true;
        }
        return false;
    }

    /**
     * @param $newsId
     * @return array
     */
    public static function getImageByNewsId($newsId,$default = 0)
    {
        if($default) {
            $data = self::find()->where(['news_id' => $newsId, 'default_image_id' => $default])->asArray()->all();
        } else {
            $data = self::find()->where(['news_id' => $newsId])->asArray()->all();
        }

        return ArrayHelper::map($data, 'id', 'name');
    }

    public static function getImageColorById($image_id)
    {
        $data = self::find()->where(['id' => $image_id])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'color_id');
    }

    /**
     * @param $newsId
     * @return array
     */
    public static function getDefaultImageIdByNewsId($newsId)
    {
        $data = self::find()->where(['news_id' => $newsId, 'default_image_id' => 1, 'type' => 1])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'id');
    }

    public static function updatDefaultImage($new_id, $old_id)
    {
        self::getDb()->createCommand("UPDATE news_images SET default_image_id = 1 WHERE id = $new_id")->execute();
        self::getDb()->createCommand("UPDATE news_images SET default_image_id = 0 WHERE id = $old_id")->execute();
        return true;
    }
}
