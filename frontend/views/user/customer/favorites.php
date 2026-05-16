<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $products frontend\models\Product[] */

?>
<div class="row products">
    <?php if(empty($products)) : ?>
        <div class="col-md-12 col-sm-12">
            <div class="col-md-offset-3 col-md-6">
                <h3 class="text-center">You have not any <u>favorite</u> auctions!</h3>
            </div>
        </div>
    <?php else: ?>
    <?php foreach ($products as $key => $product): ?>
            <div class="" id="<?= 'fvrt_'.$key;?>">
                <div class="product_list_item box">
                    <div class="col-md-6 pd0">
                        <div class="col-md-4 col-sm-4">
                            <div class="row">
                                <div class="product_list_thumb">
                                    <a href="<?php echo Url::to(['/product/index', 'id' => $key]) ?>"> <?php echo backend\models\Product::getImagesToFront($key) ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-5">
                            <div class="row">
                                <div class="title_list_box">
                                    <h2 class="product_title"><a
                                            href="<?php echo Url::to('/product/index/' . $key) ?>"><?php echo $product['name'] ?></a>
                                    </h2>
                                </div>
                                <div class="prod_desc hidden-xs">
                                            <span>
                                                <?php echo $product['short_description'] ?>
                                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pd0">
                        <div class="col-md-8 col-sm-8">
                            <div class="row list_shop_by">
<button type="button" onclick="addToBasket(1,<?=$product['id']?>)" class="btn"><span class="icon-online-shopping-cart"></span></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
