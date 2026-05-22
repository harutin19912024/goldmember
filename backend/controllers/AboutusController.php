<?php

namespace backend\controllers;

use Yii;
use backend\models\Aboutus;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\User;
use backend\models\TrAboutus;
use yii\web\UploadedFile;
use backend\models\Files;
use yii\helpers\BaseFileHelper;
use common\models\Language;
use yii\imagine\Image;

/**
 * AboutusController implements the CRUD actions for Aboutus model.
 */
class AboutusController extends Controller {

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
     * Lists all Aboutus models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Aboutus::find(),
        ]);
        $model = new Aboutus();
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

    /**
     * Displays a single Aboutus model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Aboutus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Aboutus();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelFiles = new Files();
            $file = UploadedFile::getInstances($modelFiles, 'path');

            if (!empty($file)) {
                $ProdDefImg = Yii::$app->request->post('defaultImage');
                $paths = $this->upload($file, $model->id);
                $modelFiles->multiSave($paths, $model->id, $ProdDefImg, 'about');
            }

            $objLang = new Language();
            $languages = $objLang->find()->asArray()->all();
            foreach ($languages as $value) {
                $trmodel = new TrAboutus();
                $trmodel->title = $model->title;
                $trmodel->short_description = $model->short_description;
                $trmodel->description = $model->description;
                $trmodel->aboutus_id = $model->id;
                $trmodel->language_id = $value['id'];
                $trmodel->save();
            }
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
            $modelFiles = new Files();
            return $this->render('create', [
                        'model' => $model,
                        'modelFiles' => $modelFiles,
                        'defoultId' => $defaultLanguage->id
            ]);
        }
    }

    public function upload($imageFile, $id) {
        $directory = Yii::getAlias("@backend/web/uploads/images/about/" . $id);
        $directoryThumb = Yii::getAlias("@backend/web/uploads/images/about/" . $id . "/thumbnail");
        BaseFileHelper::createDirectory($directory);
        BaseFileHelper::createDirectory($directoryThumb);
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $filePath = $directory . '/' . $fileName;
                $filePathThumb = $directoryThumb . '/' . $fileName;
                $image->saveAs($filePath);
                Image::thumbnail($filePath, 120, 120)->save(Yii::getAlias($directoryThumb . '/' . $fileName), ['quality' => 100]);
                $paths[$key + 1] = $fileName;
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
            $directory = Yii::getAlias("@backend/web/uploads/images/about/" . $model->category_id);
            $directoryThumb = Yii::getAlias("@backend/web/uploads/images/about/" . $model->category_id . "/thumbnail");

            // BaseFileHelper::removeDirectory($directoryThumb);
            //BaseFileHelper::removeDirectory($directory);
            if (file_exists($directory . '/' . $model->path)) {
                unlink($directory . '/' . $model->path);
                unlink($directoryThumb . '/' . $model->path);
            }

            return $model->delete();
        }
    }

    /**
     * Updates an existing Aboutus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $modelFiles = new Files();
            $file = UploadedFile::getInstances($modelFiles, 'path');

            if (!empty($file)) {
                $ProdDefImg = Yii::$app->request->post('defaultImage');
                $paths = $this->upload($file, $model->id);
                \Yii::$app
                ->db
                ->createCommand()
                ->delete('files', ['category' => 'about'])
                ->execute();
                foreach ($paths as $key => $value) {
                    $updateFile = new Files();
                    $updateFile->path = $value;
                        $updateFile->category_id = $model->id;
                        $updateFile->category = 'about';
                        $updateFile->top = 1;
                        $updateFile->status = 1;
                        $updateFile->faxbid_or_vinradar = 1;
                    $updateFile->save();
                }
            }
            $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
            $trmodel = TrAboutus::findOne(['aboutus_id' => $id, 'language_id' => $defaultLanguage->id]);
            if (!empty($trmodel)) {
                $trmodel->title = $model->title;
                $trmodel->short_description = $model->short_description;
                $trmodel->description = $model->description;
                $trmodel->save();
            } else {
                $tr_attribute = new TrAboutus();
                $tr_attribute->title = $model->title;
                $tr_attribute->description = $model->description;
                $tr_attribute->short_description = $model->short_description;
                $tr_attribute->language_id = $defaultLanguage->id;
                $tr_attribute->aboutus_id = $model->id;
				$tr_attribute->save();
            }
            return $this->redirect('index');
        } else {
            $images = Files::find()->where(['category' => 'about', 'category_id' => $id])->asArray()->all();
            $modelFiles = new Files();
            return $this->render('update', [
                        'model' => $model,
                        'modelFiles' => $modelFiles,
                        'images' => $images,
            ]);
        }
    }

    /**
     * Deletes an existing Aboutus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Aboutus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Aboutus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Aboutus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return bool
     */
    public function actionDefaultImage() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
			if($data['category'] == "aboutus"){
				$data['category'] = "about";
			}
            return Files::updatDefaultImage($data['newid'], $data['product_id'],$data['category']);
        }
    }

}
