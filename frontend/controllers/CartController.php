<?php

namespace frontend\controllers;

use common\components\Location;
use frontend\models\CustomerAddress;
use Yii;
use frontend\models\Address;
use frontend\models\Customer;
use frontend\models\Order;
use frontend\models\Service;
use yii\helpers\Url;
use yz\shoppingcart\ShoppingCart;
use yii\web\Controller;
use backend\models\Product;
use common\models\Language;
use common\models\Countries;
use common\models\Zones;

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

    public function actionList($id = NULL) {

        $session = Yii::$app->session;

        $arrCountries = array();
        $arrCountries = Countries::findAll(['status'=>1]);

        $arrFreightMethods = array();

        return $this->render('order', [
            'paymentForm'=>$this->renderPartial('forms/payment-form.php'),
           // 'freightForm'=>$this->renderPartial('forms/freight-form',['arrFreightMethods'=>$arrFreightMethods]),
            'basketProducts' => $session->get('basket')['products'],
            'total' => $session->get('basketTotalPrice'),
            'totalWeight' => $session->get('basketTotalWeight'),
            'productCount' => $session->get('basketProductCount'),
            'countries'=>$arrCountries
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
    
    public function actionSaveAddress(){
        if (\Yii::$app->request->isAjax) {
            $billingInfo = \Yii::$app->request->post('billingInfo');
            $orderInfo = \Yii::$app->request->post('orderInfo');
            $shippingInfo = \Yii::$app->request->post('shippingInfo');
			$customer = Customer::find()->where(['email'=>$billingInfo['email']])->one();
            $order = new Order();
            $customerAddress = new CustomerAddress();
			if(!empty($customer)){
				$customer->name = $billingInfo['name'];
				$customer->surname = $billingInfo['lastname'];
				//$customer->mobile_phone = $billingInfo['mobile_phone'];
				$customer->email = $billingInfo['email'];
				$customer->phone = $billingInfo['phone'];
				$customer->status = 1;
				$customer->last_ip = Yii::$app->request->getUserIP();
			}else{
				$customer = new Customer();
				$customer->name = $billingInfo['name'];
				$customer->surname = $billingInfo['lastname'];
				//$customer->mobile_phone = $billingInfo['mobile_phone'];
				$customer->email = $billingInfo['email'];
				$customer->phone = $billingInfo['phone'];
				$customer->status = 1;
				$customer->user_id = null;
				$customer->last_ip = Yii::$app->request->getUserIP();
			}
           
            if($customer->save(false)){
                if($coordinate = $this->ValidateAddress($shippingInfo)){
                    $customerAddress->city = 'Moscow';
                    $customerAddress->country = 'Russian';
                    $customerAddress->address = $shippingInfo['address'];
                    //$customerAddress->state = $shippingInfo['state'];
                    //$customerAddress->long = $coordinate['lng'];
                    //$customerAddress->lat = $coordinate['lat'];
                   // $customerAddress->zip = $shippingInfo['zip'];
                    $customerAddress->customer_id = $customer->id;
                    if($customerAddress->save(false)){
                        $order->customer_id = $customer->id;
                        $order->customer_address_id = $customerAddress->id;
                        $order->status = 0;
                        $order->additional_info =$shippingInfo['additional_info'];
                        $order->billing_address = json_encode($billingInfo);
                        $order->shipping_address = json_encode($shippingInfo);
                        $order->order_info = json_encode($orderInfo);
                        if($order->save(false)){
							$session = Yii::$app->session;
							$session->remove('basket');
							$session->remove('basketTotalPrice');
							$session->remove('basketProductCount');
                            echo json_encode(['result'=>true]);exit();
                        }else{
							echo json_encode(['result'=>false]);exit();
						}
                    }else{
						echo json_encode(['result'=>false]);exit();
					}
                    Yii::$app->session->set('Customer', $customer->id);
                }else{
					echo json_encode(['result'=>false]);exit();
				}
               
            }else{
				echo json_encode(['result'=>false]);exit();
			}

        }
        
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
        $newaddr = $address['address'] . ' Moscow';
        $region = 'Russian';
        $coord = Location::getLatLngByAddress($newaddr, $region);
        if (isset($coord['lat']) && isset($coord['lng'])) {
            return $coord;
        }
        return true;
    }

}
