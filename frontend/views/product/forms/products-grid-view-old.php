<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\components\CurrencyHelper;
?>
    <div class="row products">

   <?php foreach($products as $model){?>
    <div class="grid-box col-md-4 col-sm-6">
        <div class="product_grid box">
            <div class="product_overlay"><i class="material-icons">&#xE86C;</i><h2>Added To Basket</h2></div>
            <div class="product_thumb">
                <a href="<?php echo Url::to(['product/view', 'id' => $model['id']]) ?>"> <?php echo  Html::img(Yii::$app->params['adminUrl'].'uploads/images/'. $model['image']); ?></a>
            </div>
            <div class="title_box">
                <h2 class="product_title"><a
                        href="<?php echo Url::to(['/product/view/' , 'id' => $model['id']]) ?>" title="<?=$model['name']?>">
                        <?php
                        echo strlen($model['name']) > 23 ? $string = trim(substr($model['name'], 0, 23)).'...':$model['name'];
                        ?>
                    </a>
                </h2>
            </div>
			<div>
				Price : <?=$model['price']?>
			</div>
			<div class="rate">
                                <p>Rating: </p>
                                <input type="hidden" id="test_input_<?=$model['id']?>">
                                <input type="hidden" id="product_id" value="<?=$model['id']?>">
                                <div class="w_review_stars w_modal_rating" id="product_rate_<?=$model['id']?>" onmouseover="showStars('<?=$model['id']?>')" data-mark="<?=$model['rate']?>">
                                    <div class="w_stars">
                                        <div class="w_star_hover" data-rating="1"></div>
                                        <div class="w_star_hover" data-rating="2"></div>
                                    </div>
                                    <div class="w_stars">
                                        <div class="w_star_hover" data-rating="3"></div>
                                        <div class="w_star_hover" data-rating="4"></div>
                                    </div>
                                    <div class="w_stars">
                                        <div class="w_star_hover" data-rating="5"></div>
                                        <div class="w_star_hover" data-rating="6"></div>
                                    </div>
                                    <div class="w_stars">
                                        <div class="w_star_hover" data-rating="7"></div>
                                        <div class="w_star_hover" data-rating="8"></div>
                                    </div>
                                    <div class="w_stars">
                                        <div class="w_star_hover" data-rating="9"></div>
                                        <div class="w_star_hover" data-rating="10"></div>
                                    </div>
                                </div>
                            </div>
            <div class="product-package">
                <ul>
                        <li>
                            <form action="#" class="shop-quantity">
                                <button type="button" class="btn btn-b js-qty minus"
                                        onclick="changeCount(this)"> -
                                </button>
                                <input type="text" value="1" id="input-number-<?php echo $model['id'] ?>"
                                       class="input-quantity">

                                <button type="button" class="btn btn-b js-qty plus"
                                        onclick="changeCount(this)"> +
                                </button>
                            </form>
                            <div class="order_by" data-product-id="<?php echo $model['id'] ?>">
                                <i class="material-icons add-to-cart" data-toggle="tooltip" onclick="addToBasket(<?php echo $model['id'] ?>)" title="Add To Cart">shopping_cart</i>
                            </div>
                        </li>

                </ul>
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
