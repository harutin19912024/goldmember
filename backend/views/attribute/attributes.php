<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use backend\models\Attribute;
?>
<div class="m10">
	<?php foreach($attributes as $attribute):?>
		<div class="col-md-4">
			<div class="option-group field">

				<label class="block mt15 option option-primary" for="attribute_<?php echo $attribute['id']?>">
					<input type="checkbox" onclick="attributeChecked('<?php echo $attribute['id']?>',this.checked)" name="attribute_checked" id="attribute_<?php echo $attribute['id']?>">
					<span class="checkbox"></span> <?php echo $attribute['name']?>
				</label>
				<div class="form-group">
				<?php $subAttributes = Attribute::find()->where(['parent_id'=>$attribute['id']])->asArray()->all()?>
				<?php if(!empty($subAttributes)):?>
					<select name="sub_attr_id[<?php echo $attribute['id']?>][option]" disabled id="sub_attr_id_<?php echo $attribute['id']?>" class="form-control">
							<option value=''>Пожалуйста выберите фильтр</option>
						<?php foreach($subAttributes as $subs): ?>
							<option value="<?=$subs['id']?>"><?=$subs['name']?></option>
						<?php endforeach;?>
					</select>
				<?php endif;?>
				<input type="text" name="sub_attr_id[<?php echo $attribute['id']?>][value]" disabled id="attribute_value_<?php echo $attribute['id']?>" class="form-control" placeholder="Значения Фильтра">
				<input type="hidden" name="ProductAttribute[value][<?php echo $attribute['id']?>]" class="form-control" placeholder="Attribute Value">
				</div>
			</div>
		</div>
	<?php endforeach;?>
</div>

