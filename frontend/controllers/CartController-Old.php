<?php

namespace frontend\controllers;

use common\components\Location;
use frontend\models\CustomerAddress;
use Yii;
use frontend\models\Address;
use frontend\models\Customer;
use frontend\models\Order;
use frontend\models\Product;
use frontend\models\Service;
use yii\helpers\Url;
use yz\shoppingcart\ShoppingCart;
use yii\web\Controller;
use backend\models\Product;
use common\models\Language;

class CartController extends Controller {

    public function actionAdd() {
        if (\Yii::$app->request->isAjax) {
            $id = \Yii::$app->request->post('service_id');
            $product = Service::findOne($id);
            if ($product) {
                \Yii::$app->cart->put($product);
            }
        }
    }

    public function actionList() {
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;
        Url::remember();
        $products = $cart->getPositions();
        $total = $cart->getCost();

        return $this->render('list', [
                    'products' => $products,
                    'total' => $total,
        ]);
    }

    public function actionRemove($id) {

        $product = Service::findOne($id);
        if ($product) {
            \Yii::$app->cart->remove($product);
            $this->redirect(['cart/list']);
        }
    }

    public function actionUpdate($id, $quantity) {
        $product = Service::findOne($id);
        if ($product) {
            \Yii::$app->cart->update($product, $quantity);
            $this->redirect(['cart/list']);
        }
    }

    public function actionOrderList($product_id = NULL) {
        $languege = Language::find()->where(['short_code' => Yii::$app->language])->asArray()->all();
        if(!is_null($product_id)) {
            if ($languege[0]['is_default']) {
                $basketProducts = Product::find()->where(['id' => $product_id])->all();
            } else {
                $basketProducts = TrProduct::find()->where(['language_id' => $languege[0]['id'], 'product_id' => $product_id])->all();
            }
            $cookies = Yii::$app->request->cookies;
            echo "<pre>";
            print_r($cookies->getValue('canCount'));die;
        }else{
            $session = Yii::$app->session;
            if(!empty($session->get('basket')['product'])){
                $basketProducts = $session->get('basket')['product'];
            }
        }
        return $this->render('order',['basketProducts'=>$basketProducts]);
    }

    public function actionOrder() {
        if (!empty(Yii::$app->user->identity)) {
            $address = new Address();
            $customer = new Customer();
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
            /* @var $cart ShoppingCart */
            $cart = \Yii::$app->cart;

            /* @var $products Product[] */
            $products = $cart->getPositions();
            $total = $cart->getCost();

            if ($address->load(\Yii::$app->request->post()) && $address->validate()) {
                $Addresses = [
                    [
                        'country' => $address->country,
                        'state' => $address->state,
                        'city' => $address->city,
                        'address' => $address->addr1,
                    ],
                    [
                        'country' => $address->country,
                        'state' => $address->state,
                        'city' => $address->city,
                        'address' => $address->addr2,
                    ],
                ];
                $isValid = true;

                foreach ($Addresses as $Address) {
                    $isValid = $this->ValidateAddress($Address) && $isValid;
                }

                if ($isValid) {
                    $transaction = $customer->getDb()->beginTransaction();
                    if (Yii::$app->user->isGuest) {
                        /* add new customer if is guest */
                        $customer->name = $address->name;
                        $customer->email = $address->email;
                        $customer->phone = $address->phone;
                        $customer->status = 1;
                        $customer->user_id = null;
                        $customer->last_ip = Yii::$app->request->getUserIP();
                        $customer->save(false);
                        Yii::$app->session->set('Customer', $customer->id);
                        $customer_id = $customer->id;
                    } else {
                        $customer_id = Yii::$app->user->identity->customer->id;
                    }

                    /* add customer address */
                    if (!isset($address->addr_id)) {
                        $customerAddress->batchInsert($Addresses, $customer_id);
                        $customer_address_id = Yii::$app->db->getLastInsertID();
                    } else {
                        $customer_address_id = $address->addr_id;
                    }

                    /* add order */
                    foreach ($products as $product) {
                        $orderItem = new Order();
                        $orderItem->customer_id = $customer_id;
                        $orderItem->customer_address_id = $customer_address_id;
                        $orderItem->status = 0;
                        $orderItem->created_date = date("Y-m-d H:i:s");
                        $orderItem->updated_date = date("Y-m-d H:i:s");
                        $orderItem->service_id = $product->id;
                        if (!$orderItem->save(false)) {
                            $transaction->rollBack();
                            return $this->redirect('cart/list');
                        }
                    }

                    $transaction->commit();
                    \Yii::$app->cart->removeAll();
                    return $this->redirect(['service/pay']);
                } else {
                    Yii::$app->session->addFlash('error', 'Your Address is invalid!');
                    return $this->redirect('/cart/order');
                }
            }
        }
        return $this->render('order');
    }

    public function ValidateAddress($address) {
        $isValid = false;
        $newaddr = $address['address'] . ' ' . $address['city'];
        $region = $address['country'];
        $coord = Location::getLatLngByAddress($newaddr, $region);
        if (isset($coord['lat']) && isset($coord['lng'])) {
            $isValid = true;
        }
        return $isValid;
    }

    public function FindFreeTechicans() {
        $technicans = [];
        // find sqript
        return $technicans;
    }

}
