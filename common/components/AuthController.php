<?php
namespace common\components;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AuthController extends Controller
{

    public function behaviors()
    {

        $behavior = [

            'access' =>
                [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['login', 'error'],
                            'allow' => true,
                        ],
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],

                    ],
                    'denyCallback' => function () {
                        return \Yii::$app->response->redirect(['admin/login']);
                    },
                ],


            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];

        return $behavior;
    }
}