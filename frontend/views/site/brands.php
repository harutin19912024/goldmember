<?php
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\Slider;
use frontend\models\Category;
use backend\models\Brand;
$sliders = Slider::find()->where(['status'=>1])->asArray()->all();
//$brands = Brand::find()->where(['status'=>1])->asArray()->all();
/* @var $this yii\web\View */
$this->title = Yii::t('app','SANSTROY').' | '.Yii::t('app','Brand');
?>
<div class="container">
		<div class="row wrapper-main">
			<?= $this->render('/site/category',['choosen_category'=>0]) ?>
			<div class="col-lg-9 col-sm-8">
				<div id="carousel-example-generic" class="carousel slide slide_" data-ride="carousel" style="margin-top: 10px;">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<?php foreach ($sliders as $key=>$slider) : ?>
						<li data-target="#carousel-example-generic" data-slide-to="<?=$key?>" class="<?php if(!$key):?>active<?php endif;?>"></li>
						<?php endforeach; ?>
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<?php foreach ($sliders as $key=>$slider) : ?>
							<div class="item <?php if(!$key):?>active<?php endif;?>">
								<a href="/<?=Yii::$app->language?>/<?=$slider['link']?>" target="_blank"><?php echo  Html::img(Yii::$app->params['adminUrl'] .'uploads/images/slider/'.$slider['id'].'/thumbnail/'. $slider['path'],['style'=>'height:330px']); ?></a>
							</div>
						<?php endforeach; ?>
					</div>
					<!-- Controls -->
					<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
				<div class="text-left-m text-top">
					<a href="<?php if(isset($_GET['letter'])):?>/<?=Yii::$app->language?>/brands<?php else:?>javascript:;<?php endif;?>" class="btn btn-pink btn-brands">ПРОИЗВОДИТЕЛИ</a>
				</div>
				<div class="cat-brends">
					<a href="?letter=1">1</a><a href="?letter=3">3</a><a href="?letter=A">A</a><a href="?letter=B">B</a><a href="?letter=C">C</a><a href="?letter=D">D</a><a href="?letter=E">E</a><a href="?letter=F">F</a><a href="?letter=G">G</a><a href="?letter=H">H</a><a href="?letter=I">I</a><a href="?letter=J">J</a><a href="?letter=K">K</a><a href="?letter=L">L</a><a href="?letter=M">M</a><a href="?letter=N">N</a><a href="?letter=O">O</a><a href="?letter=P">P</a><a href="?letter=R">R</a><a href="?letter=S">S</a><a href="?letter=T">T</a><a href="?letter=U">U</a><a href="?letter=V">V</a><a href="?letter=W">W</a><a href="?letter=Z">Z</a><a href="?letter=rus">РУС</a>
				</div>
				<div class="brands-item">
					<?php foreach ($brands as $brand) : ?>
					<div class="row">
						<div class="col-lg-4 col-sm-5 brand-lgo">
								<?php echo Html::a('<div class="brand-title">' . $brand['name'] . "</div>",'/'.$brand['website_link']) ?>
								<a href="javascript:;">
									<?php if($brand['path'] != ''){ $imageSizes = getimagesize(Yii::$app->params['adminUrl'] .'uploads/images/brand/'. $brand['id'].'/'.$brand['path']);
										if(intval($imageSizes[0]) > 70 || intval($imageSizes[1]) > 150){
									?>
									<?php echo  Html::img(Yii::$app->params['adminUrl'] .'uploads/images/brand/'. $brand['id'].'/'.$brand['path'],['class'=>'icon','style'=>"height:70px;width: 150px;"]); ?>
										<?php }else{?>
								<?php echo  Html::img(Yii::$app->params['adminUrl'] .'uploads/images/brand/'. $brand['id'].'/'.$brand['path'],['class'=>'icon','style'=>"width: 100px;"]); ?>
								
									<?php }}?>
								</a> 
						</div>
						<div class="col-lg-4 col-sm-5">
								<div class="brand-title">Описание</div>
								<p style="color:#000;"><?=$brand['description']?></p>
						</div>
						<div class="col-lg-4 col-sm-7">
								<div class="brand-title">В нашем каталоге:</div>
								<?php $categoryList = json_decode($brand['category_ids']);?>
								<?php if(!empty($categoryList)):?>
								<ol class="brand-cat-list">
									<?php foreach($categoryList as $catID):?>
										<?php $category = Category::findOne($catID);?>
										<li><a href="/<?=Yii::$app->language?>/<?=$category->route_name?>?brand_ids=<?=$brand['id']?>"><?=$category->name?></a></li>
									<?php endforeach;?>
								</ol>
								<?php endif;?>
							</div>
					</div>
					<hr class="brand-line">
					<?php endforeach;?>
				</div>
			</div>
		</div>
	</div>