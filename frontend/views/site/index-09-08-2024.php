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
use backend\models\Team;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$sliders = Slider::find()->where(['status' => 1])->asArray()->all();
$aboutImage = Files::find()->where(['category'=>'about'])->one();
$aboutUs = Aboutus::find()->one();
$products = Product::findList(['limit' => 6]);
$bestOffers = Product::findList(['best_offer' => 1]);
$team = Team::find()->all();
$settings = Sitesettings::find_One();
$settings = $settings[0];
$this->title = Yii::t('app', 'Vin Radar') . ' | ' . Yii::t('app', 'Home');
$product = new Product();
?>

<div id="welcome">
    <div id="parallax1" class="parallax">
        <?php
                    $imagePath = ($aboutImage->faxbid_or_vinradar) ? Yii::$app->params['adminUrl'] : Yii::$app->params['faxBid'];
                ?>
        <div class="parallax-bg" style="background-image:url(<?=$imagePath . 'uploads/images/slider/' . $sliders[0]['id'].'/'.$sliders[0]['path']; ?>)"></div>
        <div class="parallax-content">
            <div class="container">
            

            </div>
        </div>
    </div>
</div>


<div id="intro">
    <div class="container">
        <div class="booking-wrapper" style="opacity:0.9">
        <div class="booking">
            <?php $form = ActiveForm::begin(['method' => 'get','action' => '/search', 'options' => [
                'class' => 'form1'
             ]]); ?>
             <div class="row">
              <div class="col6" style="padding:14px">
                    <input type="text" class="form-control" onkeyup="checkInput(this)" oncut="checkInputOnCut()" onpaste="checkInputOnPast()" name="vin" placeholder="Search by Vin">
                    <button class="btn btn-default" disabled id="searchByVin" style="float: right;position: relative;top: -34px;"><?=Yii::t('app', 'Search by VIN') ?></button>
                  </div>
                  </div>
            <div class="row">
              <div class="col1">
                <div class="select1_wrapper">
                  <div class="select1_inner">
                    <select class="select2 select" style="width: 100%" name="model_id">
                      <option value="0">Model</option>
                      <?php $models = $product->getAllModels();?>
                      <?php foreach($models as $key=>$model):?>
                      <option value="<?=$key?>"><?=$model?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col1">
                <div class="select1_wrapper">
                  <label>Model</label>
                  <div class="select1_inner">
                    <select class="select2 select" style="width: 100%" name="mark_id">
                      <option value="0">Mark</option>
                      <?php $marks = $product->getAllMarks();?>
                      <?php foreach($marks as $markId => $mark):?>
                      <option value="<?=$markId?>"><?=$mark?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col1">
                <div class="select1_wrapper">
                  <div class="select1_inner">
                    <select class="select2 select" style="width: 100%" name="year">
                      <option value="0">Year</option>
                      <?php 
                        $firstYear = (int)date('Y') - 20;
                        $lastYear = $firstYear + 20;
                        for($i=$firstYear;$i<=$lastYear;$i++)
                        {
                            echo '<option value='.$i.'>'.$i.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              
               <div class="col3">
                <button type="submit" class="btn-default btn-form1-submit"><span><?=Yii::t('app', 'SEARCH THE VEHICLE') ?></span></button>
              </div>
            </div>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
<div style="margin-top: 80px;"></div>
        <div class="row">
            <?php foreach($products as $product):?>
            <div class="col-sm-4">
                <div class="thumb1">
                    <div class="thumbnail clearfix">
                        <figure>
                            <a href="<?=$product['category_route_name']?>/<?=$product['route_name']?>">
                                <?php
                                    $imagePath = ($product['faxbid_or_vinradar']) ? Yii::$app->params['adminUrl'] : Yii::$app->params['faxBid'];
                                ?>
                                <?php echo Html::img($imagePath . 'uploads/images/' . $product['image'], ['class' => 'img-responsive']); ?>
                            </a>
                        </figure>
                        <div class="caption">
                            
                            <div class="txt2"><?=$product['name']?></div>
                            <div class="txt3"><?=$product['short_description']?></div>
                            <div class="link"><a href="<?=$product['category_route_name']?>/<?=$product['route_name']?>" class="btn-default btn1"><span>
                                <?=Yii::t('app', 'READ MORE')?></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>


    </div>
</div>
<div id="best">
    <div class="container">
        <div class="title1"><span><?=Yii::t('app', 'Latest Cars')?></span></div>
        <div class="tabs1">
            <div class="tabs1_content">
                <div id="tabs1-1">

                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="row">
                                <?php foreach($bestOffers as $product):?>
                                <div class="col-sm-4">
                                    <div class="thumb2">
                                        <div class="thumbnail clearfix">
                                            <figure>
                                                <a href="<?=$product['category_route_name']?>/<?=$product['route_name']?>">
                                <?php
                                    $imagePath = ($product['faxbid_or_vinradar']) ? Yii::$app->params['adminUrl'] : Yii::$app->params['faxBid'];
                                ?>
                                <?php echo Html::img($imagePath . 'uploads/images/' . $product['image'], ['class' => 'img-responsive']); ?>
                            </a>
                                            </figure>
                                            <div class="caption">
                                                <div class="txt1"><?=$product['name']?></div>
                                                <div class="txt2"><?=$product['short_description']?></div>
                                                <div class="info clearfix">
                                                    <span class="speed"><?=$product['mileage']?></span>
                                                </div>
                                                <div class="txt3">
                                                     <?=$product['year']?> • <?php echo $product['transmission'] ? 'Automatic': 'Manual'; ?> • <?=$product['extColorName']?> • <?=$product['mileage']?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<div id="welcome">
    <div id="parallax1" class="parallax">
            <?php
                    $imagePath = ($aboutImage->faxbid_or_vinradar) ? Yii::$app->params['adminUrl'] : Yii::$app->params['faxBid'];
            ?>
        <div class="parallax-bg" style="background-image:url(<?=$imagePath . 'uploads/images/about/' . $aboutUs['id'].'/'.$aboutImage->path; ?>)"></div>
        <div class="parallax-content">
            <div class="container">
                
                <div class="logo-s">
                    
                    </div>

                <div class="txt1"><?=$aboutUs['title']?></div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="txt2"><?=$aboutUs['description']?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="txt3"><?=$aboutUs['short_description']?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="info">
    <div class="info-wrapper">
        <div class="container">
            <div class="info-inner">
                <div class="info1">
                    <div class="info1-inner animated" data-animation="fadeInDown" data-animation-delay="200">
                        <img src="images/ic1.png" alt="" class="img1">
                        <div class="caption">
                            <div class="txt1"><span class="animated-number" data-duration="2000"
                                                    data-animation-delay="0">1250</span></div>
                            <div class="txt2">NEW CARS IN STOCK</div>
                        </div>
                    </div>
                </div>
                <div class="info1">
                    <div class="info1-inner animated" data-animation="fadeInDown" data-animation-delay="250">
                        <img src="images/ic2.png" alt="" class="img1">
                        <div class="caption">
                            <div class="txt1"><span class="animated-number" data-duration="2000"
                                                    data-animation-delay="0">2120</span>+
                            </div>
                            <div class="txt2">USED CARS IN STOCK</div>
                        </div>
                    </div>
                </div>
                <div class="info1">
                    <div class="info1-inner animated" data-animation="fadeInDown" data-animation-delay="300">
                        <img src="images/ic3.png" alt="" class="img1">
                        <div class="caption">
                            <div class="txt1"><span class="animated-number" data-duration="2000"
                                                    data-animation-delay="0">9753</span></div>
                            <div class="txt2">HAPPY CUSTOMERS</div>
                        </div>
                    </div>
                </div>
                <div class="info1">
                    <div class="info1-inner animated" data-animation="fadeInDown" data-animation-delay="350">
                        <img src="images/ic4.png" alt="" class="img1">
                        <div class="caption">
                            <div class="txt1"><span class="animated-number" data-duration="2000"
                                                    data-animation-delay="0">1022</span></div>
                            <div class="txt2">CAR SPARE PARTS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
