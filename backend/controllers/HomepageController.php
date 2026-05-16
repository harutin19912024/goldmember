<?php

namespace backend\controllers;

use Yii;
use backend\models\Homepage;
use backend\models\TrHomepage;
use backend\models\HomepageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\imagine\Image;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;

/**
 * SitesettingsController implements the CRUD actions for Sitesettings model.
 */
class HomepageController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sitesettings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HomepageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sitesettings model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Sitesettings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Homepage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			$file = UploadedFile::getInstances($model, 'path');
			if (!empty($file)) {
				$paths = $this->upload($file, $model->id);
				$path = array_values($paths);
				$model->path = $path[0];
				$model->save();
			}
			
			$objLang = new Language();
			$languages = $objLang->find()->asArray()->all();
			foreach ($languages as $value) {
				$trmodel = new TrHomepage();
				$trmodel->title = $model->title;
				$trmodel->description = $model->description;
				$trmodel->homepage_id = $model->id;
				$trmodel->language_id = $value['id'];
				$trmodel->save();
			}
            return $this->redirect('/homepage/update?id='.$model->id);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sitesettings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			
			$file = UploadedFile::getInstances($model, 'path');
            if (!empty($file)) {
				$directory = Yii::getAlias("@backend/web/uploads/images/homepage/" . $model->id);
				$directoryThumb = Yii::getAlias("@backend/web/uploads/images/homepage/" . $model->id.'/thumbnail');
				$brand_path = $model;
				if (file_exists($directory . '/' . $brand_path->path) && $brand_path->path != "") {
					unlink($directory . '/' . $brand_path->path);
					BaseFileHelper::removeDirectory($directory);
					BaseFileHelper::removeDirectory($directoryThumb);
				}
                $paths = $this->upload($file, $model->id);
				$paths = array_values($paths);
				$model->path = $paths[0];
            }else{
				$model->path = Yii::$app->request->post('imagePath');
			}
			if($model->save()) {
				$trModel = new TrHomepage();
				$trModel->title = $model->title;
				$trModel->description = $model->description;
				$trModel->updateDefaultTranslate($model->id);
				return $this->redirect('/homepage/update?id='.$model->id);
			}
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	
	/**
     * Updates an existing Sitesettings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateText($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			$model->save();
            return $this->redirect('/site/index');
        } else {
            return $this->render('updateText', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sitesettings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sitesettings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Sitesettings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Homepage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function upload($imageFile, $id) {
        $directory = Yii::getAlias("@backend/web/uploads/images/homepage/" . $id);
        $directoryThumb = Yii::getAlias("@backend/web/uploads/images/homepage/" . $id . "/thumbnail");
        BaseFileHelper::createDirectory($directory);
        BaseFileHelper::createDirectory($directoryThumb);
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $fileName_return = $fileName;
                $filePath = $directory . '/' . $fileName;
                //$filePathThumb = $directoryThumb . '/' . $fileName;
                $image->saveAs($filePath);
                Image::thumbnail($filePath, 265, 125)->save(Yii::getAlias($directoryThumb . '/' . $fileName), ['quality' => 100]);
                $paths[$key + 1] = $fileName_return;
            }
            return $paths;
        }
        return false;
    }
}