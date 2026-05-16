<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\components\CurrencyHelper;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Mobile-Centre.Shop') . ' | ' . Yii::t('app', 'Home');
?>
<section id="slider">
    <div class="container">
        <div class="col-sm-12">
            <div class="bs-example">
                <div id="owl-demo" class="owl-carousel owl-theme">
                    <?php foreach ($InSliderProducts as $product) : ?>
                        <div class="item">
                            <div class="owl-caption">
                                <div class="row">
                                    <div class="col-md-4 pull-right">
                                        <?php echo Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . $product['image']); ?>
                                    </div>
                                    <div class="col-md-8 pull-left">
                                        <div class="caption_text">
                                            <?php echo Html::a("<h1><br>" . $product['name'] . "</h1>", Url::to(['product/index', 'id' => $product['id']]))
                                            ?>
                                            <span class="hidden-xs">
                                                <?php echo $product['short_description']; ?>
                                            </span>
                                            <a href="<?= Url::to(['product/view', 'id' => $product['id']]); ?>" class="btn btn-big btn_blue">BUY NOW</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="content">
    <div class="container product_wrapper">
        <div class="row">
            <div class="col-md-3">
                <div class="widget">
                    <div class="box kits">
                        <a href="javascript:;"><?= Yii::t('app', 'OFFERS RIGHT NOW') ?></a>
                        <div id="owl-kits" class="owl-carousel ">
                            <?php foreach ($InSliderProducts as $product) : ?>
                                <div class="kit_item">
                                    <?php echo Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . $product['image']); ?>
                                    <div class="kit_cont">
                                        <?php echo Html::a("<h2><br>" . $product['name'] . "</h2>", Url::to(['product/index', 'id' => $product['id']]))
                                        ?>
                                        <span><?php echo $product['short_description']; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="btn btn-big"><?= Yii::t('app', 'BUY') ?></button>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <?php $count = 0;
                    foreach ($popular as $pop) {
                        ?>
    <?php if ($count <= 2): ?>
                            <div class="col-md-4">
                                <div class="product_item daily popular_item box">
                                    <div class="pop_line">
                                        <div class="small_status sts_pop pull-left"><?= Yii::t('app', 'POPULAR'); ?></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="row text-center">
                                        <a href="<?php echo Url::to(['product/view', 'id' => $pop['id']]) ?>">
        <?php echo Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/' . $pop['image']); ?>
                                        </a>
                                    </div>
                                    <h2 class="product_title"><?= $pop['name'] ?></h2>
                                </div>
                            </div>
                            <?php $count++;
                        endif;
                        ?>
                <?php } ?>

                </div>
                <?php
                $provider = new ArrayDataProvider([
                    'allModels' => $products,
                    'pagination' => [
                        'pageSize' => 9,
                    ],
                    'sort' => [
                        'attributes' => ['id', 'name'],
                    ],
                ]);
                $rows = $provider->getModels();
                ?>

<?= $this->render('/product/forms/products-grid-view', ['products' => $rows, 'currency_details' => $currency_details, 'provider' => $provider]) ?>

            </div>
        </div>
    </div>
</section>
<?php
$this->registerJs('
     var owl = $("#owl-demo");
        owl.owlCarousel({
            items : 3, //10 items above 1000px browser width
            itemsDesktop : [1000,3], //5 items between 1000px and 901px
            itemsDesktopSmall : [900,2], // 3 items betweem 900px and 601px
            itemsTablet: [567,2], //2 items between 600 and 0;
            itemsTablet: [480,1],
            itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
        });

        $(".next").click(function(){
            owl.trigger("owl.next");
        })
        $(".prev").click(function(){
            owl.trigger("owl.prev");
        })
');
$this->registerJs('
    $(window).scroll(function() {
        if ($(this).scrollTop() > 1){
            $("header").addClass("sticky");
        }
        else{
            $("header").removeClass("sticky");
        }
    });
');
$this->registerJs('
    if (navigator.appVersion.indexOf("Chrome/") != -1) {
    }
');
?>