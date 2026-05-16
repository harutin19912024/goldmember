<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;
use backend\models\Attribute;
use backend\models\ProductImage;
use yii\helpers\Url;
use common\models\Language;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $product_attribute_model backend\models\ProductAttribute */
/* @var $product_parts_model backend\models\ProductParts */
/* @var $form yii\widgets\ActiveForm */
$languages = Language::find()->asArray()->all();
?>
<?php
$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
$templatePrice = '<div class="">{label}<div class=""><div class="input-group">
{input}<span class="input-group-addon"><i class="fa fa-euro"></i></span></div>{error}</div></div>';

$imagePaths = ProductImage::getImageByProductId($model->id);
$defaultImage = ProductImage::getDefaultImageIdByProductId($model->id);
$formId = 'productUpdate';
$action = '/product/update?id=' . $model->id;
$tabsName = Yii::t('app', 'Update Product');
?>
<div class="admin-form">
<div class="panel sort-disable mb50" id="p2" data-panel-color="false" data-panel-fullscreen="false" data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title"><?php echo Yii::t('app', 'Add New Brand')?></span>
            <span style="float: left;" class="panel-controls"><a href="#" class="panel-control-loader"></a><a href="#" style="margin-left: 5px" class="panel-control-collapse"></a></span>
            <ul class="nav panel-tabs-border panel-tabs">

                <?php  if(!$model->isNewRecord){
                    foreach($languages as $value): ?>
                        <li class="<?php if($value['is_default']){$defoultId =$value['id']; echo 'active';}?>">
                            <a href="#tab_<?php echo $value['id']?>"  data-toggle="tab" onclick="" disabled="disabled">
                                <span class="flag-xs flag-<?php echo $value['short_code']?>"></span>
                            </a>
                        </li>
                    <?php endforeach;
                }
                ?>


            </ul>

        </div>
