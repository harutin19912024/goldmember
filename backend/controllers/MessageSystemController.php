<?php

namespace backend\controllers;

use Yii;
use common\models\MessageSystem;
use backend\models\MessageSystemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Customer;
use backend\models\CustomerSearch;

/**
 * MessageSystemController implements the CRUD actions for MessageSystem model.
 */
class MessageSystemController extends Controller
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
     * Lists all MessageSystem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageSystemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new MessageSystem();
        $searchModelUser = new CustomerSearch();
        $dataProviderUser = $searchModelUser->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
//            $model->status = 0;
//            $model->save();
            echo $_form = $this->renderPartial('_form', [
                'model' => $model,
                'dataProviderUser' => $dataProviderUser,
            ]);
            exit();
        }


        $_form = $this->renderPartial('_form', [
            'searchModel' => $searchModel,
            'model' => $model,
            'dataProviderUser' => $dataProviderUser,
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderUser' => $dataProviderUser,
            '_Form' => $_form ,
        ]);
    }


    public function actionInbox(){
        $searchModel = new MessageSystemSearch();
        $dataProvider = $searchModel->searchInbox();

        return $this->render('inbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetInbox(){
        if(Yii::$app->request->isAjax) {
            $page = Yii::$app->request->post('page');
            if($page == 'inbox'){
                $searchModel = new MessageSystemSearch();
                $dataProvider = $searchModel->searchInbox();

                $inboxView = $this->renderPartial('inbox-part', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
                echo $inboxView;exit();
            }else {
                $messageCount = MessageSystem::find()->where(['status' => 1, 'recipient_user_id' => Yii::$app->user->identity->id])->count();
                echo $messageCount;
                exit();
            }
        }
    }

    public function actionAnswer($id){

        $model = new MessageSystem();
        $message = MessageSystem::find()->select('sender_user_id')->where(['id' => $id])->asArray()->all();
        if (Yii::$app->request->post()) {
            $model_old = $this->findModel($id);
            $model_old->status = 0;
            $model_old->save();

            $model->sender_user_id = Yii::$app->user->identity->id;
            $model->recipient_user_id = $message[0]['sender_user_id'];
            $model->status = 1;
            $model->title = Yii::$app->request->post('MessageSystem')['title'];
            $model->content = Yii::$app->request->post('MessageSystem')['content'];
           if($model->save()){
               $this->sendMessage($message[0]['sender_user_id']);
           }

            return $this->redirect('index');
        }

        return $this->render('answer', [
            'model' => $model,
            'id' => $id,
            'message' => $message[0],
        ]);
    }

    public function sendMessage($recipient_user_id){
            $customer_model =  Customer::find()->where(['id'=>$recipient_user_id])->asArray()->all();
            $name = $customer_model[0]["name"];
            $url =Yii::$app->params['baseUrlHome'].'/site/login';
            $message = "Hello $name!<br/><br/>
        Your have unread message,<br/>
         Please enter to your <a href='$url'>account</a> to see message";
            return Yii::$app
                ->mailer
                ->compose('email-layout', ['content' => $message])
                ->setFrom(['admin-odenson@test.com' => Yii::$app->name])
                ->setTo($customer_model[0]['email'])
                ->setSubject('New Message')
                ->send();
    }

    /**
     * Displays a single MessageSystem model.
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
     * Creates a new MessageSystem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if (Yii::$app->request->post()) {
            if(!empty(Yii::$app->request->post('selection'))){
                $recipientUsersID = Yii::$app->request->post('selection');
            }else{
                $recipientUsersID = Yii::$app->request->post('MessageSystem')['recipient_user_id'];
            }
            
            foreach($recipientUsersID as $value){
                $model = new MessageSystem();
                $model->sender_user_id = Yii::$app->user->identity->id;
                $model->recipient_user_id = $value;
                $model->status = 1;
                $model->title = Yii::$app->request->post('MessageSystem')['title'];
                $model->content = Yii::$app->request->post('MessageSystem')['content'];
                if($model->save()){
                    $this->sendMessage($value);
                }
            }

            return $this->redirect('index');
        } else {
            $model = new MessageSystem();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MessageSystem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MessageSystem model.
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
     * Finds the MessageSystem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MessageSystem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MessageSystem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
