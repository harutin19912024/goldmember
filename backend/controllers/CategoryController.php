<?php

namespace backend\controllers;

use backend\models\Product;
use backend\models\TrProduct;
use Yii;
use backend\models\Category;
use backend\models\TrCategory;
use backend\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Attribute;
use backend\models\TrAttribute;
use yii\filters\AccessControl;
use backend\models\User;
use common\models\Language;
use common\components\RuleHelper;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller {

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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new Category();
        $searchModel = new CategorySearch();
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionGetform() {
        $model = new Category();
        $searchModel = new CategorySearch();
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
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $category = new Category();
            $postData = Yii::$app->request->post('Category');
            $category->setAttributes($postData);

            $order = Category::find() // AQ instance
                    ->select('max(ordering)') // we need only one column
                    ->scalar();

            $ordering = $order ? $order + 1 : 1;
            $category->setAttribute('ordering',$ordering);
            if ($category->save()) {
                RuleHelper::setFile("routes.json");
                RuleHelper::setPath(Yii::$app->basePath."/../frontend/config");
                RuleHelper::makeRule($category->route_name,"product/index",$category->id);
                
                $file = UploadedFile::getInstances($category, 'path');
                if (!empty($file)) {
                    $paths = $this->upload($file, $category->id);
                    $paths = array_values($paths);
                    $category->path = $paths[0];
                }
                $category->save();
			
                $languages = Language::find()->asArray()->all();
                foreach ($languages as $value) {
                        $model = new TrCategory();
                        $model->name = $category->name;
                        $model->short_description = $category->short_description;
                        $model->description = $category->description;
                        $model->category_id = $category->id;
                        $model->language_id = $value['id'];
                        $model->save();

                }
                Yii::$app->session->setFlash('success', 'Category successfully created');
                return $this->redirect(['update',
                            'id' => $category->id,
                ]);
            }else{
                $defaultLanguage = Language::find()->where(['is_default' => 1])->one();

                return $this->render('create', [
                    'model' => $category,
                    'defoultId' => $defaultLanguage->id
                ]);
            }
        } else {
            $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
            $model = new Category();
            return $this->render('create', [
                        'model' => $model,
                        'defoultId' => $defaultLanguage->id
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oldRouteName = $model->route_name;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            RuleHelper::setFile("routes.json");
            RuleHelper::setPath(Yii::$app->basePath."/../frontend/config");
            RuleHelper::updateRule($model->route_name,"product/index",$model->id, $oldRouteName);
            
            $defaultLanguage = Yii::$app->request->post('default_language');
            $model->updateDefaultTranslate($defaultLanguage);
            
            $file = UploadedFile::getInstances($model, 'path');
            if (!empty($file)) {
                $directory = Yii::getAlias("@backend/web/uploads/images/category/" . $model->id);
                $directoryThumb = Yii::getAlias("@backend/web/uploads/images/category/" . $id . "/thumbnail");
                $path = Attribute::find()->where(['id' => $id])->one();
                if (file_exists($directory . '/' . $path->path) && $path->path != "") {
                    unlink($directory . '/' . $path->path);
                    unlink($directoryThumb . '/' . $path->path);
                    BaseFileHelper::removeDirectory($directory);
                    BaseFileHelper::removeDirectory($directoryThumb);
                }
                $paths = $this->upload($file, $model->id);
                $paths = array_values($paths);
                $model->path = $paths[0];
            } else {
                $model->path = Yii::$app->request->post('imagePath');
            }
            if($model->save()){
				Yii::$app->session->setFlash('success', Yii::t('app','Category successfully updated'));
            return $this->redirect(['index']);
			}
            Yii::$app->session->setFlash('error', Yii::t('app','Something went wrong'));
            return $this->redirect(['index']);
        }
        else {
           return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $tr_categories = TrCategory::find()->where(['category_id'=>$id])->all();
        foreach($tr_categories as $tr_categoru){
            $tr_categoru->delete();
        }
        if (TrCategory::find()->where(['category_id'=>$id])->count() == 0) {
            $model = $this->findModel($id);
            if($model){
                RuleHelper::setFile("routes.json");
                RuleHelper::setPath(Yii::$app->basePath."/../frontend/config");
                RuleHelper::deleteRule($model->id);
                $model->delete();
                Yii::$app->session->setFlash('success', 'Category successfully removed');
            }
            Yii::$app->session->setFlash('success', 'Something went wrong!');
            return $this->redirect(['index']);
        }
    }
    public function actionDeleteByAjax(){

        if (Yii::$app->request->isAjax) {
            $category_ids = Yii::$app->request->post('ids');
            try {
                $forinkeys = [];
                $allow = true;
                foreach ($category_ids as $id){
                    $attribute = Attribute::find()->where(['category_id'=> $id])->one();
                    $product = Product::find()->where(['category_id'=> $id])->one();
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
                    foreach ($category_ids as $id){
                      $model = $this->findModel($id);
                        RuleHelper::deleteRule($model->id);
                    }
                    Category::deleteAll(['in','id', $category_ids]);
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
            $model = new Category();
            $data = Yii::$app->request->post();
            return $model->bachUpdate($data);
        }
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Category::findOne($id)) !== null) {
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
        $categories = Category::find()->all();
        foreach ($categories as $c) {
            RuleHelper::makeRule($c->route_name, "product/index", $c->id);

        }
    }


    public function actionTrcats(){

        //categories
        $categories = Category::find()->all();
        foreach($categories as $c){
         $tr = TrCategory::findOne(['language_id' => 4,'category_id'=>$c->id]);

            if(!$tr){
                $tr = new TrCategory();

                $tr->language_id = 4;
                $tr->category_id = $c->id;
                $tr->name = $c->name;
                $tr->short_description = $c->short_description;
                $tr->description = $c->description;
                if(!$tr->save()){
                    echo"<pre>";print_r($tr->errors);die;
                }
            }
        }
    }
    
    public function upload($imageFile, $id) {
        $directory = Yii::getAlias("@backend/web/uploads/images/category/" . $id);
        $directoryThumb = Yii::getAlias("@backend/web/uploads/images/category/" . $id . "/thumbnail");
        BaseFileHelper::createDirectory($directory);
        BaseFileHelper::createDirectory($directoryThumb);
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $fileName_return = $fileName;
                $filePath = $directory . '/' . $fileName;
                //$filePathThumb = $directoryThumb . '/' . $fileName;
                $image->saveAs($filePath);
                Image::thumbnail($filePath, 160, 160)->save(Yii::getAlias($directoryThumb . '/' . $fileName), ['quality' => 100]);
                $paths[$key + 1] = $fileName_return;
            }
            return $paths;
        }
        return false;
    }


}
