<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<div class="row">
    <?php foreach ($products as $key => $value): ?>
        <?php if ($isDefaultLanguage): ?>
            <div class="col-md-12">
                <div class="product_list_item box">
                    <div class="product_top_nav pop_line">
                        <div class="pull-right"><i class="material-icons">add</i></div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="row">
                            <div class="product_list_thumb">
                                <a href="<?php echo Url::to(['product/index', 'id' => $value->id]) ?>"> <?php echo backend\models\Product::getImagesToFront($value->id) ?></a>
                            </div>	                		
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5">
                        <div class="row">
                            <div class="title_list_box">
                                <h2 class="product_title"><a href="<?php echo Url::to('product/index/' . $value->id) ?>"><?php echo $value->name ?></a></h2>
                            </div>
                            <div class="prod_desc hidden-xs">			                	
                                <span>
                                    <?php echo $value->short_description ?>
                                </span>       		
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="row pull-right text-right list_info">
                            <div class="col-md-12 col-xs-12"><span class="price"><?php echo $value->price ?></span></div>
                            <div class="col-md-12 col-xs-6"><span class="prod_weight"><b>Weight</b> <i><?php echo $value->weight ?>g</i></span></div>
                            <div class="col-md-12 col-xs-6"><span class="ship_status"><i class="material-icons">local_shipping</i> Free Shipping</span></div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <div class="row list_shop_by">
                            <div class="col-md-6 col-sm-6 col-xs-12 pd0">								
                                <div class="shopping_by">1 CAN<span class="price">5.09</span></div>
                                <form action="#" class="shop-quantity product-quant">
                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                    <input type="text" value="1" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                </form>
                                <div class="order_by">
                                    <button class="btn" onclick="buyProduct(<?= $value->id ?>)">BUY</button>
                                    <i class="material-icons">shopping_cart</i>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 pd0">								
                                <div class="shopping_by">10 PACK<span class="price">47.90</span></div>
                                <form action="#" class="shop-quantity product-quant">
                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                    <input type="text" value="1" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                </form>
                                <div class="order_by">
                                    <button class="btn" onclick="buyProduct(<?= $value->id ?>)">BUY</button>
                                    <i class="material-icons">shopping_cart</i>
                                </div>                      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-md-12">
                <div class="product_list_item box">
                    <div class="product_top_nav pop_line">
                        <div class="pull-right"><i class="material-icons">add</i></div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="row">
                            <div class="product_list_thumb">
                                <a href="<?php echo Url::to(['product/index', 'id' => $value->product->id]) ?>"> <?php echo backend\models\Product::getImagesToFront($value->product->id) ?></a>
                            </div>	                		
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5">
                        <div class="row">
                            <div class="title_list_box">
                                <h2 class="product_title"><a href="<?php echo Url::to('product/index/' . $value->product->id) ?>"><?php echo $value->name ?></a></h2>
                            </div>
                            <div class="prod_desc hidden-xs">			                	
                                <span>
                                    <?php echo $value->product->short_description ?>
                                </span>       		
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="row pull-right text-right list_info">
                            <div class="col-md-12 col-xs-12"><span class="price"><?php echo $value->product->price ?></span></div>
                            <div class="col-md-12 col-xs-6"><span class="prod_weight"><b>Weight</b> <i><?php echo $value->product->weight ?>g</i></span></div>
                            <div class="col-md-12 col-xs-6"><span class="ship_status"><i class="material-icons">local_shipping</i> Free Shipping</span></div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <div class="row list_shop_by">
                            <div class="col-md-6 col-sm-6 col-xs-12 pd0">								
                                <div class="shopping_by">1 CAN<span class="price">5.09</span></div>
                                <form action="#" class="shop-quantity product-quant">
                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                    <input type="text" value="1" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                </form>
                                <div class="order_by">
                                    <button class="btn" onclick="buyProduct(<?= $value->product_id ?>)">BUY</button>
                                    <i class="material-icons">shopping_cart</i>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 pd0">								
                                <div class="shopping_by">10 PACK<span class="price">47.90</span></div>
                                <form action="#" class="shop-quantity product-quant">
                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                    <input type="text" value="1" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                </form>
                                <div class="order_by">
                                    <button class="btn" onclick="buyProduct(<?= $value->product_id ?>)">BUY</button>
                                    <i class="material-icons">shopping_cart</i>
                                </div>                      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<div class="box pagination">
    <?php
    $lastPage = '';
    if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page'])) {
        $lastPage = (int) (Yii::$app->request->getQueryParams()['page']) - 1;
    } if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page']) && Yii::$app->request->getQueryParams()['page'] != '1'):
        ?>
        <a href="<?php echo Url::to('/product/products/' . $lastPage) ?>" class="prev pull-left"><i class="material-icons">&#xE5CB;</i></a>
            <?php endif; ?>
    <div class="pages">
        <div class="pages">
            <?php if ($last != 1): ?>
                <?php for ($i = 1; $i <= $last; $i++): ?>
                    <a href="<?php echo Url::to('/product/products/' . $i) ?>"><?php echo $i ?></a>
    <?php endfor; ?>
    <?php endif; ?>
        </div>
    </div>
    <?php
    $nextPage = '';
    if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page'])) {
        $nextPage = (int) (Yii::$app->request->getQueryParams()['page']) + 1;
    } if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page']) && Yii::$app->request->getQueryParams()['page'] != $last):
        ?>
        <a href="<?php echo Url::to('/product/products/' . $nextPage) ?>" class="next pull-right"><i class="material-icons">&#xE5CC;</i></a>
<?php endif; ?>
</div>