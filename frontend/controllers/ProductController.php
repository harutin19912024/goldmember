<?php

namespace frontend\controllers;

use frontend\models\Brand;
use frontend\models\Comment;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\TrProduct;
use backend\models\Auction;
use frontend\models\Attribute;
use frontend\models\ProductAttribute;
use backend\models\ProductsFilters;
use frontend\models\Product;
use yii\data\ArrayDataProvider;
use backend\models\ProductImage;
use yii\web\NotFoundHttpException;
use common\models\Favorites;
use frontend\models\Category;
use backend\models\TrCategory;
use backend\models\AttributeCategory;
use yii\data\Pagination;
use common\models\Language;
use yii\helpers\ArrayHelper;

/**
 * Product controller
 */
class ProductController extends Controller {
    /**
     * @inheritdoc
     */
    public function behaviors() {
	  return [
		'verbs' => [
		    'class' => VerbFilter::className(),
		    'actions' => [
		    //'logout' => ['post'],
		    ],
		],
	  ];
    }
    /**
     * @inheritdoc
     */
    public function actions() {
	  return [
		'error' => [
		    'class' => 'yii\web\ErrorAction',
		],
		'captcha' => [
		    'class' => 'yii\captcha\CaptchaAction',
		    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
		],
	  ];
    }
    public function actionIndex($id = null) {
	  $filter = ['limit'=>24];

	  if (Yii::$app->request->get('model_id')) {
		$filter['model_id'] = Yii::$app->request->get('model_id');
	  }
	  if (Yii::$app->request->get('mark_id')) {
		$filter['mark_id'] = Yii::$app->request->get('mark_id');
	  }
	  if (Yii::$app->request->get('year')) {
		$filter['mark_id'] = Yii::$app->request->get('year');
	  }
	  if (Yii::$app->request->get('vin')) {
		$filter['vin'] = Yii::$app->request->get('vin');
	  }

	$products = Product::findList($filter);
	
	if(!empty($products) && count($products) == 1 && Yii::$app->request->get('vin') != '') {
	    $model = $this->findModel(array_values($products)[0]['id']);
    	  return $this->render('product_new_view', [
    			  'model' => $model
    	  ]);
	}

	  if (Yii::$app->request->isAjax) {

		$view = Yii::$app->request->post('view');
		$provider = new ArrayDataProvider([
		    'allModels' => $products,
		    'pagination' => [
			  'pageSize' => 12,
		    ],
		    'sort' => [
			  'attributes' => ['id', 'name'],
		    ],
		]);
		$rows = $provider->getModels();
		if ($view == "list") {
		    echo $this->renderPartial('forms/products-list-view', [
			  'products' => $rows,
			  'active' => 'list',
			  'page' => [],
			  'provider' => $provider
		    ]);
		    exit();
		} else {
		    echo $this->renderPartial('forms/products-grid-view', [
			  'products' => $products,
			  'active' => 'grid',
			  'page' => [],
			  'provider' => $provider
		    ]);
		    exit();
		}
	  }
	  
	  $view_type = isset($_COOKIE['view_type']) ? $_COOKIE['view_type'] : 'grid';
	  return $this->render('index', [
			  'products' => $products,
			  'active' => 'grid',
			  'wihtouFilter' => false,
			  'page' => [],
			  'view_type' => $view_type,
	  ]);
    }
    
