<?php

namespace backend\controllers;

use Yii;
use backend\models\TrPages;
use backend\models\TrPagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrPagesController implements the CRUD actions for TrPages model.
 */
class TrPagesController extends Controller {

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
     * Lists all TrPages models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TrPagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrPages model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TrPages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TrPages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TrPages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate() {

        if (isset(Yii::$app->request->post()['TrPages'])) {

            $model = new TrPages();

            $arrPost = Yii::$app->request->post()['TrPages'];
            $trModel = $model->findOne(['language_id' => $arrPost['language_id'], 'pages_id' => $arrPost['pages_id']]);

            if ($trModel) {
                $trModel->title = $arrPost['title'];
                $trModel->short_description = $arrPost['short_description'];
                $trModel->content = $arrPost['content'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->pages_id = $arrPost['pages_id'];
            } else {
                $trModel = new TrPages();
                $trModel->title = $arrPost['title'];
                $trModel->short_description = $arrPost['short_description'];
                $trModel->content = $arrPost['content'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->pages_id = $arrPost['pages_id'];
            }


            if ($trModel->save()) {
                echo 'true';
                exit();
            } else {
                echo 'false';
                exit();
            }
        } elseif (!empty(Yii::$app->request->post()) && Yii::$app->request->isAjax) {

            $arrPost = Yii::$app->request->post();
            $tr_pagesObj = new TrPages();
            $tr_pages = $tr_pagesObj->findOne(['language_id' => $arrPost['lang'], 'pages_id' => $arrPost['pages']]);

            if (!$tr_pages) {
                $tr_pages = new TrPages();
                $tr_pages->language_id = $arrPost['lang'];
                $tr_pages->pages_id = $arrPost['pages'];
            }
            echo $this->renderPartial('_form', [
                'model' => $tr_pages,
            ]);
            exit();
        }
    }

    /**
     * Deletes an existing TrPages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrPages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrPages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TrPages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
