<?php

namespace backend\controllers;

use Yii;
use backend\models\TrBlog;
use yii\web\Controller;
use yii\filters\VerbFilter;

class TrBlogController extends Controller
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

        if (isset($post['TrBlog'])) {
            $data = $post['TrBlog'];
            $model = TrBlog::findOne(['language_id' => $data['language_id'], 'blog_id' => $data['blog_id']])
                  ?? new TrBlog();
            $model->attributes = $data;
            $model->save();
            return $this->redirect(['/' . Yii::$app->language . '/blog/index']);
        }

        if (!empty($post) && Yii::$app->request->isAjax) {
            $model = TrBlog::findOne(['language_id' => $post['lang'], 'blog_id' => $post['blog']])
                  ?? new TrBlog(['language_id' => $post['lang'], 'blog_id' => $post['blog']]);
            echo $this->renderAjax('_form', ['model' => $model]);
            Yii::$app->end();
        }
    }
}
