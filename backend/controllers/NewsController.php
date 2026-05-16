<?php

namespace backend\controllers;

use backend\models\Attribute;
use backend\models\Brand;
use backend\models\NewsCategory;
use backend\models\Category;
use backend\models\NewsImages;
use Yii;
use backend\models\TrNews;
use backend\models\News;
use backend\models\NewsSearch;
use backend\models\NewsFilters;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use yii\helpers\BaseFileHelper;
use yii\filters\AccessControl;
use backend\models\User;
use common\models\Language;
use common\components\RuleHelper;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'view' => ['GET'],
                    'create' => ['GET', 'POST'],
                    'update' => ['GET', 'POST'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => 'common\components\CAccessRule',
                ],
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            User::ADMIN,
                        ],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $model = new News();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'defoultId' => $defaultLanguage->id
        ]);
    }

    /**
     * Lists all Brand models.
     * @return mixed
     */
    public function actionGetform()
    {
        $model = new News();
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
            echo $_form = $this->renderAjax('_form', [
                'model' => $model,
                'categories' => $model->getAllCategories()
            ]);
            exit();
        }

        $_form = $this->renderPartial('_form', [
            'model' => $model,
            'categories' => $model->getAllCategories(),
            'attributes' => $attributes
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            '_Form' => $_form
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $News_image_model = new NewsImages();

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            if ($post['News']['top_news']) {
                $post['News']['top_news'] = (int)$post['News']['top_news'];
            }
            if ($post['News']['latest_news']) {
                $post['News']['latest_news'] = (int)$post['News']['latest_news'];
            }
            if ($post['News']['is_primary_news']) {
                $post['News']['is_primary_news'] = (int)$post['News']['is_primary_news'];
            }

            $model = new News();
            $News_image_model = new NewsImages();
            // $News_filters = new NewssFilters();
            $ProdDefImg = Yii::$app->request->post('defaultImage');
            $sub_attr_id = Yii::$app->request->post('sub_attr_id');
            $connectNewsPost = Yii::$app->request->post('connectNews');
            $NewssCount = News::find()->count();

            $model->load($post);
            $order = News::find()// AQ instance
            ->select('max(ordering)')// we need only one column
            ->scalar();
            $model->ordering = $order ? $order + 1 : 1;
            $model->rate = 0;
            if ($model->save()) {
                if($model->is_primary_news) {
                    News::updatePrimaryNews($model->id);
                }
                $category = NewsCategory::findOne($model->category_id);
                RuleHelper::setFile("news-routes.json");
                RuleHelper::setPath(Yii::$app->basePath . "/../frontend/config");
                RuleHelper::makeRule($category->route_name . '/' . $model->route_name, "news/view", $model->id);

                // if (!empty($connectNewsPost)) {
                //     foreach ($connectNewsPost as $conNews) {
                //         $connectNews = new ConnectedNewss();
                //         $connectNews->news_id = $model->id;
                //         $connectNews->conn_news_id = $conNews;
                //         $connectNews->save();
                //     }
                // }
                if (!empty($sub_attr_id)) {
                    foreach ($sub_attr_id as $key => $filters) {
                        $News_filters = new NewssFilters();
                        $News_filters->news_id = $model->id;
                        if (isset($filters['value'])) {
                            $News_filters->value = $filters['value'];
                        } else {
                            $News_filters->value = $filters['option'];
                        }
                        $News_filters->filter_id = isset($filters['option']) ? $filters['option'] : null;
                        $News_filters->attribute_id = $key;
                        $News_filters->save();
                    }
                }

                $objLang = new Language();
                $languages = $objLang->find()->asArray()->all();
                foreach ($languages as $value) {
                    $News = new TrNews();
                    $News->title = $model->title;
                    $News->short_description = $model->short_description;
                    $News->content = $model->content;
                    $News->news_id = $model->id;
                    $News->language_id = $value['id'];
                    $News->save();
                }

                $images = UploadedFile::getInstances($model, 'imageFiles');
                $paths = $this->upload($images, $model->id);
                $News_image_model->multiSave($paths, $model->id, $ProdDefImg, 1);

                Yii::$app->session->setFlash('success', 'News successfully created');
                return $this->redirect(['news/update',
                    'id' => $model->id,
                    'defoultId' => $defaultLanguage->id,
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Somthing want wrong');
                $news = $newssCount = News::find()->asArray()->all();
                return $this->render('create', [
                    'model' => $model,
                    'defoultId' => $defaultLanguage->id,
                    'news' => $news,
                    'categories' => $model->getAllCategories(),
                ]);
            }
        } else {
            $news = $newsCount = News::find()->asArray()->all();
            $model = new News();
            return $this->render('create', [
                'model' => $model,
                'news' => $news,
                'defoultId' => $defaultLanguage->id,
                'categories' => $model->getAllCategories(),
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $News_image_model = new NewsImages();
        $ProdDefImg = Yii::$app->request->post('defaultImage');
        $sub_attr_id = Yii::$app->request->post('sub_attr_id');
        if (Yii::$app->request->post()) {
            $oldRouteName = $model->route_name;
            $post = Yii::$app->request->post();
            if ($post['News']['top_news']) {
                $post['News']['top_news'] = (int)$post['News']['top_news'];
            }
            if ($post['News']['latest_news']) {
                $post['News']['latest_news'] = (int)$post['News']['latest_news'];
            }
            if ($post['News']['is_primary_news']) {
                $post['News']['is_primary_news'] = (int)$post['News']['is_primary_news'];
            }

            // $model->art_no = 1000 + (int)$id . "Chew";
            $model->load($post);
            if ($model->save()) {
                if($model->is_primary_news) {
                    News::updatePrimaryNews($model->id);
                }
                //echo "<pre>";print_r($model);die;
                $category = NewsCategory::findOne($model->category_id);
                RuleHelper::setFile("news-routes.json");
                RuleHelper::setPath(Yii::$app->basePath . "/../frontend/config");
                RuleHelper::updateRule($category->route_name . '/' . $model->route_name, "news/view", $model->id, $oldRouteName);
                if (!empty($sub_attr_id)) {
                    $filters = NewssFilters::getDb()->createCommand()->
                    delete(NewssFilters::tableName(), ['news_id' => $model->id])
                        ->execute();
                    foreach ($sub_attr_id as $key => $filters) {
                        $News_filters = new NewsFilters();
                        $News_filters->news_id = $model->id;
                        if (isset($filters['value'])) {
                            $News_filters->value = $filters['value'];
                        } else {
                            $News_filters->value = $filters['option'];
                        }
                        $News_filters->filter_id = isset($filters['option']) ? $filters['option'] : null;
                        $News_filters->attribute_id = $key;
                        $News_filters->save();
                    }
                }

                // $connectNewsPost = Yii::$app->request->post('connectNews');
                // if (!empty($connectNewsPost)) {
                //     ConnectedNewss::deleteAll('news_id = :id', [':id' => $id]);
                //     foreach ($connectNewsPost as $conNews) {
                //         $connectNews = new ConnectedNewss();
                //         $connectNews->news_id = $model->id;
                //         $connectNews->conn_news_id = $conNews;
                //         $connectNews->save();
                //     }
                // }
                $images = UploadedFile::getInstances($model, 'imageFiles');
                $paths = $this->upload($images, $model->id);
                $News_image_model->multiSave($paths, $model->id, '', 1);
                $model->updateDefaultTranslate();
                Yii::$app->session->setFlash('success', Yii::t('app', 'News successfully updated'));
                return $this->redirect(['news/update',
                    'id' => $model->id,
                ]);
            } else {
                $Newss = News::find()->where(['!=', 'id', $id])->asArray()->all();
               // $connNewss = ConnectedNews::find()->where(['news_id', $id])->asArray()->all();
                return $this->render('update', [
                    'model' => $model,
                    'news' => $Newss,
                    //'connNewss' => $connNewss,
                    'categories' => $model->getAllCategories(),
                    'news_attribute_model' => $News_attribute_model,
                ]);
            }
        } else {
            $Newss = News::find()->where(['!=', 'id', $id])->asArray()->all();
            // $connNewssAll = ConnectedNews::find()->where(['News_id' => $id])->asArray()->all();
            // $connNewss = [];
            // foreach ($connNewssAll as $NewsID) {
            //     $connNewss[$NewsID['News_id']][] = $NewsID['conn_News_id'];
            // }
            
            return $this->render('update', [
                'model' => $model,
                'news' => $Newss,
                // 'connNews' => $connNewss,
                'categories' => $model->getAllCategories()
            ]);
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $News = $this->findModel($id);
        if ($News) {
            if ($News->DeleteData($id)) {
                return $this->redirect(['index']);
            }
        }
    }

    public function actionDeleteByAjax()
    {
        if (Yii::$app->request->isAjax) {
            $News_ids = Yii::$app->request->post('ids');
            try {
                $forinkeys = [];
                $allow = true;
                foreach ($News_ids as $id) {
                    $model = News::findOne($id);
                    $model->DeleteData();
                }
                if ($allow) {
                    echo true;
                    exit();
                }
                print_r(json_encode($forinkeys));
                exit();
            } catch (\mysqli_sql_exception $e) {
                Yii::$app->session->setFlash('error', 'you are not deleted');
                echo json_encode(['deleted' => 'error']);
                exit();
            }
        }
    }

    public function actionChangestatus()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('data');
            $data = json_decode($post);
            $model = $this->findModel($data->id);
            $model->status = $data->status;
            if ($model->update()) {
                echo 'true';
                exit();
            } else {
                echo 'false';
                exit();
            }
        }
    }

    public function actionChangeItemsStatus()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('data');
            $News_ids = $post['id'];
            $status = $post['status'];
            try {
                if (News::updateAll(['status' => $status], ['in', 'id', $News_ids])) {
                    echo true;
                    exit();
                }
                echo false;
                exit();
            } catch (\mysqli_sql_exception $e) {
                echo false;
                exit();
            }
        }
    }

    public function actionAddInSlider()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('data');
            $News_ids = $post['id'];
            try {
                if (News::updateAll(['in_slider' => 1], ['in', 'id', $News_ids])) {
                    echo true;
                    exit();
                }
                echo false;
                exit();
            } catch (\mysqli_sql_exception $e) {
                echo false;
                exit();
            }
        }
    }


    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function upload($imageFile, $id)
    {
        $directory = 'uploads/images/news/'.$id.'/';
        $directoryThumb = 'uploads/images/news/thumbnail/'.$id;
        //$directoryThumbMin = 'uploads/images/thumbnail-min/';
        BaseFileHelper::createDirectory($directory);
        BaseFileHelper::createDirectory($directoryThumb);
        if (!is_dir($directory)) {
            mkdir($directory);
        }
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $filePath = $directory . $fileName;
                $filePathThumb = $directoryThumb . $fileName;
                if ($image->saveAs($filePath)) {
                    Image::thumbnail($filePath, 360, 360)->save(Yii::getAlias($directoryThumb . '/' . $fileName), ['quality' => 100]);
                    $paths[$key + 1] = $fileName;
                } else {
                    echo "<pre>";
                    print_r($directory);
                    die;
                }
            }
            return $paths;
        }
        return false;
    }

    /**
     * @return false|int
     * @throws \Exception
     */
    public function actionDeleteImage()
    {
        if (Yii::$app->request->isAjax) {
            $model = new NewsImages();
            $id = Yii::$app->request->post('id');
            $model = $model->findOne($id);
            if (file_exists($model->name)) {
                unlink($model->name);
            }
            return $model->delete();
        }
    }

    /**
     * @return bool
     */
    public function actionDefaultImage()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            return NewsImages::updatDefaultImage($data['newid'], $data['oldid']);
        }
    }

}
