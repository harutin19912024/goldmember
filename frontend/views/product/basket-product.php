<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php if (!empty($basketArray['products'])): ?>
                <?php foreach ($basketArray['products'] as $key => $value): ?>
<div class="row shoping-item" id="basket-item-<?=$key?>">
												<div class="col-xs-10">
													<div class="row">
														<div class="col-xs-4">
															<a href="#" class="product-image">
																<?php echo backend\models\Product::getImagesToFrontThumb($key) ?>
															</a>
														</div>
														<div class="col-xs-8">
															<div class="product-detailes"> <a href="#" class="product-name"><?=$value['name'] ?></a>
																<div class="product-price"><?= $value['count'] ?> x <?=$value['price']?> РУБ.</div>
															</div>
														</div>
													</div>
												</div> 
												<div class="col-xs-2">
													<a href="javascript:void(0)" onclick="removeBucketProduct(<?php echo $key ?>,<?php echo $key ?>)"><span class="icon-delete"></span></a>
												</div>
											</div>
											<?php endforeach; ?>
            <?php endif; ?>
<div class="shopping-cart-total" style="color: black;float: right;margin-top: -10px;font-size: 16px;">
            <span class="lighter-text">Общая сумма:</span><span class="main-color-text"
                                                          id="basket-product-prices"><?php echo $totalPrice ?> РУБ.</span>
        </div>