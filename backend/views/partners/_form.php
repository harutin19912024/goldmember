<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use common\models\Language;

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
    $formId = 'partnersUpdate';
    $action = '/partners/update?id=' . $model->id;
    $tabsName = Yii::t('app', 'Update partners');
} else {
    $formId = 'partnersCreate';
    $action = '/partners/create';
    $tabsName = Yii::t('app', 'Add partners');
}

?>
<div class="admin-form">
    <?= Html::a(Yii::t('app', 'Back to list'), ['/partners/index'], ['class' => 'btn btn-primary mb15']) ?>
    <div class="panel sort-disable mb50" id="p2" data-panel-color="false" data-panel-fullscreen="false"
         data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title"><?= Yii::t('app', 'Partners') ?></span>
            <?php if (!$model->isNewRecord): ?>
            <ul class="nav panel-tabs-border panel-tabs">
                <?php foreach ($languages as $lang): ?>
                    <li class="<?= $lang['is_default'] ? 'active' : '' ?>">
                        <a href="#tab_pt_<?= $lang['id'] ?>" data-toggle="tab"
                           onclick="editPartnersTr(<?= $lang['id'] ?>, <?= $model->id ?>, <?= $lang['is_default'] ?>)">
                            <span class="flag-xs flag-<?= $lang['short_code'] ?>"></span>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
            <span style="float:left;" class="panel-controls">
                <a href="#" class="panel-control-loader"></a>
                <a href="#" style="margin-left:5px" class="panel-control-collapse"></a>
            </span>
        </div>
        <div class="panel-body" style="display: block;">
            <div class="tab-content pn br-n admin-form">
                <div class="tab-pane" id="tr_partners"></div>

                <div class="tab-pane active">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => [$action],
                        'id' => $formId,
                        'options' => ['enctype' => 'multipart/form-data']
                    ]);
                    ?>
                    <div class="tab-content pn br-n admin-form">
                        <div class="tab-content row">
                            <div class="col-md-12">
                                <?=
                                $form->field($model, 'title', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>'])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')])->label(false)
                                ?>
                            </div>
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
                        </div>
                    </div>
                    <!-- end section -->

                    <div class="section row mbn">
                        <div class="col-md-6 pt15">
                            <?=
                            $form->field($model, 'path', ['template' => '<div><div class="box">{input}{label}{error}</div></div>'])
                                ->fileInput(
                                    [
                                        'multiple' => false,
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
                                                        <i class="fa fa-close icon-close"></i>
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
                                                                <div class="row table-layout change_image"
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