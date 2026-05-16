<?php
use \yii\helpers\Html;

use common\components\CurrencyHelper;
use backend\models\Currency;
$defaultCurrency = Currency::find()->where(['default'=>1])->one();

/* @var $this yii\web\View */
/* @var $products frontend\models\Product[] */
?>
<h1>Your cart</h1>
<div class="blank-box"></div>
<section class="step-heading">
    <div class="container">
        <div class="step-hinner">
            <h2>Reparez votre smartphone en moins d'une heure. </h2>
            <h4>Réparez votre iPhone, iPad, iPod ou Samsung Galaxy</h4>
        </div>
    </div>
</section>
<section class="select-block step-arrow">
    <div class="container">
        <div class="step-2-inner">
            <div class="container-fluid">
                <div class="row mb15 step-2-content w100p">
                    <div class="col-md-4">
                        <h4>
                            Service
                        </h4>
                    </div>
                    <div class="col-md-2">
                        <h4>
                            Price
                        </h4>
                    </div>
                    <div class="col-md-2">
                        <h4>
                            Quantity
                        </h4>
                    </div>
                    <div class="col-md-2">
                        <h4>
                            Cost
                        </h4>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <?php foreach ($products as $product): ?>
                    <div class="row mb15">
                        <div class="col-md-4">
                            <?= Html::encode($product->name) ?>
                        </div>
                        <div class="col-md-2">
                            <?php if(!empty(Yii::$app->session->get('currency'))):?>
                                    <?php echo CurrencyHelper::changeValue(Yii::$app->session->get('currency')['currenncyID'], $product->price) ?>
                                <?php else:?>
                                    <?php echo CurrencyHelper::changeValue($defaultCurrency->id,$product->price) ?>
                                <?php endif;?>
                        </div>
                        <div class="col-md-2">
                            <div class="quantity pull-left mr5">
                                <?= $quantity = $product->getQuantity() ?>
                            </div>
                            <div class="step-btn pull-left">
                                <?= Html::a('-', ['cart/update', 'id' => $product->getId(), 'quantity' => $quantity - 1], ['disabled' => ($quantity - 1) < 1]) ?>
                                <?= Html::a('+', ['cart/update', 'id' => $product->getId(), 'quantity' => $quantity + 1]) ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <?= $product->getCost() ?>
                        </div>
                        <div class="col-md-2">
                            <div class="step-btn">
                                <?= Html::a('×', ['cart/remove', 'id' => $product->getId()]) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
                <div class="row">
                    <div class="col-md-8">

                    </div>
                    <div class="col-md-2">
                        Total:
                                <?php if(!empty(Yii::$app->session->get('currency'))):?>
                                    <?php echo CurrencyHelper::changeValue(Yii::$app->session->get('currency')['currenncyID'], $total) ?>
                                <?php else:?>
                                    <?php echo CurrencyHelper::changeValue($defaultCurrency->id,$total) ?>
                                <?php endif;?>
                    </div>
                    <div class="col-md-2">
                        <div class="step-1-btn">
                            <?php if(!Yii::$app->user->isGuest):?>
                            <?= Html::a('Order', ['/service/address']) ?>
                            <?php endif; ?>
                            <?php if(Yii::$app->user->isGuest):?>
                                <?= Html::a('Order', ['/site/login']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>