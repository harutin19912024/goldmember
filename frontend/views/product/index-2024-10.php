<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\Attribute;
use backend\models\ProductAttribute;
use backend\models\Slider;
use frontend\models\Category;
use yii\widgets\ActiveForm;
$this->title = Yii::t('app', 'Vin Radar') . ' | ' . Yii::t('app', 'Cars');

$sliders = Slider::find()->where(['status' => 1])->asArray()->all();
if (isset($cat_id)) {
    $categoryInfo = Category::findOne($cat_id);
    if (isset($categoryInfo->parent_id)) {
	  $parentCategoryInfo = Category::findOne($categoryInfo->parent_id);
    }
} else {
    $cat_id = 0;
}
?>
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
		<div class="top3-wrapper">
  <div class="container">
    <div class="booking">
            <?php $form = ActiveForm::begin(['method' => 'get','action' => '/search', 'options' => [
                'class' => 'form1'
             ]]); ?>
             <div class="row">
              <div class="col6" style="padding:14px">
                    <input type="text" class="form-control" name="vin" placeholder="Search by Vin">
                    <button class="btn btn-default" style="float: right;position: relative;top: -34px;">Search</button>
                  </div>
                  </div>
          <?php ActiveForm::end(); ?>
        </div>
  </div>
</div>

<div id="content">
  <div class="container">
      <div class="row">
					<?php if ($view_type != "list"): ?>
					  <?=
					  $this->render('forms/products-grid-view', [
						'products' => $rows,
						'active' => $active,
						'page' => $page,
						'provider' => $provider
					  ]);
					  ?>
					<?php else: ?>
					  <?=
					  $this->render('forms/products-list-view', [
						'products' => $rows,
						'active' => $active,
						'page' => $page,
						'provider' => $provider
					  ])
					  ?>
					<?php endif; ?>
					</div>
            </div>
        </div>