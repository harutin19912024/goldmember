<?php
use yii\data\ArrayDataProvider;
$this->title = Yii::t('app','Mobile-Centre.Shop').' | '.Yii::t('app','Products');
use frontend\models\Attribute;
use backend\models\Slider;
$attributes = Attribute::findList();
$sliders = Slider::find()->where(['status'=>1])->asArray()->all();
?>
<?php $view_type = @$_COOKIE['view']?>

<section id="content">
    <div class="container top">
	<div class="content_top">
        <div class="wrapper_w">
          <div class="pull-right">
            <div class="breadcrumbs"><a href="/"><?=Yii::t('app','Home')?></a> <span>&#8250;</span> <a href="#"><?=Yii::t('app','Products')?></a> <span>&#8250;</span> <a href="#">Khaki Long Sleeve Sweater</a></div>
          </div>
        </div>
      </div>
      <div class="row" style="border-top: 2px solid rgb(237, 237, 238);">
        <div class="span9" id="column_right">
          <section class="slider">
            <div class="flexslider small">
              <ul class="slides">
			  <?php foreach ($sliders as $slider) : ?>
                <li> <a href="#"><img src="<?php echo Yii::$app->params['adminUrl'] .'uploads/images/slider/'.$slider['id'].'/thumbnailproduct/'.$slider['path']; ?>" alt="" /></a> </li>
				<?php endforeach; ?>
              </ul>
            </div>
          </section>
         <div class="listing_header_row1">
			<?= $this->render('forms/product-filtr', ['active' => $view_type]) ?>
          </div>
          <div class="line1"></div>
          <div class="listing_header_row2">
            <div class="pull-left">Items <strong>1</strong> to <strong>9</strong> of <strong>15</strong> total</div>
            <div class="pull-right">
              <div class="num"><a href="#">1</a> <a href="#">2</a> <a href="#" class="small_icon"><i class="icon-right-thin"></i></a></div>
            </div>
          </div>
          <h2>Listing</h2>
          <div class="listing_header_row1">
		  <?php
                    $provider = new ArrayDataProvider([
                        'allModels' => $products,
                        'pagination' => [
                            'pageSize' => 12,
                        ],
                        'sort' => [
                            'attributes' => ['id', 'name'],
                        ],
                    ]);
                    $rows = $provider->getModels();
					
                ?>
			 <?php if($view_type != "list"): ?>
                    <?= $this->render('forms/product-grid-view-all', [
                        'products' => $rows,
                        'active' => $active,
                        'page'=>$page,
                        'provider'=>$provider
                        ]);
                    ?>
                <?php else:?>
                    <?= $this->render('forms/products-list-view', [
                        'products' => $rows,
                        'active' => $active,
                        'page'=>$page,
                        'provider'=>$provider
                    ]) ?>
					<?php endif;?>
          </div>
          <div class="line1"></div>
          <div class="listing_header_row2">
            <div class="pull-left">Items <strong>1</strong> to <strong>9</strong> of <strong>15</strong> total</div>
            <div class="pull-right">
              <div class="num"><a href="#">1</a> <a href="#">2</a> <a href="#" class="small_icon"><i class="icon-right-thin"></i></a></div>
            </div>
          </div>
        </div>
        <div class="span3" id="column_left">
          <div class="row">
            <div class="span3">
			
				<div class="block">
					<div class="block-title"><strong><span>Price</span></strong></div>
					<div class="block-content">
						<div id="price_slider_wrapper">
							<div id="noUiSlider" class="noUiSlider"></div>
							<div id="valueInput"> </div>
						</div>
					</div>
				</div>
              <div class="block_listing">
                <div class="block block-layered-nav">
                  <div class="block-title"><strong><span>Listing</span></strong></div>
                  <div class="block-content">
                    <dl id="narrow-by-list">
                      <dt class="odd"><?=Yii::t('app','Attributes')?></dt>
                      <dd class="odd">
                        <ol>
							<?php foreach($attributes as $atribute):?>
							<li><a href="#"><?=$atribute['name']?></a>(6)</li>
							<?php endforeach;?>
                        </ol>
                      </dd>
                      <?= $this->render('forms/brand-form', ['brands' => $brands,'cat_id'=>$cat_id,'checked_brands'=>$checked_brands]) ?>
                      <?= $this->render('forms/special-offer', ['checked_special'=>$checked_special]) ?>
                    </dl>
                  </div>
                </div>
              </div>
              <div class="block">
                <div class="block-title"><strong><span>POPULAR TAGS</span></strong></div>
                <div class="block-content"><img src="/img/tag_cloud.png" width="256" height="153" alt=""></div>
              </div>
            </div>
			
            <div class="span3">
              <div class="banners_outer">
                <div class="flexslider banners">
                  <ul class="slides">
                    <li> <a href="#"><img src="/img/banner1.jpg" alt="" ></a> </li>
                    <li> <a href="#"><img src="/img/banner2.jpg" alt="" ></a> </li>
                    <li> <a href="#"><img src="/img/banner3.jpg" alt="" ></a> </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div id="push"></div>
