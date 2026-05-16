<?php

namespace backend\controllers;

use backend\models\Files;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use yii\helpers\BaseFileHelper;
use Yii;

/**
 * MediaController implements the CRUD actions for InteriorColors model.
 */
class MediaController extends Controller
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
     * Lists all InteriorColors models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Files();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionGallery() 
    {
        $searchModel = new Files();
        $dataProvider = $searchModel->search($this->request->queryParams, 'gallery');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pageName' => 'Gallery',
            'isGallery' => 1,
        ]);
    }
    
    public function actionVideo() 
    {
        $searchModel = new Files();
        $dataProvider = $searchModel->search($this->request->queryParams, 'video');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pageName' => 'Video',
            'isGallery' => 0,
        ]);
    }

    /**
     * Displays a single Files model.
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
     * Creates a new Files model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Files();
        $isGallery = $this->request->queryParams['isGallery'];

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $isGallery) {
                $images = UploadedFile::getInstances($model, 'path');
                $paths = $this->upload($images);
                $model->saveGallery($paths);
                return $this->redirect('gallery');
            } else {
                $model->load($this->request->post());
                $model->category = "video";
                $model->save();
                return $this->redirect('video');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'isGallery' => $isGallery,
            'pageName' => ($isGallery == '1') ? 'Gallery' : 'Video'
        ]);
    }

    /**
     * Updates an existing Files model.
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
     * Deletes an existing Files model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $isGallery = $this->request->queryParams['isGallery'];
        if($isGallery) {
            return $this->redirect(['media/gallery']);
        } else {
            return $this->redirect(['media/video']);
        }
    }

    /**
     * Finds the Files model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Files the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Files::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    public function upload($imageFile)
    {
		set_time_limit(0);
		ini_set('memory_limit', '-1');
        $directory = Yii::getAlias("@backend/web/uploads/gallery");
        BaseFileHelper::createDirectory($directory);
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $filePath = $directory . '/' . $fileName;
                $image->saveAs($filePath);

                $paths[$key + 1] = $fileName;
            }
            return $paths;
        }
        return false;
    }
}
