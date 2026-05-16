<?php

namespace backend\controllers;

use Yii;
use backend\models\Team;
use backend\models\TeamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Files;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Team models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Team model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Team model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Team();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelFiles = new Files();
            $file = UploadedFile::getInstances($modelFiles, 'path');
            if (!empty($file)) {
                $model->image = $this->upload($file, $model->id);
                $model->save();
            }

            return $this->redirect('index');
        }

        $modelFiles = new Files();

        return $this->render('create', [
            'model' => $model,
            'modelFiles' => $modelFiles,
        ]);
    }

    /**
     * Updates an existing Team model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //echo "<pre>"; print_r(Yii::$app->request->post());die;

            $modelFiles = new Files();
            $cropedData = Yii::$app->request->post('Crop');
            $file = UploadedFile::getInstances($modelFiles, 'path');
            $directory = Yii::getAlias("@backend/web/uploads/images/team/" . $id);
            if (!empty($file)) {
                if (file_exists($directory . '/' . $model->image)) {
                    unlink($directory . '/' . $model->image);
                }
                $model->image = $this->upload($file, $model->id, $cropedData);
                $model->save();
            } elseif (!empty($cropedData)) {
               $this->crop($model->image, $id, $cropedData);
            }

            return $this->redirect('index');
        }

        $modelFiles = new Files();

        return $this->render('update', [
            'model' => $model,
            'modelFiles' => $modelFiles,
        ]);
    }

    /**
     * Deletes an existing Team model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $directory = Yii::getAlias("@backend/web/uploads/images/team/" . $id);
        if (file_exists($directory . '/' . $model->image)) {
            unlink($directory . '/' . $model->image);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Team model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Team the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function upload($imageFile, $id, $cropData = [])
    {
        $directory = Yii::getAlias("@backend/web/uploads/images/team/" . $id);
        $directoryThumb = Yii::getAlias("@backend/web/uploads/images/team/" . $id . '/thumb');

        BaseFileHelper::createDirectory($directory);
        BaseFileHelper::createDirectory($directoryThumb);

        if ($imageFile) {
            $fileName_return = '';

            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $filePath = $directory . '/' . $fileName;

                $image->saveAs($filePath);
                $fileName_return = $fileName;

                // Optional crop after upload
                if (!empty($cropData)) {
                    $this->crop($fileName, $id, $cropData);
                }
            }

            return $fileName_return;
        }

        return false;
    }

    public function crop($fileName, $id, $cropData = [])
    {
        $directory = Yii::getAlias("@backend/web/uploads/images/team/" . $id);
        $directoryThumb = $directory . '/thumb';

        BaseFileHelper::createDirectory($directoryThumb);

        $filePathOriginal = $directory . '/' . $fileName;
        $filePathThumb = $directoryThumb . '/' . $fileName;
        
        if (file_exists($filePathThumb)) {
            unlink($filePathThumb);
        }

        if (is_file($filePathOriginal) && !empty($cropData) && isset($cropData['x'], $cropData['y'], $cropData['width'], $cropData['height'])) {
            $x = (int)$cropData['x'];
            $y = (int)$cropData['y'];
            $w = (int)$cropData['width'];
            $h = (int)$cropData['height'];

            Image::crop($filePathOriginal, $w, $h, [$x, $y])
                ->save($filePathThumb, ['quality' => 90]);
        }
    }

}