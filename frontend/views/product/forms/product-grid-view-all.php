<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\components\CurrencyHelper;
?>
<div class="span9">
          <div class="row big_with_description">
		  <?php foreach($products as $model){?>
<div class="span2 product">
              <div class="product-image-wrapper">
			 <a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>">
			   <?php echo backend\models\Product::getImagesToFrontThumb($model['id']) ?>
			   <?php echo backend\models\Product::getImagesToFront($model['id'],'roll_over_img') ?>
			   </a>
			  </div>
              <div class="wrapper-hover">
                <div class="product-name"><a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>"><?=$model['name']?></a></div>
                <div class="wrapper">
                  <div class="product-price"><?php if($model['stock']):?>
						<span class="new"><i class="fa fa-rub" aria-hidden="true"></i><?=$model['new_price']?></span>
						<span class="old"><i class="fa fa-rub" aria-hidden="true"></i><?=$model['price']?></span>
						<?php else:?>
						<i class="fa fa-rub" aria-hidden="true"></i><?=$model['price']?>
						<?php endif;?></div>
                  <div class="product-tocart"> <a href="javascript:;" onclick="addToBasket(<?php echo $model['id'] ?>)"><i class="icon-basket"></i></a> </div>
                </div>
              </div>
            </div>
            <div class="preview small hidden-tablet hidden-phone">
              <div class="wrapper">
                <div class="col-1 hidden">
				<?php $imagesPath = backend\models\Product::getProductImagesSecondry($model['id'],'thumb'); ?>
					<?php foreach($imagesPath['tag'] as $key=>$image):?>
					<a href="#" data-rel="<?=$imagesPath['url'][$key]?>" class="image">
						<?=$image?>
					</a>
					<?php endforeach;?>
				</div>
                <div class="col-2">
                  <div class="big_image">
					  <a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>">
						<?php echo backend\models\Product::getImagesToFrontThumb($model['id']) ?>
					  </a>
				  </div>
                  <div class="wrapper-hover">
                    <div class="product-name"><a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>"><?=$model['short_description']?></a></div>
                    <div class="wrapper">
						<div class="product-price">
						<?php if($model['stock']):?>
						<span class="new"><i class="fa fa-rub" aria-hidden="true"></i><?=$model['new_price']?></span>
						<span class="old"><i class="fa fa-rub" aria-hidden="true"></i><?=$model['price']?></span>
						<?php else:?>
						<?=$model['price']?>
						<?php endif;?>
						</div>                     
						<div class="product-tocart"> <a href="javascript:;" onclick="addToBasket(<?php echo $model['id'] ?>)"><i class="icon-basket"></i></a> </div>
                    </div>
                    <div class="product-link"> <a href="#">Add to Favorites</a> <br>
                      <a href="#">Add to Compare</a> </div>
                    <div class="rating"> <strong> <a href="#"><i class="icon-star"></i></a> <a href="#"><i class="icon-star"></i></a> <a href="#"><i class="icon-star"></i></a> <a href="#"><i class="icon-star"></i></a> </strong> <a href="#"><i class="icon-star"></i></a> </div>
                  </div>
                </div>
              </div>
            </div>
			<?php } ?>
            </div>
            </div>