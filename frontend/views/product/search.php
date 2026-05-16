<?php
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\Attribute;
use backend\models\ProductAttribute;
$this->title = Yii::t('app','SANSTROY').' | '.Yii::t('app','Products'); 
use backend\models\Slider;
use frontend\models\Category;
$sliders = Slider::find()->where(['status'=>1])->asArray()->all();
if(isset($cat_id)){
	$categoryInfo = Category::findOne($cat_id);
	if(isset($categoryInfo->parent_id)){
		$parentCategoryInfo = Category::findOne($categoryInfo->parent_id);
	}
}else{
	$cat_id = 0;
}

?>
<?php $view_type = @$_COOKIE['view']?>
<div class="container">
                <div class="row wrapper-main">
				 <?= $this->render('/site/category',['choosen_category'=>$cat_id]) ?>
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
						<?php if(isset($categoryInfo)):?>
                        <ul class="nav-product">
                            <li class="active">
                                <a href="/<?=Yii::$app->language?>">Главная</a>
                            </li>
							<?php if(isset($parentCategoryInfo)):?>
                            <li class="active">
                                <a href="/<?=Yii::$app->language?>/<?=$parentCategoryInfo->route_name?>"><?=$parentCategoryInfo->name?></a>
                            </li>
							<?php endif;?>
                            <li>
                                <a href="javascript:;"><?=$categoryInfo->name?></a>
                            </li>
                        </ul>
						<?php elseif(Yii::$app->request->get('best_seller') || Yii::$app->request->get('new')
									|| Yii::$app->request->get('sale') || Yii::$app->request->get('official')
									|| Yii::$app->request->get('in_slider')):?>
							<ul class="nav-product">
								<li class="active">
									<a href="/<?=Yii::$app->language?>">Главная</a>
								</li>
								<li>
									<a href="javascript:;">
									<?php if(Yii::$app->request->get('best_seller')):?>
									<?=Yii::t('app','Best Seller')?>
									<?php elseif(Yii::$app->request->get('new')):?>
									<?=Yii::t('app','New Product')?>
									<?php elseif(Yii::$app->request->get('sale')):?>
									<?=Yii::t('app','Sale')?>
									<?php elseif(Yii::$app->request->get('official')):?>
									<?=Yii::t('app','Discount')?>
									<?php elseif(Yii::$app->request->get('in_slider')):?>
									<?=Yii::t('app','Week Product')?>
									<?php endif;?>
									</a>
								</li>
							</ul>
						<?php elseif(isset($filterName)):?>
						<ul class="nav-product">
						<li class="active">
									<a href="/<?=Yii::$app->language?>">Главная</a>
								</li>
								<li>
									<a href="javascript:;"><?=$filterName->name?></a>
								</li>
							</ul>
						<?php endif;?>
						  <?php if(!$wihtouFilter):?>
						  <ul class="product-type" style="margin-top: 10px;">
							<?php $count=0; foreach($attributes as $key=>$attribute):?>
							<?php if(!empty($attribute) && isset($attribute['path']) && !is_null($attribute['path']) && $attribute['path'] != ''):?>
									<li id="sub_filter_<?=$attribute['id']?>" class="sub_filter"><a href="javascript:;" onclick="filterProduct('<?=$attribute['id']?>');">
									<?php echo Html::img(Yii::$app->params['adminUrl'] . 'uploads/images/attribute/' .$attribute['id'].'/thumbnail/'.$attribute['path'], ['class' => 'icon']); ?>
									<span style="display: inherit;"><?=$attribute['name']?></span></a></li>
							<?php endif;?>
							<?php endforeach;?>
						  </ul>
						  <?php endif;?>
						  <form action="" id="filter_product">
						  <div class="catalog">
						     <div class="padding-section">
							        <div class="row">
							    <div class="col-lg-4 col-sm-5">
								  <h2>Бренды</h2>
						          <ul class="checkbox_">
									<?php foreach($brands as $brand):?>
								    <li>
									  <input type="checkbox" class="brand_checkbox" <?php $brend_id = Yii::$app->request->get('brand_ids'); if(isset($brend_id) && $brand['id'] == $brend_id):?>checked<?php endif;?>  value="<?=$brand['id']?>" onclick="filterProduct()" id="box-<?=$brand['id']?>" name="brand_id[]">
									  <label for="box-<?=$brand['id']?>"><?=$brand['name']?></label>
									</li>
									<?php endforeach;?>
								</ul>
							 </div>
							  <div class="col-lg-8 col-sm-7">
							    <div class="row">
								   <div class="col-lg-10">
								     	  <div class="slider-wrap">
											<h2>Цена</h2>
											<div id="slider-price"></div>
												<div class="values">
													<div><span>от</span><input type="text" class="sliderValue" onkeyup="filterProduct()" data-index="0" value="10"></div>
													<div><span>до</span><input type="text" class="sliderValue" onkeyup="filterProduct()" data-index="1" value="255000"></div>
												</div>
						                </div>
								   </div>
                                </div>	
								<div class="row about-product">
								  <div class="col-lg-8 col-sm-7">
									  <ul class="filter-attribute">
										<?php $count=0; foreach($attributes as $key=>$attribute):?>
										<?php if(!empty($attribute) && (is_null($attribute['parent_id']) || $attribute['parent_id'] == '')):?>
											<?php if($count < 10):?>
											<?php $attrSub = Attribute::find()->where(['parent_id'=>$key])
																	->andWhere(['or',
																   ['path'=>null],
																   ['path'=>'']])->asArray()->all();?>
												   <?php if(!empty($attrSub)):?>
												   <?php if(!$attribute['is_unity']):?>
														<li>
															<a href="javascript:;" data-toggle="collapse" data-target="#attr_<?=$key?>"><span class="icon-play2"> </span><?=$attribute['name']?></a>
															<div id="attr_<?=$key?>" class="collapse">
																<ul>
																	<?php foreach($attrSub as $j=>$att):?>
																			<li><a href="javascript:;" onclick="checkboxCheck('<?=$key?>','<?=$att['id']?>');"><input type="checkbox" value="<?=$att['id']?>" class="attribute_checkbox" id="checkbox_input_<?=$key?>_<?=$att['id']?>"><?=$att['name']?></a></li>
																	<?php endforeach;?>
																</ul>
															</div>
														</li>
														<?php endif;?>
														<?php elseif($attribute['is_unity']):?>
														<li>
															<a href="javascript:;" data-toggle="collapse" data-target="#attr_<?=$key?>"><span class="icon-play2"> </span><?=$attribute['name']?></a>
															<div id="attr_<?=$key?>" class="collapse">
																	<div><input type="text" class="range" onkeyup="filtrByUnity('<?=$attribute['id']?>',this.value,1)" data-index="0" value="0">
																	<span class="beet">—</span><input type="text" class="range" onkeyup="filtrByUnity('<?=$attribute['id']?>',this.value,2)" data-index="1" value="150"></div>
															</div>
														</li>
													<?php endif;?>
											   <?php endif; $count++;?>
											   <?php endif;?>
										<?php endforeach;?>
									  </ul>
									 </div>
									  <div class="col-lg-4 col-sm-5">
									  <ul class="filter-attribute">
										<?php $secondCount=0; foreach($attributes as $key=>$attribute):?>
											<?php if(!empty($attribute) && (is_null($attribute['parent_id']) || $attribute['parent_id'] == '')):?>
											<?php if($secondCount >=10 && $secondCount<21):?>
											<?php $attrSub = Attribute::find()->where(['parent_id'=>$key])
																	->andWhere(['or',
																   ['path'=>null],
																   ['path'=>'']])->asArray()->all();?>
											   <?php if(!empty($attrSub)):?>
											   <li>
													<a href="javascript:;" data-toggle="collapse" data-target="#attr_<?=$key?>"><span class="icon-play2"> </span><?=$attribute['name']?></a>
													<div id="attr_<?=$key?>" class="collapse">
														<?php if(!$attribute['is_unity']):?>
														<ul>
															<?php foreach($attrSub as $j=>$att):?>
															<li><a href="javascript:;" onclick="checkboxCheck('<?=$key?>','<?=$att['id']?>');"><input type="checkbox" value="<?=$att['id']?>" class="attribute_checkbox" id="checkbox_input_<?=$key?>_<?=$att['id']?>"><?=$att['name']?></a></li>
															<?php endforeach;?>
														</ul>
														<?php else:?>
															<div><input type="text" class="range" onkeyup="filtrByUnity('<?=$key?>',this.value,1)" data-index="0" value="0">
															<span class="beet">—</span><input type="text" class="range" onkeyup="filtrByUnity('<?=$key?>',this.value,2)" data-index="1" value="150"></div>
														<?php endif;?>
													</div>
											   </li>
											   <?php endif;?>
											   <?php endif;?>
											   <?php $secondCount++;  endif;?>
										<?php endforeach;?>
									  </ul>
									  </div>
								</div>
							 </div>
							</div>
							</div>
							<div class="bottom-element row">
								    <div class="col-lg-8">
									  <p class="text-left" id="filter-found">Найдено <?=count($products)?> товара</p>
									</div>
									 <div class="col-lg-4">
									    <a href="javascript:;" onclick="window.location.reload()" class="btn pull-right">Очистить все</a>
									 </div>
								</div>
						  </div>
						  </form>
							<?php
								$provider = new ArrayDataProvider([
									'allModels' => $products,
									'pagination' => [
										'pageSize' => 16,
									],
									'sort' => [
										'attributes' => ['id', 'name'],
									],
								]);
								$rows = $provider->getModels();
							?>
							<section id="product-list">
							<?php if($view_type != "list"): ?>
								<?= $this->render('forms/products-grid-view', [
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
							</section>
                       
				</div>
		</div>
</div>
<input type="hidden" value="<?=$cat_id?>" id="current_category_id"> 
<input type="hidden" value="" id="current_sub_cat"> 
<input type="hidden" value="" id="range1"> 
<input type="hidden" value="" id="range2"> 
<input type="hidden" value="" id="range_attribute"> 
<input type="hidden" value="" id="sort_by"> 
<div id="attr_ids"></div>
<input type="hidden" value="<?php $brend_id = Yii::$app->request->get('brand_ids'); if(isset($brend_id)):?><?=Yii::$app->request->get('brand_ids')?><?php endif;?>" id="brend_id"> 
