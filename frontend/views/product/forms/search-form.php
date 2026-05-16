<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\web\View;
?>
<div class="row box search_result">
    <div class="result-scroll">
        <?php foreach ($products as $key => $value): ?>
            <div class="col-md-12 search-item"><div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 search_image pd0">
                    <div class="product_list_thumb">
                        <a href="<?php echo Url::to(['product/view', 'id' => $value['id']]) ?>"> <?php echo backend\models\Product::getImagesToFront($value['id']) ?></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 search_package pd0">

                    <div class="row list_shop_by">
                        <?php if(isset($value['arrPack'])):?>
                        <?php foreach ($value['arrPack'] as $k => $pack) { ?>
                            <?php if (isset($pack['id'])): ?>
                                <div class="price_package col-md-12 col-sm-12 col-xs-12 pd0">

                                    <div class="shopping_by"><?php echo $pack['name'] ?><span class="price"><?php echo $pack['price'] ?></span></div>
                                    <form action="#" class="shop-quantity product-quant">
                                        <button type="button" class="btn btn-b js-qty minus" onclick="changeCount(this)"> - </button>
                                        <input type="text" value="1" class="input-quantity" id="input-number-<?php echo $pack['package_id'] ?>">
                                        <button type="button" class="btn btn-b js-qty plus" onclick="changeCount(this)"> + </button>
                                    </form>
                                    <div class="order_by" data-product-id="<?php echo $pack['id'] ?>">
                                        <i class="material-icons hidden-xs add-to-cart" data-toggle="tooltip" onclick="buyProduct(<?php echo $pack['id'] ?>,<?php echo $pack['package_id'] ?>)" title="Add To Cart">shopping_cart</i>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php } ?>
                        <?php endif; ?>
                    </div>
                </div><div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        <?php endforeach; ?>
    </div>
</div>