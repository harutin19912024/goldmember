<?php


namespace frontend\controllers;


use common\models\Countries;
use common\models\Favorites;
use common\models\Product;
use frontend\models\CustomerAddress;
use common\models\User;
use frontend\models\EditPassword;
use Yii;
use frontend\models\Order;
use frontend\models\OrderSearch;
use yii\filters\VerbFilter;
use frontend\models\Customer;
use yii\helpers\ArrayHelper;
use common\models\Language;


class UserController extends \yii\web\Controller

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
                    'update-item' => ['POST'],
                    'delete' => ['POST'],
                    'edite-address' => ['POST'],
                ],
            ],

            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['history', 'profile', 'edit-password', 'update-item'],
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

    public function actionProfile()
    {


        $this->layout = 'main';
        $role = Yii::$app->user->identity->role;
        $view_file_path = '';
        $userModel = null;
        if ($role == 20) {
            $userModel = Yii::$app->user->identity->customer;
            $view_file_path = 'customer/profile';
        } elseif ($role == User::REPAIRER) {
            $userModel = Yii::$app->user->identity->repairer;
            $view_file_path = 'repairer/profile';
        }

		$user_id = Yii::$app->user->identity->id; 
		$favorites = \frontend\models\Product::getFavoritesByUser($user_id);
//         $customerAdressObj = CustomerAddress::findOne(['customer_id' => $userModel->id, 'default_address' => 1]);
// 		if ($customerAdressObj) {
//             $modelAdd = $customerAdressObj;
//             $addresForm = $this->renderPartial('customer/update', array(
//                 'model' => $modelAdd
//             ));
//         } else {
//             $modelAdd = new CustomerAddress();
//             $countries = Countries::find()->select(['id', 'name'])->where(['status' => 1])->asArray()->all();
//             $countries = ArrayHelper::map($countries, 'name', 'name');
//             $addresForm = $this->renderPartial('customer/create', array(
//                 'model' => $modelAdd,
//                 'countries' => $countries,
//             ));

//         }

        if (Yii::$app->request->post()) {
            $postArr = Yii::$app->request->post();
            $postArr['CustomerAddress']['customer_id'] = $userModel->id;
            $postArr['CustomerAddress']['default_address'] = 1;
            if ($modelAdd->load($postArr) && $modelAdd->save()) {
                return $this->redirect(['user/profile']);
            } else {
                return $this->render($view_file_path, [
                    'UserModel' => $userModel,
                    //'addressForm' => $addresForm,
					'favorites' => $favorites
                ]);

            }

        }
		$languege = Language::find()->where(['short_code' => Yii::$app->language])->asArray()->all();
        return $this->render($view_file_path, [
            'UserModel' => $userModel,
			'isDefaultLanguage' => $languege[0]['is_default'],
            //'addressForm' => $addresForm,
            'favorites' => $favorites
        ]);
    }


    public function actionHistory()
    {
        $this->layout = 'profile';
        $role = Yii::$app->user->identity->role;
        $view_file_path = '';
        if ($role == User::CUSTOMER) {
            $user_id = Yii::$app->user->identity->customer->id;
            $view_file_path = 'customer/history';
        } elseif ($role == User::REPAIRER) {
            $user_id = Yii::$app->user->identity->repairer->id;
            $view_file_path = 'repairer/history';
        }
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user_id, $role);

        return $this->render($view_file_path, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */

    public function actionEditPassword()
    {
        $this->layout = 'profile';
        $model = new EditPassword();
        $model->username = Yii::$app->user->identity->username;
        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
            Yii::$app->session->setFlash('success', 'Your password successfuly updated!');
            return $this->redirect('/user/profile');
        }

        return $this->render('edit-password', [
            'model' => $model,
        ]);
    }

    public function actionProfileImage()
    {
        $this->layout = 'profile';
        return $this->render('profile-image');
    }

    public function actionUpdateItem()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();

            $Customer = Customer::findOne(Yii::$app->user->identity->customer->id);
            if ($Customer->load($post) && $Customer->validate()) {
                $Customer->update();
            } else {
                return json_encode($Customer->errors);
            }
        }
    }
}