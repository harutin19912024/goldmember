<?php
use yii\helpers\Url;
use yii\helpers\Html;
$productsPopular = array_chunk($popular, 3, true);
$this->registerCssFile("@web/css/popular.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
], 'css-print-theme');
?>

<div class="carousel slide carousel-populaar" id="myCarousel">
        <div class="carousel-inner">
		<?php foreach ($productsPopular as $key=>$pop) { ?>
            <div class="item <?php if(!$key):?>active<?php endif;?>">
                    <ul class="thumbnails">
						<?php foreach($pop as $produc):?>
                        <li class="col-sm-3">	
							<div class="fff">
								<div class="thumbnail">
									<a href="<?php echo Url::to(['product/view', 'id' => $produc['id']]) ?>">
										<?php echo  Html::img(Yii::$app->params['adminUrl'] .'uploads/images/'. $produc['image']); ?>
									</a>
								</div>
								<div class="caption">
									<h4><?= $produc['name']?></h4>
									<p>Nullam Condimentum Nibh Etiam Sem</p>
									<a class="btn btn-mini" href="#">Read More</a>
								</div>
                            </div>
                        </li>
						<?php endforeach;?>
                    </ul>
              </div><!-- /Slide3 --> 
			   <?php } ?>
        </div>
		<nav>
			<ul class="control-box pager">
				<li><a data-slide="prev" href="#myCarousel" class=""><i class="glyphicon glyphicon-chevron-left"></i></a></li>
				<li><a data-slide="next" href="#myCarousel" class=""><i class="glyphicon glyphicon-chevron-right"></i></li>
			</ul>
		</nav>
	   <!-- /.control-box -->   
</div>