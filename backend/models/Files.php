<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%files}}".
 *
 * @property string $id
 * @property string $path
 * @property integer $category_id
 * @property string $category
 * @property integer $status
 * @property integer $top
 */
class Files extends \yii\db\ActiveRecord {
    
    public static $Extensions = ['jpg', 'png'];
    public $imageFiles;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%files}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['category_id', 'category', 'status', 'top'], 'required'],
            [['category_id', 'status', 'top', 'faxbid_or_vinradar'], 'integer'],
            [['path', 'category', 'title','link'], 'string', 'max' => 255],
            [['path'], 'image',  'skipOnEmpty' => true,  'extensions' => 'png, jpg','maxFiles' => 10,'maxSize' => 1024 * 1024 * 5]
        ];
    }
//'minWidth' => 50,'minHeight' => 50, 'maxWidth' => 600,'maxHeight' => 800,
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'path' => Yii::t('app', 'Path'),
            'category_id' => Yii::t('app', 'Category ID'),
            'category' => Yii::t('app', 'Category'),
            'status' => Yii::t('app', 'Status'),
            'top' => Yii::t('app', 'Top'),
        ];
    }

    /**
     * @param $productId
     * @return array
     */
    public static function getDefaultImageIdByProductId($category_id, $category) {
        $data = self::find()->where(['category_id' => $category_id, 'category' => $category, 'top' => 1])->asArray()->all();
        return ArrayHelper::map($data, 'id', 'id');
    }
    
    public function saveGallery($paths)
    {
        foreach ($paths as $key => $value) {

                $data[] = [
                    'title' => $this->title,
                    'path' => $value,
                    'category_id' => null,
                    'category' => 'gallery',
                    'status' => 1
                ];
            }
            Yii::$app->db->createCommand()
                    ->batchInsert(
                            'files', ['title','path', 'category_id', 'category', 'status'], $data
                    )
                    ->execute();
            return true;
    }

    /**
     * @param $paths
     * @param $product_id
     * @param $default_image
     * @return bool
     */
    public function multiSave($paths, $product_id, $default_image, $img_type, $isVinRadar = 1) {
        if (!empty($paths)) {
            $data = [];
            //$paths = isset($paths['path']) ? $paths['path'] : $paths;

            foreach ($paths as $key => $value) {

                if ($key == $default_image) {
                    $data[] = [
                        'path' => $value,
                        'category_id' => $product_id,
                        'category' => $img_type,
                        'top' => $default_image,
                        'extension' =>$paths['extension'][$key] ?? '',
                        'status' => 1
                    ];
                } else {
                    $data[] = [
                        'path' => $value,
                        'category_id' => $product_id,
                        'category' => $img_type,
                        'top' => 0,
						'extension' => $paths['extension'][$key] ?? '',
                        'status' => 1
                    ];
                }
            }
            Yii::$app->db->createCommand()
                    ->batchInsert(
                            'files', ['path', 'category_id', 'category', 'top','extension', 'status'], $data
                    )
                    ->execute();
            return true;
        }
        return false;
    }

    public function getDefaultImage($id)
    {
        $result = self::find()->where(['id' => $id, 'top' => 1])->asArray()->all();
//        var_dump(ArrayHelper::map($result,'default_image_id','name'));die;
        return ArrayHelper::map($result, 'top', 'title');
    }

    public static function updatDefaultImage($new_id, $category_id, $category) {
        if ($category == 'aboutus') {
            $category = 'about';
        }
        self::getDb()->createCommand("UPDATE files SET top = 0 WHERE category_id = $category_id AND category = '" . $category . "'")->execute();
        self::getDb()->createCommand("UPDATE files SET top = 1 WHERE id = $new_id AND category_id = $category_id")->execute();
        return true;
    }

    public static function getImagesToFront($category, $category_id = null, $class = '', $alt = '', $top = 0) {
        $params = [
            'class' => $class,
            'alt' => $alt,
        ];
        if (!is_null($category_id)) {
            $images = self::find()->where(['category' => $category, 'category_id' => $category_id, 'status' => 1, 'top' => $top])->asArray()->all();
        } else {
            $images = self::find()->where(['category' => $category, 'status' => 1])->asArray()->all();
        }

        if (!empty($images[0])) {
            return Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . $category . '/' . $category_id . '/' . $images[0]['path'], $params);
        } else {
            return Html::img(Yii::$app->params['adminUrl'] . 'img/default.png',$params);
        }
    }

    public static function getImagesToThumb($category, $category_id = null, $class = '', $alt = '', $top = 0) {
        $params = [
            'class' => $class,
            'alt' => $alt,
        ];
        if (!is_null($category_id)) {
            $images = self::find()->where(['category' => $category, 'category_id' => $category_id, 'status' => 1, 'top' => $top])->asArray()->all();
        } else {
            $images = self::find()->where(['category' => $category, 'status' => 1])->asArray()->all();
        }

        if (!empty($images[0])) {
            return Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . $category . '/' . $category_id . '/thumbnail/' . $images[0]['path'], $params);
        } else {
            return Html::img(Yii::$app->params['adminUrl'] . 'img/default.png');
        }
    }

    public static function getImagesThumb($category, $category_id = null, $class = '', $alt = '', $top = 0) {
        $params = [
            'class' => $class,
            'alt' => $alt,
        ];
        if (!is_null($category_id)) {
            $images = self::find()->where(['category' => $category, 'category_id' => $category_id, 'status' => 1, 'top' => $top])->asArray()->all();
        } else {
            $images = self::find()->where(['category' => $category, 'status' => 1])->asArray()->all();
        }
        if (!empty($images[0])) {
            return Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . $category . '/' . $category_id . '/thumbnail/' . $images[0]['path'], $params);
        } else {
            return Html::img(Yii::$app->params['adminUrl'] . 'img/default.png');
        }
    }

    public static function slider() {
        return self::find()->where(['category' => 'slider', 'status' => 1])->asArray()->all();
    }
    
     /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $category = '')
    {
        if($category != '') {
            $query = self::find()->where(['category' => $category]);
        } else {
            $query = self::find();
        }
        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        // $query->andFilterWhere(['like', 'description', $this->description])
        //     ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

}
