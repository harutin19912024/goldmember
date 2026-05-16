<?php
/**
 * Created by PhpStorm.
 * User: Venus
 * Date: 05.11.2016
 * Time: 23:03
 */

namespace frontend\controllers;


use frontend\models\Brand;
use frontend\models\Category;
use frontend\models\Product;

use Yii;
use yii\web\Controller;
//use yii\filters\VerbFilter;
//use backend\models\TrProduct;
use backend\models\ProductSearch;
//use backend\models\ProductImage;
//use yii\web\NotFoundHttpException;
//use common\models\Favorites;
//use backend\models\Category;
//use backend\models\TrCategory;
//use yii\data\Pagination;
//use common\models\Language;

class CategoryController extends Controller
{
    public function actionSlug($slug = null){
        $this->layout ='@frontend/views/layouts/productLayout';

        $filter_info = array();
        $categories = Category::findList();
        if($slug){
            $filter_info['by_cat'][]=$slug;
        }
        $products = Product::findList($filter_info);
        $view = 'product-grid-view';
        return $this->render('list', [
            'categories' => $categories,
            'products' => $products,
            'view'=>$view
        ]);
    }
}