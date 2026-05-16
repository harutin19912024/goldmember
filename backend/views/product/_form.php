<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;
use backend\models\TrAttribute;
use backend\models\ProductImage;
use common\models\Language;
use dosamigos\multiselect\MultiSelect;
use dosamigos\multiselect\MultiSelectListBox;
use yii\web\JsExpression;
use backend\models\Attribute;
use backend\models\ProductsFilters;

use unclead\multipleinput\MultipleInput;

$languages = Language::find()->asArray()->all();

$otherAttr = [];
$attr = Attribute::find()->where(['parent_id' => 47])->asArray()->all();
foreach ($attr as $att) {
    $otherAttr[$att['name']] = $att['name'];
}

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $product_attribute_model backend\models\ProductAttribute */
/* @var $product_parts_model backend\models\ProductParts */
/* @var $form yii\widgets\ActiveForm */
/* @var $productPackage common\models\ProductPackage */
?>
<?php
$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
$templatePrice = '<div class="">{label}<div class=""><div class="input-group">
{input}<span class="input-group-addon"><i class="fa fa-euro"></i></span></div>{error}</div></div>';
if (!$model->isNewRecord) {
    $imagePaths = ProductImage::getImageByProductId($model->id);
    $otherImagePaths = ProductImage::getImageByProductIdOther($model->id);
    $defaultImage = ProductImage::getDefaultImageIdByProductId($model->id);
    $formId = 'productUpdate';
    $action = '/product/update?id=' . $model->id;
    $tabsName = Yii::t('app', 'Update Product');
} else {
    $formId = 'productCreate';
    $action = '/product/create';
    $tabsName = Yii::t('app', 'Add New Product');
}

$productSku = '';
$categoryId = null;
$subCategory = null;
$stateId = null;
$cityId = null;
$addressId = null;
$postRequest = Yii::$app->request->getQueryParam('Product', []);
if ($model->isNewRecord && Yii::$app->request->getQueryParam('product_sku', '')) {
    $productSku = Yii::$app->request->getQueryParam('product_sku', '');
} else {
    $productSku = $model->product_sku;
}

if ($model->isNewRecord && !empty($postRequest) && isset($postRequest['category_id'])) {
    $categoryId = Yii::$app->request->getQueryParam('Product', [])['category_id'];
} elseif (!$model->isNewRecord) {
    $categoryId = $model->category_id;
}
$defoultId = 1;

