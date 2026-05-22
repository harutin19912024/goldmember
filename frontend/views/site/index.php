<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\components\CurrencyHelper;
use yii\data\ArrayDataProvider;
use backend\models\Slider;
use backend\models\Files;
use backend\models\Sitesettings;
use frontend\models\Category;
use frontend\models\Product;
use backend\models\Aboutus;
use backend\models\TrAboutus;
use backend\models\Team;
use backend\models\News;
use backend\models\ExchangeRates;
use backend\models\MetalPrices;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\db\Query;
use common\models\MetalPriceReal;
// Detection\MobileDetect removed — PHP 8.2 type incompatibility; use CSS/srcset instead
use common\components\UserComponent;
use common\models\Language;


// Mobile detection via CSS (media queries) — no PHP needed

$language = Language::find()->where(['short_code' => Yii::$app->language])->one();

$sliders = Slider::find()->where(['status' => 1])->asArray()->all();
$aboutImage = Files::find()->where(['category'=>'about'])->one();
$aboutUs = TrAboutus::find()->where(['language_id' => $language->id])->asArray()->one();



$regitserdUsers = UserComponent::getRegisteredUsersCount();

$products = Product::findList(['limit' => 6]);
$bestOffers = Product::findList(['best_offer' => 1]);
$director = Team::find()->where(['is_director' => 1])->one();
$directorImage = Yii::$app->params['adminUrl']. 'uploads/images/team/' . $director->id .'/thumb/'. $director->image;
$settings = Sitesettings::find_One();

$settings = $settings[0];
$product = new Product();

$newses = News::find()->limit(15)->all();

$exchanges = ExchangeRates::find()
    ->where(['between', 'updated_at', date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])
    ->all();


// $subQuery = (new Query())
//     ->select([
//         '*',
//         'ROW_NUMBER() OVER (PARTITION BY metal_id ORDER BY created_at DESC) AS row_num'
//     ])
//     ->from('metal_prices');

$karats = ['999', '995', '750', '875', '585'];
$startOfDay = date('Y-m-d 00:00:00');
$endOfDay = date('Y-m-d 23:59:59');
$metalPrices = MetalPrices::find()
    ->where(['karat' => $karats])
    ->andWhere(['between', 'created_at', $startOfDay, $endOfDay])
    ->with(['metal', 'currency'])
    ->orderBy(['karat' => SORT_DESC])
    ->all();
    
//echo "<pre>"; print_r($director);die;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Home');

?>
<?php
$responseData = [];
$price = [];
$metalPriceApiData = MetalPriceReal::find()
        ->orderBy(['created_date' => SORT_DESC])
        ->one();
    if(!empty($metalPriceApiData) && empty($metalPriceApiData->request_error)) {
        // request_data is stored as a JSON string; decode if needed.
        $price = is_string($metalPriceApiData->request_data)
            ? (json_decode($metalPriceApiData->request_data, true) ?: [])
            : ($metalPriceApiData->request_data ?: []);
    }
    if (!empty($price['bid']) && !empty($price['ask'])) {
        $pricePerGram = round($price['bid'] / 31.1035, 4);
        $sell = round($price['ask'] / 31.1035, 4);
        
        $responseData['999']['buy'] = $pricePerGram * 0.999;
        $responseData['999']['sell'] = $sell * 0.999;

        $responseData['995']['buy'] = $pricePerGram * 0.995;
        $responseData['995']['sell'] = $sell * 0.995;
        
        $responseData['875']['buy'] = $pricePerGram * 0.875;
        $responseData['875']['sell'] = $sell * 0.875;
        
        $responseData['750']['buy'] = $pricePerGram * 0.750;
        $responseData['750']['sell'] = $sell * 0.750;
        
        $responseData['585']['buy'] = $pricePerGram * 0.585;
        $responseData['585']['sell'] = $sell * 0.585;
       
    }
        
?>

<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100 img-div">
        <!-- Use srcset for responsive image — avoids PHP MobileDetect dependency -->
        <img class="w-100 h-100"
             src="/images/home-page.jpeg"
             srcset="/images/home-page-small.jpg 768w, /images/home-page.jpeg 1442w"
             sizes="(max-width: 768px) 768px, 1442px"
             alt="Banner Image" height="400" width="1442" loading="eager"/>
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-4 col-12 px-0">
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title"><?=Yii::t('app', 'In gold we trust')?>!!!</h1>
            <p class="text-content fs-4 pb-1 white-color fw-normal mb-5 h-base ">
                <?=Yii::t('app', 'Get the best prices for buying and selling gold with trust and transparency')?>.<br><?=Yii::t('app', 'Visit us today')?>!</p>
        </div>
    </div>
