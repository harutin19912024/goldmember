<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\Product */

use frontend\models\Product;
use yii\helpers\Url;
use yii\helpers\Html;
$images = Product::getImages($model->id);
$this->title = Yii::t('app', 'Vin Radar') . ' | ' . Yii::t('app', $model->name);

?>
<div id="content">
  <div class="container">

    <div class="row">
      <div class="col-sm-12 col-md-9 column-content">

        <div class="gslider-wrapper">
          <div id="gslider" class="flexslider">
            <ul class="slides">
                <?php foreach($images as $imageId => $image): ?>
                <?php
                    $imagePath = ($model->faxbid_or_vinradar) ? Yii::$app->params['adminUrl'] : Yii::$app->params['faxBid'];
                ?>
              <li>
                <img src="<?=$imagePath . 'uploads/images/'.$image; ?>" alt="" class="img-responsive">
              </li>
              <?php endforeach;?>
            </ul>
          </div>
          <div id="carousel" class="flexslider">
            <ul class="slides">
                <?php foreach($images as $imageId => $image): ?>
                <?php
                    $imagePath = ($model->faxbid_or_vinradar) ? Yii::$app->params['adminUrl'] : Yii::$app->params['faxBid'];
                ?>
              <li>
                <img src="<?=$imagePath . 'uploads/images/'.$image; ?>" alt="" class="img-responsive">
              </li>
              <?php endforeach;?>
            </ul>
          </div>
        </div>

        <div class="tabs2-wrapper">
          <ul class="tabs clearfix" data-tabgroup="second-tab-group">
            <li><a href="#tabs3-1">VEHICLE OVERVIEW</a></li>
          </ul>
        </div>

        <div id="second-tab-group" class="tabgroup">
          <div id="tabs3-1" class="active" style="display: block;">
                  <?=$model->description?>
                  </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-3 column-sidebar">



        <div class="banner2-wrapper">
          <div class="banner2">
            <div class="top-info clearfix">
              <div class="info1">
                <div class="txt1"><?=$model->name?></div>
              </div>
            </div>
            <div class="txt3">Year : <?=$model->year?></div>
            <div class="txt4">VIN : <?=$model->vin?></div>
            <?php if(!empty($model->engine)):?><div class="txt4">Engine : <?=$model->engine->name?></div><?php endif;?>
            <?php if(!empty($model->damage)):?><div class="txt4">Damage : <?=$model->damage->name?></div><?php endif;?>
            <?php if(!empty($model->extColor)):?><div class="txt4">Exterior Color : <?=$model->extColor->name?></div><?php endif;?>
            <div class="txt5"><?=$model->short_description?></div>
            
          </div>
        </div>
      </div>

    </div>

  </div>
</div>