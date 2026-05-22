<?php

namespace backend\controllers;

use Yii;
use backend\models\MetalPrices;
use backend\models\Exchange;
use backend\models\ExchangeRates;
use common\models\MetalPriceReal;
use backend\models\MetalPricesSearch;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class MetalPricesController extends Controller
{
    const TROY_OUNCE_GRAMS = 31.1035;
    const METAL_API_KEY = '328dbc54ea99eeba3e5ca4e64604cc19';
    const METAL_API_URL = 'https://api.metalpriceapi.com/v1/latest';

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
                        'fetch-latest' => ['POST'],
                        'get-prices' => ['GET', 'POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel = new MetalPricesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $metalId = (int)($this->request->getQueryParam('metal_id') ?: 1);
        $currencyId = (int)($this->request->getQueryParam('currency_id') ?: 1);

        if ($this->request->isPost) {
            return $this->savePrices($this->request->post('MetalPrices'));
        }

        $model = new MetalPrices();
        $model->loadDefaultValues();
        $model->metal_id = $metalId;
        $model->currency_id = $currencyId;

        $priceData = $this->buildPriceData($metalId, $currencyId);

        return $this->render('create', [
            'model' => $model,
            'metalId' => $metalId,
            'currencyId' => $currencyId,
            'rateStatus' => $priceData['rateStatus'],
            'ratePrice' => $priceData['ratePrice'],
            'apiPrices' => $priceData['apiPrices'],
            'original_buy_price' => $priceData['originalBuy'],
            'original_sell_price' => $priceData['originalSell'],
            'apiTimestamp' => $priceData['apiTimestamp'],
            'existingByKarat' => $priceData['existingByKarat'],
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * AJAX: returns the karat grid for a given metal/currency combo
     * so the form can refresh without a full page reload.
     */
    public function actionGetPrices()
    {
        $metalId = (int)($this->request->get('metal_id') ?: $this->request->post('metal_id') ?: 1);
        $currencyId = (int)($this->request->get('currency_id') ?: $this->request->post('currency_id') ?: 1);

        $model = new MetalPrices(['metal_id' => $metalId, 'currency_id' => $currencyId]);
        $priceData = $this->buildPriceData($metalId, $currencyId);

        return $this->renderAjax('_karat-grid', [
            'model' => $model,
            'apiPrices' => $priceData['apiPrices'],
            'existingByKarat' => $priceData['existingByKarat'],
            'apiTimestamp' => $priceData['apiTimestamp'],
            'original_buy_price' => $priceData['originalBuy'],
            'original_sell_price' => $priceData['originalSell'],
        ]);
    }

    /**
     * POST endpoint: fetch latest prices from the metal API and store in metal_price_real.
     * Called from the "Fetch latest" button on the create form.
     */
    public function actionFetchLatest()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            // Fetch all 4 metals in one call: base=USD → rates['XAU'/'XAG'/...] = ounces per 1 USD
            $url = self::METAL_API_URL . '?api_key=' . self::METAL_API_KEY . '&base=USD&currencies=XAU,XAG,XPT,XPD';
            $ctx = stream_context_create(['http' => ['timeout' => 10]]);
            $raw = @file_get_contents($url, false, $ctx);
            if ($raw === false) {
                return ['success' => false, 'error' => 'API request failed (network)'];
            }
            $data = json_decode($raw, true);
            if (empty($data['rates']) || !is_array($data['rates'])) {
                return ['success' => false, 'error' => 'Unexpected API response', 'raw' => substr($raw, 0, 300)];
            }

            $ts = $data['timestamp'] ?? time();
            $saved = [];
            // metal_id → symbol
            $metals = [1 => 'XAU', 2 => 'XAG', 3 => 'XPT', 4 => 'XPD'];
            foreach ($metals as $metalId => $symbol) {
                if (empty($data['rates'][$symbol])) continue;
                // rate = "ounces of metal per 1 USD"  →  price_per_oz_USD = 1 / rate
                $pricePerOz = 1.0 / (float)$data['rates'][$symbol];

                $row = new MetalPriceReal();
                $row->metal_id = $metalId;
                $row->currency_id = 1;
                $row->request_data = json_encode([
                    'timestamp'        => $ts,
                    'metal'            => $symbol,
                    'currency'         => 'USD',
                    'price'            => $pricePerOz,
                    'prev_close_price' => $pricePerOz,
                    'ask'              => $pricePerOz,
                    'bid'              => $pricePerOz,
                    'price_gram_24k'   => round($pricePerOz / self::TROY_OUNCE_GRAMS, 4),
                ]);
                $row->save(false);
                $saved[$symbol] = round($pricePerOz, 2);
            }

            return ['success' => true, 'timestamp' => date('Y-m-d H:i:s', $ts), 'prices' => $saved];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Build prices for a given metal/currency. Returns:
     *  - apiPrices: ['metalName' => str, 'prices' => [karat => ['buy' => x, 'sell' => y]]]
     *  - rateStatus / ratePrice: trend vs previous close
     *  - originalBuy / originalSell: per-gram pure-metal price (before karat purity, in target currency)
     *  - apiTimestamp: when the API row was fetched
     *  - existingByKarat: today's already-saved prices for this metal/currency, keyed by karat
     */
    protected function buildPriceData(int $metalId, int $currencyId): array
    {
        $apiPrices = [];
        $originalBuy = 0;
        $originalSell = 0;
        $rateStatus = 0;
        $ratePrice = 0;
        $apiTimestamp = null;

        $row = MetalPriceReal::find()
            ->where(['metal_id' => $metalId])
            ->orderBy(['created_date' => SORT_DESC])
            ->one();
        if ($row && empty($row->request_error)) {
            $raw = is_string($row->request_data) ? json_decode($row->request_data, true) : $row->request_data;
            if (is_array($raw) && !empty($raw['bid']) && !empty($raw['ask'])) {
                $apiTimestamp = $row->created_date;
                $rateStatus = ($raw['price'] - $raw['prev_close_price'] > 0) ? 1 : 0;
                $ratePrice = round($raw['price'] - $raw['prev_close_price'], 4);

                $rate = $this->currencyMultiplier($currencyId);
                $bidUsd = $raw['bid'] / self::TROY_OUNCE_GRAMS;
                $askUsd = $raw['ask'] / self::TROY_OUNCE_GRAMS;
                $originalBuy = round($bidUsd * $rate, 4);
                $originalSell = round($askUsd * $rate, 4);

                $purities = Exchange::getPuritiesByMetal($metalId);
                $apiPrices = [
                    'metalName' => Exchange::AVAILABLE_METALS_NAME[$raw['metal']] ?? 'METAL',
                    'prices' => [],
                ];
                foreach ($purities as $karat => $percentage) {
                    $apiPrices['prices'][$karat] = [
                        'buy'  => round($originalBuy * ($percentage / 100), 4),
                        'sell' => round($originalSell * ($percentage / 100), 4),
                    ];
                }
            }
        }

        // Pre-load any rows already saved today for this metal+currency so we can show + upsert
        $existingByKarat = [];
        $today = date('Y-m-d');
        $todays = MetalPrices::find()
            ->where(['metal_id' => $metalId, 'currency_id' => $currencyId])
            ->andWhere(['>=', 'created_at', $today . ' 00:00:00'])
            ->all();
        foreach ($todays as $r) {
            $existingByKarat[$r->karat] = $r;
        }

        return compact('apiPrices', 'originalBuy', 'originalSell', 'rateStatus', 'ratePrice', 'apiTimestamp', 'existingByKarat');
    }

    /**
     * Multiplier to convert a USD price into the target currency, using exchange_rates
     * (which stores AMD-per-X rates: amd_per_usd, amd_per_eur, amd_per_rub, etc.).
     *   price_in_X  = price_in_USD * (AMD_per_USD / AMD_per_X)
     *   price_in_AMD = price_in_USD * AMD_per_USD
     */
    protected function currencyMultiplier(int $currencyId): float
    {
        if ($currencyId === 1) {
            return 1.0;
        }

        $usdRow = ExchangeRates::find()->where(['currency_id' => 1])->orderBy(['updated_at' => SORT_DESC])->one();
        $amdPerUsd = $usdRow ? (float)$usdRow->sell_rate : 0;
        if ($amdPerUsd <= 0) {
            return 1.0;
        }

        if ($currencyId === 3) {
            return $amdPerUsd;
        }

        $targetRow = ExchangeRates::find()->where(['currency_id' => $currencyId])->orderBy(['updated_at' => SORT_DESC])->one();
        $amdPerTarget = $targetRow ? (float)$targetRow->sell_rate : 0;
        if ($amdPerTarget <= 0) {
            return 1.0;
        }
        return $amdPerUsd / $amdPerTarget;
    }

    /**
     * Save (upsert) per-karat prices in a single transaction.
     * Upsert key: (metal_id, currency_id, karat, DATE(created_at)).
     */
    protected function savePrices($postData)
    {
        if (!is_array($postData) || empty($postData['karat'])) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'No karat data submitted.'));
            return $this->redirect(['create']);
        }

        $metalId = (int)$postData['metal_id'];
        $currencyId = (int)$postData['currency_id'];
        $rateStatus = (int)($postData['rate_status'] ?? 0);
        $ratePrice = (float)($postData['rate_price'] ?? 0);
        $today = date('Y-m-d');

        $tx = Yii::$app->db->beginTransaction();
        $inserted = 0;
        $updated = 0;
        try {
            foreach ($postData['karat'] as $i => $karat) {
                $karat = (int)$karat;
                if ($karat <= 0) continue;

                $existing = MetalPrices::find()
                    ->where(['metal_id' => $metalId, 'currency_id' => $currencyId, 'karat' => $karat])
                    ->andWhere(['>=', 'created_at', $today . ' 00:00:00'])
                    ->one();

                $row = $existing ?: new MetalPrices();
                $row->metal_id    = $metalId;
                $row->currency_id = $currencyId;
                $row->karat       = $karat;
                $row->sell_price          = (float)($postData['sell_price'][$i] ?? 0);
                $row->buy_price           = (float)($postData['buy_price'][$i] ?? 0);
                $row->original_sell_price = (float)($postData['original_sell_price'][$i] ?? 0);
                $row->original_buy_price  = (float)($postData['original_buy_price'][$i] ?? 0);
                $row->rate_status = $rateStatus;
                $row->rate_price  = $ratePrice;

                if (!$row->save()) {
                    throw new \RuntimeException('Save failed for karat ' . $karat . ': ' . json_encode($row->getErrors()));
                }
                $existing ? $updated++ : $inserted++;
            }
            $tx->commit();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Saved {ins} new, updated {upd} existing.', ['ins' => $inserted, 'upd' => $updated]));
            return $this->redirect(['index']);
        } catch (\Throwable $e) {
            $tx->rollBack();
            Yii::$app->session->setFlash('error', Yii::t('app', 'Save failed: ') . $e->getMessage());
            return $this->redirect(['create', 'metal_id' => $metalId, 'currency_id' => $currencyId]);
        }
    }

    protected function findModel($id)
    {
        if (($model = MetalPrices::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
