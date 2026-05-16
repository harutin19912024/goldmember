<?php
use yii\helpers\Html;

?>
<h2>Choose freight method</h2>
<div class="row">
    <div class="col-md-12">
        <ul class="radios">
            <?php foreach($arrFreightMethods as $value){
              if($value['type'] == 0){
                  $img = '@web/images/postnord.jpg';
              }else{
                  $img = '@web/images/ups-logo.jpg';
              }

                ?>
                <li>
                    <input type="radio" name="group" id="ups-<?= $value['id']?>" checked>
                    <label for="ups-<?= $value['id']?>"><?= Html::img($img); ?> <?= $value['name'] ?></label>
                    <div class="pull-right pd10"><?= $value['price'] ?></div>
                    <div class="clearfix"></div>
                </li>
            <?php } ?>

        </ul>
    </div>
</div>