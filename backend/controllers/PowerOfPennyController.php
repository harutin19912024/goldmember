<?php

namespace backend\controllers;

use backend\models\Files;
use common\models\Language;
use backend\models\PowerOfPenny;
use backend\models\TrPowerOfPenny;
use backend\models\PowerOfPennySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use yii\helpers\BaseFileHelper;
use Yii;

/**
 * PowerOfPennyController implements the CRUD actions for PowerOfPenny model.
 */
class PowerOfPennyController extends Controller
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
     * Lists all PowerOfPenny models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PowerOfPennySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PowerOfPenny model.
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
     * Creates a new PowerOfPenny model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PowerOfPenny();
        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
        $modelFiles = new Files();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $ProdDefImg = Yii::$app->request->post('defaultImage');
                $objLang = new Language();
                $languages = $objLang->find()->asArray()->all();
                foreach ($languages as $value) {
                    $News = new TrPowerOfPenny();
                    $News->name = $model->name;
                    $News->content = $model->content;
                    $News->power_of_penny_id = $model->id;
                    $News->language_id = $value['id'];
                    $News->save();
                }
                $images = UploadedFile::getInstances($model, 'imageFiles');
                $paths = $this->upload($images, $model->id);
                $modelFiles->multiSave($paths, $model->id, $ProdDefImg, 'about');

                Yii::$app->session->setFlash('success', 'News successfully created');
                return $this->redirect(['power-of-penny/update',
                    'id' => $model->id,
                    'defoultId' => $defaultLanguage->id,
                ]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'defoultId' => $defaultLanguage->id,
        ]);
    }

    /**
     * Updates an existing PowerOfPenny model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PowerOfPenny model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PowerOfPenny model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PowerOfPenny the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PowerOfPenny::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function upload($imageFile, $id)
    {
        $directory = 'uploads/power-of-penny/news/'.$id.'/';
        //$directoryThumbMin = 'uploads/images/thumbnail-min/';
        BaseFileHelper::createDirectory($directory);
        if (!is_dir($directory)) {
            mkdir($directory);
        }
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $filePath = $directory . $fileName;
                if ($image->saveAs($filePath)) {
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
}
