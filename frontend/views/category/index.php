<section id="content">
    <div class="container box single-box">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pd0 col-flex">
                <div class="single-left-panel">
                    <ul>
                        <li><h2>Art.no</h2><span><?php echo $product->art_no ?></span></li>
                        <li><h2>Weight</h2><span><i><?php echo $product->weight ?></i> gram</span></li>
                        <li class="share">
                            <input type="checkbox" class="checkbox" id="share">
                            <label for="share" class="entypo-export">
                                <span>SHARE</span>
                                <i class="material-icons">more_horiz</i>
                            </label>
                            <div class="social">
                                <ul>
                                    <li class="entypo-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></li>
                                    <li class="entypo-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></li>
                                    <li class="entypo-gplus"><i class="fa fa-google-plus" aria-hidden="true"></i></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <!-- <div class="prd_share"><h2>Share</h2></div> -->
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right prod-top-nav">
                            <a class="add-favorite" data-toggle="tooltip" title="add to favorite" href="javascript:void(0)" onclick="favorite('<?php echo $product['id']?>')">
                                <button id="heart" onclick="favorite(<?php echo $product['id']?>)">
                                  <span class="glyphicon glyphicon-heart">
                                    <span class="glyphicon glyphicon-heart">
                                    </span>
                                  </span>
                                </button>
                            </a>
                            <a class="product-print" data-toggle="tooltip" title="print page" href="javascript:window.print()"><i class="material-icons">print</i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="single_image">
                            <?php echo backend\models\Product::getImagesToFront($product->id, 'image-zoom')?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="prod-info">
                            <h2><?php echo $product->name?></h2>
                            <ul>
                                <li>For England + VAT</li>
                                <li>Portion 10 pack</li>
                                <li>Portion 1 can</li>
                            </ul>
                            <label class="control control--checkbox">Available In Stock
                                <input type="checkbox" <?php if($product->product_count > 0):?>checked="checked"<?php endif;?> disabled="disabled" />
                                <div class="control__indicator"></div>
                            </label>
                            <div class="rate">
                                <p>rating</p>
                                <input type="hidden" id="test_input" type="number" />
                                <input type="hidden" id="product_id" type="number" value="<?php echo $product->id ?>" />
                                <div class="w_review_stars w_modal_rating" data-mark="<?php echo  $product->rate; ?>">
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
                        </div>
                    </div>
                    <div class="col-md-8 col-md-offset-4 product-nav">
                        <ul class="pull-right">
                            <li>
                                <div class="current_price">
                                    <span>PRICE</span>
                                    <span class="price prc_new"><?php echo $product->price?></span>
                                </div>
                            </li>
                            <li>
                                <div class="can-box">
                                    <input class="input-number" type="text" value="1" min="1" max="20"> can
                                    <div class="triangles">
                                        <span class="can-increment"><i class="glyphicon glyphicon-triangle-top"></i></span>
                                        <span class="can-decrement"><i class="glyphicon glyphicon-triangle-bottom"></i></span>
                                    </div>
                                </div>
                            </li>
                            <li><label for="quant">QUANTITY</label><input id="quant" type="text" class="quant" value="<?php echo $product->product_count?>"></li>
                            <li><button class="btn btn-to-card" onclick="buyProduct(<?php echo $product->id?>)">ADD TO CARD</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container prod-com-cont">
        <div class="row flex">
            <div class="col-md-7">
                <div class="prd_comments box">
                    <img src="/images/comments.png">
                </div>
            </div>
            <div class="col-md-5">
                <div class="comment_login box">
                    <h2 class="fs14">LEAVE A COMMENT</h2>
                    <?php if(Yii::$app->user->isGuest):?>
                    <div class="pls_login">
                        <h3>
                            <span>to leave a comment please</span><br>
                            <span><a href="/site/login">login</a> or <a href="/site/login">register</a></span>
                        </h3>
                    </div>
                    <?php elseif (isset(Yii::$app->user->identity->id)):?>
                    <div class="pls_login">
                        <textarea class="form-control" rows="6" placeholder="Comment"></textarea>
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="container box best_sellers">
        <h3 class="text-center fs14">CUSTOMERS WHO BOUGHT THIS PRODUCT ALSO BOUGHT</h3>
        <div class="best_items">
            <div class="col-md-3">
                <div class="item_best">
                    <img src="/images/best_sells/odens_cold.png" alt="">
                    <h4>Oden's Cold Portion</h4>
                    <span>CHEWINC TOBACCO</span>
                    <div class="best_prices">
                        <span class="price bst_prc_item prc_old">39.99</span>
                        <span class="price bst_prc_item prc_new">29.99</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="item_best">
                    <img src="/images/best_sells/odens_original.png" alt="">
                    <h4>Oden's Original Portion</h4>
                    <span>CHEWINC TOBACCO</span>
                    <div class="best_prices">
                        <span class="price bst_prc_item prc_old">29.99</span>
                        <span class="price bst_prc_item prc_new">19.99</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="item_best">
                    <img src="/images/best_sells/odens_salty.png" alt="">
                    <h4>WOW! Salty Stuff</h4>
                    <span>CHEWINC TOBACCO</span>
                    <div class="best_prices">
                        <span class="price bst_prc_item prc_old">49.99</span>
                        <span class="price bst_prc_item prc_new">39.99</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="item_best">
                    <img src="/images/best_sells/odens_taboca.png" alt="">
                    <h4>Taboca Original Portion</h4>
                    <span>CHEWINC TOBACCO</span>
                    <div class="best_prices">
                        <span class="price bst_prc_item prc_old">39.99</span>
                        <span class="price bst_prc_item prc_new">29.99</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
