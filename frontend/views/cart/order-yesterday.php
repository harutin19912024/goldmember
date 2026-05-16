<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Product;
$session = Yii::$app->session;
?>
<section id="content">
    <div class="container box single-box">
        <div class="row">
            <div class="tabs">

                <input type="radio" id="tab1" name="tab-control" checked>
                <input type="radio" id="tab2" name="tab-control">
                <input type="radio" id="tab3" name="tab-control">
                <div class="tab-nav">
                    <ul>
                        <li title="Features"><label for="tab1" role="button"><br><span>CART</span></label></li>
                        <li title="Delivery Contents"><label for="tab2" role="button"><br><span>SHIPPING</span></label></li>
                        <li title="Shipping"><label for="tab3" role="button"><br><span>PAYMENT</span></label></li>
                    </ul>
                </div>
                <div class="slider"><div class="indicator"></div></div>
                <div class="content">
                    <section>
                        <div class="col-sm-12">
                            <div class="table-responsive mb50 shop-cart">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="span55">PRODUCT</th>
                                            <th class="span10">SIZE</th>
                                            <th class="span20">QUANTITY</th>
                                            <th class="span10">AMOUNT</th>
                                            <th class="span5"></th>
                                            <th class="span5"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($basketProducts)):?>
                                        <?php foreach($basketProducts as $key=>$value):?>
                                        <?php if(isset($value['info']->id)):?>
                                        <tr id="bucket-info-<?=$key?>">
                                            <td class="product">
                                                <?php echo Product::getImagesToFront($value['info']->id); ?>
                                                <div class="p_desc">
                                                    <h3><?=$value['info']->name?></h3>
                                                    <span>Art.no: <?=$value['info']->art_no?></span> <span>Stock: <i>In Stock</i></span>
                                                </div>
                                            </td>
                                            <td><span>1 CAN</span></td>
                                            <td class="qunatity">
                                                <form action="#" class="shop-quantity">
                                                    <button type="button" class="btn btn-b js-qty minus"> - </button>
                                                    <input type="text" value="1" class="input-quantity">
                                                    <button type="button" class="btn btn-b js-qty plus"> + </button>
                                                </form>
                                                <span>stock: <i style="color: green"><?php if($value['info']->product_count >12):?> 12+<?php else: ?><?php echo $value['info']->product_count?><?php endif;?></i></span>
                                            </td>
                                            <td><?php echo $value['info']->price?>$</td>
                                            <td>
                                                <a href="javascript:void(0)" onclick="removeBucketProduct(<?php echo $key?>)" data-toggle="tooltip" title="Remove product" class="remove-product"><i class="material-icons">clear</i></a>
                                            </td>
                                        </tr>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                        <?php endif;?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix pd20">
                                <div class="pull-left">
                                    <button class="btn btn-big btn-ship">Continue Shipping<i></i></button>
                                </div>
                                <div class="pull-right">
                                    <span class="pd20">Total: <span class="price dollar prc_new"><?php echo $total?></span></span>
                                    <button type="submit" class="btn btn-default order-continue">Continue</button>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section>
                        <div class="container">
                            <div class="ship_info">
                                <div class="ord-col col-md-6">
                                    <div class="bl_info_box">
                                        <h3 class="text-center">Billing Address</h3>
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="name" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->name?><?php endif;?>" />
                                                    <label for="name">
                                                        <span data-text="First Name">First Name</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="lastname" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->surname?><?php endif;?>" />
                                                    <label for="lastname">
                                                        <span data-text="Last Name">Last Name</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="phone" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->phone?><?php endif;?>" placeholder="Not required" />
                                                    <label for="phone">
                                                        <span data-text="Phone">Phone</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="mobile_phone" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->mobile_phone?><?php endif;?>" />
                                                    <label for="mobile_phone">
                                                        <span data-text="Mobile Phone">Mobile Phone</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="email" id="email" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->email?><?php endif;?>" />
                                                    <label for="email">
                                                        <span data-text="Email">Email</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ord-col col-md-6">
                                    <div class="shp_info_box">
                                        <h3 class="text-center">Shipping Address</h3>
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="company" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->company_name?><?php endif;?>" />
                                                    <label for="company">
                                                        <span data-text="Company Name">Company Name</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="country" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->customerAddresses[0]->country?><?php endif;?>" />
                                                    <label for="country">
                                                        <span data-text="Country">Country</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="state" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->customerAddresses[0]->state?><?php endif;?>" />
                                                    <label for="state">
                                                        <span data-text="State">State</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="city" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->customerAddresses[0]->city?><?php endif;?>" />
                                                    <label for="city">
                                                        <span data-text="City">City</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="address" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->customerAddresses[0]->address?><?php endif;?>" />
                                                    <label for="address">
                                                        <span data-text="Address">Address</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="form-group">
                                                <fieldset class="form-fieldset ui-input">
                                                    <input type="text" id="postcode" value="<?php if(!Yii::$app->user->isGuest):?><?php echo Yii::$app->user->identity->customer->customerAddresses[0]->zip?><?php endif;?>" />
                                                    <label for="postcode">
                                                        <span data-text="Zip/Post code">Zip/Post code</span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="clearfix pd20  ord-footer">
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-default order-continue" id="step_2">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2>Shipping</h2>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam nemo ducimus eius, magnam error quisquam sunt voluptate labore, excepturi numquam! Alias libero optio sed harum debitis! Veniam, quia in eum.
                    </section>

                </div>
            </div>
        </div>
    </div>
</section>