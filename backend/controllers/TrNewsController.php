<?php

namespace backend\controllers;

use Yii;
use backend\models\TrNews;
use backend\models\News;
use backend\models\TrNewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrProductController implements the CRUD actions for TrProduct model.
 */
class TrNewsController extends Controller
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
     * Lists all TrProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrNewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrProduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TrProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrNews();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TrProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        if (isset(Yii::$app->request->post()['TrNews'])) {

            $model = new TrNews();

            $arrPost = Yii::$app->request->post()['TrNews'];
            $trModel = $model->findOne(['language_id' => $arrPost['language_id'], 'news_id' => $arrPost['news_id']]);
            if ($trModel) {
                $trModel->title = $arrPost['title'];
                $trModel->short_description = $arrPost['short_description'];
                $trModel->content = $arrPost['content'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->news_id = $arrPost['news_id'];
            } else {
                $trModel = new TrNews();
                $trModel->title = $arrPost['title'];
                $trModel->short_description = $arrPost['short_description'];
                $trModel->content = $arrPost['content'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->news_id = $arrPost['news_id'];
            }


            if ($trModel->save()) {
                echo 'true';
                exit();
            } else {
                echo 'false';
                exit();
            }
        } elseif (Yii::$app->request->isAjax) {

            $arrPost = Yii::$app->request->post();
            $tr_productObj = new TrNews();
            $tr_product = $tr_productObj->findOne(['language_id' => $arrPost['lang'], 'news_id' => $arrPost['news']]);

            if (!$tr_product) {
                $tr_product = new TrNews();
                $news = News::findOne($arrPost['news']);
                $tr_product->language_id = $arrPost['lang'];
                $tr_product->news_id = $arrPost['news'];
                $tr_product->title = $news->title;
                $tr_product->short_description = $news->short_description;
                $tr_product->content = $news->content;
            }
            echo $this->renderAjax('_form', [
                'model' => $tr_product,
            ]);
            exit();
        }
    }

    /**
     * Deletes an existing TrProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrNews::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
