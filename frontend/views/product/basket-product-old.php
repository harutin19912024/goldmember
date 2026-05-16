<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\CurrencyHelper;
use backend\models\Currency;
$defaultCurrency = Currency::find()->where(['default'=>1])->one();
?>

<div class="container">
    <div class="shopping-cart">
        <ul class="shopping-cart-items" id="basket_section">
            <?php if (!empty($packages)): ?>
                <?php foreach ($packages as $key => $value): ?>
                    <li class="clearfix">
                        <?php echo backend\models\Product::getImagesToFront(@$value['product']['productID']) ?>
                        <span class="item-name"><?= @$value['product']['productName'] ?></span>
                        <span class="item-price">$
                                <?php if(!empty(Yii::$app->session->get('currency'))):?>
                                    <?php echo CurrencyHelper::changeValue(Yii::$app->session->get('currency')['currenncyID'], $value['price']) ?>
                                <?php else:?>
                                    <?php echo CurrencyHelper::changeValue($defaultCurrency->id,$value['price']) ?>
                                <?php endif;?>
                        </span>
                            <form action="#" class="shop-quantity basket_quant">
                                <button type="button" class="btn btn-b js-qty minus" onclick="changeCount(this,<?php echo $key ?>,<?= @$value['product']['productID'] ?>)"> - </button>
                                <input type="text" value="<?= $value['count'] ?>" id="input-number-32" class="input-quantity">
                                <button type="button" class="btn btn-b js-qty plus" onclick="changeCount(this,<?php echo $key ?>,<?= @$value['product']['productID'] ?>)"> + </button>
                            </form>
                            <a href="javascript:void(0)"
                               onclick="removeBucketProduct(<?php echo $key ?>,<?php echo @$value['product']['productID'] ?>)"
                               data-toggle="tooltip" data-placement="left" title="Remove item"
                               class="remove-product"><i
                                    class="material-icons">clear</i></a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <div class="shopping-cart-total">
            <span class="lighter-text">Total:</span><span class="main-color-text"
                                                          id="basket-product-prices">$<?php echo $totalPrice ?></span>
        </div>
        <a href="<?php echo Url::to('/cart/list') ?>" class="button">PROCCED TO CHECKOUT</a>
    </div> <!--end shopping-cart -->

</div>