</section>
<section class="bg-white-color quote-section">
    <div class="container row px-3 px-md-0 mx-auto">
        <h3 class="quote-title px-0"><?= Yii::t('app', 'Welcome to Goldmember'); ?></h3>

        <div class="shadow row align-items-center px-0 mx-auto content-wrapper">
            <div class="col-md-3 px-2 text-md-end mb-md-0 mb-4 text-center">
                <img src="<?=$directorImage?>" alt="<?=Yii::t('app', 'Albert Papikyan')?>" class="img-fluid">
            </div>
            <div class="col-md-9 content">
                <div>
                    <div class="text-content"><?=$aboutUs['short_description']?></div>

                    <div class="d-flex justify-content-between align-content-center quote-person-info">
                        <div>
                            <strong class="quote-person-name"><?=Yii::t('app', $director->fname. ' ' .$director->sname)?></strong>
                            <span class="quote-person-position"><?=Yii::t('app', 'CEO')?></span>
                        </div>
                        <img class="quote-icon" src="/images/icons/quote.svg" alt="Quote">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container px-3 px-md-0">
    <div class="row text-center  cards-section">
        <div class="col-md-4 pb-md-0 pb-4">
            <a href="/<?=Yii::$app->language?>/power-of-pany" class="bg-primary-color text-white d-flex justify-content-center card-content shadow">
                <div class="card-icon">
                    <img src="/images/icons/money.svg" alt="Icon">
                </div>
                <div>
                    <h5 class="card-title"><?= Yii::t('app', 'Power of penny'); ?></h5>
                    <p class="card-description text-start"><?= Yii::t('app', 'Support us with donation'); ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-4 pb-md-0 pb-4">
            <a href="/<?=Yii::$app->language?>/auction" class="bg-primary-color text-white d-flex justify-content-center card-content shadow">
                <div class="card-icon">
                    <img src="/images/icons/auction.svg" alt="Icon">
                </div>
                <div>
                    <h5 class="card-title"><?=Yii::t('app', 'Auction') ?></h5>
                    <p class="card-description text-start"><?= Yii::t('app', 'Bid now for a chance to own'); ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-4 pb-md-0 pb-4">
            <a href="/<?=Yii::$app->language?>/best-offer"  class="bg-primary-color text-white d-flex justify-content-center card-content shadow">
                <div class="card-icon">
                    <img src="/images/icons/offer.svg" alt="Icon">
                </div>
                <div>
                    <h5 class="card-title"><?=Yii::t('app', 'Best Offer'); ?></h5>
                    <p class="card-description text-start"><?= Yii::t('app', 'Best deal! Act now!'); ?></p>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="position-relative price-news-section overflow-hidden">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100 background-image">
        <img class="w-100 h-100" src="/images/black.jpg" alt="Banner Image" height="920" width="1440"/>
    </div>
    <div class="container px-0">
        <ul class="row text-center primary-color overview-cards px-2">
            <li class="col-md-3 overview-card">
                <h2 class="overview-title primary-alternate-color"><?= $onlineNow ?></h2>
                <p class="overview-content"><?= Yii::t('app', 'Online Now'); ?></p>
            </li>
            <li class="col-md-3 overview-card">
                <h2 class="overview-title primary-alternate-color"><?= $registeredUsers ?></h2>
                <p class="overview-content"><?= Yii::t('app', 'Registered Clients'); ?></p>
            </li>
            <li class="col-md-3 overview-card">
                <h2 class="overview-title primary-alternate-color"><?= $todaysVisits ?></h2>
                <p class="overview-content"><?= Yii::t('app', 'Today\'s Visits'); ?></p>
            </li>
            <li class="col-md-3 overview-card">
                <h2 class="overview-title primary-alternate-color"><?= $totalVisits ?></h2>
                <p class="overview-content"><?= Yii::t('app', 'Total Visits'); ?></p>
            </li>
        </ul>
        <div class="row">
