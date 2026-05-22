<?php

namespace backend\controllers;

use Yii;
use backend\models\Material;
use backend\models\TrMaterial;
use backend\models\TrMaterialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrMaterialController implements the CRUD actions for TrMaterial model.
 */
class TrMaterialController extends Controller
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
     * Lists all TrMaterial models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TrMaterialSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrMaterial model.
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
     * Creates a new TrMaterial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TrMaterial();

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
     * Updates an existing TrMaterial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        if (isset(Yii::$app->request->post()['TrMaterial'])) {
            $arrPost = Yii::$app->request->post()['TrMaterial'];
            $trModel = TrMaterial::findOne(['language_id' => $arrPost['language_id'], 'material_id' => $arrPost['material_id']]);
            if (!$trModel) {
                $trModel = new TrMaterial();
                $trModel->language_id = $arrPost['language_id'];
                $trModel->material_id = $arrPost['material_id'];
            }
            $trModel->name = $arrPost['name'] ?? null;
            $trModel->short_description = $arrPost['short_description'] ?? null;
            $trModel->description = $arrPost['description'] ?? null;
            echo $trModel->save() ? 'true' : 'false';
            exit();
        } elseif (!empty(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            $arrPost = Yii::$app->request->post();
            $tr = TrMaterial::findOne(['language_id' => $arrPost['lang'], 'material_id' => $arrPost['material']]);
            if (!$tr) {
                $tr = new TrMaterial();
                $tr->language_id = $arrPost['lang'];
                $tr->material_id = $arrPost['material'];
                $material = Material::findOne($arrPost['material']);
                if ($material) {
                    $tr->name = $material->name;
                }
            }
            echo $this->renderPartial('_form', ['model' => $tr]);
            exit();
        }
    }

    /**
     * Deletes an existing TrMaterial model.
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
     * Finds the TrMaterial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TrMaterial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrMaterial::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
