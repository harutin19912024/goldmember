<?php

use frontend\models\Category;
use backend\models\SubCategory;

$parent_categories = Category::findParentList();
$childCategory = [];
//echo "<pre>";print_r($parent_categories);die;
?>

<div class="container">
    <div class="row justify-content-center">
	  <div class="services-part">
		<ul class="nav nav-tabs">
		    <?php
		    $i = 0;
		    foreach ($parent_categories as $key => $category):
			  ?>
    		    <li><a data-toggle="tab" href="#category<?= $key ?>" class="<?php if (!$i): ?>active<?php endif; ?>"><?= $category ?></a></li>
			  <?php $i++; ?>
		    <?php endforeach; ?>
		</ul>
		<div class="tab-content services-wrapper clearfix">
		    <?php
		    $i = 0;
		    foreach ($parent_categories as $key => $category):
			  ?>
			  <?php $childCategory = SubCategory::find()->where(['sub_cat_id' => $key])->all(); ?>
    		    <div id="category<?= $key ?>" class="tab-pane <?php if (!$i): ?>active<?php endif; ?> clearfix">
				<?php foreach ($childCategory as $categ): ?>
				    <?php $cat = Category::find()->where(['id' => $categ->category_id, 'opened' => 1])->one(); ?>
				    <?php if (!empty($cat)): ?>
					  <a href="/<?= Yii::$app->language ?>/<?= Category::getCategoryRouteName($cat->id) ?>">
						<div class="service-item">
						    <div class="service-item-wrapper">
							  <div class="service-item-img">
								<img src="<?= Yii::$app->params['adminUrl'] . 'uploads/images/category/' . $cat->id . '/' . $cat->path ?>">
								<h3><?= Category::getCategoryName($categ->category_id) ?></h3>
							  </div>
						    </div>
						</div>
					  </a>
				    <?php endif; ?>
				<?php endforeach; ?>
    		    </div>
			  <?php $i++; ?>
		    <?php endforeach; ?>
		</div>
	  </div>
    </div>
</div>