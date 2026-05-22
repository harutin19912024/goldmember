<?php

namespace backend\controllers;

use Yii;
use backend\models\PowerOfPenny;
use backend\models\TrPowerOfPenny;
use backend\models\TrPowerOfPennySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrPowerOfPennyController implements the CRUD actions for TrPowerOfPenny model.
 */
class TrPowerOfPennyController extends Controller
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
     * Lists all TrPowerOfPenny models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TrPowerOfPennySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrPowerOfPenny model.
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
     * Creates a new TrPowerOfPenny model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TrPowerOfPenny();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TrPowerOfPenny model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        if (isset(Yii::$app->request->post()['TrPowerOfPenny'])) {
            $arrPost = Yii::$app->request->post()['TrPowerOfPenny'];
            $trModel = TrPowerOfPenny::findOne(['language_id' => $arrPost['language_id'], 'power_of_penny_id' => $arrPost['power_of_penny_id']]);
            if (!$trModel) {
                $trModel = new TrPowerOfPenny();
                $trModel->language_id = $arrPost['language_id'];
                $trModel->power_of_penny_id = $arrPost['power_of_penny_id'];
            }
            $trModel->name = $arrPost['name'] ?? null;
            $trModel->content = $arrPost['content'] ?? null;
            echo $trModel->save() ? 'true' : 'false';
            exit();
        } elseif (!empty(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            $arrPost = Yii::$app->request->post();
            $tr = TrPowerOfPenny::findOne(['language_id' => $arrPost['lang'], 'power_of_penny_id' => $arrPost['powerofpenny']]);
            if (!$tr) {
                $tr = new TrPowerOfPenny();
                $tr->language_id = $arrPost['lang'];
                $tr->power_of_penny_id = $arrPost['powerofpenny'];
                $pop = PowerOfPenny::findOne($arrPost['powerofpenny']);
                if ($pop) {
                    $tr->name = $pop->name ?? null;
                    $tr->content = $pop->content ?? null;
                }
            }
            echo $this->renderPartial('_form', ['model' => $tr]);
            exit();
        }
    }

    /**
     * Deletes an existing TrPowerOfPenny model.
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
     * Finds the TrPowerOfPenny model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TrPowerOfPenny the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrPowerOfPenny::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
