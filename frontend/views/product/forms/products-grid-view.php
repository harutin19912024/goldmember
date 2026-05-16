<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\components\CurrencyHelper;
use frontend\models\Category;
use frontend\models\Product;
$product = new Product();
?>

      <div class="col-sm-12 col-md-3 col-md-pull-3-l column-sidebar">

        <div class="sidebar-form-wrapper">
          <div class="sidebar-form">
            <?php $form = ActiveForm::begin(['method' => 'get','action' => '/search', 'options' => [
                'class' => 'form2'
             ]]); ?>

              <div class="select1_wrapper">
                <label>SELECT A Model</label>
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

              <div class="select1_wrapper">
                <label>SELECT A Mark</label>
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

              <div class="select1_wrapper">
                <label>SELECT A Year</label>
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
              <button type="submit" class="btn-default btn-form2-submit">SUBMIT FILTERS</button>

            <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-9 col-md-push-9-l column-content">
<?php if (!empty($products)): ?>
        <div id="first-tab-group" class="tabgroup">
          <div id="tabs2-1" class="active" style="display: block;">
		  
		  <?php foreach ($products as $key => $product) : ?>
		    <?php $catRout = frontend\models\Category::getCatRout($product['category_id']); ?>
			<div class="car-view1-wrapper">
              <div class="car-view1 clearfix">
                <figure>
                    <?php
                                    $imagePath = ($product['faxbid_or_vinradar']) ? Yii::$app->params['adminUrl']: Yii::$app->params['faxBid'];
                                ?>
				<img src="<?= $imagePath . 'uploads/images/' . $product['image'] ?>" alt="" class="img-responsive">
				</figure>
                <div class="caption">
                  <div class="top-info clearfix">
                    <div class="info1">
                      <div class="txt1"><?= $product['name'] ?></div>
                    </div>
                  </div>
                  <div class="txt4"><?= $product['description'] ?></div>
                  <div class="bot-info clearfix">
                    <div class="info3">
                      <div class="txt5"><?= $product['mileage'] ?></div>
                      <div class="txt6">Registered <?= $product['year'] ?>&nbsp;
					  </div>
                    </div>
                    <div class="info4">
                      <div class="txt7">
                        <a href="/car/<?= $product['id'] ?>" class="btn-default btn3">VIEW DETAILS</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
		<?php endforeach; ?>
            </div>

            <div class="car-view1-wrapper">
              <div class="car-view1 clearfix">
                <figure><img src="http://via.placeholder.com/322x230" alt="" class="img-responsive"></figure>
                <div class="caption">
                  <div class="top-info clearfix">
                    <div class="info1">
                      <div class="txt1">VEHICLE NAME</div>
                      <div class="txt2">
                        <span class="txt">FIRST DRIVE REVIEW</span>
                        <span class="stars">
                          <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star-o" aria-hidden="true"></i>
                        </span>
                      </div>
                    </div>
                    <div class="info2">
                      <div class="txt3">$99,415</div>
                    </div>
                  </div>
                  <div class="txt4">
                    In a pickup market gone fancy, the Silverado sticks to its basic-truck recipe. The nuico steering is accurate, and the Silverado handles more like a big car than a big truck,  la and the Silverado handles more like a big car than a big truck and the Silverado duoe handles more like a big car than a big truck.
                  </div>
                  <div class="bot-info clearfix">
                    <div class="info3">
                      <div class="txt5">0 kM</div>
                      <div class="txt6">Registered 2017&nbsp; /&nbsp; New&nbsp; /&nbsp; 8-Speed Automatic&nbsp; /&nbsp; Petrol</div>
                    </div>
                    <div class="info4">
                      <div class="txt7">
                        <a href="details.html" class="btn-default btn3">VIEW DETAILS</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="pager_wrapper">
              <ul class="pager clearfix">
                <li class="prev"><a href="#"></a></li>
                <li class="active"><a href="#">1</a></li>
                <li class="li"><a href="#">2</a></li>
                <li class="li"><a href="#">3</a></li>
                <li class="li"><a href="#">4</a></li>
                <li class="dots">....</li>
                <li class="next"><a href="#"></a></li>
              </ul>
            </div>

          </div>
          
 <?php else: ?>
    	  <div class="col-xs-4"></div>
    	  <div class="col-xs-4">
    		<p class="text-danger text-center">Ничего не найдено</p>
    	  </div>
    	  <div class="col-xs-4"></div>
	  <?php endif; ?>
        </div>
      </div>
	
	  
		
	 
    <div class="row">
                <div class="col-12">
                    <div class="south-pagination d-flex justify-content-end">
                        <nav aria-label="Page navigation">
							  <?php
								  echo \yii\widgets\LinkPager::widget([
									'pagination' => $provider->pagination,
									'prevPageLabel' => false,
									'nextPageLabel' => false,
									'options' => [
										'class' => 'pagination',
									]
								  ]);
								  ?>
                        </nav>
                    </div>
                </div>
            </div>
