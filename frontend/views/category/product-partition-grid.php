<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<div class="row products">
    <?php foreach ($products as $key => $value): ?>
        <?php if ($isDefaultLanguage): ?>
            <div class="col-md-4">
                <div class="product_item mob-prod box">
                    <div class="product_top_nav pop_line">
                        <span class="price pull-left"><?php echo $value->price ?></span>
                        <div class="pull-right">
                            <i class="material-icons" title="add to favorites">add</i>
                            <i class="material-icons" title="add to cart">shopping_cart</i>
                        </div>
                    </div>
                    <div class="product_thumb">
                        <a href="<?php echo Url::to(['product/index', 'id' => $value->id]) ?>"> <?php echo backend\models\Product::getImagesToFront($value->id) ?></a>
                    </div>
                    <div class="title_box">
                        <h2 class="product_title"><a href="<?php echo Url::to('product/index/' . $value->id) ?>"><?php echo $value->name ?></a></h2>
                    </div>
                    <div class="product-hover box">
                        <ul>
                            <li>
                                <div class="shopping_by">1 CAN<span class="price">5.09</span></div>
                                <form action="#" class="shop-quantity product-quant">
                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                    <input type="text" value="1" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                </form>
                                <div class="order_by">
                                    <button class="btn">BUY</button>
                                    <i class="material-icons hidden-xs">shopping_cart</i>
                                </div>
                            </li>
                            <li>
                                <div class="shopping_by">10 PACK<span class="price">47.90</span></div>
                                <form action="#" class="shop-quantity product-quant">
                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                    <input type="text" value="1" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                </form>
                                <div class="order_by">
                                    <button class="btn">BUY</button>
                                    <i class="material-icons hidden-xs">shopping_cart</i>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-md-4">
                <div class="product_item mob-prod box">
                    <div class="product_top_nav pop_line">
                        <span class="price pull-left"><?php echo $value->product->price ?></span>
                        <div class="pull-right">
                            <i class="material-icons" title="add to favorites">add</i>
                            <i class="material-icons" title="add to cart">shopping_cart</i>
                        </div>
                    </div>
                    <div class="product_thumb">
                        <a href="<?php echo Url::to(['product/index', 'id' => $value->product->product_id]) ?>"> <?php echo backend\models\Product::getImagesToFront($value->product->product_id) ?></a>
                    </div>
                    <div class="title_box">
                        <h2 class="product_title"><a href="<?php echo Url::to('product/index/' . $value->product->product_id) ?>"><?php echo $value->name ?></a></h2>
                    </div>
                    <div class="product-hover box">
                        <ul>
                            <li>
                                <div class="shopping_by">1 CAN<span class="price">5.09</span></div>
                                <form action="#" class="shop-quantity product-quant">
                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                    <input type="text" value="1" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                </form>
                                <div class="order_by">
                                    <button class="btn">BUY</button>
                                    <i class="material-icons hidden-xs">shopping_cart</i>
                                </div>
                            </li>
                            <li>
                                <div class="shopping_by">10 PACK<span class="price">47.90</span></div>
                                <form action="#" class="shop-quantity product-quant">
                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                    <input type="text" value="1" class="input-quantity">
                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                </form>
                                <div class="order_by">
                                    <button class="btn">BUY</button>
                                    <i class="material-icons hidden-xs">shopping_cart</i>
                                </div>
                            </li>
                        </ul>
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