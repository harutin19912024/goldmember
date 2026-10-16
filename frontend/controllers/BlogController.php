<?php

namespace frontend\controllers;

use yii\web\Controller;

/**
 * Legacy blog URLs. Articles are published through News; the old blog pages
 * came from an unrelated shop template (and allowed public create/update/delete),
 * so every blog URL now permanently redirects to the news section.
 */
class BlogController extends Controller {

    public function actionIndex() {
        return $this->redirect(['/site/news-list'], 301);
    }

    public function actionView($id = null) {
        return $this->redirect(['/site/news-list'], 301);
    }

}
