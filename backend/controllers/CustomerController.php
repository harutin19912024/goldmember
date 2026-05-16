<?php

namespace backend\controllers;

use common\models\Countries;
use common\models\States;
use common\models\CustomerAddress;
use frontend\models\CustomerCard;
use Yii;
use common\models\Customer;
use backend\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use yii\filters\AccessControl;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
                    'create' => ['POST'],
                    'update' => ['POST'],
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $model = new Customer();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $customerAddress_model = new CustomerAddress();
        $customer_model = new Customer();
        $countres = Countries::find()->asArray()->all();
        $states = States::find()->asArray()->all();

        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
            $customerAddress_model_update = CustomerAddress::findOne(['customer_id'=>$id]);
            if(!$customerAddress_model_update){
                $customerAddress_model_update = new CustomerAddress();
            }
            echo $_form = $this->renderPartial('update_form', [
                'customer_model' => $model,
                'customerAddress_model' => $customerAddress_model_update,
                'countries' => $countres ,
                'states' => $states ,
            ]);
            exit();
        }
        $_form = $this->renderPartial('_form', [
            'customer_model' => $customer_model,
            'customerAddress_model' => $customerAddress_model,
            'countries' => $countres ,
            'states' => $states ,
        ]);

        return $this->render('index', [
            'model'=>$model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customer_model' => $customer_model,
            'customerAddress_model' => $customerAddress_model,
            '_Form' => $_form ,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $customerAddress_model = new CustomerAddress();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'customerAddress_model' => $customerAddress_model,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $customer_model = new Customer();
        $customerAddress_model = new CustomerAddress();
        $customerData =[
            'Customer' => Yii::$app->request->post('Customer')
        ];
        $customerAddressDefaultData =[
            'CustomerAddress' => Yii::$app->request->post('CustomerAddress')['default']
        ];
        $customerAddressData = Yii::$app->request->post('CustomerAddress');

        if ($customer_model->load($customerData)) {
            $customer_model->last_ip= Yii::$app->request->getUserIP();
            $user = new User();
            $userData = $user->addUser($customer_model);
            $customer_model->user_id=$userData['user_id'];
            $customer_model->save();
            $customerAddress_model->batchInsert($customerAddressData, $customer_model->id);
            if ($userData && $this->sendEmail($customer_model->email, 'Invite',$userData )) {
                echo 'true';
                exit();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to send email for repairer.');
            }
        } else {
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $customer_model = $this->findModel($id);
        $customerAddress_model = new CustomerAddress();
        $customerAddressData = Yii::$app->request->post('CustomerAddress');
        $customerData =[
            'Customer' => Yii::$app->request->post('Customer')
        ];

        if ($customer_model->load($customerData) && $customer_model->save()) {

            $customerAddress_model->bachUpdate($customerAddressData, $customer_model->id);

            return $this->redirect(['index', 'id' => $customer_model->id]);
        } else {
            return $this->render('update', [
                'customer_model' => $customer_model,
                'customerAddress_model' => $customerAddress_model,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app','Customer deleted'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteByAjax(){

        if (Yii::$app->request->isAjax) {
            $product_ids = Yii::$app->request->post('ids');
            try {
                $forinkeys = [];
                $allow = true;
                foreach ($product_ids as $id){

                    $customerAddress = CustomerAddress::find()->where(['customer_id'=> $id])->one()->id;
                    $customerCard = CustomerCard::find()->where(['customer_id'=> $id])->one()->id;
//                    $payment = Payment::find()->where(['customer_id'=> $id])->one()->id;

                    if ($customerAddress){
                        $allow = false;
                        $forinkeys[$id]['brand'] = $customerAddress;
                    }
                    if ($customerCard){
                        $allow = false;
                        $forinkeys[$id]['category'] = $customerCard;
                    }
//                    if ($payment){
//                        $allow = false;
//                        $forinkeys[$id]['producteAttribute'] = $payment;
//                    }
                }
                if($allow){
                    Customer::deleteAll(['in','id', $product_ids]);
                    echo true; exit();
                }
                print_r(json_encode($forinkeys)); exit();
            } catch (\mysqli_sql_exception $e) {
                Yii::$app->session->setFlash('error', 'you are not deleted');
                echo json_encode(['deleted' => 'error']); exit();
            }
        }
    }
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * change customer status with ajax
     */
    public function actionChangestatus()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('data');
            $data = json_decode($post);
            $model = $this->findModel($data->id);
            $model->status = $data->status;
            if ($model->save()) {
                echo 'true';
                exit();
            } else {
                echo 'false';
                exit();
            }
        }
    }

    /**
     * @param $to
     * @param $subject
     * @param $data
     * @return bool
     */
    public function sendEmail($to, $subject, $data)
    {
        $username =ucfirst($data['username']);
        $password = $data['password'];
        $name = preg_replace("/[0-9]+/",'',$username);
        $message = "Hello $name!<br/><br/>
        Your username is $username,<br/>
             password is $password<br/>
         You was added as Customer in our site. You'll love it!";
        return Yii::$app
            ->mailer
            ->compose('email-layout', ['content' => $message])
            ->setFrom(['admin-odenson@test.com' => Yii::$app->name])
            ->setTo($to)
            ->setSubject($subject)
            ->send();
    }

    /**
     * @param $imageFile
     * @return array|bool
     */
    public function upload($imageFile)
    {
        $directory = 'uploads/customers';
        if (!is_dir($directory)) {
            mkdir($directory);
        }
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . $key . '.' . $image->extension;
                $filePath = $directory . $fileName;
                if ($image->saveAs($filePath)) {
                    $paths[$key + 1] = $filePath;
                } else {
                    echo "<pre>";
                    print_r($directory);
                    die;
                }
            }
            return $paths;
        }
        return false;
    }
}
