<?php

namespace backend\controllers;

use Yii;
use backend\models\Exchange;
use backend\models\ExchangeForm;
use backend\models\ExchangeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\helpers\ExchangeHelper;

/**
 * AddressController implements the CRUD actions for Address model.
 */
class ExchangeController extends Controller
{
    private $helper = null;

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
     * Lists all Address models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->helper = new ExchangeHelper();

        $searchModel = new ExchangeSearch();
        $model = new Exchange();
       
        $view = 'index';
//        if(Yii::$app->request->isPost) {
//            $exchangeForm->load(Yii::$app->request->post());
//            $startDate = $exchangeForm->startDate ?? '';
//            $symbol = Exchange::AVAILABLE_METALS[Yii::$app->request->queryParams['metal']];
//            $currency = Exchange::AVAILABLE_CURRENCIES[Yii::$app->request->queryParams['currency']];
//        }

        if(!empty(Yii::$app->request->queryParams['startDate'])) {
            $startDate = date('Ymd', strtotime(Yii::$app->request->queryParams['startDate']));
        }

        if(!empty(Yii::$app->request->queryParams['metal'])) {
            $view = 'metal';
            $symbol = Exchange::AVAILABLE_METALS[Yii::$app->request->queryParams['metal']];
        }

        if(!empty(Yii::$app->request->queryParams['currency'])) {
            $currency = Exchange::AVAILABLE_CURRENCIES[Yii::$app->request->queryParams['currency']];
        }

        $startOfDay = date('Y-m-d 00:00:00');
        $endOfDay = date('Y-m-d 23:59:59');

        

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['start' => $startOfDay, 'end' => $endOfDay]);

        return $this->render($view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'prices' => $priceData,
            'exchangeForm' => $exchangeForm,
            'model' => $model
        ]);
    }
    
    public function actionMetal()
    {
        $startOfDay = date('Y-m-d 00:00:00');
        $endOfDay = date('Y-m-d 23:59:59');
        $searchModel = new ExchangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['start' => $startOfDay, 'end' => $endOfDay]);
        return $this->render('metal-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'exchangeForm' => $exchangeForm,
            'model' => $model
        ]);
    }
    
    public function actionCreateMetalPrices()
    {
        $exchangeForm = new ExchangeForm();
        $model = new Exchange();
        $startDate = "";
        $symbol = "XAU";
        $currency = "USD";
        $this->helper->setStartDate($startDate);
        //$price = $this->helper->getMetalPrice($symbol, $currency);
        //$priceData = [];
        $priceData= [
            'metalName' => 'GOLD',
            'prices' => [
                999 => 99.0807,
                995 => 98.684,
                990 => 98.1881,
                958 => 95.0143,
                916 => 90.8488,
                900 => 89.2619,
                875 => 86.7824,
                833 => 82.6169,
                750 => 74.3849,
                708 => 70.2194,
                666 => 66.0538,
                625 => 61.9874,
                585 => 58.0202,
                500 => 49.59,
                417 => 41.358,
                375 => 37.1925,
                333 => 33.0269,
                250 => 24.795,
            ]
        ];

      
        
        return $this->render('metal-prices', [
            'prices' => $priceData,
            'exchangeForm' => $exchangeForm,
            'model' => $model
        ]);
        
    }

    public function actionSavePrices()
    {
        $model = new Exchange();
        $modelForm = new ExchangeForm();
        echo "<pre>"; print_r($_POST);die;
        if ($modelForm->load(Yii::$app->request->post())) {
            $prices = Yii::$app->request->post();
            $model->data = json_encode($prices);
            if(!$model->save()) {
                print_r($model->getErrors());
                die;
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Displays a single Address model.
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
     * Creates a new Address model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Exchange();
        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save()) {
                print_r(Yii::$app->request->post());die;
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Address model.
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
     * Deletes an existing Address model.
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
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Exchange::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetAddressMultiple() {
        if (Yii::$app->request->isAjax) {
            $city_ids = Yii::$app->request->post('cityIds');

            $address = Address::getAddressByIds($city_ids);
            echo json_encode($address);
            exit();
        }
    }
    
    public function actionExchanges()
    {
        // Define the API endpoint and your access token
        $apiUrl = 'https://www.goldapi.io/api/XAU/USD';
        $accessToken = 'goldapi-bq8slytuo6j0-io';
        
        // Initialize a cURL session
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string instead of outputting it
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'x-access-token: ' . $accessToken // Set the request headers
        ));
        
        // Execute the cURL request
        $response = curl_exec($ch);
        
        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Decode the JSON response
            $data = json_decode($response, true);
        
            // Check if the JSON response is valid
            if (json_last_error() === JSON_ERROR_NONE) {
                // Extract the gold price
                $goldPrice = $data['price'] ?? 'Price not available';
        
                // Output the gold price
                echo 'Gold Price (USD): ' . $goldPrice;
            } else {
                echo 'Failed to decode JSON response: ' . json_last_error_msg();
            }
        }
        
        // Close the cURL session
        curl_close($ch);
    }
}
