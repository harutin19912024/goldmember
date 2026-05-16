<?php

namespace backend\controllers;

use Yii;
use backend\models\Pages;
use backend\models\PagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Language;
use backend\models\User;
use backend\models\TrPages;
use backend\models\Files;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;

/**
 * PagesController implements the CRUD actions for Pages model.
 */
class PagesController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'view' => ['GET'],
                    //'create' => ['POST'],
                     'update' => ['POST','GET'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => 'common\components\CAccessRule',
                ],
                'only' => ['index', 'view', 'create', 'update', 'delete','delete-image'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete','delete-image'],
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
     * Lists all Pages models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new Pages();
        $searchModel = new PagesSearch();
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
    public function actionGetform() {
        $model = new Pages();
        $searchModel = new PagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
            echo $_form = $this->renderPartial('_form', [
                    'model' => $model,
            ]);
            exit();
        }

        $_form = $this->renderPartial('_form', [
            'model' => $model,
        ]);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    '_Form' => $_form
        ]);
    }

    /**
     * Displays a single Pages model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (Yii::$app->request->post()) {

            $pages = new Pages();
            $postData = Yii::$app->request->post('Pages');
            $pages->setAttributes($postData);

            $order = Pages::find() // AQ instance
                    ->select('max(ordering)') // we need only one column
                    ->scalar();
            $pages->ordering = $order ? $order + 1 : 1;
            if ($pages->save()) {
				$modelFiles = new Files();
				$file = UploadedFile::getInstances($modelFiles, 'path');
				if (!empty($file)) {
					$ProdDefImg = Yii::$app->request->post('defaultImage');
					$paths = $this->upload($file, $pages->id);
					$modelFiles->multiSave($paths, $pages->id, $ProdDefImg, 'pages');
				}
				
                $objLang = new Language();
                $languages = $objLang->find()->asArray()->all();
                foreach ($languages as $value) {

                        $model = new TrPages();
                        $model->title = $pages->title;
                        //$model->short_description = $pages->short_description;
                        $model->content = $pages->content;
                        $model->pages_id = $pages->id;
                        $model->language_id = $value['id'];
                        $model->save();

                }
                Yii::$app->session->setFlash('success', 'Page successfully created');
                return $this->redirect(['update',
                            'id' => $pages->id,
                ]);
            }
        } else {
            $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
            $model = new Pages();
			$modelFiles = new Files();
            return $this->render('create', [
                        'model' => $model,
						'modelFiles' => $modelFiles,
                        'defoultId' => $defaultLanguage->id
            ]);
        }


        $model = new Pages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if(isset(Yii::$app->request->post()['Pages']) && !empty(Yii::$app->request->post()['Pages'])) {
            $model_m = new TrPages();

            $arrPost = Yii::$app->request->post()['Pages'];
            $model->setAttributes($arrPost);
            $model->save();
            $trModel = $model_m->findOne(['language_id' => 1, 'pages_id' => $id]);

            if ($trModel) {
                $trModel->title = $arrPost['title'];
                //$trModel->short_description = $arrPost['short_description'];
                $trModel->content = $arrPost['content'];
            } else {
                $trModel = new TrPages();
                $trModel->title = $arrPost['title'];
                //$trModel->short_description = $arrPost['short_description'];
                $trModel->content = $arrPost['content'];
                $trModel->language_id = 1; 
                $trModel->pages_id = $id;
            }
			
			
			$modelFiles = new Files();
            $file = UploadedFile::getInstances($modelFiles, 'path');

            if (!empty($file)) {
                $ProdDefImg = Yii::$app->request->post('defaultImage');
                $paths = $this->upload($file, $model->id);
                foreach ($paths['path'] as $key => $value) {
                    $updateFile = new Files();
                    if ($key == $ProdDefImg) {
                        $updateFile->path = $value;
                        $updateFile->category_id = $model->id;
                        $updateFile->category = 'pages';
                        $updateFile->top = $ProdDefImg;
                        $updateFile->status = 1;
                        $updateFile->extension = $paths['extension'][$key];
                    } else {
                        $updateFile->path = $value;
                        $updateFile->category_id = $model->id;
                        $updateFile->category = 'pages';
                        $updateFile->top = 0;
                        $updateFile->status = 1;
						$updateFile->extension = $paths['extension'][$key];
                    }
                    $updateFile->save();
                }
            }

            if ($trModel->save()) {
               return $this->redirect('index');
            } else {
                 return $this->redirect(['update', 'id' => $model->id]);
            }
        }

		$modelFiles = new Files();
		$images = Files::find()->where(['category' => 'pages', 'category_id' => $id])->asArray()->all();
		return $this->render('update', [
					'model' => $model,
					'modelFiles' => $modelFiles,
					'images' => $images,
		]);

    }

    /**
     * Deletes an existing Pages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $tr_pages = TrPages::find()->where(['pages_id' => $id])->all();
        foreach ($tr_pages as $tr_page) {
            $tr_page->delete();
        }
        if (TrPages::find()->where(['pages_id' => $id])->count() == 0) {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Page successfully removed');
            return $this->redirect(['index']);
        }
    }
	
	public function upload($imageFile, $id) {
        $directory = Yii::getAlias("@backend/web/uploads/images/pages/" . $id);
        //$directoryThumb = Yii::getAlias("@backend/web/uploads/images/pages/" . $id . "/thumbnail");
        BaseFileHelper::createDirectory($directory);
        //BaseFileHelper::createDirectory($directoryThumb);
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $fileName_return = $fileName;
                $filePath = $directory . '/' . $fileName;
                //$filePathThumb = $directoryThumb . '/' . $fileName;
                $image->saveAs($filePath);
				/* if($image->extension != "pdf"){
					Image::thumbnail($filePath, 120, 120)->save(Yii::getAlias($directoryThumb . '/' . $fileName), ['quality' => 100]);
				} */
                
                $paths['path'][$key + 1] = $fileName_return;
                $paths['extension'][$key + 1] = $image->extension;
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
            $model = new Files();
            $id = Yii::$app->request->post('id');
			$directory = Yii::getAlias("@backend/web/uploads/images/pages/" . $id);
            $model = $model->findOne($id);
            if (file_exists($directory.'/'.$model->path)) {
                unlink($directory.'/'.$model->pat);
            }
            return $model->delete();
        }
    }
	
	   /**
     * @return bool
     */
    public function actionDefaultImage() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            return Files::updatDefaultImage($data['newid'], $data['product_id'],$data['category']);
        }
    }

    /**
     * Finds the Pages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Pages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
