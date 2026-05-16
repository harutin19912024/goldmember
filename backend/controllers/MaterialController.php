<?php

namespace backend\controllers;

use backend\models\Material;
use backend\models\TrMaterial;
use backend\models\MaterialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use common\models\Language;
use Yii;


/**
 * MaterialController implements the CRUD actions for Material model.
 */
class MaterialController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Material models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MaterialSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Material model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Material model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Material();
        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $file = UploadedFile::getInstances($model, 'path');
                if (!empty($file)) {
                    $paths = $this->upload($file, $model->id);
                    $paths = array_values($paths);
                    $model->path = $paths[0];
                }
                
                $objLang = new Language();
                $languages = $objLang->find()->asArray()->all();
                foreach ($languages as $value) {
                        $trModel = new TrMaterial();
                        $trModel->name = $model->name;
                        $trModel->short_description = $model->short_description;
                        $trModel->description = $model->description;
                        $trModel->material_id = $model->id;
                        $trModel->language_id = $value['id'];
                        $trModel->save();

                }
                Yii::$app->session->setFlash('success', 'Category successfully created');
                return $this->redirect(['update',
                            'id' => $model->id,
                            'defoultId' => $defaultLanguage->id
                ]);
            }
        } else {
            $model->loadDefaultValues();
        }

        
        return $this->render('create', [
            'model' => $model,
            'defoultId' => $defaultLanguage->id
        ]);
    }

    /**
     * Updates an existing Material model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
           $file = UploadedFile::getInstances($model, 'path');
            if (!empty($file)) {
                $directory = Yii::getAlias("@backend/web/uploads/images/material/" . $model->id);
                $material = Material::find()->where(['id' => $id])->one();
                if (file_exists($directory . '/' . $material->path) && $path->path != "") {
                    unlink($directory . '/' . $material->path);
                    BaseFileHelper::removeDirectory($directory);
                }
                $paths = $this->upload($file, $model->id);
                $paths = array_values($paths);
                $model->path = $paths[0];
            } else {
                $model->path = Yii::$app->request->post('imagePath');
            }
            if($model->save()){
                $model->updateTranslation();
				Yii::$app->session->setFlash('success', Yii::t('app','Material successfully updated'));
                return $this->redirect(['index']);
			}
            Yii::$app->session->setFlash('error', Yii::t('app','Material update error'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Material model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $tr_categories = TrMaterial::find()->where(['material_id'=>$id])->all();
        foreach($tr_categories as $tr_categoru){
            $tr_categoru->delete();
        }
        if (TrMaterial::find()->where(['material_id'=>$id])->count() == 0) {
            $model = $this->findModel($id);
            if($model){
                $model->delete();
                Yii::$app->session->setFlash('success', 'Material successfully removed');
            }
            Yii::$app->session->setFlash('success', 'Something went wrong!');
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Material model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Material the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Material::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    public function upload($imageFile, $id) {
        $directory = Yii::getAlias("@backend/web/uploads/images/material/" . $id);
        BaseFileHelper::createDirectory($directory);
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $fileName_return = $fileName;
                $filePath = $directory . '/' . $fileName;
                $image->saveAs($filePath);
                $paths[$key + 1] = $fileName_return;
            }
            return $paths;
        }
        return false;
    }
}
