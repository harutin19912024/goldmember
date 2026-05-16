<?php

namespace backend\controllers;

use Yii;
use backend\models\TrPartners;
use yii\web\Controller;
use yii\filters\VerbFilter;

class TrPartnersController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['POST']]],
        ];
    }

    public function actionUpdate()
    {
        $post = Yii::$app->request->post();

        if (isset($post['TrPartners'])) {
            $data = $post['TrPartners'];
            $model = TrPartners::findOne(['language_id' => $data['language_id'], 'partners_id' => $data['partners_id']])
                  ?? new TrPartners();
            $model->attributes = $data;
            $model->save();
            return $this->redirect(['/' . Yii::$app->language . '/partners/index']);
        }

        if (!empty($post) && Yii::$app->request->isAjax) {
            $model = TrPartners::findOne(['language_id' => $post['lang'], 'partners_id' => $post['partners']])
                  ?? new TrPartners(['language_id' => $post['lang'], 'partners_id' => $post['partners']]);
            echo $this->renderAjax('_form', ['model' => $model]);
            Yii::$app->end();
        }
    }
}