<?php
$form = ActiveForm::begin([
            'action' => [$action],
            'id' => $formId,
            'options' => [
                'enctype' => 'multipart/form-data',
//            'onsubmit' =>'FormValidate()',
            ]
                ]
);
?>
    <div class="panel-body"  style="display: block;">
            <div class="tab-content pn br-n admin-form">
                <div class="tab-pane active" id="tab_<?php echo $defoultId;?>">
                    <div class="tab-content row">
                <div class="col-md-3">
                    <?=
                            $form->field($model, 'name', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>'])
                            ->textInput(['maxlength' => true, 'placeholder' => 'Product Name'])->label(false)
                    ?>
                </div>

                <div class="col-md-3">

                    <?=
                            $form->field($model, 'product_sku', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-barcode"></i></label></label>{error}</div>'])
                            ->textInput(['maxlength' => true, 'placeholder' => 'Product SKU'])->label(false)
                    ?>
                </div>

                <div class="col-md-3">
                    <?=
                            $form->field($model, 'price', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-euro"></i></label></label>{error}</div>'])
                            ->textInput(['placeholder' => 'Product Price'])->label(false)
                    ?>
                </div>
                <div class="col-md-3">
<?=
        $form->field($model, 'weight', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}</label>{error}</div>'])
        ->textInput(['placeholder' => Yii::t('app', 'Product Weight')])->label(false)
?>
                </div>
                <!-- end section -->

            </div>
            <div class="section row">
                <div class="col-md-4">
                    <?=
                    $form->field($model, 'brand_id', ['template' => $template])->widget(Select2::className(), [
                        'data' => $model->getAllBrands(),
                        'language' => Yii::$app->language,
                        'options' => ['placeholder' => Yii::t('app', 'Select Brand ...')],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => false,
                        ],
                    ])->label(false)
                    ?>
                </div>
                <div class="col-md-4">
                    <?=
                    $form->field($model, 'status', ['template' => $template])->widget(Select2::className(), [
                        'data' => [Yii::t('app', "Unavailable"), Yii::t('app', "Available"), Yii::t('app', "Low Stock"), Yii::t('app', "Out of Stock")],
                        'language' => Yii::$app->language,
                        'options' => ['placeholder' => Yii::t('app', 'Select Status ...')],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => false,
                        ],
                    ])->label(false)
                    ?>
                </div>
                <div class="col-md-4">
                    <?=
                    $form->field($model, 'category_id', ['template' => $template])->widget(Select2::className(), [
                        'data' => $model->getAllCategories(),
                        'language' => Yii::$app->language,
                        'options' => ['placeholder' => Yii::t('app', 'Select Category ...'), 'onchange' => 'getAttributes(this.value)'], //'onchange'=>'getProductAttr(this.value,"'.Yii::$app->language.'")'
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => false,
                        ],
                    ])->label(false)
                    ?>
                </div>
                <!-- end section -->
            </div>
            <div class="section row ">
                <div class="col-md-4">
                    <?=
                            $form->field($model, 'product_count', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-euro"></i></label></label>{error}</div>'])
                            ->textInput(['placeholder' => Yii::t('app', 'Product Count')])->label(false)
                    ?>

                </div>
            </div>
            <div class="section row " id="category_attributes">
<?php foreach ($attributes as $attribute): ?>
                    <div class="col-md-4">
                        <div class="option-group field">
                            <label class="block mt15 option option-primary" for="attribute_<?php echo $attribute['id'] ?>">
                                <input type="checkbox" checked="checked" onclick="attributeChecked('<?php echo $attribute['id'] ?>', this.checked)" name="attribute_checked" id="attribute_<?php echo $attribute['id'] ?>">
                                <span class="checkbox"></span><?php echo $attribute->getAttibute($attribute['attribute_id'])[0]['name'] ?>
                            </label>
                            <div class="form-group">
                                <input type="text" name="ProductAttribute[value][<?php echo $attribute['attribute_id'] ?>]" value="<?php echo $attribute['value'] ?>" class="form-control" id="attribute_value_<?php echo $attribute['id'] ?>" placeholder="Attribute Value">
                            </div>
                        </div>
                    </div>
<?php endforeach; ?>
            </div>

        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title"><?php echo Yii::t('app', 'Description') ?></span>
        </div>
        <div class="panel-body">
            <div class="section row mbn">
                <div class="col-md-6 pt15">
                    <?=
                            $form->field($model, 'imageFiles[]', ['template' => '<div><div class="box">{input}{label}{error}</div></div>'])
                            ->fileInput(
                                    [
                                        'multiple' => true,
                                        'accept' => 'image/*',
                                        'onchange' => 'showMyImage(this, -1)',
                                        'class' => 'inputfile inputfile-6',
                                        'data-multiple-caption' => "{count} files selected",
                            ])->label('<span></span> <strong class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&ensp;Brows…</strong>', ['class' => ''])
                    ?>
                    <div class="hidden" id="defaultimg">
                        <input type="radio" id="def_img_part_-1" name="defaultImage" value="" class="hidden"/>
                    </div>
                    <div class="col-md-12 pt15" id="selectedFiles_-1">

                    </div>
                </div>
                <div class="col-md-6 pl15">
                    <div class="section mb10">
                        <?=
                                $form->field($model, 'short_description', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-comment"></i></label></label>{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Short Description'])->label(false)
                        ?>
                    </div>
                    <div class="section mb10">
<?=
        $form->field($model, 'description', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-comments"></i></label></label>{error}</div>'])
        ->textarea(['rows' => 6, 'class' => 'form-control', 'placeholder' => 'Description'])->label(false)
?>
                    </div>

                </div>
<?php if (!$model->isNewRecord): ?>
                    <div class="col-md-6 pl15 pull-right">
                        <div class="gallery-page sb-l-o sb-r-c onload-check">
                            <div class="">
                                <div id="mix-container">
                                    <div class="fail-message">
                                        <span><?php echo Yii::t('app', 'No images were found for the selected product') ?></span>
                                    </div>

                                                <?php if (!empty($imagePaths)) : ?>
                                                    <?php foreach ($imagePaths as $key => $imagePath): ?>
                                            <div style="display: inline-block;" class="mix label1 folder1 <?= ($defaultImage[$key] == $key) ? 'default-view' : '' ?>" id="image_<?php echo $key ?>">
                                                <span class="close remove">
                                                    <i class="fa fa-close icon-close"></i>
                                                </span>
                                                <div class="panel p6 pbn">
                                                    <div class="of-h">
            <?php
            echo Html::img('/uploads/images/' . $imagePath, [
                'class' => 'img-responsive',
                'title' => $model->name,
                'alt' => '',
            ])
            ?>
                                                        <div class="row table-layout change_image"
                                                             data-key="<?php echo $key ?>">
                                                            <div class="col-xs-8 va-m pln">
                                                                <h6><?= $model->name . '.jpg' ?></h6>
                                                            </div>
                                                            <div class="col-xs-4 text-right va-m prn">
                                                                <span
                                                                    class="fa fa-eye-slash fs12 text-muted"></span>
                                                                <span
                                                                    class="fa fa-circle fs10 text-info ml10"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
        <?php endforeach; ?>
    <?php endif; ?>
                                    <div class="gap"></div>
                                    <div class="gap"></div>
                                    <div class="gap"></div>
                                    <div class="gap"></div>

                                </div>
                            </div>
                        </div>
                    </div>
<?php endif; ?>
            </div>
        </div>
        <div class="panel-footer text-right">
<?=
Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
    'class' => $model->isNewRecord ? 'button btn-lg btn-primary ' : 'button btn-sm btn-success',
    'id' => $formId,
    'type' => 'button'
])
?>
<?php echo Html::a(Yii::t('app', 'Reset'), Url::to('/' . Yii::$app->language . '/product/index', true), ['class' => 'btn btn-default btn-sm ph25 reste-button-product']); ?>
        </div>
    </div>


</div>

<?php ActiveForm::end(); ?>
</div>

