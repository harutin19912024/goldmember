<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;
use backend\models\TrAttribute;
use backend\models\NewsImages;
use common\models\Language;
use dosamigos\multiselect\MultiSelect;
use dosamigos\multiselect\MultiSelectListBox;
use yii\web\JsExpression;
use backend\models\Attribute;
use backend\models\NewssFilters;
use unclead\multipleinput\MultipleInput;

$languages = Language::find()->asArray()->all();

/* @var $this yii\web\View */
/* @var $model backend\models\news */
/* @var $news_parts_model backend\models\newsParts */
/* @var $form yii\widgets\ActiveForm */
/* @var $newsPackage common\models\newsPackage */
?>
<?php
$template = '<div class="">{label}<div class="">{input}{error}</div></div>';
$templatePrice = '<div class="">{label}<div class=""><div class="input-group">
{input}<span class="input-group-addon"><i class="fa fa-euro"></i></span></div>{error}</div></div>';
if (!$model->isNewRecord) {
    $imagePaths = NewsImages::getImageBynewsId($model->id);
    $defaultImage = NewsImages::getDefaultImageIdBynewsId($model->id);
    $formId = 'newsUpdate';
    $action = '/news/update?id=' . $model->id;
    $tabsName = Yii::t('app', 'Update news');
} else {
    $formId = 'newsCreate';
    $action = '/news/create';
    $tabsName = Yii::t('app', 'Add New news');
}
?>
<div class="admin-form">
    <?= Html::a(Yii::t('app', 'Back to list'), ['/news/index'], ['class' => 'btn btn-primary mb15']) ?>
    <div class="panel sort-disable mb50" id="p2" data-panel-color="false" data-panel-fullscreen="false"
         data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title"><?php echo Yii::t('app', 'General') ?></span>
            <span style="float: left;" class="panel-controls"><a href="#" class="panel-control-loader"></a><a
                        href="#" style="margin-left: 5px" class="panel-control-collapse"></a></span>
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
                            <a href="#tab_<?php echo $value['id'] ?>" data-toggle="tab"
                               onclick="editNewsTr(<?php echo $value['id']; ?>,<?php echo $model->id; ?>,<?php echo $value['is_default']; ?>)"
                               disabled="disabled">
                                <span class="flag-xs flag-<?php echo $value['short_code'] ?>"></span>
                            </a>
                        </li>
                    <?php
                    endforeach;
                }
                ?>
            </ul>
        </div>
        <div class="panel-body" style="display: block;">
            <div class="tab-content pn br-n admin-form">
                <div class="tab-pane" id="tr_news"></div>

                <div class="tab-pane active" id="tab_<?php echo $defoultId; ?>">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => [$action],
                        'id' => $formId,
                        'options' => ['enctype' => 'multipart/form-data']
                    ]);
                    ?>
                    <div class="tab-content pn br-n admin-form">
                        <div class="tab-content row">
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'title', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>'])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')])->label(false)
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'category_id', ['template' => $template])->widget(Select2::className(), [
                                    'data' => $model->getAllCategories(),
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app', 'Select Category'), 'onchange' => 'getAttributes(this.value)'], //'onchange'=>'getnewsAttr(this.value,"'.Yii::$app->language.'")'
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
                                $form->field($model, 'route_name', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                {input}<label for="customer-name" class="field-icon"><i class="fa fa-link"></i></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Route Name')])->label(false)
                                ?>

                            </div>
                            <div class="col-md-3">
                                <?php $model->status = 1 ?>
                                <?=
                                $form->field($model, 'status', ['template' => $template])->widget(Select2::className(), [
                                    'data' => [Yii::t('app', "Unavailable"), Yii::t('app', "Available")],
                                    'language' => Yii::$app->language,
                                    'options' => ['placeholder' => Yii::t('app', 'Select Status')],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => false,
                                    ],
                                    'pluginLoading' => false,
                                ])->label(false)
                                ?>
                            </div>
                        </div>
                        
                        <div class="section row">
                            <div class="col-md-6">
                                <?=
                                $form->field($model, 'video_url', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                {input}<label for="customer-name" class="field-icon"><i class="fa fa-link"></i></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Video URL')])->label(false)
                                ?>

                            </div>
                            
                        </div>
                        <div class="section row">
                            <div class="col-md-2">
                                <div class="panel-body clearfix p10 ph15">
                                    <label class="switch ib switch-primary pull-left input-align mt10">
                                        <input type="hidden" name="News[top_news]" value="0"/>
                                        <input type="checkbox" name="News[top_news]"
                                               id="news-top_news"
                                               value="1" <?php echo ($model->top_news == 1) ? 'checked' : '' ?>
                                               onchange="togglePrimaryNews(this);">
                                        <label for="news-top_news" data-on="YES"
                                               data-off="NO"></label>
                                        <span><?= Yii::t('app', "Top news") ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="panel-body clearfix p10 ph15">
                                    <label class="switch ib switch-primary pull-left input-align mt10">
                                        <input type="hidden" name="News[latest_news]" value="0"/>
                                        <input type="checkbox" name="News[latest_news]"
                                               id="news-latest_news"
                                               value="1" <?php echo ($model->latest_news == 1) ? 'checked' : '' ?>>
                                        <label for="news-latest_news" data-on="YES"
                                               data-off="NO"></label>
                                        <span><?= Yii::t('app', "Latest News") ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2"
                                 id="is_primary_news">
                                <div class="panel-body clearfix p10 ph15">
                                    <label class="switch ib switch-primary pull-left input-align mt10">
                                        <input type="hidden" name="News[is_primary_news]" value="0"/>
                                        <input type="checkbox" name="News[is_primary_news]"
                                               id="news-is_primary_news"
                                               value="1" <?php echo ($model->is_primary_news == 1) ? 'checked' : '' ?>>
                                        <label for="news-is_primary_news" data-on="YES"
                                               data-off="NO"></label>
                                        <span><?= Yii::t('app', "Is Primary News") ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2"
                                 id="running_line">
                                <div class="panel-body clearfix p10 ph15">
                                    <label class="switch ib switch-primary pull-left input-align mt10">
                                        <input type="hidden" name="News[running_line]" value="0"/>
                                        <input type="checkbox" name="News[running_line]"
                                               id="news-running_line"
                                               value="1" <?php echo ($model->running_line == 1) ? 'checked' : '' ?>>
                                        <label for="news-running_line" data-on="YES"
                                               data-off="NO"></label>
                                        <span><?= Yii::t('app', "Running Line News") ?></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                        
                    </div>
                    <!-- end section -->

                    <h2 style="text-align: center"><?php echo Yii::t('app', 'Content') ?></h2>
                    <div class="section row">
                        <div class="col-md-6 pl15">
                            <div class="section mb10">
                                <label for="customer-name"
                                       class="field prepend-icon"><?= Yii::t('app', 'Short Description') ?></label>
                                <?=
                                $form->field($model, 'short_description', ['template' => '<div class="col-md-12" style="padding: 0">{input}{error}</div>'])
                                    ->textarea(['maxlength' => true])
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6 pl15">
                            <div class="section mb10">
                                <label for="customer-name"
                                       class="field prepend-icon"><?= Yii::t('app', 'Description') ?></label>
                                <?=
                                $form->field($model, 'content', ['template' => '<div class="col-md-12" style="padding: 0">{input}{error}</div>'])
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
                        <div class="section row mbn">
                            <div class="col-md-12 pl15">
                                <div class="gallery-page sb-l-o sb-r-c onload-check">
                                    <div class="">
                                        <div id="mix-container">
                                            <div class="fail-message">
                                                <span><?php echo Yii::t('app', 'No images were found for the selected news') ?></span>
                                            </div>
                                            <?php if (!empty($imagePaths)) : ?>
                                                <?php foreach ($imagePaths as $key => $imagePath): ?>
                                                    <div style="display: inline-block;width: 145px; float: left;"
                                                         class="mix label1 folder1 <?= @($defaultImage[$key] == $key) ? 'default-view' : '' ?>"
                                                         id="image_<?php echo $key ?>">
                                                      <span class="close remove">
                                                        <i class="fa fa-close icon-close-news"></i>
                                                      </span>
                                                        <div class="panel p6 pbn">
                                                            <div class="of-h">
                                                                <?php
                                                                echo Html::img('/uploads/images/news/' . $model->id . '/' . $imagePath, [
                                                                    'class' => 'img-responsive',
                                                                    'title' => $model->title,
                                                                    'alt' => '',
                                                                ])
                                                                ?>
                                                                <div class="row table-layout change_image_news"
                                                                     data-key="<?php echo $key ?>">
                                                                    <div class="col-xs-8 va-m pln">
                                                                        <h6><?= $model->title . '.jpg' ?></h6>
                                                                    </div>
                                                                    <div class="col-xs-4 text-right va-m prn">
                                                                        <span class="fa fa-eye-slash fs12 text-muted"></span>
                                                                        <span class="fa fa-circle fs10 text-info ml10"></span>
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
                        </div>
                    <?php endif; ?>
                    <div class="panel-footer text-right">
                        <?=
                        Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                            'class' => $model->isNewRecord ? 'button btn-lg btn-primary ' : 'button btn-lg btn-success',
                            'id' => $formId,
                            'type' => 'button'
                        ])
                        ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->registerJs("
            CKEDITOR.replace('news-content'); 
            CKEDITOR.replace('news-short_description'); 
"); ?>
<?php
$this->registerJs("

$('#news-title').on('focusout',function(){
	if($('#news-route_name').val() == ''){
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
   $('#news-route_name').val(rout_name);
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