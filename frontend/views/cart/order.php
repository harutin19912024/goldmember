<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Product;
use common\models\Countries;
use common\models\States;
$session = Yii::$app->session;
$this->title = Yii::t('app','SANSTROY').' | '.Yii::t('app', 'Basket');
$product_ids = [];
?>
<div class="container">
	<div class="row wrapper-main">
		<div class="col-sm-12">
			<ul class="nav-product">
				<li class="active">
					<a href="/<?=Yii::$app->language?>">Главная</a>
				</li>
				<li>
					<a href="/<?=Yii::$app->language?>/product/index">Список продуктов</a>
				</li>
				<li>
					<a href="javascript:;" class="active"><?=Yii::t('app', 'Basket')?></a>
				</li>

			</ul>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<h1 style="color:black;"><?= Html::encode(Yii::t('app', 'Basket')) ?></h1>
			</div>
		</div>
        <?php if (!empty($basketProducts)): ?>
            <div class="ordering">
                <div class="col-sm-12">
                    <div class="table-responsive mb50 shop-cart">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="span55"><?=Yii::t('app','Product Name')?></th>
                                <th class="span20">Количество</th>
                                <th class="span10">Сумма</th>
                                <th class="span5"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  foreach ($basketProducts as $key => $value): ?>
								<?php $product_ids[] = ['count'=>@$value['count'],'product_id'=>$key]?>
                                <tr id="bucket-info-<?= $key ?>" style="height: 185px;">
                                    <td class="product">
                                        <?php echo Product::getImagesToFront($key); ?>
                                        <div class="p_desc" style='width: 452px;overflow-x: scroll;'>
                                            <h3><?= @$value['name'] ?></h3>
                                            <span>
												<?php echo Product::findOne($key)->short_description; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="qunatity">

                                        <form action="#" class="shop-quantity">
                                            <button type="button" class="btn btn-b js-qty minus"
                                                    onclick="changeCount(this,<?= $key ?>,<?=$key ?>)">
                                                -
                                            </button>
                                            <input type="text" value="<?= @$value['count'] ?>"
                                                   class="input-quantity">
                                            <button type="button" class="btn btn-b js-qty plus"
                                                    onclick="changeCount(this,<?=$key ?>,<?=$key ?>)">
                                                +
                                            </button>
                                        </form>

                                    </td>
                                    <td id="order-package-price-<?php echo $key ?>">
                                        <?=number_format($value['totalprice'], 0, ',', ' ')?> РУБ.
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           onclick="removeBucketProduct(<?php echo $key ?>,<?= @$value['product']['productID'] ?>)"
                                           data-toggle="tooltip" title="Remove product"
                                           class="remove-product"><i class="icon-delete"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="total-finish">
                    <div class="row">
                            <div class="finish-price pull-right" style='width: 225px;'><h3>Общая сумма : </h3>
                                    <span class="price" id="bucket-total-price"><?=$total?> РУБ.</span></div>
                            <div class="clearfix"></div>
                    </div>
                </div>
				<div class="clearfix"></div>
				
				<div class="ordering">
                <div class="col-sm-12">
                <div class="ship_info">
                    <form action="<?php echo Url::to('/cart/order') ?>" id="order-address" class="col-md-12">
						<input type="hidden" value="<?=$total?>" id="total_price" name="total_price">
						<input type="hidden" value='<?=json_encode($product_ids)?>' id="product_info" name="product_ids">
                        <div class="ord-col">
                            <div class="bl_info_box">
                                <h3 class="text-center">Информация о доставке</h3>
                                <div class="form-horizontal">
                                   
                                    <div class="form-group">
                                        <fieldset class="form-fieldset ui-input">
                                            <input type="text" id="name"
                                                   value="<?php if (!Yii::$app->user->isGuest): ?><?php echo Yii::$app->user->identity->customer->name ?><?php endif; ?>"/>
                                            <label for="name">
                                                <span style="color: red;">*</span><span data-text="Имя">Имя</span>
                                            </label>
                                        </fieldset>
                                    </div> 
                                    <div class="form-group"> 
                                        <fieldset class="form-fieldset ui-input">
                                            <input type="text" id="lastname"
                                                   value="<?php if (!Yii::$app->user->isGuest): ?><?php echo Yii::$app->user->identity->customer->surname ?><?php endif; ?>"/>
                                            <label for="lastname">
                                                <span style="color: red;">*</span><span data-text="Фамилия">Фамилия</span>
                                            </label>
                                        </fieldset>
                                    </div>
									<div class="form-group">
                                        <fieldset class="form-fieldset ui-input">
                                            <input type="text" id="email"
                                                   value="<?php if (!Yii::$app->user->isGuest): ?><?php echo Yii::$app->user->identity->customer->email ?><?php endif; ?>"/>
                                            <label for="lastname">
                                                <span style="color: red;">*</span><span data-text="Емайл">Емайл</span>
                                            </label>
                                        </fieldset>
                                    </div>
                                    <div class="form-group">
                                        <fieldset class="form-fieldset ui-input">
                                            <input type="text" id="address"
                                                   value="<?php if (!Yii::$app->user->isGuest && !empty(Yii::$app->user->identity->customer->customerAddresses)): ?><?php echo Yii::$app->user->identity->customer->customerAddresses[0]->address ?><?php endif; ?>"/>
                                            <label for="address">
                                                <span style="color: red;">*</span><span data-text="Адрес">Адрес</span>
                                            </label>
                                        </fieldset>
                                    </div>
                                    <div class="form-group">
                                        <fieldset class="form-fieldset ui-input">
                                            <input type="text" id="phone"
                                                   value="<?php if (!Yii::$app->user->isGuest): ?><?php echo Yii::$app->user->identity->customer->phone ?><?php endif; ?>"/>
                                            <label for="phone">
                                                <span style="color: red;">*</span><span data-text="Телефон">Телефон</span>
                                            </label>
                                        </fieldset>
                                    </div>
									 <div class="form-group">
                                        <fieldset class="form-fieldset ui-input">
                                           <textarea class="form-control" name='additional_info' id="additional-info"></textarea>
                                            <label for="additional-info">
                                                <span data-text="Оставить дополнительная информация">Оставить Дополнительную информацию</span>
                                            </label>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                    <div class="clearfix"></div>
                </div>
                </div>
                </div>
                <div class="col-sm-12 ">
                    <div class="clearfix pd20  ord-footer">
                        <div class="pull-left">
                            <a href="<?php echo Url::to(['product/index']) ?>" class="btn btn-big btn-ship btn-back">Вернуться к продуктам</a>
                        </div>
                        <div class="pull-right">
                            <button type="submit" onclick="contineToPayment()" class="btn btn-default order-continue">Подтвердить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else:?>
            <div class="row">
                <div class="checkout_empty">
                    <h2><div></div>
                        <?=Yii::t('app','Your Cart is Empty')?>
                    </h2>
                    <br>
                    <div class="text-center"><a href="/product/index" class="btn btn-big btn-ship btn-back">
					 <?=Yii::t('app','Back To Products')?></a></div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>