    public function actionAuction($auctionId)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Please log in to view auction details.'));
            return $this->redirect(['/site/login']);
        }

        $auction = Auction::findOne($auctionId);
        if (!$auction) {
            throw new NotFoundHttpException('Auction not found.');
        }

        $now = time();
        $startTime = strtotime($auction->start_date);
        $endTime   = $auction->end_date ? strtotime($auction->end_date) : null;

        if ($now >= $startTime && ($endTime === null || $now <= $endTime)) {
            $status = 'live';
        } elseif ($endTime && $now > $endTime) {
            $status = 'ended';
        } else {
            $status = 'upcoming';
        }

        return $this->render('auction-detail', [
            'auction' => $auction,
            'status'  => $status,
        ]);
    }
	
	
	public function actionFilterProduct()
	{
		
		$model = new Product();
		$products = $model->search(Yii::$app->request->queryParams);

		
		$view_type = isset($_COOKIE['view_type']) ? $_COOKIE['view_type'] : 'grid';
		return $this->render('index', [
			  'products' => $products,
			  'active' => 'grid',
			  'wihtouFilter' => false,
			  'page' => [],
			  'view_type' => $view_type,
		]);
	}
	
	
    public function actionFilter() {
	  if (Yii::$app->request->isAjax) {
		$post = Yii::$app->request->post();
		$get = Yii::$app->request->get();

		$filter = array();
		$product_ids = [];
		$filter['ids_attr'] = [];
		if (isset($post['prices'])) {
		    $filter['price1'] = $post['prices'][0];
		    $filter['price2'] = $post['prices'][1];
		}

		if (isset($post['range']) && !empty($post['range'][0])) {
		    $filter['range1'] = $post['range'][0];
		    $filter['range2'] = $post['range'][1];
		}
		if (isset($post['brand_ids'])) {
		    $filter['brand_ids'] = $post['brand_ids'];
		}
		$choosen_sort = '';
		if (isset($post['sort_by'])) {
		    $filter['sort_by'] = $post['sort_by'];
		    $choosen_sort = $post['sort_by'];
		}

		$subCategories = Category::find()->where(['parent_id' => $post['cat_id']])->asArray()->all();
		if (!empty($subCategories)) {
		    foreach ($subCategories as $cat) {
			  $filter['category_ids'][] = $cat['id'];
		    }
		    $filter['category_ids'][count($filter['category_ids'])] = $post['cat_id'];
		} else {
		    $filter['cat_id'] = $post['cat_id'];
		}
		$where = [];
		if (isset($post['attribute_id']) && !empty($post['attribute_id'][0])) {
		    $product_ids = ProductsFilters::find()->select('product_id')->where(['filter_id' => $post['attribute_id'][0]])->asArray()->all();

		    if (!empty($product_ids)) {
			  $compair = [];
			  foreach ($product_ids as $id) {
				if (isset($post['parent_attributes']) && count($post['parent_attributes']) > 1) {
				    foreach ($post['attr_id'] as $attr_id) {
					  $filters = ProductsFilters::find()->select(['attribute_id'])
								->where(['product_id' => $id['product_id'], 'filter_id' => $attr_id])->asArray()->all();

					  foreach ($filters as $filter_id) {
						$compair[$id['product_id']][] = $filter_id['attribute_id'];
					  }
				    }

				    if (!empty($compair)) {
					  foreach ($compair as $key => $comp) {
						$containsSearch = count(array_intersect($post['parent_attributes'], $comp)) == count($post['parent_attributes']);
						if ($containsSearch) {
						    $filter['ids_attr'][$id['product_id']] = $id['product_id'];
						}
					  }
				    }
				} else {
				    if (isset($post['attr_id']) && !empty($post['attr_id'])) {
					  foreach ($post['attr_id'] as $attr_id) {
						$product = ProductsFilters::find()->select('product_id')
								    ->where(['product_id' => $id['product_id'], 'filter_id' => $attr_id])->asArray()->all();
						foreach ($product as $prod) {
						    $filter['ids_attr'][$id['product_id']] = $prod['product_id'];
						}
					  }
				    } else {
					  $filter['ids_attr'][$id['product_id']] = $id['product_id'];
				    }
				}
			  }
		    }
		} elseif (isset($post['attr_id']) && !empty($post['attr_id']) && empty($post['attribute_id'][0])) {
		    if (isset($post['parent_attributes']) && count($post['parent_attributes']) > 1) {
			  $compair = [];
			  foreach ($post['attr_id'] as $attr_id) {
				$filters = ProductsFilters::find()->select(['product_id', 'attribute_id'])
						    ->where(['filter_id' => $attr_id])->asArray()->all();
				foreach ($filters as $filter_id) {
				    $compair[$filter_id['product_id']][] = $filter_id['attribute_id'];
				}
			  }
			  if (!empty($compair)) {
				foreach ($compair as $key => $comp) {
				    $containsSearch = count(array_intersect($post['parent_attributes'], $comp)) == count($post['parent_attributes']);
				    if ($containsSearch) {
					  $filter['ids_attr'][$key] = $key;
				    }
				}
			  }
		    } else {
			  foreach ($post['attr_id'] as $attr_id) {
				$product_ids = ProductsFilters::find()->select(['product_id', 'attribute_id'])->where(['filter_id' => $attr_id])->asArray()->all();
				foreach ($product_ids as $id) {
				    $filter['ids_attr'][$id['product_id']] = $id['product_id'];
				}
			  }
		    }
		}

		$products = [];
		$params = [];
		$productsCount = 0;
		$params = array();
		$productsCount = Product::findList($filter, $params, true);
		if (!empty($get) && isset($get['page'])) {
		    $params['offset'] = $get['page'] * 12;
		    $params['limit'] = 12;
		    $params['page'] = $get['page'];
		    $params['pagescount'] = ceil($productsCount / $params['limit']);
		} else {
		    if ($productsCount > 12) {
			  $params['offset'] = 0;
			  $params['limit'] = 12;
			  $params['page'] = 1;
			  $params['pagescount'] = ceil($productsCount / $params['limit']);
		    }
		}

		$products = Product::findList($filter, $params);
		if (isset($post['attribute_id']) && !empty($post['attribute_id'][0])) {
		    if (empty($filter['ids_attr'])) {
			  $products = [];
			  $productsCount = 0;
		    }
		} elseif (empty($post['attribute_id'][0]) && !empty($post['attr_id'])) {
		    if (empty($filter['ids_attr'])) {
			  $products = [];
			  $productsCount = 0;
		    }
		}

		if ($post['view'] == 'list') {
		    echo json_encode(['html' => $this->renderPartial('forms/product-partial-list', [
				'products' => $products,
				'active' => 'list',
				'page' => $params
			  ]), 'count' => $productsCount]);
		    exit();
		} else {
		    echo json_encode(['html' => $this->renderPartial('forms/product-partial-grid', [
				'products' => $products,
				'active' => 'grid',
				'choosen_sort' => $choosen_sort,
				'page' => $params
			  ]), 'count' => $productsCount]);
		    exit();
		}
	  }
    }
    
    public function actionSearch() {
	  $product_vin = Yii::$app->request->get('vin');
	  $products = Product::findList(['like' => $product_vin]);

	  $view_type = isset($_COOKIE['view_type']) ? $_COOKIE['view_type'] : 'grid';
	  return $this->render('index', [
			  'products' => $products,
			  'view_type' => $view_type,
	  ]);

	  /*
	    $view_type = isset($_COOKIE['view_type'])?$_COOKIE['view_type']:'grid';
	    $brands = Brand::findList();
	    $attributes = [];
	    $productAttr = [];
	    $productAttrId = [];
	    foreach($products as $product){
	    $productAttr = ProductAttribute::find()->where(['product_id'=>$product['id']])->select('attribute_id')->asArray()->all();
	    foreach($productAttr as $attrId){
	    if(isset($attrId['attribute_id'])){
	    $productAttrId[] = $attrId['attribute_id'];
	    }
	    }
	    $attributes = Attribute::find()->where(['in', 'id', $productAttrId])->asArray()->all();
	    }
	    $params =array();
	    $cat_id = 0;
	    return $this->render('search', [
	    'products' => $products,
	    'brands' => $brands,
	    'attributes' => $attributes,
	    'active' => 'grid',
	    'page'=>$params,
	    'cat_id'=>$cat_id,
	    'view_type'=>$view_type,
	    ]); */
    }
    public function createTree(&$list, $parent) {
	  $tree = array();
	  foreach ($parent as $k => $l) {
		if (isset($list[$l['id']])) {
		    $l['child'] = $this->createTree($list, $list[$l['id']]);
		}
		$tree[] = $l;
	  }
	  return $tree;
    }
    public function actionView($id) {
	  if (Yii::$app->user->isGuest) {
		Url::remember();
	  }
	  $model = $this->findModel($id);

	  return $this->render('product_new_view', [
			  'model' => $model
	  ]);
    }
    public function actionRate() {
	  if (Yii::$app->request->isAjax) {
		$product_id = (int) (Yii::$app->request->post('id'));
		$rate = (int) (Yii::$app->request->post('rate'));
		$product = $this->findModel($product_id);
		$product->rate = $rate;
		$product->save();
		echo $rate;
		exit();
	  }
    }
    public function actionFavorite() {
	  if (Yii::$app->request->isAjax) {
		$product_id = (int) (Yii::$app->request->post('id'));
		//  $user_id = Yii::$app->user->identity->id;
		$user_id = Yii::$app->user->identity->id;
		$favorite = Favorites::findOne(['user_id' => $user_id, 'product_id' => $product_id]);
		if ($favorite) {
		    $favorite->delete();
		    $message = "Продукт удален из списка избранного!";
		} else {
		    $favorite = new Favorites();
		    $favorite->user_id = $user_id;
		    $favorite->product_id = $product_id;
		    $favorite->save();
		    $message = "Продукт добавлен в избранное!";
		}
		if ($favorite->hasErrors()) {
		    return json_encode(['success' => false, 'message' => "Something went wrong!"]);
		}
		return json_encode(['success' => true, 'message' => $message]);
	  } else if (Yii::$app->request->isGet) {
		
	  }
    }
    public function actionChangeProductView() {
	  if (Yii::$app->request->isAjax) {
		$view = Yii::$app->request->post('view');
		$languege = Language::find()->where(['short_code' => Yii::$app->language])->asArray()->all();
		if (!$languege[0]['is_default']) {
		    $products = TrProduct::find()->where(['language_id' => $languege[0]['id']]);
		} else {
		    $products = Product::find();
		}

		$countQuery = clone $products;
		$categories = TrCategory::find()->where(['language_id' => $languege[0]['id']])->all();
		$images = ProductImage::find()->asArray()->all();
		$pageSize = 3;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$last = ceil($countQuery->count() / $pageSize);

		$products = $countQuery->offset($pages->offset)
			  ->limit($pages->limit)
			  ->all();
		if ($view == "list") {
		    echo $this->renderPartial('product-list-view', [
			  'products' => $products,
			  'active' => 'list',
			  'categories' => $categories,
			  'isDefaultLanguage' => $languege[0]['is_default'],
			  'pages' => $pages,
			  'last' => $last,
			  'images' => $images
		    ]);
		    exit();
		} else {
		    echo $this->renderPartial('product-grid-view', [
			  'products' => $products,
			  'active' => 'grid',
			  'isDefaultLanguage' => $languege[0]['is_default'],
			  'categories' => $categories,
			  'pages' => $pages,
			  'last' => $last,
			  'images' => $images
		    ]);
		    exit();
		}
	  }
    }
    public function actionProductToBasket() {

	  if (Yii::$app->request->isAjax) {
		$product_id = Yii::$app->request->post('id');
		$count = Yii::$app->request->post('count');
		$basketProductCount = 1;
		$session = Yii::$app->session;
		$languege = Language::find()->where(['short_code' => Yii::$app->language])->asArray()->all();
		if (isset($count)) {
		    $basketProductCount = $count;
		}

		$basketArray['products'] = [];
		if ($session->has('basket')) {
		    $basketArray = $session->get('basket');
		}
		$index = $this->whatever($basketArray['products'], $product_id);
		if (!empty($basketArray['products']) && is_bool($index) === false) {
		    if ($basketProductCount > 1) {
			  $productInfo = Product::findProductInfo($product_id, $basketProductCount);
			  $basketArray['products'][$product_id]['count'] += $basketProductCount;
		    } else {
			  $productInfo = Product::findProductInfo($product_id, $basketArray['products'][$product_id]['count']);
			  $basketArray['products'][$product_id]['count'] += 1;
		    }
		    $basketArray['products'][$product_id]['name'] = $productInfo['name'];
		    $basketArray['products'][$product_id]['totalprice'] += $productInfo['totalprice'];
		    $basketArray['products'][$product_id]['price'] = $productInfo['price'];
		} else {
		    $newBasket = Product::findProductInfo($product_id, $basketProductCount);
		    $basketArray['products'][$product_id] = $newBasket;
		}

		$totalPrice = 0;
		$productCount = 0;
		if (!empty($basketArray['products'])) {
		    foreach ($basketArray['products'] as $key => $products) {
			  if (isset($products['totalprice'])) {
				$totalPrice += @$products['totalprice'];
			  } else {
				$totalPrice += $value['price'];
			  }
			  $productCount++;
		    }
		}

		/* Adding info into session */
		$session->set('basket', $basketArray);
		$session->set('basketTotalPrice', $totalPrice);
		$session->set('basketProductCount', $productCount);
		$result['html'] = $this->renderPartial('basket-product', [
		    'totalPrice' => $totalPrice,
		    'basketArray' => $basketArray,
		]);
		$result['ProductCount'] = count($basketArray['products']);
		$result['basketTotalPrice'] = $totalPrice;
		echo json_encode($result);
		exit();
	  }
    }
    public function actionUpdateBasketProduct() {
	  if (Yii::$app->request->isAjax) {
		$basketArray = Yii::$app->session->get('basket');
		$basket_key = Yii::$app->request->post('basket_key');
		$product_id = Yii::$app->request->post('product_id');
		$count = Yii::$app->request->post('count');
		$basketArray['products'][$basket_key]['totalprice'] = $basketArray['products'][$basket_key]['price'] * $count;
		$packageTotalPrice = $basketArray['products'][$basket_key]['totalprice'];
		$basketArray['products'][$basket_key]['count'] = $count;
		Yii::$app->session->set('basket', $basketArray);
//            $totalPrice = Yii::$app->session->get('basketTotalPrice');
		$currentPackagePrice = 0;
		foreach ($basketArray['products'] as $package) {
		    $currentPackagePrice += $package['totalprice'];
		}
		Yii::$app->session->set('basketTotalPrice', $currentPackagePrice);
		echo json_encode(['basketTotalPrice' => $currentPackagePrice, 'packageTotalPrice' => $packageTotalPrice]);
	  }
    }
    public function actionRemoveFromBucket() {
	  if (Yii::$app->request->isAjax) {
		$key = Yii::$app->request->post('bucket');
		$product_id = Yii::$app->request->post('product_id');
		$session = Yii::$app->session;
		$basketArray = $session->get('basket');
		$basketProductCount = $session->get('basketProductCount');
		$basketTotalPrice = $session->get('basketTotalPrice');
		$counter = 0;
		$currentBasketPrice = 0;
		$newbasketArray['products'] = [];
		foreach ($basketArray['products'] as $i => $value) {

		    if ($key == $i) {
			  $currentBasketPrice = @$value['totalprice'];
			  continue;
		    }
		    $newbasketArray['products'][$i] = $value;
		}
		$basketProductCount = $basketProductCount - 1;
		$session->set('basketProductCount', $basketProductCount);

		$basketPrice = $basketTotalPrice - $currentBasketPrice;
		$session->set('basketTotalPrice', $basketPrice);
		$session->remove('basket');
		$session->set('basket', $newbasketArray);

		$html = $this->renderPartial('basket-product', [
		    'products' => $newbasketArray['products'],
		    'totalPrice' => $basketPrice,
		]);
		echo json_encode(['totalPrice' => $basketPrice, 'totalCount' => $basketProductCount, 'html' => $html]);
		exit();
	  }
    }
    private function whatever($array, $val) {
	  foreach ($array as $index => $item) {
		if ($index == $val) {
		    return $index;
		}
	  }
	  return false;
    }
    public function actionChangePackage() {
	  if (Yii::$app->request->isAjax) {
		$package_id = Yii::$app->request->post('package_id');
		$packageInfo = Product::findPackageInfo($package_id);
		echo json_encode($packageInfo);
		exit();
	  }
    }
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
	  if (($model = Product::findOne($id)) !== null) {
		return $model;
	  } else {
		throw new NotFoundHttpException('The requested page does not exist.');
	  }
    }
    public function actionAddComment() {
	  if (Yii::$app->request->isAjax && !empty(Yii::$app->request->post())) {
		$comment = new Comment();
		echo $comment->addComment();
	  }
    }
    public function actionRefreshComments() {
	  if (Yii::$app->request->isAjax && !empty(Yii::$app->request->post())) {
		$comments = Comment::findAll(['product_id' => Yii::$app->request->post('productId')]);
		echo $this->renderPartial('product_comment_view', ['comments' => $comments]);
	  }
    }
}