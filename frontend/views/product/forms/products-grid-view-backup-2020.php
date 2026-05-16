<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\components\CurrencyHelper;
use frontend\models\Category;
use frontend\models\Product;
?>
<div class="row clearboth">
    <div class="col-sm-4 text-center">
	  <?php
	  echo \yii\widgets\LinkPager::widget([
		'pagination' => $provider->pagination,
		'prevPageLabel' => false,
		'nextPageLabel' => false,
		'maxButtonCount' => 2,
		'options' => [
		    'class' => 'pagination pagination_',
		]
	  ]);
	  ?>
    </div>
    <div class="col-sm-3"></div>
</div>

<div class="row clearboth">
    <div class="product-item">
	  <?php if (!empty($products)): ?>
		<?php foreach ($products as $key => $product) : ?>
		    <?php $catRout = frontend\models\Category::getCatRout($product['category_id']); ?>
	  	  <div class="col-12 col-md-6 col-xl-4">
	  		<a href="/<?= Category::getCategoryRouteName($product['category_id']) ?>/<?= $product['route_name'] ?>" target="_blank">
	  		    <div class="single-featured-property mb-50 wow fadeInUp" data-wow-delay="<?= 100 * $key ?>ms">
	  			  <!-- Property Thumbnail -->
	  			  <div class="property-thumb">
	  				<img src="<?= Yii::$app->params['adminUrl'] . 'uploads/images/' . $product['image'] ?>" alt=""></a>
	  				<div class="tag">
	  				    <span>For Sale</span>
	  				</div>
	  				<div class="list-price">
	  				    <p>$<?= $product['price'] ?></p>
	  				</div>
	  			  </div>
	  			  <!-- Property Content -->
	  			  <div class="property-content">
	  				<h5><a href="/<?= Category::getCategoryRouteName($product['category_id']) ?>/<?= $product['route_name'] ?>"><?= $product['name'] ?></a></h5>
	  				<p class="location"><img src="img/icons/point2.png" alt=""><?= $product['address'] ?></p>
	  				<p><?= $product['short_description'] ?></p>
	  			  </div>
	  		    </div>
	  		</a>
	  	  </div>
		<?php endforeach; ?>
	  <?php else: ?>
    	  <div class="col-xs-4"></div>
    	  <div class="col-xs-4">
    		<p class="text-danger text-center">Ничего не найдено</p>
    	  </div>
    	  <div class="col-xs-4"></div>
	  <?php endif; ?>
    </div>
</div>
