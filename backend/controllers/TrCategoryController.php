<?php

namespace backend\controllers;

use Yii;
use backend\models\TrCategory;
use backend\models\TrCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrCategoryController implements the CRUD actions for TrCategory model.
 */
class TrCategoryController extends Controller {

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
     * Lists all TrCategory models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TrCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TrCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TrCategory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TrCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate() {

        if (isset(Yii::$app->request->post()['TrCategory'])) {

            $model = new TrCategory();

            $arrPost = Yii::$app->request->post()['TrCategory'];
            $trModel = $model->findOne(['language_id' => $arrPost['language_id'], 'category_id' => $arrPost['category_id']]);
            if ($trModel) {
                $trModel->name = $arrPost['name'];
                $trModel->short_description = $arrPost['short_description'];
                $trModel->description = $arrPost['description'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->category_id = $arrPost['category_id'];
            } else {
                $trModel = new TrCategory();
                $trModel->name = $arrPost['name'];
                $trModel->short_description = $arrPost['short_description'];
                $trModel->description = $arrPost['description'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->category_id = $arrPost['category_id'];
            }


            if ($trModel->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return json_encode(['success'=>false,'message'=>$trModel->errors]);
            }
        } elseif (!empty(Yii::$app->request->post()) && Yii::$app->request->isAjax) {

            $arrPost = Yii::$app->request->post();
            $tr_brandObj = new TrCategory();
            $tr_brand = $tr_brandObj->findOne(['language_id' => $arrPost['lang'], 'category_id' => $arrPost['category']]);

            if (!$tr_brand) {
                $tr_brand = new TrCategory();
                $tr_brand->language_id = $arrPost['lang'];
                $tr_brand->category_id = $arrPost['category'];
            }
            echo $this->renderAjax('_form', [
                'model' => $tr_brand,
            ]);
            exit();
        }
    }

    /**
     * Deletes an existing TrCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TrCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
