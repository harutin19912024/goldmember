<?php

namespace backend\models;

use Yii;
use common\models\Language;
use backend\models\NewsCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $short_description
 * @property int $status
 * @property int $category_id
 * @property string $meta_description
 * @property string $meta_title
 * @property int $ordering
 * @property string $route_name
 * @property string $source_url
 * @property int $top_news
 * @property int $resized
 * @property int $rate
 * @property int $latest_news
 * @property string $created_date
 * @property string $updated_date
 *
 * @property ConnectedNews[] $connectedNews
 * @property NewsCategory $category
 * @property NewsAttribute[] $newsAttributes
 * @property NewsImages[] $newsImages
 * @property NewsModifications[] $newsModifications
 * @property TrNews[] $trNews
 */
class News extends \yii\db\ActiveRecord
{

    const UPLOAD_MAX_COUNT = 10;

    public static $Extensions = ['jpg', 'png'];
    public $imageFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content','category_id','route_name'], 'required'],
            [['content', 'meta_description'], 'string'],
            [['status', 'category_id', 'ordering', 'top_news', 'latest_news','is_primary_news', 'resized', 'rate', 'running_line'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['title', 'short_description'], 'string', 'max' => 250],
            [['meta_title', 'route_name', 'source_url'], 'string', 'max' => 255],
            [['video_url'], 'string', 'max' => 500],
            [['route_name'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'content' => Yii::t('app', 'Content'),
            'short_description' => Yii::t('app', 'Short Description'),
            'status' => Yii::t('app', 'Status'),
            'category_id' => Yii::t('app', 'Category ID'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'ordering' => Yii::t('app', 'Ordering'),
            'route_name' => Yii::t('app', 'Route Name'),
            'source_url' => Yii::t('app', 'Source Url'),
            'top_news' => Yii::t('app', 'Top News'),
            'latest_news' => Yii::t('app', 'Latest News'),
            'is_primary_news' => Yii::t('app', 'Primary News'),
            'resized' => Yii::t('app', 'Resized'),
            'rate' => Yii::t('app', 'Rate'),
            'running_line' => Yii::t('app', 'Show in Running line'),
            'video_url' => Yii::t('app', 'Video URL'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
        ];
    }

   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(NewsCategory::className(), ['id' => 'category_id']);
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsDetails()
    {
        return $this->hasMany(NewsDetails::className(), ['news_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsImagesPrimary()
    {
        return $this->hasOne(NewsImages::class, ['news_id' => 'id'])->where(['default_image_id' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsFilters()
    {
        return $this->hasMany(NewsFilters::className(), ['news_id' => 'id']);
    }

    /**
     * Gets query for [[NewsImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsImages()
    {
        return $this->hasMany(NewsImages::class, ['news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsModifications()
    {
        return $this->hasMany(NewsModifications::className(), ['news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrNews()
    {
        return $this->hasMany(TrNews::className(), ['news_id' => 'id']);
    }

    /**
     * list of categories
     * @return array
     */
    public function getAllCategories()
    {

        $Categories = NewsCategory::find()->where([])->all();
        return ArrayHelper::map($Categories, 'id', 'name');
    }

    /**
     * @param $news_id
     * @return array
     */
    public function getDefaultImage($news_id)
    {
        $result = NewsImages::find()->where(['news_id' => $news_id, 'default_image_id' => 1, 'type' => 1])->asArray()->all();
//        var_dump(ArrayHelper::map($result,'default_image_id','name'));die;
        return ArrayHelper::map($result, 'default_image_id', 'name');
    }

    public function getImages($news_id)
    {
        $images = NewsImages::find()->where(['news_id' => $news_id, 'type' => 1])->asArray()->all();
        return ArrayHelper::map($images, 'id', 'name');
    }

    public function DeleteData()
    {
        $ProdImages = $this->getImages($this->id);
        foreach ($ProdImages as $item => $prodImage) {
            if (file_exists(Yii::$app->basePath . '/web/uploads/images/' . $prodImage)) {
                unlink(Yii::$app->basePath . '/web/uploads/images/' . $prodImage);
                unlink(Yii::$app->basePath . '/web/uploads/images/thumbnail/' . $prodImage);
            }
            NewsImages::findOne($item)->delete();
        }
        $tr_products = TrNews::findAll(['news_id' => $this->id]);
        foreach ($tr_products as $trporudtc) {
            $trporudtc->delete();
        }

        return $this->delete();
    }

    public static function getImagesToFront($news_id, $class = '', $alt = '', $path = false)
    {
        $params = [
            'class' => $class,
            'alt' => $alt
        ];

        $images = NewsImages::find()->where(['news_id' => $news_id, 'type' => 1, 'default_image_id' => 1])->asArray()->all();
        $dotParts = explode('.', @$images[0]['name']);
        if ($class == 'image-zoom') {
            $params['data-zoom-image'] = Yii::$app->params['adminUrl'] . 'uploads/images/thumbnail/' . $images[0]['name'];
        }

        if (!isset($dotParts[count($dotParts) - 1])) {
            throw new \yii\web\HttpException(404, 'Image must have extension');
        }
        if ($path) {
            return Yii::$app->params['adminUrl'] . 'uploads/images/thumbnail/' . @$images[0]['name'];
        } else {
            return Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/thumbnail/' . @$images[0]['name'], $params);
        }
    }

    /**
     * @param $news_id
     * @param string $class
     * @param string $alt
     * @return array
     */
    public static function getNewsImagesSecondry($news_id, $class = '', $alt = '')
    {
        $params = [
            'class' => 'img-responsive ' . $class,
            'alt' => $alt,
            'data-cloudzoom' => "
								 zoomPosition:'inside',
								 zoomOffsetX:0,
								 zoomFlyOut:false,
								 variableMagnification:false,
								 disableZoom:'auto',
								 touchStartDelay:100,
								 propagateGalleryEvent:true
								 "
        ];

        $images = NewsImages::find()->where(['news_id' => $news_id, 'type' => 1])->asArray()->all();
        $imagesHTML = ['tag' => [], 'url' => []];
        foreach ($images as $image) {
            $dotParts = explode('.', @$image['name']);
            if ($class == 'image-zoom') {
                $params['data-zoom-image'] = Yii::$app->params['adminUrl'] . 'uploads/images/' . $image['name'];
            }

            if (!isset($dotParts[count($dotParts) - 1])) {
                throw new \yii\web\HttpException(404, 'Image must have extension');
            }
            $imagesHTML['tag'][] = Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . @$image['name'], $params);
            $imagesHTML['url'][] = Yii::$app->params['adminUrl'] . 'uploads/images/' . @$image['name'];
        }
        return $imagesHTML;
    }

    /**
     * @param $news_id
     * @param string $class
     * @param string $alt
     * @return mixed
     */
    public static function getImagesToFrontThumb($news_id, $class = '', $alt = '')
    {
        $params = [
            'class' => 'img-responsive ' . $class,
            'alt' => $alt,
        ];

        $images = NewsImages::find()->where(['news_id' => $news_id, 'type' => 1, 'default_image_id' => 1])->asArray()->all();

        $dotParts = explode('.', @$images[0]['name']);

        if ($class == 'image-zoom') {
            $params['data-zoom-image'] = Yii::$app->params['adminUrl'] . 'uploads/images/' . $images[0]['name'];
        }

        if (!isset($dotParts[count($dotParts) - 1])) {
            throw new \yii\web\HttpException(404, 'Image must have extension');
        }

        return Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/thumbnail/' . @$images[0]['name'], $params);
    }

    /**
     * @return bool
     */
    public function updateDefaultTranslate()
    {
        $defaultLanguage = Language::find()->where(['is_default'=>1])->one();
        $tr = TrNews::findOne(['language_id' => $defaultLanguage->id, 'news_id' => $this->id]);

        if (!$tr) {
            $tr = new TrNews();
            $tr->setAttribute('language_id', $defaultLanguage->id);
            $tr->setAttribute('news_id', $this->id);
        }
        $tr->setAttribute('title', $this->title);
        $tr->setAttribute('short_description', $this->short_description);
        $tr->setAttribute('content', $this->content);
        $tr->save();

        return true;
    }

    public static function updatePrimaryNews($newsId)
    {
        $updateQuery = "UPDATE `news` SET `is_primary_news` = 0 where id !=$newsId" ;
        return self::getDb()->createCommand($updateQuery)->execute();
    }
}
