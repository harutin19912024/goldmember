<?php
use frontend\models\Category;

function createTreeProduct(&$list, $parent){
		$tree = array();
		foreach ($parent as $k=>$l){
			if(isset($list[$l['id']])){
				$l['child'] = createTreeProduct($list, $list[$l['id']]);
			}
			$tree[] = $l;
		} 
		return $tree;
}
$categories = Category::findList();
        $parent_categories = Category::findParentList();
		
		$new = array();
		foreach ($categories as $a){
			$new[$a['parent_id']][] = $a;
		}
		foreach ($parent_categories as $cat){
			$categoriesTree[] = createTreeProduct($new, array($cat));
		}
$category_id = isset(Yii::$app->request->get()['id']) ? Yii::$app->request->get()['id']:0;
?>
<div class="widget">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                       aria-expanded="true" aria-controls="collapseOne">
                        <?=
                        Yii::t('app','PRODUCTS BY CATEGORY');
                        ?>
                        <span class="caret"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                 aria-labelledby="headingOne">
				 <div class="panel-body">
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display: block; position: static; margin-bottom: 0px; width: 269px;border-radius: 2px;">
						<?php foreach ($categoriesTree as $parent_category): ?>
							<?php foreach ($parent_category as $category): ?>
								<?php if(isset($category['child'])):?>
								<li class="dropdown-submenu first-level">
									<a href="/<?php echo Yii::$app->language; ?>/<?php echo $category['route_name'] ?>"><?=$category['name']?></a>
										<ul class="dropdown-menu">
											<?php foreach ($category['child'] as $childs): ?>
												<?php if(isset($childs['child'])):?>
													<li class="dropdown-submenu second-level">
														<a href="/<?php echo Yii::$app->language; ?>/<?php echo $childs['route_name'] ?>"><?=$childs['name']?></a>
														<ul class="dropdown-menu">
															<?php foreach ($childs['child'] as $childsChild): ?>
																<li class="tree-level"><a href="/<?php echo Yii::$app->language; ?>/<?php echo $childsChild['route_name'] ?>"><?=$childsChild['name']?></a></li>
															<?php endforeach; ?>
														</ul>
													</li>
												<?php else:?>
													<li class="second-level"><a href="/<?php echo Yii::$app->language; ?>/<?php echo $childs['route_name'] ?>"><?=$childs['name']?></a></li>
												<?php endif;?>
											<?php endforeach; ?>
										</ul>
								</li>
								<?php else:?>
									<li class="first-level"><a href="/<?php echo Yii::$app->language; ?>/<?php echo $category['route_name'] ?>"><?=$category['name']?></a></li>
								<?php endif;?>
								<?php endforeach; ?>
						<?php endforeach; ?>
					</ul>
				</div>
            </div>
        </div>
    </div>
</div>