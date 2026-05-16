<?php
use yii\helpers\Url;
use common\components\CurrencyHelper;
use yii\helpers\Html;
?>
<div class="row">
    <?php foreach($products as $model){?>
        <div class="col-md-12">
            <div class="product_list_item box">
                <div class="product_top_nav pop_line">

                </div>
                <div class="col-lg-4 col-md-3 col-sm-3">
                    <div class="row">
                        <div class="product_list_thumb">
                            <a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>"><?php echo  Html::img(Yii::$app->params['adminUrl'].'uploads/images/'. $model['image']); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="row">
                        <div class="title_list_box">
                            <h2 class="product_title"><a href="<?php echo Url::to('product/view/' . $model['id']) ?>"><?php echo $model['name'] ?></a></h2>
                        </div>
                        <div class="prod_desc hidden-xs">
                            <span>
                                <?php echo $model['short_description'] ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">

                    <div class="row list_shop_by">
                        <?php foreach ($model['package'] as $k => $pack) { ?>
                                <div class="price_package col-md-12 col-sm-12 col-xs-12 pd0">
                                    <form action="#" class="shop-quantity product-quant">
                                        <button type="button" class="btn btn-b js-qty minus" onclick="changeCount(this)"> - </button>
                                        <input type="text" value="1" class="input-quantity" id="input-number-<?php echo $k ?>">
                                        <button type="button" class="btn btn-b js-qty plus" onclick="changeCount(this)"> + </button>
                                    </form>
                                    <div class="order_by" data-product-id="<?php echo $model['id'] ?>">
                                        <i class="material-icons hidden-xs add-to-cart" data-toggle="tooltip" onclick="buyProduct(<?php echo $model['id'] ?>,<?php echo $k ?>)" title="Add To Cart">shopping_cart</i>
                                    </div>
                                </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-8 col-sm-8">

                </div>
            </div>
        </div>
<?php } ?>
</div>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$provider->pagination,
]);
?>


