<?php

namespace backend\controllers;

use backend\models\Attribute;
use backend\models\ProductsDetails;
use backend\models\Category;
use backend\models\ProductAttribute;
use backend\models\ProductAttributeSearch;
use backend\models\ProductImage;
use Yii;
use backend\models\TrProduct;
use backend\models\Product;
use backend\models\ProductSearch;
use backend\models\ProductsFilters;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use yii\helpers\BaseFileHelper;
use yii\filters\AccessControl;
use backend\models\User;
use common\models\Language;
use common\components\RuleHelper;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'view' => ['GET'],
                    'create' => ['GET', 'POST'],
                    'update' => ['GET', 'POST'],
                    'delete' => ['POST'],
                    'fix-new-product' => ['GET', 'POST']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => 'common\components\CAccessRule',
                ],
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {

        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
        $parentAttributes = Attribute::find()->where(['parent_id' => NUll])->asArray()->all();
        $childAttributes = Attribute::find()->where('parent_id IS NOT NULL')->asArray()->all();
        $attributes = [];

        foreach ($parentAttributes as $v) {

            $childAttr = array_filter(
                $childAttributes,
                function ($e) use ($v) {
                    return $e['parent_id'] == $v['id'];
                }
            );

            $attributes[$v['id']] = ['id' => $v['id'], 'name' => $v['name'], 'childAttributes' => $childAttr];
        }


            $searchModel = new ProductSearch();
            $model = new Product();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			
            return $this->render('index', [
                'searchModel' => $searchModel,
                'attributes' => $attributes,
                'dataProvider' => $dataProvider,
                'model' => $model,
                'defoultId' => $defaultLanguage->id
            ]);
    }

    /**
     * Lists all Brand models.
     * @return mixed
     */
    public function actionGetform()
    {
        $model = new Product();
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
            $attributes = ProductAttribute::find()->where(['product_id' => $id])->all();
            echo $_form = $this->renderAjax('_form', [
                'model' => $model,
                'categories' => $model->getAllCategories(),
                'attributes' => $attributes
            ]);
            exit();
        }

        $_form = $this->renderPartial('_form', [
            'model' => $model,
            'categories' => $model->getAllCategories(),
            'attributes' => $attributes
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            '_Form' => $_form
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $productAttr = ProductAttribute::find()->where(['product_id' => $id])->asArray()->all();
        $productDetails = ProductsDetails::find()->where(['product_id' => $id])->asArray()->all();

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
                SELECT pf.product_id, pf.value,
                (SELECT a.name FROM attribute a WHERE pf.attribute_id = a.id ) as attribute,
                (SELECT a.name FROM attribute a WHERE pf.filter_id = a.id ) as filter
            FROM products_filters pf WHERE pf.product_id = :product_id
        ", [':product_id' => $model->id]);

        $filters = $command->queryAll();

        return $this->render('view', [
            'model' => $model,
            'productDetails' => $productDetails,
            'productAttr' => $productAttr,
            'filters' => $filters,
        ]);
    }
	
	public function actionUpdateImageType() 
	{
		if (Yii::$app->request->isAjax) {
			$imageId = Yii::$app->request->post('imageId');
            $productImage = ProductImage::findOne($imageId);
			$productImage->folder = 'other';
			$productImage->save();
		}
	}

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
		$model = new Product();
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $product_image_model = new ProductImage();
            $product_attribute_model = new ProductAttribute();
            $ProductsDetails_model = new ProductsDetails();
            $ProdDefImg = Yii::$app->request->post('defaultImage');
            $ProductAttributeItems = Yii::$app->request->post('ProductAttribute');
            $sub_attr_id = Yii::$app->request->post('sub_attr_id');
            $ProductsDetails = Yii::$app->request->post('ProductsDetails');

            $model->load($post);
            $order = Product::find()// AQ instance
            ->select('max(ordering)')// we need only one column
            ->scalar();
            $model->ordering = $order ? $order + 1 : 1;
           
            if ($model->save()) {

                $category = Category::findOne($model->category_id);
                RuleHelper::setFile("product-routes.json");
                RuleHelper::setPath(Yii::$app->basePath . "/../frontend/config");
                RuleHelper::makeRule($category->route_name . '/' . $model->route_name, "product/view", $model->id);


                $objLang = new Language();
                $languages = $objLang->find()->asArray()->all();
                foreach ($languages as $value) {
                    $product = new TrProduct();
                    $product->title = $model->title;
                    $product->short_description = $model->short_description;
                    $product->description = $model->description;
                    $product->product_id = $model->id;
                    $product->language_id = $value['id'];
                    $product->save();
                }


                if (!empty($ProductAttributeItems)) {
                    foreach ($ProductAttributeItems as $key => $value) {
                        $product_attribute_model->saveData($value, $model->id);
                    }
                }

                if (!empty($ProductsDetails['name'])) {
                    $ProductsDetails_model->saveData($ProductsDetails['name'], $model->id);
                }
                $images = UploadedFile::getInstances($model, 'imageFiles');
                $paths = $this->upload($images, $model->id);
                $product_image_model->multiSave($paths, $model->id, $ProdDefImg, 1);

                Yii::$app->session->setFlash('success', 'Product successfully created');
                return $this->redirect(['product/update',
                    'id' => $model->id,
                    'defoultId' => $defaultLanguage->id,
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Something want wrong');
                $products = $productsCount = Product::find()->asArray()->all();
                $detailsModel = new ProductsDetails();
                return $this->render('create', [
                    'model' => $model,
                    'defoultId' => $defaultLanguage->id,
                    'products' => $products,
                    'detailsModel' => $detailsModel,
                    'categories' => $model->getAllCategories(),
                ]);
            }
        } else {
            $products = $productsCount = Product::find()->asArray()->all();
            $model = new Product();
            $detailsModel = new ProductsDetails();
            return $this->render('create', [
                'model' => $model,
                'products' => $products,
                'detailsModel' => $detailsModel,
                'defoultId' => $defaultLanguage->id,
                'categories' => $model->getAllCategories(),
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $connection = Yii::$app->getDb();
        $transaction = $connection->beginTransaction();
        try {
            $model = $this->findModel($id);
            $price = $model->price;
            $product_image_model = new ProductImage();
            $product_attribute_model = new ProductAttribute();
            $ProdDefImg = Yii::$app->request->post('defaultImage');
            $ProductAttributeItems = Yii::$app->request->post('ProductAttribute');
            $ProductsDetails = Yii::$app->request->post('ProductsDetails');
            $sub_attr_id = Yii::$app->request->post('sub_attr_id');
            $ProductsDetails_model = new ProductsDetails();

            if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
                $oldRouteName = $model->route_name;
                $post = Yii::$app->request->post();
                if ($model->save()) {
                    $category = Category::findOne($model->category_id);
                    RuleHelper::setFile("product-routes.json");
                    RuleHelper::setPath(Yii::$app->basePath . "/../frontend/config");
                    RuleHelper::updateRule($category->route_name . '/' . $model->route_name, "product/view", $model->id, $oldRouteName);
                   
                    $objLang = new Language();
                    $languages = $objLang->find()->asArray()->all();
                    foreach ($languages as $value) {
                        $trproduct = TrProduct::find()->where(['product_id' => $model->id, 'language_id' => $value['id']])->one();
                        if (!$trproduct) {
                            $trproduct = new TrProduct();
                        }
                        $trproduct->title = $model->title;
                        $trproduct->short_description = $model->short_description;
                        $trproduct->description = $model->description;
                        $trproduct->save();
                    }


                    if (!empty($ProductAttributeItems)) {
                        foreach ($ProductAttributeItems as $key => $value) {
                            $product_attribute_model->saveData($ProductAttributeItems['value'], $model->id);
                        }
                    }

                    if (isset($ProductsDetails['old_name']) && !empty($ProductsDetails['old_name'])) {
                        if (!empty($ProductsDetails['name'])) {
                            $detailsResult = array_merge_recursive($ProductsDetails['name'], $ProductsDetails['old_name']);
                        } else {
                            $detailsResult = $ProductsDetails['name'];
                        }
                        $ProductsDetails_model->saveData($detailsResult, $model->id);
                    } elseif (!empty($ProductsDetails['name'])) {
                        $ProductsDetails_model->saveData($ProductsDetails['name'], $model->id);
                    }

                    $images = UploadedFile::getInstances($model, 'imageFiles');
                    if ($images) {
                        $paths = $this->upload($images, $model->id);
                        $product_image_model->multiSave($paths, $model->id, $ProdDefImg, 1);
                    }
                    $defaultLanguage = Yii::$app->request->post('default_language');
                    $model->updateDefaultTranslate($defaultLanguage);
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Product successfully updated'));
                    $transaction->commit();
                    return $this->redirect(['product/update',
                        'id' => $model->id,
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        $products = Product::find()->where(['!=', 'id', $id])->asArray()->all();

        $productAttr = ProductAttribute::find()->where(['product_id' => $id])->asArray()->all();
        $detailsModel = new ProductsDetails();
        $productDetails = ProductsDetails::find()->where(['product_id' => $id])->asArray()->all();
        return $this->render('update', [
            'model' => $model,
            'products' => $products,
            'detailsModel' => $detailsModel,
            'productDetails' => $productDetails,
            'productAttr' => $productAttr,
            'categories' => $model->getAllCategories(),
            'product_attribute_model' => $product_attribute_model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $Product = $this->findModel($id);
        if ($Product) {
            if ($Product->DeleteData($id)) {
                return $this->redirect(['index']);
            }
        }
    }

    public function actionRemoveDetails()
    {
        $details_id = Yii::$app->request->post('details_id');
        $detail = ProductsDetails::findOne($details_id);
        if ($detail) {
            $detail->delete();
            echo json_encode(['success' => true]);
            exit();
        } else {
            echo json_encode(['success' => false]);
            exit();
        }
    }

    public function actionDeleteByAjax()
    {
        if (Yii::$app->request->isAjax) {
            $product_ids = Yii::$app->request->post('ids');
            try {
                $forinkeys = [];
                $allow = true;
                foreach ($product_ids as $id) {
                    $model = Product::findOne($id);
                    $model->DeleteData();
                }
                if ($allow) {
                    echo true;
                    exit();
                }
                print_r(json_encode($forinkeys));
                exit();
            } catch (\mysqli_sql_exception $e) {
                Yii::$app->session->setFlash('error', 'you are not deleted');
                echo json_encode(['deleted' => 'error']);
                exit();
            }
        }
    }

    public function actionChangestatus()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('data');
            $data = json_decode($post);
            $model = $this->findModel($data->id);
            $model->status = $data->status;
            if ($model->update()) {
                echo 'true';
                exit();
            } else {
                echo 'false';
                exit();
            }
        }
    }

    public function actionChangeitemsstatus()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('data');
            $product_ids = $post['id'];
            $status = $post['status'];
            try {
                if (Product::updateAll(['status' => $status], ['in', 'id', $product_ids])) {
                    echo true;
                    exit();
                }
                echo false;
                exit();
            } catch (\mysqli_sql_exception $e) {
                echo false;
                exit();
            }
        }
    }

    public function actionAddinslider()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post('data');
            $product_ids = $post['id'];
            try {
                if (Product::updateAll(['in_slider' => 1], ['in', 'id', $product_ids])) {
                    echo true;
                    exit();
                }
                echo false;
                exit();
            } catch (\mysqli_sql_exception $e) {
                echo false;
                exit();
            }
        }
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

public function compressImage($source, $destination, $quality) {

            $info = getimagesize($source);

            if ($info['mime'] == 'image/jpeg') 
                $image = imagecreatefromjpeg($source);

            elseif ($info['mime'] == 'image/gif') 
                $image = imagecreatefromgif($source);

            elseif ($info['mime'] == 'image/png') 
                $image = imagecreatefrompng($source);

            imagejpeg($image, $destination, $quality);

        }

    public function upload($imageFile, $id)
    {
		set_time_limit(0);
		ini_set('memory_limit', '-1');
        $directory = Yii::getAlias("@backend/web/uploads/images");
        $directoryThumb = Yii::getAlias("@backend/web/uploads/images/thumbnail");

        BaseFileHelper::createDirectory($directory);
        BaseFileHelper::createDirectory($directoryThumb);
        if ($imageFile) {
            $paths = [];
            foreach ($imageFile as $key => $image) {
                $uid = uniqid(time(), true);
                $fileName = $uid . '_' . $key . '.' . $image->extension;
                $fileName_return = $fileName;

                $filePath = $directory . '/' . $fileName;
 
                $image->saveAs($filePath);
                Image::thumbnail($filePath, 670, 820)->save(Yii::getAlias($directoryThumb . '/' . $fileName), ['quality' => 60]);
                $paths[$key + 1] = $fileName_return;
            }
            return $paths;
        }
        return false;
    }

    /**
     * @return string
     */
    public function actionProductDetails()
    {
        if (Yii::$app->request->isAjax) {
            $category_id = Yii::$app->request->post('category_id');
            $attributes = TrAttribute::getAttributeByCategory($category_id);
            return json_encode($attributes);
        }
    }

    /**
     * @return false|int
     * @throws \Exception
     */
    public function actionDeleteImage()
    {
        if (Yii::$app->request->isAjax) {
            $model = new ProductImage();
            $id = Yii::$app->request->post('id');
            $imageModel = $model->findOne($id);
            if (file_exists($imageModel->name)) {
                unlink($imageModel->name);
            }
            return $imageModel->delete();
        }
    }

    /**
     * @return bool
     */
    public function actionDefaultImage()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            return ProductImage::updatDefaultImage($data['newid'], $data['oldid']);
        }
    }

}
