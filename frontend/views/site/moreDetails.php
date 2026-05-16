<?php

use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\MetalPrices;
use common\models\MetalPriceReal;



$startOfDay = date('Y-m-d 00:00:00');
$endOfDay = date('Y-m-d 23:59:59');

// $metalPrices = MetalPrices::find()
//     ->where(['between', 'created_at', $startOfDay, $endOfDay])
//     ->with(['metal', 'currency'])
//     ->all();

$goldPrices = MetalPrices::find()
    ->where(['between', 'created_at', $startOfDay, $endOfDay])
    ->andWhere(['metal_id' => 1])
    ->with(['metal', 'currency'])
    ->orderBy(['created_at' => SORT_DESC])
    ->all();

//echo "<pre>"; print_r($goldPrices);die;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Trading');
?>
<?php
$responseData = [];
$metalPriceApiData = MetalPriceReal::find()
        ->orderBy(['created_date' => SORT_DESC])
        ->one();
    if(!empty($metalPriceApiData) && empty($metalPriceApiData->request_error)) {
        $price = $metalPriceApiData->request_data;
        //$pricePerGram = $price['price_gram_24k'];
        
        $pricePerGram = round($price['bid'] / 31.1035, 4);
        $sell = round($price['ask'] / 31.1035, 4);
        
        $responseData['999']['buy'] = $pricePerGram * 0.999;
        $responseData['999']['sell'] = $sell * 0.999;

        $responseData['995']['buy'] = $pricePerGram * 0.995;
        $responseData['995']['sell'] = $sell * 0.995;
        
        $responseData['750']['buy'] = $pricePerGram * 0.750;
        $responseData['750']['sell'] = $sell * 0.750;
        
        $responseData['585']['buy'] = $pricePerGram * 0.585;
        $responseData['585']['sell'] = $sell * 0.585;
        
        $responseData['958']['buy'] = $pricePerGram * 0.958;
        $responseData['958']['sell'] = $sell * 0.958;
        
        $responseData['916']['buy'] = $pricePerGram * 0.916;
        $responseData['916']['sell'] = $sell * 0.916;
        
        $responseData['900 - 21.6K']['buy'] = $pricePerGram * 0.900;
        $responseData['900 - 21.6K']['sell'] = $sell * 0.900;
        
        $responseData['875']['buy'] = $pricePerGram * 0.875;
        $responseData['875']['sell'] = $sell * 0.875;
        
        $responseData['500 - 12K']['buy'] = $pricePerGram * 0.500;
        $responseData['500 - 12K']['sell'] = $sell * 0.500;
        
        $responseData['416 - 10K']['buy'] = $pricePerGram * 0.416;
        $responseData['416 - 10K']['sell'] = $sell * 0.416;
        
        $responseData['375 - 9K']['buy'] = $pricePerGram * 0.375;
        $responseData['375 - 9K']['sell'] = $sell * 0.375;
        
        $responseData['333']['buy'] = $pricePerGram * 0.333;
        $responseData['333']['sell'] = $sell * 0.333;
            

    }




//$apiUrl = "https://api.metalpriceapi.com/v1/latest";
// $yesterday = date('Y-m-d', strtotime('-1 day'));
// $apiUrl = "https://api.metalpriceapi.com/v1/latest?api_key=328dbc54ea99eeba3e5ca4e64604cc19";
// $yesterdaysPrices = 'https://api.metalpriceapi.com/v1/'.$yesterday.'?api_key=328dbc54ea99eeba3e5ca4e64604cc19';
$metalInfo = [
        1 => 'Gold',
        2 => 'Silver',
        3 => 'Platinium',
        4 => 'Paladium'
    ];
$metals = [
         'Gold' => 'USDXAU', 'Silver' => 'USDXAG', 'Platinium' => 'USDXPD', 'Paladium' => 'USDXPT'
    ];

//$apiUrlYesterday = "https://api.metalpriceapi.com/v1/latest?api_key=328dbc54ea99eeba3e5ca4e64604cc19&base=XAU&currencies=USD";

// Fetch the data
// $response = file_get_contents($apiUrl);
// $data = json_decode($response, true);


// $responseYesterday = file_get_contents($yesterdaysPrices);
// $dataYesterday = json_decode($responseYesterday, true);
//echo "<pre>"; print_r($dataYesterday);die;

?>


<!-- section id="hero" class="hero section accent-background">
    <div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
        <div class="container position-relative">
          <div class="row gy-4 mt-5">

            <div class="col-xl-4 col-md-4 col-sm-3">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-gift"></i></div>
                <h4 class="title"><a href="/donate" class="stretched-link"><?=Yii::t('app', 'Donate')?></a></h4>
              </div>
            </div>

            <div class="col-xl-4 col-md-4 col-sm-3">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-hammer"></i></div>
                <h4 class="title"><a href="/auction" class="stretched-link"><?=Yii::t('app', 'Auction')?></a></h4>
              </div>
            </div>

            <div class="col-xl-4 col-md-4 col-sm-3">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-gem"></i></div>
                <h4 class="title"><a href="/briliant" class="stretched-link"><?=Yii::t('app', 'Diamond')?></a></h4>
              </div>
            </div>

          </div>
        </div>
      </div>
    