?>
<div class="admin-form">

    <?= Html::a(Yii::t('app', 'Back to list'), ['/product/index'], ['class' => 'btn btn-primary mb15']) ?>
    <ul class="nav panel-tabs-border panel-tabs-product">
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
                    <a href="#tab_<?php echo $value['id'] ?>" data-toggle="tab"
                       onclick="editProductTr(<?php echo $value['id']; ?>,<?php echo $model->id; ?>,<?php echo $value['is_default']; ?>)">
                        <span class="flag-xs flag-<?php echo $value['short_code'] ?>"></span>
                    </a>
                </li>
            <?php
            endforeach;
        }
        ?>
    </ul>

    <div class="tab-content">
        <?php
        foreach ($languages

        as $value):
        ?>
        <?php
        if ($value['is_default']) {
        $dfoultId = $value['id'];
        ?>

        <div class="tab-pane active" id="tab_<?php echo $dfoultId; ?>">
            <?php $form = ActiveForm::begin(['action' => [$action], 'options' => ['enctype' => 'multipart/form-data',]]); ?>
            <input type="hidden" name="default_language" value="<?=$defoultId?>">
            <div class="panel sort-disable mb50" id="p2" data-panel-color="false"
                 data-panel-fullscreen="false"
                 data-panel-remove="false" data-panel-title="false">
                <div class="panel-heading">
                    <span class="panel-title"><?php echo Yii::t('app', 'General') ?></span>
                    <span style="float: left;" class="panel-controls">
	  				<a href="#" class="panel-control-loader"></a>
	  				<a href="#" style="margin-left: 5px" class="panel-control-collapse"></a>
	  			  </span>

                </div>
                <div class="panel-body" style="display: block;">
                    <div class="tab-content pn br-n admin-form">
                        <div class="tab-content row">
                            <div class="col-md-12">
                                <?=
                                $form->field($model, 'title', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                        {input}<label for="customer-name" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>'])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')])->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="tab-content row">
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'price', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Price')])->label(false)
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'fineness', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Fineness')])->label(false)
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'weight', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Weight')])->label(false)
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'color', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Color')])->label(false)
                                ?>
                            </div>
                        </div>
                        
                        <div class="tab-content row">
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'state', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                        {input}<label for="customer-name" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>'])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Product State')])->label(false)
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'country', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Creation Country')])->label(false)
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'gemstone', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Gemstone')])->label(false)
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'product_sku', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Product Code')])->label(false)
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'material_id', ['template' => $template])->widget(Select2::className(), [
                                    'data' => $model->getAllMaterials(),
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app', 'Select Material')], //'onchange'=>'getProductAttr(this.value,"'.Yii::$app->language.'")'
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
                                $form->field($model, 'rateing', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                        {input}<label for="customer-name" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>'])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Rateing')])->label(false)
                                ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?=
                                $form->field($model, 'route_name', ['template' => '{input}{error}'])
                                    ->textInput(['placeholder' => Yii::t('app', 'URL To Show')])->label(false)
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'category_id', ['template' => $template])->widget(Select2::className(), [
                                    'data' => $model->getAllCategories(),
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app', 'Select Category'), 'onchange' => 'getAttributes(this.value)', 'value' => $categoryId], //'onchange'=>'getProductAttr(this.value,"'.Yii::$app->language.'")'
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => false,
                                    ],
                                    'pluginLoading' => false,
                                ])->label(Yii::t('app', 'Choose Category'))
                                ?>
                            </div>
                            
                            <div class="col-md-2">
                                <?php
                                if (!$model->status && $model->status !== 0) $model->status = 1
                                ?>
                                <?=
                                $form->field($model, 'status', ['template' => $template])->widget(Select2::className(), [
                                    'data' => [Yii::t('app', "Passive"), Yii::t('app', "Active")],
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app', 'Select Status')],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => false,
                                    ],
                                    'pluginLoading' => false,
                                ])->label(Yii::t('app', 'Select Status'))
                                ?>
                            </div>
                            
                            <div class="col-md-2">
                                <?php
                                if (!$model->is_auction && $model->is_auction !== 0) $model->is_auction = 1
                                ?>
                                <?=
                                $form->field($model, 'is_auction', ['template' => $template])->widget(Select2::className(), [
                                    'data' => [Yii::t('app', "No"), Yii::t('app', "Yes")],
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app', 'For Auction')],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => false,
                                    ],
                                    'pluginLoading' => false,
                                ])
                                ?>
                            </div>
                            
                            <div class="col-md-2">
                                <?php
                                if (!$model->isNewRecord && !$model->popular) $model->popular = 0
                                ?>
                                <?=
                                $form->field($model, 'popular', ['template' => $template])->widget(Select2::className(), [
                                    'data' => [0 => 'No', 1 => 'Yes'],
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app', 'Popular')],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => false,
                                    ],
                                    'pluginLoading' => false,
                                ])
                                ?>
                            </div>
                            <div class="col-md-2">
                                <?php
                                if (!$model->isNewRecord && !$model->best_offer) $model->best_offer = 0
                                ?>
                                <?=
                                $form->field($model, 'best_offer', ['template' => $template])->widget(Select2::className(), [
                                    'data' => [0 => 'No', 1 => 'Yes'],
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app', 'Best Offer')],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => false,
                                    ],
                                    'pluginLoading' => false,
                                ])
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- end section -->
                </div>
            </div>
        </div>
    </div>

    <div class="panel sort-disable mb50" id="p2" data-panel-color="false"
         data-panel-fullscreen="false"
         data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title"><?php echo Yii::t('app', 'Description') ?></span>
            <span style="float: left;" class="panel-controls">
	  		    <a href="#" class="panel-control-loader"></a>
	  		    <a href="#" style="margin-left: 5px" class="panel-control-collapse"></a>
	  		</span>
        </div>
        <div class="panel-body">
            <div class="section row">
                <div class="col-md-12 pl15">
                    <div class="section mb10">
                        <label for="customer-name"
                               class="field prepend-icon"><?= Yii::t('app', 'Short Description') ?></label>
                        <?=
                        $form->field($model, 'short_description', ['template' => '<div class="col-md-12" style="padding: 0">{input}{error}</div>'])
                            ->textarea(['maxlength' => true])
                        ?>
                    </div>
                </div>
                <div class="col-md-12 pl15">
                    <div class="section mb10">
                        <label for="customer-name"
                               class="field prepend-icon"><?= Yii::t('app', 'Description') ?></label>
                        <?=
                        $form->field($model, 'description', ['template' => '<div class="col-md-12" style="padding: 0">{input}{error}</div>'])
                            ->textarea(['rows' => 6, 'class' => 'form-control'])
                        ?>
                    </div>

                </div>
            </div>
            <div class="section row mbn">
                <div class="col-md-6 pt15">
                    <p>Image Size(800X1200)</p>
                    <?=
                    $form->field($model, 'imageFiles[]', ['template' => '<div><div class="box">{input}{label}{error}</div></div>'])
                        ->fileInput(
                            [
                                'multiple' => true,
                                'accept' => 'image/*',
                                'onchange' => 'showMyImage(this, -1,true)',
                                'class' => 'inputfile inputfile-6',
                                'data-multiple-caption' => "{count} files selected",
                            ])->label('<span></span> <strong class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&ensp;Brows…</strong>', ['class' => ''])
                    ?>
                    <div class="hidden" id="defaultimg">
                        <input type="radio" id="def_img_part_-1" name="defaultImage" value=""
                               class="hidden"/>
                    </div>
                    <div class="col-md-12 pt15" id="selectedFiles_-1">

                    </div>
                </div>
            </div>

            <?php if (!$model->isNewRecord): ?>
                <div class="col-md-12 pl15">
                    <div class="gallery-page sb-l-o sb-r-c onload-check">
                        <div class="">
                            <div id="mix-container">
                                <div class="fail-message">
                                    <span><?php echo Yii::t('app', 'No images were found for the selected product') ?></span>
                                </div>
                                <p>Images</p>
                                <div class="row">
                                    <?php if (!empty($imagePaths)) : ?>
                                        <?php foreach ($imagePaths as $key => $imagePath): ?>
                                            <div style="height: 240px;"
                                                 class="col-md-1 mix label1 folder1 <?= @($defaultImage[$key] == $key) ? 'default-view' : '' ?>"
                                                 id="image_<?php echo $imagePath['id'] ?>"
                                                 data-src="<?= '/uploads/images/' . $imagePath['name'] ?>">
			  					  <span class="close remove">
			  						<i class="fa fa-close icon-close"></i>
			  					  </span>
                                                <div class="panel p6 pbn">
                                                    <div class="of-h">
                                                        <?php
                                                        echo Html::img('/uploads/images/' . $imagePath['name'], [
                                                            'class' => 'img-responsive',
                                                            'title' => $model->title,
                                                            'alt' => '',
                                                            'data-id' => $imagePath['id'],
                                                            'id' => 'product-image-' . $imagePath['id'],
                                                            //'ondragover'=>'allowDrop(event, "'.$imagePath['id'].'")'
                                                        ])
                                                        ?>
                                                        <div class="row table-layout change_image"
                                                             data-key="<?php echo $imagePath['id'] ?>">
                                                            <div class="col-xs-8 va-m pln">
                                                                <h6><?= $model->title . '.jpg' ?></h6>
                                                            </div>
                                                            <div class="col-xs-4 text-right va-m prn">
                                                                <span class="fa fa-eye-slash fs12 text-muted"
                                                                      onclick="showOnHomePage(<?= $model->id ?>,<?= $imagePath['id'] ?>,)"></span>
                                                                <span class="fa fa-circle fs10 text-info ml10"
                                                                      onclick="makeAsDefaultImage(<?= $model->id ?>,<?= $imagePath['id'] ?>)"></span>
                                                                <span class="fa fa-user-secret fa-2x text-danger ml10"
                                                                      style="position: inherit;right: 35px;"
                                                                      onclick="moveToSecure(<?= $imagePath['id'] ?>)"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="gap"></div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="panel-footer text-right">
            <?=
            Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                'class' => $model->isNewRecord ? 'button btn-lg btn-primary ' : 'button btn-lg btn-success',
                'id' => $formId,
                'type' => 'button'
            ])
            ?>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>
    </div>

