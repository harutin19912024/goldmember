<?php

use yii\helpers\Url;
use common\components\CurrencyHelper;
use yii\helpers\Html;
?>

<?php foreach ($products as $model) { ?>
    <div class="row product-listing">
        <div class="span3 product">
    	  <div class="product-image-wrapper">
    		<div class="label_sale_top_right"></div>
    		<a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>">
			  <?php echo backend\models\Product::getImagesToFrontThumb($model['id']) ?>
			  <?php echo backend\models\Product::getImagesToFront($model['id'], 'roll_over_img') ?>
    		</a> 
    	  </div>
        </div>
        <div class="preview hidden-tablet hidden-phone">
    	  <div class="wrapper">
    		<div class="col-1">
			  <?php $imagesPath = backend\models\Product::getProductImagesSecondry($model['id'], 'thumb'); ?>
			  <?php foreach ($imagesPath['tag'] as $key => $image): ?>
	  		    <a href="#" data-rel="<?= $imagesPath['url'][$key] ?>" class="image">
				    <?= $image ?>
	  		    </a>
			  <?php endforeach; ?>
    		</div>
    		<div class="col-2">
    		    <div class="big_image">
    			  <a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>">
				    <?php echo backend\models\Product::getImagesToFrontThumb($model['id']) ?>
    			  </a>
    		    </div>
    		    <div class="wrapper-hover">
    			  <div class="product-name"><a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>"><?= $model['short_description'] ?></a></div>
    			  <div class="wrapper">
    				<div class="product-price">
					  <?php if ($model['stock']): ?>
	  				    <span class="new"><i class="fa fa-rub" aria-hidden="true"></i><?= $model['new_price'] ?></span>
	  				    <span class="old"><?= $model['price'] ?></span>
					  <?php else: ?>
						<?= $model['price'] ?>
					  <?php endif; ?>
    				</div>
    				<div class="product-tocart"><a href="javascript:;" onclick="addToBasket(<?php echo $model['id'] ?>)"><i class="icon-basket"></i></a> </div>
    			  </div>
    			  <div class="product-link"> 
    				<a href="#"><?= Yii::t('app', 'Add to Favorites') ?></a> <br>
    			  </div>
    			  <div class="rating"> <strong> <a href="#"><i class="icon-star"></i></a> 
    				    <a href="#"><i class="icon-star"></i></a> <a href="#"><i class="icon-star"></i></a> <a href="#"><i class="icon-star"></i></a> 
    				</strong> <a href="#"><i class="icon-star"></i></a> </div>
    		    </div>
    		</div>
    	  </div>
        </div>
        <div class="span6 product-detailes">
    	  <div class="product-name bottom-line"><a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>"><?= $model['name'] ?></a></div>
    	  <div class="bottom-line">
    		<div class="price-box"> 
			  <?php if ($model['stock']): ?>
	  		    <span class="old-price"> 
	  			  <span class="price"><i class="fa fa-rub" aria-hidden="true"></i><?= $model['price'] ?></span> 
	  		    </span>
	  		    <span class="special-price"> 
	  			  <span class="price"><i class="fa fa-rub" aria-hidden="true"></i><?= $model['new_price'] ?></span> 
	  		    </span> 
			  <?php else: ?>
	  		    <i class="fa fa-rub" aria-hidden="true"></i><?= $model['price'] ?>
			  <?php endif; ?>
    		</div>
    		<div class="product-review"><a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>"><?= Yii::t('app', 'Be the first to review this product') ?></a></div>
    	  </div>
    	  <div class="bottom-line"><?= $model['short_description'] ?></div>
    	  <div class="product-buttons"><a href="javascript:;" onclick="addToBasket(<?php echo $model['id'] ?>)" class="button btn-cart"><i class="icon-basket"></i><?= Yii::t('app', 'Add to Cart') ?></a>
    		<div class="add-to-links">
    		    <ul>
                        <li><a href="#" class="small_icon_color"><i class="icon-heart"></i></a><a href="#"><?= Yii::t('app', 'Add to wishlist') ?></a></li>
                        <li><a href="#" class="small_icon_color"><i class="icon-popup"></i></a><a href="#"><?= Yii::t('app', 'Add to compare') ?> </a></li>                    
    			  <li><a href="#" class="small_icon_color"><i class=" icon-mail-1"></i></a><a href="#">Email to a friend</a></li>
    		    </ul>
    		</div>
    	  </div>
        </div>
    </div>
<?php } ?>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination' => $provider->pagination,
]);
?>


