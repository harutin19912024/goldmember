<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \backend\models\LoginForm;
use backend\models\User;
use yii\data\ActiveDataProvider;
use backend\models\Sitesettings;
use backend\models\Homepage;
use backend\models\SocialNet;
use backend\models\Delivery;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $model = User::findOne(1);
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['id' => 1]),
        ]);
        $dataProviderSettings = new ActiveDataProvider([
            'query' => Sitesettings::find(),
        ]);
        $dataProviderSocialNet = new ActiveDataProvider([
            'query' => SocialNet::find(),
        ]);
		$dataProviderHome = new ActiveDataProvider([
            'query' => Homepage::find(),
        ]);
        return $this->render('index', [
							'model' => $model, 
							'dataProvider' => $dataProvider,
							'dataProviderSocialNet' => $dataProviderSocialNet,
							'dataProviderHome' => $dataProviderHome,
							'dataProviderSettings'=>$dataProviderSettings
							]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			if(Yii::$app->user->identity->role) {
				if(Yii::$app->user->identity->user_number == 101) {
					return $this->redirect('/product/index');
				} else {
					return $this->redirect('/product/index');
				}
			}
            return $this->goBack();
        } else {
            return $this->renderPartial('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
