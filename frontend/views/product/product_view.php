<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\Product */

use frontend\models\Category;
use frontend\models\Product;
use backend\models\ProductsDetails;
use backend\models\ConnectedProducts;
use backend\models\ProductAttribute;
use backend\models\Slider;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('app', 'ARIAS') . ' | ' . Yii::t('app', $model->name);
$categoryInfo = Category::findOne($model->category_id);
$parentCategoryInfo = Category::findOne($categoryInfo->parent_id);
$productDetails = ProductsDetails::find()->where(['product_id' => $model->id])->asArray()->all();
$productAttributes = ProductAttribute::findAttributesForFrontend($model->id);
//echo "<pre>";print_r($productDetails);die;
$this->registerCssFile("/css/new-style.css");
?>

<!-- ##### Breadcumb Area Start ##### -->
<section class="breadcumb-area bg-img" style="background-image: url(/img/bg-img/hero1.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="breadcumb-content">
                        <h3 class="breadcumb-title"><?=$model->name?></h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- ##### Breadcumb Area End ##### -->

<section class="listings-content-wrapper section-padding-100">
	<div class="" style="background-color: #e4e4e4;">
		<div class="container" style="box-shadow: 0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;">
			<div class="row">
				<div class="col-12">
					<?php $images = backend\models\Product::getProductImagesSliderView($model->id, 'xzoom-gallery') ?>
					<div class="single-listings-sliders owl-carousel">
						<?php foreach ($images['url'] as $imgSrc): ?>
							<img src="<?= $imgSrc ?>" alt="" class="xzoom-gallery" style="height:600px;">
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
    <div class="container">
        <!--<div class="row">
            <div class="col-12">
                 Single Listings Slides 
				
            </div>
        </div>-->

        <div class="row justify-content-center">
            <div class="col-12 col-lg-12">
                <div class="listings-content">
                    <!-- Price -->
                    <div class="list-price">
                        <p>Վաճառք` $<?= $model->price ?></p>
                    </div>
                    <h5><?= $model->name ?></h5>
					<p class="location"><img src="img/icons/location.png" alt=""><?= $model->address ?></p>
					<p><?= $model->short_description ?></p>
                    
					
					<ul class="property-main-features list list-description d-inline-block d-md-block">
						<?php if(!empty($productAttributes)):?>
						<?php foreach($productAttributes as $attr):?>
						<li><span><?=$attr['attribute']?>:</span><span><?php if(!empty($attr['filter'])):?><?=$attr['filter']?><?php else:?><?=$attr['value']?><?php endif;?></span></li>
						<?php endforeach;?>
					<?php endif;?>
                    </ul>
					
                    <ul class="listings-core-features d-flex align-items-center">
											<?php if(!empty($productDetails)):?>
						<?php foreach($productDetails as $details):?>
                        <li><i class="fa fa-check" aria-hidden="true"></i><?=$details['name']?>` <span><?=$details['value']?></span></li>
						<?php endforeach;?>
						<?php endif;?>
                    </ul>
                   <!-- Apagayi hamar pdf file bacelu naxagic kam tan tuxt -->
				   <?php if(isset($naxagicExist)):?>
					<div class="listings-btn-groups">
						<a href="img/example.jfif" target="_blank" class="btn south-btn">Նախագիծ</a>
					</div>
					<?php endif;?>
                </div>
            </div>
        </div>
        <!-- Listing Maps -->
    </div>
</section>
