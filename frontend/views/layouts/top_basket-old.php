<?php 
use yii\helpers\Url;
?>
<?php  if ($session->has('basket') && !empty($session->get('basket')['products'])): ?>
    <div class="container">
        <div class="shopping-cart">
            <ul class="shopping-cart-items" id="basket_section">
                <?php foreach($session->get('basket')['products'] as $key=>$value): ?>
                            <li class="clearfix">
							<div class="col-xs-4">
															<a href="#" class="product-image">
															<?php echo backend\models\Product::getImagesToFrontThumb($key) ?>
																</a>
														</div>
                                <span class="item-name"><?= $value['name'] ?></span>
                                <span class="item-price price"><?= $value['price'] ?></span>
                                <form action="#" class="shop-quantity basket_quant">
                                    <button type="button" class="btn btn-b js-qty minus" onclick="changeCount(this)"> - </button>
                                    <input type="text" value="<?= $value['count'] ?>" id="input-number-32" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus" onclick="changeCount(this)"> + </button>
                                </form>
                                <a href="javascript:void(0)"
                                   onclick="removeBucketProduct(<?php echo $key ?>)"
                                   data-toggle="tooltip" data-placement="left" title="Remove product"
                                   class="remove-product"><i
                                        class="material-icons">clear</i></a>
                            </li>
                <?php endforeach;?>

            </ul>
            <div class="shopping-cart-total">
                <span class="lighter-text">Total:</span><span class="main-color-text" id="basket-product-prices">$<?php echo $session->get('basketPrice')?></span>
            </div>

            <a href="<?php echo Url::to('/cart/list')?>" class="button">PROCCED TO CHECKOUT</a>
        </div> <!--end shopping-cart -->

    </div>
<?php endif;?>