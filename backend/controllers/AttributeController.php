<?php

namespace backend\controllers;

use backend\models\ProductAttribute;
use Yii;
use backend\models\TrAttribute;
use backend\models\Attribute;
use backend\models\ProductsFilters;
use backend\models\AttributeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\User;
use common\models\Language;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;
use backend\models\AttributeCategory;

/**
 * AttributeController implements the CRUD actions for Attribute model.
 */
class AttributeController extends Controller {

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
                    'update' => ['GET', 'POST'],
                    'delete' => ['POST'],
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
     * Lists all Attribute models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new Attribute();
        $searchModel = new AttributeSearch();
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
     *  Attriibute form for index page
     * @return form
     */
    public function actionGetform() {
        $model = new Attribute();
        $searchModel = new AttributeSearch();
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
     * Displays a single Attribute model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Attribute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $attribute = new Attribute();
            $postData = Yii::$app->request->post('Attribute');
            $attrCat = Yii::$app->request->post('AttributeCategory');
            $attribute->name = $postData['name'];
            //$attribute->category_id = $postData['category_id'];
            $attribute->type = 1;
            $attribute->parent_id = $postData['parent_id'];
            $order = Attribute::find() // AQ instance
                    ->select('max(ordering)') // we need only one column
                    ->scalar();
            $attribute->ordering = $order ? $order + 1 : 1;
            if ($attribute->save()) {
                if (!empty($attrCat) && !empty($attrCat['category_id'])) {
                    foreach ($attrCat['category_id'] as $category) {
                        $attributeCategory = new AttributeCategory();
                        $attributeCategory->category_id = $category;
                        $attributeCategory->attribute_id = $attribute->id;
                        $attributeCategory->save();
                    }
                }
//                $file = UploadedFile::getInstances($attribute, 'path');
//                if (!empty($file)) {
//                    $paths = $this->upload($file, $attribute->id);
//                    $paths = array_values($paths);
//                    $attribute->path = $paths[0];
//                }
//                $attribute->save();

                $objLang = new Language();
                $languages = $objLang->find()->asArray()->all();
                foreach ($languages as $value) {
                    $model = new TrAttribute();
                    $model->name = $attribute->name;
                    $model->attribute_id = $attribute->id;
                    $model->language_id = $value['id'];
                    $model->save();
                }
                Yii::$app->session->setFlash('success', 'Attribute successfully created');
                return $this->redirect(['update',
                            'id' => $attribute->id,
                ]);
            }
        } else {
            $defaultLanguage = Language::find()->where(['is_default' => 1])->one();
            $model = new Attribute();
            $attributeCategory = new AttributeCategory();
            return $this->render('create', [
                        'model' => $model,
                        'attributeCategory' => $attributeCategory,
                        'defoultId' => $defaultLanguage->id
            ]);
        }
    }

    /**
     * Updates an existing Brand model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $attr = Yii::$app->request->post('Attribute');
            $model->name = $attr['name'];
            $model->parent_id = $attr['parent_id'];

            if ($model->save()) {
                $attributeCategory = new AttributeCategory();
                $attrCat = Yii::$app->request->post('AttributeCategory');
                if (!empty($attrCat['category_id'])) {
                    $attributeCategory->saveData($attrCat, $model->id);
                }
            }
            $model->updateDefaultTranslate();
            return $this->redirect(['index']);
        } else {
            $attribute_category = AttributeCategory::find()->where(['attribute_id' => $id])->asArray()->all();
            $categoriesAttr = [];
            foreach ($attribute_category as $cat) {
                $categoriesAttr[] = $cat['category_id'];
            }
            $attributeCategory = new AttributeCategory();
            return $this->render('update', [
                        'model' => $model,
                        'categoriesAttr' => $categoriesAttr,
                        'attributeCategory' => $attributeCategory,
            ]);
        }
    }

    /**
     * Deletes an existing Attribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $tr_attributes = TrAttribute::find(['attribute_id' => $id])->all();
        $filters = ProductsFilters::find()->where(['attribute_id' => $id])->all();
        $attr_pr = ProductAttribute::find()->where(['attribute_id' => $id])->all();
        $attr_category = AttributeCategory::find()->where(['attribute_id' => $id])->all();
        foreach ($filters as $filtr) {
            $filtr->delete();
        }
        foreach ($attr_category as $attr) {
            $attr->delete();
        }
        foreach ($attr_pr as $attr) {
            $attr->delete();
        }
        foreach ($tr_attributes as $tr_attribute) {
            $tr_attribute->delete();
        }
        if (TrAttribute::find()->where(['attribute_id' => $id])->count() == 0) {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Attribute successfully removed');
            return $this->redirect(['index']);
        }
    }

    public function actionDeleteByAjax() {

        if (Yii::$app->request->isAjax) {
            $attribute_ids = Yii::$app->request->post('ids');
            try {
                /*       $forinkeys = [];
                  $allow = true;
                  foreach ($attribute_ids as $id){
                  $productAttribute = @ProductAttribute::find()->where(['attribute_id'=> $id])->one()->id;
                  if ($productAttribute){
                  $allow = false;
                  $forinkeys[$id]['product'] = $productAttribute;
                  }
                  }
                  if($allow){ */
                Attribute::deleteAll(['in', 'id', $attribute_ids]);
                echo true;
                exit();
                // }
                // print_r(json_encode($forinkeys)); exit();
            } catch (\mysqli_sql_exception $e) {
                Yii::$app->session->setFlash('error', 'you are not deleted');
                echo json_encode(['deleted' => 'error']);
                exit();
            }
        }
    }

    /**
     * Finds the Brand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Brand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Attribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetAttributesByCategory() {
        if (Yii::$app->request->isAjax) {
            $category_id = Yii::$app->request->post('id');
            $attributesCat = AttributeCategory::find()->select('attribute_id')->where(['category_id' => $category_id])->asArray()->all();
            $attributes = [];
            foreach ($attributesCat as $attr) {
				if($attr['attribute_id'] != 47) {
					$attrs = Attribute::find()->where(['id' => $attr['attribute_id'], 'parent_id' => null])->asArray()->one();
					if (!empty($attrs)) {
						$attributes[] = $attrs;
					}
				}
            }
            echo $this->renderPartial('attributes', [
                'attributes' => $attributes,
            ]);
            exit();
        }
    }

    public function upload($imageFile, $id) {
        $directory = Yii::getAlias("@backend/web/uploads/images/attribute/" . $id);
        $directoryThumb = Yii::getAlias("@backend/web/uploads/images/attribute/" . $id . "/thumbnail");
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

    /**
     * @return false|int
     * @throws \Exception
     */
    public function actionDeleteImage() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $path = Attribute::find()->where(['id' => $id])->one();
            $directory = Yii::getAlias("@backend/web/uploads/images/attribute/" . $path->id);
            $thumbnailproduct = Yii::getAlias("@backend/web/uploads/images/attribute/" . $path->id . "/thumbnailproduct");
            $directoryThumb = Yii::getAlias("@backend/web/uploads/images/attribute/" . $path->id . "/thumbnail");
            @unlink($directory . '/' . $path->path);
            @unlink($thumbnailproduct . '/' . $path->path);
            @unlink($directoryThumb . '/' . $path->path);
            @BaseFileHelper::removeDirectory($directory);
            @BaseFileHelper::removeDirectory($directoryThumb);
            @BaseFileHelper::removeDirectory($thumbnailproduct);
            $path->path = '';
            $path->save();
        }
    }

    /**
     * @return bool
     */
    public function actionDefaultImage() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            return Files::updatDefaultImage($data['newid'], $data['product_id'], $data['category']);
        }
    }

}
