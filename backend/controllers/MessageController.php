<?php

namespace backend\controllers;

use Yii;
use backend\models\Message;
use backend\models\MessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\SourceMessage;
use common\models\Language;
use yii\filters\AccessControl;
use backend\models\User;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
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
                    'index' => ['GET', 'POST'],
                    'view' => ['GET'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => 'common\components\CAccessRule',
                ],
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            User::ADMIN,
                        ],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Message model.
     * @param integer $id
     * @param string $language
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id,Yii::$app->language),
        ]);
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($translation_id = null)
    {
        $model = new Message();
        $keywords = SourceMessage::find()->where(['id'=>$translation_id])->one();
        $languageAll = Language::find()->asArray()->all();
        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
        $modelSource = SourceMessage::findOne($translation_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            echo json_encode(['translation'=>$model->translation,'success'=>true]);exit();
        } else {
            return $this->render('create', [
                'model' => $model,
                'keywords'=>$keywords,
                'translationID'=>$translation_id,
                'languages'=>$languageAll,
                'modelSource'=>$modelSource,
                'defoultLanguage' => $defaultLanguage,
            ]);
        }
    }

    /**
     * Updates an existing Message model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $keywords = SourceMessage::find()->where(['id'=>$id])->one();
        $languageAll = Language::find()->asArray()->all();
        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
        $modelSource = SourceMessage::findOne($id);
        if (Yii::$app->request->post()) {
            $language = Yii::$app->request->post('Message')['language'];
            $modelUpdate = Message::findOne(['id' => $id, 'language' => $language]);
            //$modelUpdate->translation = Yii::$app->request->post('Message')['translation'];
            if($modelUpdate->load(Yii::$app->request->post()) && $modelUpdate->save()){
                echo json_encode(['translation'=>$model->translation,'success'=>true]);exit();
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'keywords'=>$keywords,
                'translationID'=>$id,
                'languages'=>$languageAll,
                'modelSource'=>$modelSource,
                'defoultLanguage' => $defaultLanguage,
            ]);
        }
    }

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id, Yii::$app->language)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $language
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne(['id' => $id, 'language' => Yii::$app->language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
