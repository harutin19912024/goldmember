<div class="container">
    <div class="row wrapper-main">
        <?= $this->render('/site/category', ['choosen_category' => 0]) ?>
        <div class="col-lg-9 col-sm-8">
            <ul class="nav-product">
                <li class="active">
                    <a href="/<?= Yii::$app->language ?>">Главная</a>
                </li>
                <?php if ($parentCategoryInfo): ?>
                    <li class="active">
                        <a href="/<?= Yii::$app->language ?>/<?= $parentCategoryInfo->route_name ?>"><?= $parentCategoryInfo->name ?></a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="javascript:;"><?= $categoryInfo->name ?></a>
                </li>
            </ul>
            <div class="row clearboth">
                <div class="col-md-6">
                    <div class="row d-flex">
                        <div class="col-xs-9">
                            <div id="product-image">
                                <a href="<?php echo backend\models\Product::getImagesToFront($model->id, '', '', true) ?>">
                                    <?php echo backend\models\Product::getImagesToFront($model->id, 'cloudzoom') ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div id="thumbnails">
                                <div class="thumbelina-but vert top">&#708;</div>
                                <?php $images = backend\models\Product::getProductImagesSecondry($model->id, 'cloudzoom-gallery') ?>
                                <ul>
                                    <?php foreach ($images['url'] as $imgSrc): ?>
                                        <li>
                                            <a href="<?= $imgSrc ?>" title="View">
                                                <img class="cloudzoom-gallery"
                                                     src="<?= $imgSrc ?>"
                                                     alt="thumbnail"
                                                     data-cloudzoom="
                                                     useZoom:'.cloudzoom',
                                                     image:'<?= $imgSrc ?>'
                                                     ">
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="thumbelina-but vert bottom">&#709;</div>
                            </div>
                        </div>
                    </div>
                    <div id="zoom-overlay">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="details-info">
                        <h3><?= $model->name ?></h3>

                        <?php if ($model->product_sku != ''): ?>
                            <p class="nmb-product">Код товара: <span>#<?= $model->product_sku ?></span></p>
                        <?php endif; ?>
                        <?php if ($model->getSeria($model->seria_id) != ''): ?>
                            <p class="nmb-product">Серия Товара: <span><?= $model->getSeria($model->seria_id); ?></span></p>
                        <?php endif; ?>
                        <div class="txt-product"><?= $model->short_description ?></div>
                        <ul class="price-item">
                            <?php if ($model->stock || $model->sale): ?>
                                <li><?= number_format($model->new_price, 0, ',', ' ') ?> р.</li>
                                <li><?= number_format($model->price, 0, ',', ' ') ?> р.</li>
                            <?php else: ?>
                                <li><?= number_format($model->price, 0, ',', ' ') ?> р.</li>
                                <li class="product-page-price-second"></li>
                            <?php endif; ?>
                        </ul>
                        <div class="quantity-product" >
                            <input type="button" value="-" id="moins" onclick="minus()">
                            <input type="text" size="25" value="1" id="count">
                            <input type="button" value="+" id="plus" onclick="plus()">
                        </div>
                        <button class="btn btn-pblue" onclick="addToBasket('count',<?= $model->id ?>)">Добавить в корзину <span class="icon-online-shopping-cart"></span></button>
                        <?php if (!empty(Yii::$app->user->identity)): ?>
                            <button class="btn btn-pgreen" id="heart_<?= $model->id ?>" onclick="addFavorite(<?= $model->id ?>)">Добавит в избранное<span class="flaticon-heart"> </span></button>
                        <?php endif; ?>
                        <p class="social-title">Поделитесь в сетях</p>
                        <ul class="social-top">
                            <li><a href=""><span class="icon-facebook-logotype-button2"></span></a></li>
                            <li><a href=""><span class="icon-twitter-logo-on-black-background2"></span></a></li>
                            <li><a href=""><span class="icon-linkedin-logotype-button2"></span></a></li>
                            <li><a href=""><span class="icon-social-google-plus-square-button"></span></a></li>
                            <li><a href=""><span class="icon-vk-icon"></span></a></li>
                            <li><a href=""><span class="icon-video-play-button2"></span></a></li>
                            <li><a href=""><span class="icon-odnoklassniki-logo"></span></a></li>
                            <li><a href=""><span class="icon-pinterest-logo"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs description-tab">
                <li class="active"><a data-toggle="tab" href="#home">Характеристики</a></li>
                <li><a data-toggle="tab" href="#menu1">Состав Поставки</a></li>
            </ul>

            <div class="tab-content">
                <div id="home" class="tab-pane fade in active">
                    <div class="table-responsive">          
                        <table class="table description-item">
                            <tbody>
                                <?php if (!empty($productDetails)): ?>
                                    <?php foreach ($productDetails as $detail): ?>
                                        <tr>
                                            <td><?= $detail['name'] ?></td>
                                            <td><?= $detail['value'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="menu1" class="tab-pane fade">
                    <div class="col-lg-12">
                        <?= $model->description ?>
                    </div>
                </div>
            </div>
            <a href="#" class="btn btn-pink">Связанные товары</a>
            <div class="product-item">
                <?php foreach ($connectedProducts as $prudcts): ?>
                    <?php $productInfo = Product::findOne($prudcts['conn_product_id']); ?>
                    <?php $catRout = frontend\models\Category::getCatRout($productInfo->category_id); ?>
                    <?php
                    $images = backend\models\ProductImage::find()->where(['product_id' => $productInfo->id, 'type' => 1, 'default_image_id' => 1])->asArray()->one();
                    ?>
                    <div class="item">
                        <div>
                            <?php echo Html::a("<h2>" . $productInfo->name . "</h2>", '/' . Yii::$app->language . '/' . $catRout . '/' . $productInfo->route_name) ?>
                            <a href="<?php echo Url::to('/' . Yii::$app->language . '/' . $catRout . '/' . $productInfo->route_name) ?>">
                                <?php echo Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/thumbnail/' . $images['name'], ['class' => 'icon']); ?>
                            </a>
                            <div class="row bottom-element">
                                <div class="col-xs-8">
                                    <p>Цена</p>
                                    <?php if ($productInfo->sale || $productInfo->stock): ?>
                                        <p class="discount"><?= number_format($productInfo->new_price, 0, ',', ' ') ?><img class="p-icon" src="/images/png/price-icon.png" alt="..."></p>
                                        <p class="price"><?= number_format($productInfo->price, 0, ',', ' ') ?><img class="p-icon" src="/images/png/price-bicon.png" alt="..."></p>
                                    <?php else: ?>
                                        <p class="discount"><?= number_format($productInfo->price, 0, ',', ' ') ?><img class="p-icon" src="/images/png/price-bicon.png" alt="..."></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-4">
                                    <button type="button" onclick="addToBasket(1,<?= $productInfo->id ?>)" class="btn"><span class="icon-online-shopping-cart"></span></button>
                                </div>
                            </div>   
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="carousel-example-generic" class="carousel slide slide_" data-ride="carousel" style="margin-bottom: 10px;">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php foreach ($sliders as $key => $slider) : ?>
                        <li data-target="#carousel-example-generic" data-slide-to="<?= $key ?>" class="<?php if (!$key): ?>active<?php endif; ?>"></li>
                    <?php endforeach; ?>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <?php foreach ($sliders as $key => $slider) : ?>
                        <div class="item <?php if (!$key): ?>active<?php endif; ?>">
                            <a href="/<?= Yii::$app->language ?>/<?= $slider['link'] ?>" target="_blank"><?php echo Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/slider/' . $slider['id'] . '/thumbnail/' . $slider['path'], ['style' => 'height:330px']); ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php $this->registerJs('
	$(".thumbelina li a").first().click();
'); ?>