<!--            <div id="canvas-wrapper">-->
<!--                <canvas id="demo-canvas"></canvas>-->
<!--            </div>-->
            <!-- Top News Section -->
            <div class="col-md-5 px-3 px-md-0">
                <div class="card shadow text-white bg-black-color news-card">
                    <h5 class="news-headline primary-alternate-color"><?=Yii::t('app', 'TOP NEWS');?></h5>
                    <?php if(!empty($newses)):?>
                    <ul id="newsFeed">
                        <?php foreach($newses as $news):?>
                        <?php
                        $imagePath = Yii::$app->params['adminUrl']. 'uploads/images/news/thumbnail/' . $news->id .'/'. $news->newsImages[0]->name;
                        ?>
                        <li class="news-item">
                            <a class="d-flex" href="<?=Yii::$app->language?>/news/<?=$news->id?>">
                                <div class="news-item-image img-box">
                                    <img src="<?=$imagePath?>" alt="News" class="img-fluid" id="zoomImage_<?=$news->newsImages[0]->id?>" onmouseover="zoomIn(<?=$news->newsImages[0]->id?>)" onmouseout="zoomOut(<?=$news->newsImages[0]->id?>)" id="zoomImage_<?=$news->newsImages[0]->id?>">
                                </div>
                                <div class="text">
                                    <h3 class="news-item-title"><?=$news->title?></h3>
                                    <p class="news-item-text"><?=$news->short_description?></p>
                                </div>
                            </a>
                        </li>
                        <?php endforeach;?>
                    </ul>
                    <?php endif;?>
                    <a href="/<?=Yii::$app->language?>/news/all" class="info-text"><?=Yii::t('app', 'Show more')?> <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>

            <!-- Local Prices Table -->
            <div class="col-md-7 ps-4 ">
                <div class="card shadow bg-black-color table-nav-box">
                    <ul class="nav nav-tabs">
                        <li class="nav-item ac">
                            <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#localPrice" type="button" role="tab" aria-controls="home" aria-selected="true" href="#localPrice">
                                <?=Yii::t('app', 'Local Prices')?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-bs-toggle="tab" data-bs-target="#globalPrice" type="button" role="tab" aria-controls="home" aria-selected="true" href="#globalPrice">
                                <?=Yii::t('app', 'Global Prices')?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#exchangeRates" type="button" role="tab" aria-controls="home" aria-selected="true" href="#exchangeRates">
                                <?=Yii::t('app', 'Exchange Rates')?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#trade" data-bs-toggle="tab" data-bs-target="#trade" type="button" role="tab" aria-controls="home" aria-selected="true">
                                <?=Yii::t('app', 'Trade')?></a>
                        </li>
                    </ul>
                    <div class="table primary-color active" id="localPrice" role="tabpanel" aria-labelledby="home-tab">
                        <div class="table-header">
                            <div><?=Yii::t('app', 'Metal')?></div>
                            <div><?=Yii::t('app', 'Karat')?></div>
                            <div><?=Yii::t('app', 'Sell')?></div>
                             <div><?=Yii::t('app', 'Buy')?></div>
                            <div><?=Yii::t('app', 'Change')?></div>
                        </div>
                        <div class="table-body">
                           <?php foreach($metalPrices as $price):?>
                            <div class="table-row">
                                <div><?=$price->metal->name?></div>
                                <div><?=$price->karat?></div>
                                <div><?=round($price->sell_price, 2)?></div>
                                <div><?=round($price->buy_price, 2)?></div>
                                <div><span class="text-danger">↓</span> 0.3</div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="table primary-color" id="exchangeRates" role="tabpanel" aria-labelledby="home-tab">
                        <div class="table-header">
                            <div><?=Yii::t('app', 'Currency')?></div>
                            <div><?=Yii::t('app', 'Buy')?></div>
                            <div><?=Yii::t('app', 'Sell')?></div>
                        </div>
                        <div class="table-body">
                            <?php foreach($exchanges as $exchange):?>
                            <div class="table-row">
                                <div><?=$exchange->currency->name?></div>
                                <div><?=$exchange->buy_rate?></div>
                                <div><?=$exchange->sell_rate?></div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="table primary-color" id="globalPrice" role="tabpanel" aria-labelledby="global-tab">
                        <div class="table-header">
                            <div><?=Yii::t('app', 'Metal')?></div>
                            <div><?=Yii::t('app', 'Karat')?></div>
                             <div><?=Yii::t('app', 'Buy')?></div>
                            <div><?=Yii::t('app', 'Sell')?></div>
                            <div><?=Yii::t('app', 'Change')?></div>
                        </div>
                        <div class="table-body">
                           <?php foreach($responseData as $key => $price):?>
                            <div class="table-row">
                                <div><?=Yii::t('app', 'Gold')?></div>
                                <div><?=$key?></div>
                                <div><?=round($price['buy'], 2)?></div>
                                <div><?=round($price['sell'], 2)?></div>
                                <div><span class="text-danger">↓</span> 0.3</div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="table primary-color" id="trade" role="tabpanel" aria-labelledby="global-tab">
                        <!-- TradingView chart: loaded lazily only when the Trade tab is active -->
                        <div id="tradingview-placeholder"
                             data-src="https://s.tradingview.com/kitco/widgetembed/?hideideas=1&overrides=%7B%7D&enabled_features=%5B%5D&disabled_features=%5B%5D&locale=en#%7B%22symbol%22%3A%22XAUUSD%22%2C%22frameElementId%22%3A%22tv_gold%22%2C%22interval%22%3A%221%22%2C%22hide_side_toolbar%22%3A%221%22%2C%22allow_symbol_change%22%3A%221%22%2C%22save_image%22%3A%220%22%2C%22theme%22%3A%22light%22%2C%22style%22%3A%221%22%2C%22timezone%22%3A%22America%2FNew_York%22%2C%22withdateranges%22%3A%221%22%7D"
                             style="width:100%;height:500px;display:flex;align-items:center;justify-content:center;background:#0a1818;color:#53E2D9;cursor:pointer;border-radius:4px;">
                            <div class="text-center">
                                <i class="bi bi-bar-chart-line" style="font-size:2.5rem;"></i>
                                <p class="mt-2 mb-0"><?= Yii::t('app', 'Click to load live gold chart') ?></p>
                            </div>
                        </div>
                    </div>

                    <a href="/<?=Yii::$app->language?>/more-details" class="info-text"><?=Yii::t('app', 'Show more')?> <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>
    </div>

