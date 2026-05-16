<?php

namespace backend\controllers;

use Yii;
use backend\models\Slider;
use backend\models\SliderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\User;
use yii\web\UploadedFile;
use backend\models\Files;
use yii\helpers\BaseFileHelper;
use common\models\Language;
use yii\imagine\Image;

/**
 * SliderController implements the CRUD actions for Slider model.
 */
class SliderController extends Controller {

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
                'only' => ['index', 'view', 'create', 'update', 'delete', 'allrules', 'trcats', 'trproducts'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'allrules', 'trcats', 'trproducts'],
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
     * Lists all Slider models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SliderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Slider model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Slider model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Slider();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = UploadedFile::getInstances($model, 'path');
            if (!empty($file)) {
                $paths = $this->upload($file, $model->id);
				$paths = array_values($paths);
				$model->path = $paths[0];
            }
			$model->save();
            Yii::$app->session->setFlash('success', Yii::t('app','Slider successfully created'));
            return $this->redirect("index");
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Slider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstances($model, 'path');
            if (!empty($file)) {
				$directory = Yii::getAlias("@backend/web/uploads/images/slider/" . $model->id);
				$thumbnailproduct = Yii::getAlias("@backend/web/uploads/images/slider/" . $id . "/thumbnailproduct");
				$directoryThumb = Yii::getAlias("@backend/web/uploads/images/slider/" . $id . "/thumbnail");
				$slider_path = Slider::find()->where(['id'=>$id])->one();
				if (file_exists($directory . '/' . $slider_path->path) && $slider_path->path != "") {
					unlink($directory . '/' . $slider_path->path);
					unlink($thumbnailproduct . '/' . $slider_path->path);
					unlink($directoryThumb . '/' . $slider_path->path);
					BaseFileHelper::removeDirectory($directory);
					BaseFileHelper::removeDirectory($directoryThumb);
					BaseFileHelper::removeDirectory($thumbnailproduct);
				}
                $paths = $this->upload($file, $model->id);
				$paths = array_values($paths);
				$model->path = $paths[0];
            }else{
				$model->path = Yii::$app->request->post('imagePath');
			}
			$model->save();
            return $this->redirect("index");
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Slider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Slider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Slider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Slider::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function upload($imageFile, $id) {
        $directory = Yii::getAlias("@backend/web/uploads/images/slider/" . $id);
        $thumbnailproduct = Yii::getAlias("@backend/web/uploads/images/slider/" . $id . "/thumbnailproduct");
        $directoryThumb = Yii::getAlias("@backend/web/uploads/images/slider/" . $id . "/thumbnail");
        BaseFileHelper::createDirectory($directory);
        BaseFileHelper::createDirectory($directoryThumb);
        BaseFileHelper::createDirectory($thumbnailproduct);
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $fileName_return = $fileName;
                $filePath = $directory . '/' . $fileName;
                //$filePathThumb = $directoryThumb . '/' . $fileName;
                $image->saveAs($filePath);
				Image::thumbnail($filePath, 850, 195)->save(Yii::getAlias($thumbnailproduct . '/' . $fileName), ['quality' => 100]);
                Image::thumbnail($filePath, 850, 330)->save(Yii::getAlias($directoryThumb . '/' . $fileName), ['quality' => 100]);
                $paths[$key + 1] = $fileName_return;
            }
            return $paths;
        }
        return false;
    }

    /**
     * @return false|int
     * @throws \Exception
     */
    public function actionDeleteImage() {
        if (Yii::$app->request->isAjax) {
            $model = new Files();
            $id = Yii::$app->request->post('id');
            $model = $model->findOne($id);
            $directory = Yii::getAlias("@backend/web/uploads/images/slider/" . $model->category_id);
            //$directoryThumb = Yii::getAlias("@backend/web/uploads/images/slider/" . $model->category_id . "/thumbnail");

            // BaseFileHelper::removeDirectory($directoryThumb);
            //BaseFileHelper::removeDirectory($directory);
            if (file_exists($directory . '/' . $model->path)) {
                unlink($directory . '/' . $model->path);
               // unlink($directoryThumb . '/' . $model->path);
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

}
