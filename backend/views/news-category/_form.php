<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Language;
use backend\models\NewsCategory;
use kartik\select2\Select2;

$languages = Language::find()->asArray()->all();

/* @var $this yii\web\View */
/* @var $model backend\models\NewsCategory */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
if (!$model->isNewRecord) {
    $formId = 'categoryUpdate';
    $action = '/news-category/update?id=' . $model->id;
    $categories = NewsCategory::find()->where(['!=', 'id', $model->id])->where(['parent_id' => null])->select(['name', 'id'])->asArray()->all();
    $categoryDropDown = [];
    foreach ($categories as $category) {
	  $categoryDropDown[$category['id']] = $category['name'];
    }
} else {
    $formId = 'categoryCreate';
    $action = '/news-category/create';
    $categories = NewsCategory::find()->select(['name', 'id'])->where(['parent_id' => null])->asArray()->all();
    $categoryDropDown = [];
    foreach ($categories as $category) {
	  $categoryDropDown[$category['id']] = $category['name'];
    }
}
?>

<div class="categoyr-form">
    <?= Html::a(Yii::t('app', 'Back to list'), ['/news-category/index'], ['class' => 'btn btn-primary mb15']) ?>
    <div class="panel sort-disable mb50" id="p2" data-panel-color="false" data-panel-fullscreen="false" data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title"><?php echo Yii::t('app', 'Add New Category') ?></span>
            <span style="float: left;" class="panel-controls"><a href="#" class="panel-control-loader"></a><a href="#" style="margin-left: 5px" class="panel-control-collapse"></a></span>
            <ul class="nav panel-tabs-border panel-tabs">
		    <?php
		    if (!$model->isNewRecord) {
			  foreach ($languages as $value):
				?>
	  		    <li class="<?php
				if ($value['is_default']) {
				    $defoultId = $value['id'];
				    echo 'active';
				}
				?>">
	  			  <a href="#tab_<?php echo $value['id'] ?>"  data-toggle="tab" onclick="editNewsCategoryTr(<?php echo $value['id']; ?>,<?php echo $model->id; ?>,<?php echo $value['is_default']; ?>)" disabled="disabled">
	  				<span class="flag-xs flag-<?php echo $value['short_code'] ?>"></span>
	  			  </a>
	  		    </li>
				<?php
			  endforeach;
		    }
		    ?>
            </ul>
        </div>
        <div class="panel-body"  style="display: block;">
            <div class="tab-content pn br-n admin-form">
                <div class="tab-pane" id="tr_newscategory"></div>
                <div class="tab-pane active" id="tab_<?php echo $defoultId; ?>">
			  <?php
			  $form = ActiveForm::begin([
					  'action' => [$action],
					  'id' => $formId,
			  ]);
			  ?>
                    <div class="tab-content row">
                        <div class="col-md-3">
				    <?=
						$form->field($model, 'name', ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
						->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Name')])->label(false)
				    ?>
                        </div>
                        <div class="col-md-3">
				    <?=
				    $form->field($model, 'parent_id')->widget(Select2::className(), [
					  'data' => $categoryDropDown,
					  'language' => Yii::$app->language,
					  'options' => ['placeholder' => Yii::t('app', 'Select Parent')],
					  'pluginOptions' => [
						'allowClear' => true,
						'multiple' => false,
					  ],
					  'pluginLoading' => false,
				    ])->label(false)
				    ?>
				</div>
                <div class="col-md-3">
				    <?=
				    $form->field($model, 'is_top')->widget(Select2::className(), [
					  'data' => [Yii::t('app','No'),Yii::t('app','Yes')],
					  'language' => Yii::$app->language,
					  'options' => ['placeholder' => Yii::t('app', 'Is Top Category')],
					  'pluginOptions' => [
						'allowClear' => true,
						'multiple' => false,
					  ],
					  'pluginLoading' => false,
				    ])->label(false)
				    ?>
				</div>
				<div class="col-md-3">
				    <?=
						$form->field($model, 'route_name', ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
						->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Route Name')])->label(false)
				    ?>
                        </div>
                    </div>
                    <div class="section row">
				<div class="col-md-6">
				    <label for="customer-name" class="field prepend-icon"><?= Yii::t('app', 'Short Description') ?></label>
				    <?=
						$form->field($model, 'short_description')
						->textarea(['rows' => 6, 'placeholder' => Yii::t('app', 'Short Description')])->label(false)
				    ?>
				</div>
                        <div class="col-md-6">
				    <label for="customer-name" class="field prepend-icon"><?= Yii::t('app', 'Description') ?></label>
				    <?=
						$form->field($model, 'description')
						->textarea(['rows' => 6, 'placeholder' => Yii::t('app', 'Description')])->label(false)
				    ?>
				</div>
                    </div>
                    <div class="form-group col-md-12">
				<?=
				Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
				    'class' => $model->isNewRecord ? 'btn btn-sm btn-primary pull-right ' : 'btn btn-sm btn-success pull-right',
				    'id' => $formId,
				    'type' => 'button'
				])
				?>
				<?php
				if (!$model->isNewRecord) {
				    echo Html::a(Yii::t('app', 'Reset'), Url::to('/' . Yii::$app->language . '/news-category/index', true), ['class' => 'btn btn-default btn-sm ph25 reste-button pull-right']);
				}
				?>
                    </div>
			  <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->registerJs("
            CKEDITOR.replace('newscategory-description'); 
            CKEDITOR.replace('newscategory-short_description'); 
"); ?>
<?php
$this->registerJs("
$('#newscategory-name').on('focusout',function(){
	if($('#newscategory-route_name').val() == ''){
		var rout_name = $(this).val();
	   var splBy = rout_name.split('-');
	   if(splBy.length === 1) {
	       $('#newscategory-route_name').val(rout_name.toLowerCase());
	   } else{
			splBy = splBy.filter(String);
		  rout_name = splBy.join(' ');
    	   var rout_nameArray = rout_name.match(/[A-Z]*[^A-Z]+/g);
    	   for(var i = 0; i < rout_nameArray.length; i++){
    			var splByspace = rout_nameArray[i].split(' ');
    			splByspace = splByspace.filter(String);
    			var str = splByspace.join('-'),
    			str = str.replace(/^\-{1,}|\-{1,}$/,'');
    			rout_nameArray[i]= str;
    	   }
    	   rout_name = rout_nameArray.join('-').toLowerCase()
    	   
    	   $('#newscategory-route_name').val(rout_name);
	   }
	}
   
})
")
?>

