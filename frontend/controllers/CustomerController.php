<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use frontend\models\CustomerAddress;
use frontend\models\Address;
use common\components\Location;
use frontend\models\Customer;

class CustomerController extends \yii\web\Controller
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
                    'edite-address' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'delete', 'add-address', 'edite-address', 'save-address','profile'],
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
     * @return string
     */
    public function actionEditeAddress()
    {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $model = CustomerAddress::findOne($id);
        return $this->renderPartial('update',['model'=>$model]);
    }

    public function actionSaveAddress()
    {
        $post = Yii::$app->request->post();
        $model = CustomerAddress::findOne($post['CustomerAddress']['id']);
        if($model->load($post) && $model->validate()){
            $isValid = $this->ValidateAddress(array(
                'country' => $model->country,
                'state' => $model->state,
                'city' => $model->city,
                'address' => $model->address,
            ));
            if ($isValid) {
                $addr = $model->address . ' ' . $model->city;
                $Location = Location::getLatLngByAddress($addr, $model->country);
                $model->lat = $Location['lat'];
                $model->long = $Location['lng'];
                $model->address = ($model->str_num) ? $model->str_num. ' ' .$model->address : $model->address;
                if($model->save()){
                    return json_encode(['success' => true, 'message' => $model->id]);
                }else{
                    return json_encode(['success' => false, 'message' => $model->errors]);
                }
            } else {
                return json_encode(['success' => false, 'message' => 'Invalid Address!']);
            }
        }else{
            return json_encode(['success' => false, 'message' => $model->errors]);
        }
    }

    public function actionDelete()
    {
        if(\Yii::$app->request->isPost){
            $post = \Yii::$app->request->post();
            $CustomerAddressID = $post['id'];
            $model = CustomerAddress::findOne($CustomerAddressID);
            if($model){
                $model->delete();
                return json_encode(['success'=>true]);
            }
        }
        return json_encode(['success'=>false]);
    }

    /**
     * @return string
     */
    public function actionAddAddress()
    {
        $CustomerAddress = new CustomerAddress();
        $model = new Address();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $isValid = $this->ValidateAddress(array(
                'country' => $model->country,
                'state' => $model->state,
                'city' => $model->city,
                'address' =>($model->strnumb) ? $model->strnumb.' '.$model->addr1 : $model->addr1,
            ));
            if ($isValid) {
                $street = ($model->strnumb) ? $model->strnumb.' '.$model->addr1 : $model->addr1;
                $addr = $street . ' ' . $model->city;
                $Location = Location::getLatLngByAddress($addr, $model->country);
                $CustomerAddress->country = $model->country;
                $CustomerAddress->city = $model->city;
                $CustomerAddress->state = $model->state;
                $CustomerAddress->address = $street;
                $CustomerAddress->lat = $Location['lat'];
                $CustomerAddress->long = $Location['lng'];
                $CustomerAddress->customer_id = Yii::$app->user->identity->customer->id;
                $CustomerAddress->save();
                return json_encode(['success' => true, 'message' => $CustomerAddress->id]);
            } else {
                return json_encode(['success' => false, 'message' => 'Invalid Address']);
            }
        } else {
            return json_encode(['success' => 'error', 'message' => $model->errors]);
        }
    }

    /**
     * @param $address
     * @return bool if isset coordinates true else false
     */
    public function ValidateAddress($address)
    {
        $isValid = false;
        $newaddr = $address['address'] . ' ' . $address['city'];
        $region = $address['country'];
        $coord = Location::getLatLngByAddress($newaddr, $region);
        if (isset($coord['lat']) && isset($coord['lng'])) {
            return $coord;
        }
        return $isValid;
    }
    public function actionProfile(){
        $this->layout='profile';
        return $this->render('profile');
    }
    public function actionUpdateItem()
    {
        if (Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();

            $CustomerAddress = CustomerAddress::findOne(['customer_id'=>Yii::$app->user->identity->customer->id,'default_address'=>1]);
            if($CustomerAddress->load($post) && $CustomerAddress->validate()){
                $CustomerAddress->update();
            }else{
                return json_encode($CustomerAddress->errors);
            }
        }
    }
}
