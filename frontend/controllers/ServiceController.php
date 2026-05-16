<?php

namespace frontend\controllers;

use common\components\Location;
use frontend\models\Address;
use frontend\models\Brand;
use frontend\models\Customer;
use frontend\models\CustomerAddress;
use frontend\models\CustomerCard;
use frontend\models\Product;
use frontend\models\Repairer;
use Yii;
use frontend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yz\shoppingcart\ShoppingCart;
use frontend\models\Order;
use common\components\Notification;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class ServiceController extends Controller
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
                    'filterbrand' => ['POST'],
                    'filterproduct' => ['POST'],
                    'add-address' => ['POST'],
                    'cansel-order' => ['POST'],
                    'progress' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['address', 'map', 'pay', 'status', 'add-address', 'cansel-order', 'progress'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        Url::remember();
        $brands_model = new Brand();
        $products_model = new Product();
        $brands = $brands_model->getAllBrands();
        $brand = null;
        if(isset($id)){
            $brand = $brands_model::findOne($id);
        }
        return $this->render('index', [
            'Brands' => $brands,
            'brand' => $brand,
            'ProductModel' => $products_model
        ]);
    }

    /**
     * Displays a single Service model.
     * @param integer $id
     * @return mixed
     */
    public function actionFilterbrand()
    {
        if (Yii::$app->request->isAjax) {
            $products_model = new Product();
            $brand_id = Yii::$app->request->post('brand_id');

            $brand = Brand::findOne($brand_id);
            $products = $products_model->getProductsByBrand($brand->id);
            return json_encode($products);
        }

    }

    /**
     * Creates a new Service model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFilterproduct()
    {
        if (Yii::$app->request->isAjax) {
            $product_id = Yii::$app->request->post('product_id');
            $service_model = new Service();
            $services = $service_model->getServicesByProduct($product_id);
            $html = $this->renderPartial('services', ['services' => $services]);
            echo $html;
            exit();
        }
    }

    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAddress()
    {
        $model = new Address();

        $customerAddress = new CustomerAddress();
        $customer_id = Yii::$app->user->identity->customer->id;
        $CustomerAddress = $customerAddress->getCustomerAddressByCustomerId($customer_id);
        if ($CustomerAddress != null) {
            foreach ($CustomerAddress as $key => $customerAddres) {
                $customerAddres['name'] = Yii::$app->user->identity->customer->name;
                $customerAddres['surname'] = Yii::$app->user->identity->customer->surname;
                $customerAddres['phone'] = Yii::$app->user->identity->customer->phone;
                $customerAddres['email'] = Yii::$app->user->identity->customer->email;
                $CustomerAddress[$key] = $customerAddres;
            }
        }

        return $this->render('address', [
            'model' => $model,
            'CustomerAddress' => $CustomerAddress
        ]);

    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionPay()
    {
        if (Yii::$app->user->identity->customer->customerCard) {
            return $this->redirect(['/service/status']);
        }

        if (!Yii::$app->user->isGuest) {
            $customer_id = Yii::$app->user->identity->customer->id;
        } else {
            $customer_id = Yii::$app->session->get('Customer');
        }

        if (!isset($customer_id)) {
            return $this->goBack();
        }
        $address_id = Yii::$app->request->get()['address'];


//        Yii::$app->session->destroy();
        $castomer = CustomerAddress::findOne($address_id);
        $repairer = new Repairer();
        $Coordinates = $repairer->getFreeRepairersCoord();

        $CustomerCoord = [
            'lat' => $castomer->lat,
            'lng' => $castomer->long,
        ];
        $NewCoord = ArrayHelper::index($Coordinates, 'id');
        foreach ($NewCoord as $key => $value) {
            $value['lng'] = $value['long'];
            $NewCoord[$key] = ArrayHelper::filter($value, ['lat', 'lng']);
        }
        $Nearests = Location::getNearCoordinatesFrom($NewCoord, $CustomerCoord, 20);
        if (empty($Nearests)) {
            Yii::$app->session->setFlash('error', 'The Free technicans not found around 20km radius!');
            return $this->goBack();
        }
        $keys = array_keys($Nearests);


        $model = new CustomerCard();
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;

        /* @var $products Product[] */
        $products = $cart->getPositions();
        $services = [];
        foreach ($products as $product) {
            $services[] = $product->name;
        }
        $total = $cart->getCost();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!Yii::$app->user->isGuest) {
                $customer_id = Yii::$app->user->identity->customer->id;
            } else {
                $customer_id = Yii::$app->session->get('Customer');
            }
            $model->customer_id = $customer_id;
            /* add order*/
            $Orders = [];
            foreach ($products as $product) {
                $orderItem = new Order();
                $orderItem->customer_id = $customer_id;
                $orderItem->customer_address_id = $address_id;
                $orderItem->status = 0;
                $orderItem->created_date = date("Y-m-d H:i:s");
                $orderItem->updated_date = date("Y-m-d H:i:s");
                $orderItem->accepted_date = date("Y-m-d H:i:s");
                $orderItem->service_id = $product->id;
                $orderItem->save();
                $Orders[] = $orderItem->id;
                $cart->remove($product);
            }
            foreach ($Nearests as $key => $value) {
                $DeviceTokens = $repairer:: findDeviseToken($key);
                if ($DeviceTokens) {

                    Notification::sendPushNotification($DeviceTokens, 'New Order ready for accepting!', [
                        'order_ids' => $Orders
                    ]);
                }
            }
            Yii::$app->session->set('orders', $Orders);
            Yii::$app->session->set('repairer', $keys[0]);
            Yii::$app->session->set('address', $address_id);

            return $this->redirect(['/service/map']);
        }
        return $this->render('pay', [
            'model' => $model,
            'Cost' => $total,
            'Products' => $services
        ]);
    }

    /**
     * @return string
     */
    public function actionStatus()
    {
        $Orders = Yii::$app->session->get('orders');
        $orders = Order::find()->where(['id' => $Orders])->all();
        return $this->render('status', [
            'Orders' => $orders
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionCanselOrder()
    {
        $post = Yii::$app->request->post();
        $ids = json_decode($post['ids']);
        foreach ($ids as $key => $val) {
            $model = Order::findOne($val);
            if ($model && $model->status == 0) {
                $model->delete();
            }else{
                return json_encode(['success'=>false, 'message'=>'The order can not be canceled!']);
            }
        }
    }


    /**
     * @return string
     */
    public function actionMap()
    {

        $address_id = Yii::$app->session->get('address');
        $orders = Yii::$app->session->get('orders');
        $Order = Order::find()->where(['id' => $orders])->all();
        $Repairer = $Order[0]->repairer;

        $CustomerAddress = CustomerAddress::findOne($address_id);
        $CustomerCoord = [
            'lat' => $CustomerAddress->lat,
            'lng' => $CustomerAddress->long,
        ];
//        var_dump( $Order[0]->repairer);die;
        if ($Repairer) {
            $phone = $Repairer->phone;
            $RepairerCoord = [
                'lat' => $Repairer->lat,
                'lng' => $Repairer->long,
            ];
//            $map = Location::ShowWayToCustomer($RepairerCoord, $CustomerCoord);
        } else {
//            $map = Location::ShowLocation($CustomerCoord);
            $phone = '';

            Yii::$app->session->setFlash('error', 'Your order is not accetped!');
        }
        return $this->render('map', [
//            'map' => $map,
            'repairer' => $Repairer,
            'address_id' => $address_id,
            'Orders' => $Order[0]->id,
        ]);


    }

    public function actionProgress()
    {
        $id = Yii::$app->request->post('id');

        $Order = Order::find()->where(['id'=>$id])->one();
        if($Order){
            $status = $Order->status;
        }else{
            $status = false;
        }
        return $status;
    }

    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
