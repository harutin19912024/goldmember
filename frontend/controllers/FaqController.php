<?php

namespace frontend\controllers;

use backend\models\Faq;
use backend\models\TrFaq;
use common\models\Language;
use Yii;
use yii\web\Controller;

class FaqController extends Controller
{
    public function actionIndex()
    {
        $languege = Language::find()->where(['short_code' => Yii::$app->language])->asArray()->all();
        if (!$languege[0]['is_default']) {
            $faq = TrFaq::find()->all();
        } else {
            $faq = Faq::find()->all();
        }
        return $this->render('faq', ['faq' => $faq, 'isDefaultLan' => $languege[0]['is_default']]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findFaq($id),
        ]);
    }
    protected function findFaq($id)
    {
        $languege = Language::find()->where(['short_code' => Yii::$app->language])->asArray()->all();

        if ($languege[0]['is_default'] == 1) {
            $model = Faq::findOne($id);
            return $model;
        } else{
            $model = TrFaq::findOne(['faq_id'=>$id, 'language_id'=>$languege[0]['id']]);
            return $model;
        }

    }

    public function actionAddFaqanswer() {
        if (Yii::$app->request->isAjax) {
            $answer = (int) (Yii::$app->request->post('answer'));
            $faq_id = (int) (Yii::$app->request->post('faq_id'));
            $faq = Faq::findOne($faq_id);
            if($answer){
                $faq->yes_count += 1;
            }else{
                $faq->no_count += 1;
            }
            if($faq->save()){
                echo json_encode(['yes_count'=>$faq->yes_count,'no_count'=>$faq->no_count]);exit();
            }
        }
    }


}

