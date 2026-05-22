<?php

namespace backend\controllers;

use Yii;
use backend\models\NewsCategory;
use backend\models\TrNewsCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class TrNewsCategoryController extends Controller
{
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

    public function actionUpdate()
    {
        if (isset(Yii::$app->request->post()['TrNewsCategory'])) {
            $arrPost = Yii::$app->request->post()['TrNewsCategory'];
            $trModel = TrNewsCategory::findOne(['language_id' => $arrPost['language_id'], 'category_id' => $arrPost['category_id']]);
            if (!$trModel) {
                $trModel = new TrNewsCategory();
                $trModel->language_id = $arrPost['language_id'];
                $trModel->category_id = $arrPost['category_id'];
            }
            $trModel->name = $arrPost['name'] ?? '';
            $trModel->short_description = $arrPost['short_description'] ?? '';
            $trModel->description = $arrPost['description'] ?? '';
            $trModel->route_name = $arrPost['route_name'] ?? '';
            echo $trModel->save() ? 'true' : 'false';
            exit();
        } elseif (!empty(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            $arrPost = Yii::$app->request->post();
            $tr = TrNewsCategory::findOne(['language_id' => $arrPost['lang'], 'category_id' => $arrPost['newscategory']]);
            if (!$tr) {
                $tr = new TrNewsCategory();
                $tr->language_id = $arrPost['lang'];
                $tr->category_id = $arrPost['newscategory'];
                $nc = NewsCategory::findOne($arrPost['newscategory']);
                if ($nc) {
                    $tr->name = $nc->name;
                    $tr->short_description = $nc->short_description ?? '';
                    $tr->description = $nc->description ?? '';
                    $tr->route_name = $nc->route_name ?? '';
                }
            }
            echo $this->renderPartial('_form', ['model' => $tr]);
            exit();
        }
    }

    protected function findModel($id)
    {
        if (($model = TrNewsCategory::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
