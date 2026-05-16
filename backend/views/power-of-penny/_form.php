<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Language;
use unclead\multipleinput\MultipleInput;
use backend\models\Files;

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
    $imagePaths = Files::find()->where(['category_id' => $model->id, 'category' => 'power-of-penny'])->all();
    $formId = 'newsUpdate';
    $action = '/power-of-penny/update?id=' . $model->id;
    $tabsName = Yii::t('app', 'Update Power Of Panny');
} else {
    $formId = 'newsCreate';
    $action = '/power-of-penny/create';
    $tabsName = Yii::t('app', 'Update Power Of Panny');
}
$defoultId = 1;

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
                            <div class="col-md-6">
                                <?=
                                $form->field($model, 'name', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>'])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')])->label(false)
                                ?>
                            </div>
                            <div class="col-md-6">
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
                            <div class="col-md-12">
                                <?=
                                $form->field($model, 'video_url', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                {input}<label for="customer-name" class="field-icon"><i class="fa fa-link"></i></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Video URL')])->label(false)
                                ?>

                            </div>
                            
                        </div>
                        </div>
                        
                    </div>
                    <!-- end section -->

                    <h2 style="text-align: center"><?php echo Yii::t('app', 'Content') ?></h2>
                    <div class="section row">
                        <div class="col-md-12 pl15">
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
                                                                echo Html::img('/uploads/power-of-penny/news/' . $model->id . '/' . $imagePath->path, [
                                                                    'class' => 'img-responsive',
                                                                    'title' => $model->name,
                                                                    'alt' => '',
                                                                ])
                                                                ?>
                                                                <div class="row table-layout change_image_news"
                                                                     data-key="<?php echo $key ?>">
                                                                    <div class="col-xs-8 va-m pln">
                                                                        <h6><?= $model->name . '.jpg' ?></h6>
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
            CKEDITOR.replace('powerofpenny-content'); 
"); ?>