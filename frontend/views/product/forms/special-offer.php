<?php
use frontend\models\Product;
?>
<dt class="even"><?= Yii::t('app','Special Offer');?></dt>
<dd class="even">
	<ol>
	  <li><input type="checkbox" onclick="filtrBySpecial('installment')"   class="brand-filter"  <?php echo (in_array('installment',$checked_special))?'checked="checked"':''?>/><?=Yii::t('app','Installments')?>(<?php echo Product::getProductCountByInstallments() ?>)</li>
	  <li><input type="checkbox" onclick="filtrBySpecial('gift')"  class="brand-filter"  <?php echo (in_array('gift',$checked_special))?'checked="checked"':''?>/><?=Yii::t('app','Gift')?>(<?php echo Product::getProductCountByGift() ?>)</li>
	  <li><input type="checkbox" onclick="filtrBySpecial('discount')"  class="brand-filter"  <?php echo (in_array('discount',$checked_special))?'checked="checked"':''?>/><?=Yii::t('app','Discounts')?>(<?php echo Product::getProductCountByDiscounts() ?>)</li>
	</ol>
</dd>