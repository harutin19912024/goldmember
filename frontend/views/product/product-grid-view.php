<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\web\View;

?>

<div class="container product_wrapper">
    <div class="row">
        <div class="col-md-3">

            <div class="widget">
                <div class="box filter_price">
                    <div class="clear"></div>
                    <h2><?= Yii::t('app','Price');?></h2>
                    <div class="filter-item">
                        <div class="filter-inner">
                            <div class="price_filter_inputs">
                                <input type="number" class="form-control price_val" id="low_count" value="1">
                                -
                                <input type="number" class="form-control price_val" id="high_count" value="1000">
                                <span class="price"></span>
                            </div>
                            <input id="ex2" type="text" class="span2" value="" data-slider-tooltip="hide" data-slider-min="1"
                                   data-slider-max="1000" data-slider-step="5" data-slider-value="[1,1000]"/>
                        </div>
                    </div>

                    <p class="pd10"></p>
                </div>
            </div>
            <!-- Product Filter widget -->
            <div class="widget">
                <div class="box filter-widget">
                    <div class="clear"></div>
                    <h2>BRANDS <span class="pull-right check-all" data-toggle="tooltip" title="select all">All</span>
                        <div class="clear"></div>
                    </h2>
                    <div class="vert-ftr filter-brand">
                        <label class="control control--checkbox">Odens
                            <span class="pull-right">32</span>
                            <div class="clear"></div>
                            <input type="checkbox" name="prd-brand" checked="checked"/>
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Olde ving
                            <span class="pull-right">14</span>
                            <div class="clear"></div>
                            <input type="checkbox" name="prd-brand"/>
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Islay Wiskey
                            <span class="pull-right">3</span>
                            <div class="clear"></div>
                            <input type="checkbox" name="prd-brand"/>
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Wow
                            <span class="pull-right">5</span>
                            <div class="clear"></div>
                            <input type="checkbox" name="prd-brand"/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>

                    <p class="pd10"></p>
                </div>
            </div>
            <!-- Product List widget -->
            <div class="widget">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                   aria-expanded="true" aria-controls="collapseOne">
                                    PRODUCTS BY CATEGORY <span class="caret"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                             aria-labelledby="headingOne">
                            <div class="panel-body">
                                <ul>
                                    <?php foreach ($categories as $category): ?>
                                        <li>
                                            <a href="/product/products?id=<?php echo $category->id ?>"><?php echo $category->name ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kits Widget -->
            <div class="widget">
                <div class="box kits">
                    <a href="#">OFFERS RIGHT NOW</a>
                    <div id="owl-kits" class="owl-carousel ">
                        <div class="kit_item">
                            <img src="/images/kits/kit1.png" alt="Kit 1">
                            <div class="kit_cont">
                                <h2>Kit No 1</h2>
                                <span>Original Portion</span>
                            </div>
                        </div>
                        <div class="kit_item">
                            <img src="/images/kits/kit2.png" alt="Kit 2">
                            <div class="kit_cont">
                                <h2>Kit No 2</h2>
                                <span>Original Portion</span>
                            </div>
                        </div>
                        <div class="kit_item">
                            <img src="/images/kits/kit3.png" alt="Kit 3">
                            <div class="kit_cont">
                                <h2>Kit No 3</h2>
                                <span>Original Portion</span>
                            </div>
                        </div>
                        <div class="kit_item">
                            <img src="/images/kits/kit3.png" alt="Kit 4">
                            <div class="kit_cont">
                                <h2>Kit No 4</h2>
                                <span>Original Portion</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-big">BUY</button>
                </div>
            </div>
        </div>
        <div class="col-md-9" id="product_content">
            <div class="row products">
                <?php foreach ($products as $key => $value): ?>
                    <?php if ($isDefaultLanguage): ?>
                        <div class="col-md-4">
                            <div class="product_item mob-prod box">
                                <div class="product_top_nav pop_line">
                                    <span class="price pull-left"><?php echo $value->price ?></span>
                                    <div class="pull-right" id="add-to-buket" data-id="<?php echo $value->id ?>"><i
                                            class="material-icons">add</i><i class="material-icons">shopping_cart</i>
                                    </div>
                                </div>
                                <div class="product_thumb">
                                    <a href="<?php echo Url::to(['product/index', 'id' => $value->id]) ?>"> <?php echo backend\models\Product::getImagesToFront($value->id) ?></a>
                                </div>
                                <div class="title_box">
                                    <h2 class="product_title"><a
                                            href="<?php echo Url::to('product/index/' . $value->id) ?>"><?php echo $value->name ?></a>
                                    </h2>
                                </div>
                                <div class="product-hover box">
                                    <ul>
                                        <li>
                                            <div class="shopping_by">1 CAN<span class="price">5.09</span></div>
                                            <form action="#" class="shop-quantity product-quant">
                                                <button type="button" class="btn btn-b js-qty minus"
                                                        onclick="changeCount(this)"> -
                                                </button>
                                                <input type="text" value="1" id="input-number-<?php echo $value->id ?>"
                                                       class="input-quantity">

                                                <button type="button" class="btn btn-b js-qty plus"
                                                        onclick="changeCount(this)"> +
                                                </button>
                                            </form>
                                            <div class="order_by" data-product-id="<?php echo $value->id ?>">
                                                <button class="btn" onclick="orderProduct(<?= $value->id ?>)">BUY
                                                </button>
                                                <i class="material-icons hidden-xs">shopping_cart</i>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="shopping_by">10 PACK<span class="price">47.90</span></div>
                                            <form action="#" class="shop-quantity product-quant">
                                                <button type="button" class="btn btn-b js-qty minus"> -</button>
                                                <input type="text" value="1" class="input-quantity">
                                                <button type="button" class="btn btn-b js-qty plus"> +</button>
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
                                    <div class="pull-right" id="add-to-buket"
                                         data-id="<?php echo $value->product->id ?>"><i class="material-icons">add</i><i
                                            class="material-icons">shopping_cart</i></div>
                                </div>
                                <div class="product_thumb">
                                    <a href="<?php echo Url::to(['product/index', 'id' => $value->product->id]) ?>"> <?php echo backend\models\Product::getImagesToFront($value->product->id) ?></a>
                                </div>
                                <div class="title_box">
                                    <h2 class="product_title"><a
                                            href="<?php echo Url::to('product/index/' . $value->product->id) ?>"><?php echo $value->name ?></a>
                                    </h2>
                                </div>
                                <div class="product-hover box">
                                    <ul>
                                        <li>
                                            <div class="shopping_by">1 CAN<span class="price">5.09</span></div>
                                            <form action="#" class="shop-quantity product-quant">
                                                <button type="button" class="btn btn-b js-qty minus"
                                                        onclick="changeCount(this)"> -
                                                </button>
                                                <input type="text" value="1"
                                                       id="input-number-<?php echo $value->product->id ?>"
                                                       class="input-quantity">
                                                <button type="button" class="btn btn-b js-qty plus"
                                                        onclick="changeCount(this)"> +
                                                </button>
                                            </form>
                                            <div class="order_by" data-product-id="<?php echo $value->product_id ?>">
                                                <button class="btn" onclick="orderProduct(<?= $value->product_id ?>)">
                                                    BUY
                                                </button>
                                                <i class="material-icons hidden-xs">shopping_cart</i>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="shopping_by">10 PACK<span class="price">47.90</span></div>
                                            <form action="#" class="shop-quantity product-quant">
                                                <button type="button" class="btn btn-b js-qty minus"> -</button>
                                                <input type="text" value="1" class="input-quantity">
                                                <button type="button" class="btn btn-b js-qty plus"> +</button>
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
            <?php
            $lastPage = '';
            if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page'])) {
                $lastPage = (int)(Yii::$app->request->getQueryParams()['page']) - 1;
            }
            if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page']) && Yii::$app->request->getQueryParams()['page'] != '1'):
                ?>
                <div class="box pagination">
                    <a href="<?php echo Url::to('/product/products/' . $lastPage) ?>" class="prev pull-left"><i
                            class="material-icons">&#xE5CB;</i></a>
                    <div class="pages">
                        <?php if ($last != 1): ?>
                            <?php for ($i = 1; $i <= $last; $i++): ?>
                                <a href="<?php echo Url::to('/product/products/' . $i) ?>"><?php echo $i ?></a>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                    <?php
                    $nextPage = '';
                    if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page'])) {
                        $nextPage = (int)(Yii::$app->request->getQueryParams()['page']) + 1;
                    }
                    if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page']) && Yii::$app->request->getQueryParams()['page'] != $last):
                        ?>
                        <a href="<?php echo Url::to('/product/products/' . $nextPage) ?>" class="next pull-right"><i
                                class="material-icons">&#xE5CC;</i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>