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
use backend\models\News;
use backend\models\Exchange;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;



$news = News::findOne($id);


/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'News');
$imagePath = Yii::$app->params['adminUrl']. 'uploads/images/news/' . $news->id .'/'. $news->newsImagesPrimary->name;
?>

<!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1><?=Yii::t('app', $news->title)?></h1>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="/<?=Yii::$app->language?>">Home</a></li>
            <li class="current"><?=Yii::t('app', 'News Details')?></li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <div class="container">
      <div class="row">

        <div class="col-lg-12">

          <!-- Blog Details Section -->
          <section id="blog-details" class="blog-details section">
            <div class="container">

              <article class="article">

                <div class="post-img">
                  <img src="<?=$imagePath?>" alt="" class="img-fluid">
                </div>

                <h2 class="title"><?=$news->title?></h2>

                <div class="meta-top">
                  <ul>
                    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="blog-details.html"><time datetime="<?=date('M j, Y', strtotime($news->created_date))?>"><?=date('M j, Y', strtotime($news->created_date))?></time></a></li>
                  </ul>
                </div><!-- End meta top -->

                <div class="content"><?=$news->content?></div><!-- End post content -->
                
                <?php if($news->video_url != ''):?>
          <!-- Blog Author Section -->
          <section id="blog-author" class="blog-author section">

            <div class="container">
              <div class="author-container d-flex align-items-center">
                <div>
                  <h4><?=Yii::t('app', 'Video')?></h4>

                  <div>
                      <?=$news->video_url?>
                  </div>
                </div>
              </div>
            </div>

          </section><!-- /Blog Author Section -->
<?php endif;?>
                <div class="meta-bottom">
                  <i class="bi bi-folder"></i>
                  <ul class="cats">
                    <li><a href="/<?=Yii::$app->language?>/news/<?=$news->category->route_name ?>"><?=$news->category->name?></a></li>
                  </ul>
                </div><!-- End meta bottom -->

              </article>

            </div>
          </section><!-- /Blog Details Section -->

        </div>
      </div>
    </div>