</section><!-- /Hero Section -->


    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1><?=Yii::t('app', 'Metals Prices')?></h1>
              <p class="mb-0"><?=Yii::t('app', 'PricesText')?></p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="/"><?=Yii::t('app', 'Home')?></a></li>
            <li class="current"><?=Yii::t('app', 'Metals Prices')?></li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->
    
    <section id="blog-posts" class="blog-posts section">

      <div class="container" style="box-shadow: 4px 40px 61px 20px;">
        <div class="row gy-4">
            
            <div class="gold-diagram">
                <h2><?=Yii::t('app', 'Local Prices')?></h2>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col"><?=Yii::t('app', 'Gold')?></th>
                      <th scope="col"><?=Yii::t('app','Buy')?></th>
                      <th scope="col"><?=Yii::t('app','Sell')?></th>
                    </tr>
                  </thead>
                      <tbody>
                           <?php foreach($goldPrices as $price):?>
                           <tr>
                              <th scope="row"><?=$price->karat?></th>
                              <td><?=$price->buy_price?></td>
                              <td><?=$price->sell_price?></td>
                            </tr>
                            <?php endforeach;?>
                      </tbody>
                </table>
                
                <h2><?=Yii::t('app', 'Global Prices')?></h2>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col"><?=Yii::t('app', 'Gold')?></th>
                      <th scope="col"><?=Yii::t('app','Buy')?></th>
                      <th scope="col"><?=Yii::t('app','Sell')?></th>
                    </tr>
                  </thead>
                      <tbody>
                           <?php foreach($responseData as $key=>$price):?>
                           <tr>
                              <th scope="row"><?=$key?></th>
                              <td><?=round($price['buy'], 2)?></td>
                              <td><?=round($price['sell'], 2)?></td>
                            </tr>
                            <?php endforeach;?>
                      </tbody>
                </table>
            

                <h2><?=Yii::t('app', 'Global Spot Prices')?></h2>
                <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col"><?=Yii::t('app', 'Metal Name')?></th>
                  <th scope="col"><?=Yii::t('app','Price')?></th>
                  <th scope="col"><?=Yii::t('app','+/-')?></th>
                  <th scope="col"><?=Yii::t('app','Date')?></th>
                </tr>
              </thead>
              <tbody>
                  <?php  if(!empty($data['rates'])):?>
                  <?php foreach($metals as $key => $metal):?>
                  <?php $price = round($data['rates'][$metal], 2); ?>
                <tr>
                  <th scope="row"><?=Yii::t('app', $key)?></th>
                  <td><?=$price?></td>
                  <td>
                     <?php if(isset($dataYesterday['rates'][$metal]) && $dataYesterday['rates'][$metal] > $data['rates'][$metal]): ?>
                        <span style="color: red;"><?php echo ceil(($dataYesterday['rates'][$metal] -  $data['rates'][$metal]) * 100) / 100;?></span>
                    <?php else:?>
                        <span style="color: green;">
                        <?php echo ceil(($data['rates'][$metal] - $dataYesterday['rates'][$metal]) * 100) / 100;?></span>
                    <?php endif;?>
                    </td>
                  <td><?=date('Y-m-d H:i:s', $data['timestamp'])?></td>
                </tr>
                <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            </div>
        </div>
      </div>
      
      <div class="container">
        <div class="row gy-4" style="margin-top: 390px;margin-right: -25px;margin-left: -25px;">
      <iframe title="advanced chart TradingView widget" lang="en" id="tradingview_6e6d6" frameborder="0" allowtransparency="true" scrolling="no" allowfullscreen="true" src="https://s.tradingview.com/kitco/widgetembed/?hideideas=1&amp;overrides=%7B%7D&amp;enabled_features=%5B%5D&amp;disabled_features=%5B%5D&amp;locale=en#%7B%22symbol%22%3A%22XAUUSD%22%2C%22frameElementId%22%3A%22tradingview_6e6d6%22%2C%22interval%22%3A%221%22%2C%22hide_side_toolbar%22%3A%221%22%2C%22allow_symbol_change%22%3A%221%22%2C%22save_image%22%3A%220%22%2C%22studies%22%3A%22%5B%5D%22%2C%22theme%22%3A%22light%22%2C%22style%22%3A%221%22%2C%22timezone%22%3A%22America%2FNew_York%22%2C%22withdateranges%22%3A%221%22%2C%22studies_overrides%22%3A%22%7B%7D%22%2C%22utm_source%22%3A%22www.kitco.com%22%2C%22utm_medium%22%3A%22widget_new%22%2C%22utm_campaign%22%3A%22chart%22%2C%22utm_term%22%3A%22XAUUSD%22%2C%22page-uri%22%3A%22www.kitco.com%2Fcharts%2Fgold%22%7D" style="width: 100%; height: 600px;"></iframe>
</div>
</div>
    </section><!-- /Blog Posts Section -->