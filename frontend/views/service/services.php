<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Service */

?>

<div class="que-block">
    <div class="arrows">
            <span class="left-arrow">
                <?php echo Html::a(Html::img("@web/img/left-arrow.png"), "javascript: void(0);", ["class" => "next"]) ?>
            </span>
        <span class="right-arrow">
                 <?php echo Html::a(Html::img("@web/img/right-arrow.png"), "javascript: void(0);", ["class" => "prev"]) ?>
            </span>
    </div>
    <div class="que-head">
        <h4>sélectionnez le service que vous voulez</h4>
    </div>
    <div class="que-content" id="serv-carusel">
        <?php $services = array_chunk($services, 4); ?>
        <?php foreach ($services as $key => $service): ?>
            <div class="item row">
                <?php foreach ($service as $item => $values): ?>
                    <div class="col-sm-6">
                        <div class="que-inner">
                            <h6 class="srv-name"><?php echo $values['name'] ?> </h6>
                            <p class="srv-desc"><?php echo $values['short_description'] ?></p>
                            <h5 class="srv-price"><?php echo $values['price'] . "\xE2\x82\xAc" ?></h5>
                            <div class="hover-cart">
                                <?php echo Html::a(Html::img("@web/img/cart-btn.png") . "Add to Cart", "javascript: void(0);", [
                                    " class" => "cart-qbtn add-to-cart",
                                    "data-key" =>$values["id"],
                                ]) ?>
                                <?php echo Html::a(Html::img("@web/img/cart-close.png"), "javascript: void(0);", [
                                    "class" => "q-clos-btn",
                                    "data-key" =>$values["id"],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="step-1-btn">
        <div class="rate-content">
            <h4 id="total">montant total</h4>
            <h2 id="total-price">0.00€</h2>
        </div>
        <?php if(Yii::$app->user->isGuest):?>
        <?php echo Html::a("check-out", ['/site/login']) ?>
        <?php endif; ?>
        <?php if(!Yii::$app->user->isGuest):?>
        <?php echo Html::a("check-out", ['/service/address']) ?>
        <?php endif; ?>
    </div>
</div>

