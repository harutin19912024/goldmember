
<div class="pull-left">
  <label>Sort by:</label>
  <div class="select_wrapper width1">
	<select name="select1" class="custom" tabindex="1">
	  <option value="1">Position</option>
	  <option value="2">Price</option>
	  <option value="3">Rating</option>
	  <option value="4">Date</option>
	</select>
  </div>
</div>
<div class="pull-left aligncenter hidden-phone">
	<label> <span class="hidden-tablet"><?= Yii::t('app', 'View as'); ?>:</span></label>
	<a href="javascript:void(0)" onclick="changeView('grid');" class="icon-th <?php if($active == 'grid'):?> active<?php endif;?>" title="<?= Yii::t('app', 'Grid view'); ?>"></a>
	<a href="javascript:void(0)" onclick="changeView('list');" class="icon-th-list <?php if($active == 'list'):?> active<?php endif;?>" data-toggle="tooltip" title="<?= Yii::t('app', 'List view'); ?>"></a> 
</div>
 <div class="pull-right alignright">
  <label><span class="hidden-phone">Show:</span></label>
  <div class="select_wrapper width2">
	<select name="select2" class="custom" tabindex="1">
	  <option value="1">9</option>
	  <option value="2">12</option>
	  <option value="3">24</option>
	  <option value="4">48</option>
	</select>
  </div>
  per&nbsp;page</div>