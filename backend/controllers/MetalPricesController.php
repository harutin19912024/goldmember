<?php

namespace backend\controllers;

use backend\models\MetalPrices;
use backend\models\Exchange;
use common\models\MetalPriceReal;
use backend\models\MetalPricesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helpers\ExchangeHelper;

/**
 * MetalPricesController implements the CRUD actions for MetalPrices model.
 */
class MetalPricesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [['allow' => true, 'roles' => ['@']]],
                ],
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
     * Lists all MetalPrices models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MetalPricesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MetalPrices model.
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
     * Creates a new MetalPrices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MetalPrices();
        $ratePrice = $rateStatus = 0;
        
        $buy = 0;
        $sell = 0;
        
        $metalPriceApiData = MetalPriceReal::find()
        ->orderBy(['created_date' => SORT_DESC])
        ->one();
        
        if(!empty($metalPriceApiData) && empty($metalPriceApiData->request_error)) {
            $price = $metalPriceApiData->request_data;
            $firstPrice = $price['price_gram_24k'];
            $buy = round($price['bid'] / 31.1035, 4);
            $sell = round($price['ask'] / 31.1035, 4);
            $priceData['metalName'] = Exchange::AVAILABLE_METALS_NAME[$price['metal']];
            $rateStatus = ( $price['price'] - $price['prev_close_price'] > 0 ) ? 1 : 0;
            $ratePrice = $price['price'] - $price['prev_close_price'];
            $priceData['prices'] = []; // Store prices separately
            foreach (Exchange::GOLD_PURITIES as $purity=>$percentage) {
                $priceData['prices'][$purity]['buy'] = round($buy * ($percentage / 100), 4);
                $priceData['prices'][$purity]['sell'] = round($sell * ($percentage / 100), 4);
            }
        }
        
        // "ask": 3117.98,
        // "bid": 3117.18,
        
        // 1 troy ounce = 31.1035 grams
        // Buy price per gram (Bid price / 31.1035)
        // Sell price per gram (Ask price / 31.1035)
        
       
        
        //echo "<pre>";print_r($priceData);die;
        
        // $priceData= [
        //     'metalName' => 'GOLD',
        //     'prices' => [
        //         999 => 99.0807,
        //         995 => 98.684,
        //         585 => 58.0202,
        //         990 => 98.1881,
        //         958 => 95.0143,
        //         916 => 90.8488,
        //         900 => 89.2619,
        //         875 => 86.7824,
        //         750 => 74.3849,
        //         500 => 49.59,
        //         417 => 41.358,
        //         375 => 37.1925,
        //         333 => 33.0269
        //     ]
        // ];

        if ($this->request->isPost) {
            $postData = $this->request->post('MetalPrices');
            $karats = $postData['karat'];
            $save = true;
            //echo "<pre>";print_r($postData);die;
            foreach($karats as $key=>$karat) {
                $metalPriceModel = new MetalPrices();
                $metalPriceModel->sell_price = $postData['sell_price'][$key];
                $metalPriceModel->original_buy_price = $postData['original_buy_price'][$key];
                $metalPriceModel->original_sell_price = $postData['original_sell_price'][$key];
                $metalPriceModel->buy_price = $postData['buy_price'][$key];
                $metalPriceModel->karat = $karat;
                $metalPriceModel->metal_id = $postData['metal_id'];
                $metalPriceModel->currency_id = $postData['currency_id'];
                $metalPriceModel->rate_status = $postData['rate_status'];
                $metalPriceModel->rate_price = $postData['rate_price'];
                if (!$metalPriceModel->save()) {
                     echo "<pre>"; print_r($metalPriceModel->getErrors());die;
                    $save = false;
                }
            }
            if ($save) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'rateStatus' => $rateStatus,
            'ratePrice' => $ratePrice,
            'apiPrices' => $priceData,
            'original_buy_price' => $buy,
            'original_sell_price' => $sell
        ]);
    }

    /**
     * Updates an existing MetalPrices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MetalPrices model.
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
     * Finds the MetalPrices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return MetalPrices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MetalPrices::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