</section>

<section class="contact-section position-relative" id="contact-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6  col-12 px-3 px-md-0">
                <div class="contact-box position-relative rounded bg-primary-color white-color">
                    <h4 class="text-white title"><?=Yii::t('app', 'Contact')?></h4>

                    <div class="contact-line">
                        <div class="info-box">
                            <i class="bi bi-geo-alt"></i>
                            <span><?=Yii::t('app', 'Address')?> :</span>
                        </div>
                        <a href="https://www.google.com/maps?q=37.7749,-122.4194" target="_blank">
                            <?=Yii::t('app', '20 Movses Khorenatsi Street, Yerevan 0018')?></a>
                    </div>

                    <div class="contact-line">
                        <div class="info-box">
                            <i class="bi bi-envelope"></i>
                            <span><?=Yii::t('app', 'Email')?>:</span>
                        </div>
                        <a href="mailto:info@goldmember.am">info@goldmember.am</a>
                    </div>

                    <div class="contact-line">
                        <div class="info-box">
                            <i class="bi bi-telephone"></i>
                            <span><?=Yii::t('app', 'Telephone')?>:</span>
                        </div>
                        <a href="tel:+37494261605">(+374) 94 261 605</a>
                        <a href="tel:+37494261606">(+374) 94 261 606</a>
                    </div>

                    <div class="contact-line">
                        <div class="info-box">
                            <i class="bi bi-clock"></i>
                            <span><?=Yii::t('app', 'Hours open')?>:</span>
                        </div>
                        <p>9:00 - 18:00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Map -->

    <div class="map-background w-100 position-absolute" id="map-container"
         style="background:linear-gradient(135deg,#0d2020 0%,#071414 100%);cursor:pointer;"
         data-map-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3048.529914386416!2d44.506797176539166!3d40.17501897031453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x406abddabe0c3071%3A0x4aa102e32cb6e5f7!2sGoldmember!5e0!3m2!1sen!2sam!4v1730750217913!5m2!1sen!2sam"
         title="Click to load map">
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;color:#13B2AD;pointer-events:none;">
            <i class="bi bi-geo-alt" style="font-size:2rem;"></i>
            <p class="small mt-1" style="color:#53E2D9;">20 Movses Khorenatsi Street, Yerevan</p>
        </div>
    </div><!-- End Contact Form -->
</section>

<script>
// Lazy-load iframes on click (TradingView chart + Google Maps)
// Prevents ~250 extra network requests on page load
(function () {
    function lazyIframe(placeholderId, srcAttr, iframeAttrs) {
        var ph = document.getElementById(placeholderId);
        if (!ph) return;
        ph.addEventListener('click', function () {
            var src = ph.getAttribute(srcAttr);
            var iframe = document.createElement('iframe');
            Object.assign(iframe, iframeAttrs);
            iframe.src = src;
            iframe.style.cssText = 'width:100%;height:100%;border:0;';
            ph.parentNode.replaceChild(iframe, ph);
        }, { once: true });
    }

    lazyIframe('tradingview-placeholder', 'data-src', {
        title: 'Gold price chart', frameBorder: '0',
        allowTransparency: true, scrolling: 'no', allowFullscreen: true
    });

    // Map container
    var mc = document.getElementById('map-container');
    if (mc) {
        mc.addEventListener('click', function () {
            var src = mc.getAttribute('data-map-src');
            var iframe = document.createElement('iframe');
            iframe.src = src; iframe.allowFullscreen = true;
            iframe.setAttribute('loading', 'lazy');
            iframe.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
            iframe.style.cssText = 'width:100%;height:100%;border:0;';
            mc.innerHTML = '';
            mc.appendChild(iframe);
            mc.style.cursor = 'default';
        }, { once: true });
    }
})();
</script>