<?php } else { ?>
    <?php if (!$model->isNewRecord) { ?>
        <div class="tab-pane" id="tab_<?php echo $value['id'] ?>"></div>
        <?php
    }
}
?>

<?php endforeach; ?>

</div>
</div>
<?php echo $this->registerJs("
            CKEDITOR.replace('product-description'); 
            CKEDITOR.replace('product-short_description'); 
"); ?>
<?php
$this->registerJs("

$('#product-title').on('focusout',function(){
	if($('#product-route_name').val() == ''){
   var rout_name = $(this).val();
   rout_name = rout_name.replace(/[^\w\s\-\d]/gi, '')
   var splBy = rout_name.split('-');
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
   $('#product-route_name').val(rout_name);
	}
})


jQuery('.multiple-input').on('afterInit', function(){
    console.log('calls on after initialization event');
}).on('beforeAddRow', function(e) {
    console.log('calls on before add row event');
}).on('afterAddRow', function(e, row) {
	/* var clone = $(this).clone();
   $(this).remove();
   $('#already-added').append(clone) */
}).on('beforeDeleteRow', function(e, row){
    // row - HTML container of the current row for removal.
    // For TableRenderer it is tr.multiple-input-list__item
    console.log('calls on before remove row event.');
    return confirm('Are you sure you want to delete row?')
}).on('afterDeleteRow', function(e, row){
    console.log('calls on after remove row event');
    console.log(row);
});
")
?>
