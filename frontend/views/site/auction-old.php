<?php

use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Category;
use backend\models\Auction;

$auctions = Auction::find()->limit(12)->all();



//echo "<pre>"; print_r($teams);die;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Auction');
?>
    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1><?=Yii::t('app', 'Auction')?></h1>
              <p class="mb-0"><?=Yii::t('app', 'AuctionText')?></p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.html"><?=Yii::t('app', 'Home')?></a></li>
            <li class="current"><?=Yii::t('app', 'Auction')?></li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Blog Posts Section -->
    <section id="blog-posts" class="blog-posts section">

      <div class="container">
        <div class="row gy-4">
            
            <?php foreach($auctions as $auction):?>
            <?php
                $product = $auction->product;
                if($product->image) {
                    $imagePath = Yii::$app->params['adminUrl']. 'uploads/images/' . $product->image->name;
                }
            ?>

          <div class="col-lg-4">
            <article>

              <div class="post-img">
                <img src="<?=$imagePath?>" alt="" class="img-fluid" onmouseover="zoomIn(<?=$product->image->id?>)" onmouseout="zoomOut(<?=$product->image->id?>)" id="zoomImage_<?=$product->image->id?>">
              </div>

              <p class="post-category"><?=$product->category->name?></p>

              <h2 class="title">
                <a href="/auction/<?=$auction->id?>/<?=$product->id?>"><?=$product->title?></a>
              </h2>

              <div class="d-flex align-items-center">
                <div class="post-meta">
                  <p class="post-author">Current Bid at: <span style="font-size: larger;"><?=$auction->start_price?></span></p>
                  <p class="post-date" style="font-weight:600;font-size:larger">Start Date: 
                    <time datetime="2022-01-01"><?=date('Y-m-d', strtotime($auction->start_date))?></time>
                  </p>
                </div>
              </div>
              <div class="d-flex justify-content-between mb-2">
              <p class="text-muted mb-0">Rating: </p>
              <div class="ms-auto text-warning">
                <i class="bi bi-star"></i>
                <i class="bi bi-star"></i>
                <i class="bi bi-star"></i>
                <i class="bi bi-star"></i>
                <i class="bi bi-star"></i>
              </div>
            </div>
              <hr>
             <div class="d-flex align-items-center">
                 <img src="assets/img/blog/blog-author-2.jpg" alt="" class="img-fluid post-author-img flex-shrink-0">
                <div class="post-meta">
                  <a href="<?php if (Yii::$app->user->isGuest):?>/signup<?php else:?>/auction/<?=$auction->id?><?php endif;?>" class="btn btn-danger btn-lg"><?=Yii::t('app', 'Bid Now')?></a>
                  <a href="/auction/<?=$auction->id?>/<?=$product->id?>" class="btn btn-info btn-lg"><?=Yii::t('app', 'View')?></a>
                </div>
              </div>

            </article>
          </div><!-- End post list item -->
          <?php endforeach;?>

        </div>
      </div>

    </section><!-- /Blog Posts Section -->

    <!-- Blog Pagination Section -->
    <section id="blog-pagination" class="blog-pagination section">

      <div class="container">
        <div class="d-flex justify-content-center">
          <ul>
            <li><a href="#"><i class="bi bi-chevron-left"></i></a></li>
            <li><a href="#">1</a></li>
            <li><a href="#" class="active">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li>...</li>
            <li><a href="#">10</a></li>
            <li><a href="#"><i class="bi bi-chevron-right"></i></a></li>
          </ul>
        </div>
      </div>

    </section><!-- /Blog Pagination Section -->