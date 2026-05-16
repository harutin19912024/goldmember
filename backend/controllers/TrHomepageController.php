<?php

namespace backend\controllers;

use Yii;
use backend\models\TrHomepage;
use backend\models\TrHomepageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrHomepageController implements the CRUD actions for TrHomepage model.
 */
class TrHomepageController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all TrHomepage models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TrHomepageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrHomepage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TrHomepage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TrHomepage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TrHomepage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate() {

        if (isset(Yii::$app->request->post()['TrHomepage'])) {

            $model = new TrHomepage();

            $arrPost = Yii::$app->request->post()['TrHomepage'];
			
            $trModel = $model->findOne(['language_id' => $arrPost['language_id'], 'homepage_id' => $arrPost['homepage_id']]);
            if ($trModel) {
                $trModel->title = $arrPost['title'];
                $trModel->description = $arrPost['description'];
            } else {
                $trModel = new TrHomepage();
                $trModel->title = $arrPost['title'];
                $trModel->description = $arrPost['description'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->homepage_id = $arrPost['homepage_id'];
            }


            if ($trModel->save()) {
		    return $this->redirect(['/homepage/update?id='.$trModel->homepage_id]);
            } else {
                echo 'false';
                exit();
            }
        } elseif (!empty(Yii::$app->request->post()) && Yii::$app->request->isAjax) {

            $arrPost = Yii::$app->request->post();
            $tr_brandObj = new TrHomepage();
            $tr_brand = $tr_brandObj->findOne(['language_id' => $arrPost['lang'], 'homepage_id' => $arrPost['homepage']]);

            if (!$tr_brand) {
                $tr_brand = new TrHomepage();
                $tr_brand->language_id = $arrPost['lang'];
                $tr_brand->homepage_id = $arrPost['homepage'];
            }
			
            echo $this->renderPartial('_form', [
                'model' => $tr_brand,
            ]);
            exit();
        }
    }

    /**
     * Deletes an existing TrHomepage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrHomepage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrHomepage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TrHomepage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
