<?php
use frontend\models\Brand;
?>
<form method="get" action="">
    <div class="widget">
        <div class="box filter-widget">
            <div class="clear"></div>
            <h2><?= Yii::t('app','BRANDS');?>
                <div class="clear"></div>
            </h2>
            <div class="vert-ftr filter-brand">
                <?php foreach ($brands as $brand) { ?>
                    <label class="control control--checkbox"><?= $brand['name']; ?>
                        <span class="pull-right"><?php echo Brand::getProductCountByBrand($brand['id']) ?></span>
                        <div class="clear"></div>
                        <input type="checkbox" name="brand_ids[]" value="<?= $brand['id'] ?>" class="brand-filter" id ="<?= $brand['id'] ?>" />
                        <div class="control__indicator"></div>
                    </label>
                <?php } ?>
            </div>
            <button type="submit" name="<?= Yii::t('app','Filter');?>" class="btn btn-filter"><i class="fa fa-filter" aria-hidden="true"></i> <?= Yii::t('app','Filter');?></button>
            <p class="pd10"></p>
        </div>

    </div>
</form>

