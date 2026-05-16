<?php

namespace backend\controllers;

use Yii;
use backend\models\TrSlider;
use yii\web\Controller;
use yii\filters\VerbFilter;

class TrSliderController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['POST']]],
        ];
    }

    /**
     * Handles both:
     *  - AJAX POST with {lang, slider} → render the translation _form
     *  - POST with TrSlider data        → save and redirect
     */
    public function actionUpdate()
    {
        $post = Yii::$app->request->post();

        if (isset($post['TrSlider'])) {
            $data = $post['TrSlider'];
            $model = TrSlider::findOne(['language_id' => $data['language_id'], 'slider_id' => $data['slider_id']])
                  ?? new TrSlider();
            $model->attributes = $data;
            $model->save();
            return $this->redirect(['/' . Yii::$app->language . '/slider/index']);
        }

        if (!empty($post) && Yii::$app->request->isAjax) {
            $model = TrSlider::findOne(['language_id' => $post['lang'], 'slider_id' => $post['slider']])
                  ?? new TrSlider(['language_id' => $post['lang'], 'slider_id' => $post['slider']]);
            echo $this->renderAjax('_form', ['model' => $model]);
            Yii::$app->end();
        }
    }
}
