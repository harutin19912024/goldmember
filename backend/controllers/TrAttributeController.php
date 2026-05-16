<?php

namespace backend\controllers;

use Yii;
use backend\models\TrAttribute;
use backend\models\TrAttributeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrAttributeController implements the CRUD actions for TrAttribute model.
 */
class TrAttributeController extends Controller {

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
     * Lists all TrAttribute models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TrAttributeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrAttribute model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TrAttribute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TrAttribute();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TrAttribute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate() {

        if (isset(Yii::$app->request->post()['TrAttribute'])) {

            $model = new TrAttribute();

            $arrPost = Yii::$app->request->post()['TrAttribute'];
            $trModel = $model->findOne(['language_id' => $arrPost['language_id'], 'attribute_id' => $arrPost['attribute_id']]);
            if ($trModel) {
                $trModel->name = $arrPost['name'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->attribute_id = $arrPost['attribute_id'];
            } else {
                $trModel = new TrAttribute();
                $trModel->name = $arrPost['name'];
                $trModel->language_id = $arrPost['language_id'];
                $trModel->attribute_id = $arrPost['attribute_id'];
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
            $tr_attributeObj = new TrAttribute();
            $tr_attribute = $tr_attributeObj->findOne(['language_id' => $arrPost['lang'], 'attribute_id' => $arrPost['attribute']]);

            if (!$tr_attribute) {
                $tr_attribute = new TrAttribute();
                $tr_attribute->language_id = $arrPost['lang'];
                $tr_attribute->attribute_id = $arrPost['attribute'];
            }
            echo $this->renderPartial('_form', [
                'model' => $tr_attribute,
            ]);
            exit();
        }
    }

    /**
     * Deletes an existing TrAttribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrAttribute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrAttribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TrAttribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
