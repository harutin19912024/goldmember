<?php

namespace backend\controllers;

use backend\models\Product;
use backend\models\TrProduct;
use Yii;
use backend\models\NewsCategory;
use backend\models\TrNewsCategory;
use backend\models\NewsCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Attribute;
use backend\models\TrAttribute;
use yii\filters\AccessControl;
use backend\models\User;
use common\models\Language;
use common\components\RuleHelper;

/**
 * NewsCategoryController implements the CRUD actions for NewsCategory model.
 */
class NewsCategoryController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'view' => ['GET'],
                    'create' => ['GET', 'POST'],
                    'update' => ['GET','POST'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => 'common\components\CAccessRule',
                ],
                'only' => ['index', 'view', 'create', 'update', 'delete','allrules','trcats','trproducts'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete','allrules','trcats','trproducts'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            User::ADMIN,
                        ],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all NewsCategory models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new NewsCategory();
        $searchModel = new NewsCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'defoultId' => $defaultLanguage->id
        ]);
    }

    /**
     * Lists all NewsCategory models.
     * @return mixed
     */
    public function actionGetform() {
        $model = new NewsCategory();
        $searchModel = new NewsCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
            echo $_form = $this->renderPartial('_form', [
                'model' => $model,
            ]);
            exit();
        }

        $_form = $this->renderPartial('_form', [
            'model' => $model,
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            '_Form' => $_form
        ]);
    }

    /**
     * Displays a single NewsCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new NewsCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $NewsCategory = new NewsCategory();
            $postData = Yii::$app->request->post('NewsCategory');
            $NewsCategory->setAttributes($postData);

            $order = NewsCategory::find() // AQ instance
            ->select('max(ordering)') // we need only one column
            ->scalar();

            $ordering = $order ? $order + 1 : 1;
            $NewsCategory->setAttribute('ordering',$ordering);
            if ($NewsCategory->save()) {
                RuleHelper::setFile("news-routes.json");
                RuleHelper::setPath(Yii::$app->basePath."/../frontend/config");
                RuleHelper::makeRule($NewsCategory->route_name,"news/index",$NewsCategory->id);
                $objLang = new Language();
                $languages = $objLang->find()->asArray()->all();
                foreach ($languages as $value) {
                    $model = new TrNewsCategory();
                    $model->name = $NewsCategory->name;
                    $model->short_description = $NewsCategory->short_description;
                    $model->description = $NewsCategory->description;
                    $model->category_id = $NewsCategory->id;
                    $model->route_name = $NewsCategory->route_name;
                    $model->language_id = $value['id'];
                    $model->save();

                }
                Yii::$app->session->setFlash('success', 'NewsCategory successfully created');
                return $this->redirect(['update',
                    'id' => $NewsCategory->id,
                ]);
            }else{
                $defaultLanguage = Language::find()->where(['is_default' => 1])->one();

                return $this->render('create', [
                    'model' => $NewsCategory,
                    'defoultId' => $defaultLanguage->id
                ]);
            }
        } else {
            $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
            $model = new NewsCategory();
            return $this->render('create', [
                'model' => $model,
                'defoultId' => $defaultLanguage->id
            ]);
        }
    }

    /**
     * Updates an existing NewsCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oldRouteName = $model->route_name;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            RuleHelper::setFile("news-routes.json");
            RuleHelper::setPath(Yii::$app->basePath."/../frontend/config");
            RuleHelper::updateRule($model->route_name,"news/index",$model->id, $oldRouteName);
            $model->updateDefaultTranslate();
            Yii::$app->session->setFlash('success', Yii::t('app','News Category successfully updated'));
            return $this->redirect(['index']);
        }
        else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing NewsCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $tr_categories = TrNewsCategory::find()->where(['category_id'=>$id])->all();
        foreach($tr_categories as $tr_categoru){
            $tr_categoru->delete();
        }
        if (TrNewsCategory::find()->where(['category_id'=>$id])->count() == 0) {
            $model = $this->findModel($id);
            if($model){
                RuleHelper::setFile("routes.json");
                RuleHelper::setPath(Yii::$app->basePath."/../frontend/config");
                RuleHelper::deleteRule($model->id);
                $model->delete();
                Yii::$app->session->setFlash('success', 'NewsCategory successfully removed');
            }
            Yii::$app->session->setFlash('success', 'Something went wrong!');
            return $this->redirect(['index']);
        }
    }
    public function actionDeleteByAjax(){

        if (Yii::$app->request->isAjax) {
            $NewsCategory_ids = Yii::$app->request->post('ids');
            try {
                $forinkeys = [];
                $allow = true;
                foreach ($NewsCategory_ids as $id){
                    $attribute = Attribute::find()->where(['category_id'=> $id])->one();
                    $product = News::find()->where(['category_id'=> $id])->one();
                    if($attribute){
                        $allow = false;
                        $forinkeys[$id]['attribute'] = $attribute->id;
                    }if ($product){
                        $allow = false;
                        $forinkeys[$id]['product'] = $product->id;
                    }
                }
                if($allow){
                    RuleHelper::setFile("routes.json");
                    RuleHelper::setPath(Yii::$app->basePath."/../frontend/config");
                    foreach ($NewsCategory_ids as $id){
                        $model = $this->findModel($id);
                        RuleHelper::deleteRule($model->id);
                    }
                    NewsCategory::deleteAll(['in','id', $NewsCategory_ids]);
                    echo true; exit();
                }
                print_r(json_encode($forinkeys)); exit();
            } catch (\mysqli_sql_exception $e) {
                Yii::$app->session->setFlash('error', 'you are not deleted');
                echo json_encode(['deleted' => 'error']); exit();
            }
        }
    }

    public function actionUpdateOrdering() {
        if (Yii::$app->request->isAjax) {
            $model = new NewsCategory();
            $data = Yii::$app->request->post();
            return $model->bachUpdate($data);
        }
    }

    /**
     * Finds the NewsCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NewsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = NewsCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAllrules()
    {
        @unlink(Yii::$app->basePath . "/../frontend/config/routes.json");
        RuleHelper::setFile("routes.json");
        RuleHelper::setPath(Yii::$app->basePath . "/../frontend/config");
        //categories
        $categories = NewsCategory::find()->all();
        foreach ($categories as $c) {
            RuleHelper::makeRule($c->route_name, "product/index", $c->id);

        }
    }


    public function actionTrcats(){

        //categories
        $categories = NewsCategory::find()->all();
        foreach($categories as $c){
            $tr = TrNewsCategory::findOne(['language_id' => 4,'NewsCategory_id'=>$c->id]);

            if(!$tr){
                $tr = new TrNewsCategory();

                $tr->language_id = 4;
                $tr->NewsCategory_id = $c->id;
                $tr->name = $c->name;
                $tr->short_description = $c->short_description;
                $tr->description = $c->description;
                if(!$tr->save()){
                    echo"<pre>";print_r($tr->errors);die;
                }
            }
        }
        //products

        //pages
    }

    public function actionTrproducts(){

        //categories
        $categories = Product::find()->all();
        foreach($categories as $c){
            $tr = TrProduct::findOne(['language_id' => 4,'product_id'=>$c->id]);

            if(!$tr){
                $tr = new TrProduct();

                $tr->language_id = 4;
                $tr->product_id = $c->id;
                $tr->name = $c->name;
                $tr->short_description = $c->short_description;
                $tr->description = $c->description;
                if(!$tr->save()){
                    echo"<pre>";print_r($tr->errors);die;
                }
            }
        }
        //products

        //pages
    }


}
