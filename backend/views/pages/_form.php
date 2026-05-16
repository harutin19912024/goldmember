<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use common\models\Language;
use yii\widgets\Pjax;

$languages = Language::find()->asArray()->all();

/* @var $this yii\web\View */
/* @var $model backend\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
if (!$model->isNewRecord) {
    $formId = 'pagesUpdate';
    $action = '/pages/update?id=' . $model->id;
} else {
    $formId = 'pagesCreate';
    $action = '/pages/create';
}
?>
<div class="pages-form">
    <?= Html::a('Back to page list', ['/pages/index'], ['class' => 'btn btn-primary mb15']) ?>
    <div class="panel sort-disable mb50" id="p2" data-panel-color="false" data-panel-fullscreen="false"
         data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span
                class="panel-title"><?php echo ($model->isNewRecord) ? Yii::t('app', 'Add New Page') : Yii::t('app', 'Update Brand') ?></span>
            <span style="float: left;" class="panel-controls"><a href="#" class="panel-control-loader"></a><a href="#"
                                                                                                              style="margin-left: 5px"
                                                                                                              class="panel-control-collapse"></a></span>
            <ul class="nav panel-tabs-border panel-tabs">

                <?php if (!$model->isNewRecord) {
                    foreach ($languages as $value):
                        ?>
                        <li class="<?php if ($value['is_default']) {
                            $defoultId = $value['id'];
                            echo 'active';
                        } ?>">
                            <a href="#tab_<?php echo $value['id'] ?>" data-toggle="tab"
                               onclick="editPagesTr(<?php echo $value['id']; ?>,<?php echo $model->id; ?>,<?php echo $value['is_default']; ?>)">
                                <span class="flag-xs flag-<?php echo $value['short_code'] ?>"></span>
                            </a>
                        </li>
                    <?php endforeach;
                } ?>
            </ul>
        </div>

        <div class="panel-body" style="display: block;">
            <div class="tab-content pn br-n admin-form">
                <div class="tab-pane" id="tr_pages"></div>

                <div class="tab-pane active" id="tab_<?php echo $defoultId; ?>">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => [$action],
                        'id' => $formId,
                    ]);
                    ?>
                    <div class="tab-content row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'title', ['template' => '{label}<div class="">{input}{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Page Title')])->label(false)
                            ?>
                        </div>
						<div class="col-md-4">
                            <?=
                            $form->field($model, 'parent_id')->widget(Select2::className(), [
                                'data' => $model->getParentPages(),
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
						<div class="col-md-4">
                            <?php $model->status = 1; ?>
                            <?=
                            $form->field($model, 'status')->widget(Select2::className(), [
                                'data' => [Yii::t('app', "Pasive"), Yii::t('app', "Active")],
                                'language' => Yii::$app->language,
                                'options' => ['placeholder' => Yii::t('app', 'Select Status ...')],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => false,
                                ],
                                'pluginLoading' => false,
                            ])->label(false)
                            ?>
                        </div>
						</div>
						 <div class="tab-content row">
                        <div class="col-md-12">
							<label><?=Yii::t('app','Content')?></label>
                            <?= $form->field($model, 'content')
                                ->textarea(['rows' => 6, 'placeholder' => 'Page Content'])->label(false)
                            ?>
                        </div>
                        </div>
						 <div class="tab-content row">
						 <div class="col-md-4">
                            <?= $form->field($model, 'route_name', ['template' => '{label}<div class="">{input}{error}</div>'])
                                ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Rout Name')])->label(false)
                            ?>
                        </div>
						<div class="col-md-4">
                            <?=
                            $form->field($model, 'type')->widget(Select2::className(), [
                                'data' => [Yii::t('app', "Header"), Yii::t('app', "Footer")],
                                'language' => Yii::$app->language,
                                'options' => ['placeholder' => Yii::t('app', 'Select Position')],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => false,
                                ],
                                'pluginLoading' => false,
                            ])->label(false)
                            ?>
                        </div>
						<div class="col-md-4">
                            <?=
                            $form->field($model, 'position')->widget(Select2::className(), [
                                'data' => [Yii::t('app', "Header"), Yii::t('app', "Footer")],
                                'language' => Yii::$app->language,
                                'options' => ['placeholder' => Yii::t('app', 'Select Position')],
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
                        <div class="col-md-6 pt15">
                            <?=
                                    $form->field($modelFiles, 'path[]', ['template' => '<div><div class="box">{input}{label}{error}</div></div>'])
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
                                <input type="radio" id="def_img_part_-1" name="defaultImage" value=""
                                       class="hidden"/>
                            </div>
                            <div class="col-md-12 pt15" id="selectedFiles_-1">

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
												
                                                <div style="display: inline-block;"
                                                     class="mix label1 folder1 <?php
                                                             if ($imagePath['top']) {
                                                                 echo 'default-view';
                                                             } else {
                                                                 echo '';
                                                             }
                                                             ?>"
                                                     id="image_<?php echo $imagePath['id'] ?>">
                                                    <span class="close remove">
                                                                    <i class="fa fa-close page-remove-image"></i>
                                                                </span>
                                                    <div class="panel p6 pbn">
													<?php if($imagePath['extension'] != "pdf"):?>
                                                        <div class="of-h">
                                                            <?php
                                                            echo Html::img('/uploads/images/pages/'.$model->id.'/' . $imagePath['path'], [
                                                                'class' => 'img-responsive',
                                                                'title' => $model->title,
                                                                'alt' => '',
                                                            ])
                                                            ?>
                                                            <div class="row table-layout change_image"
                                                                 data-key="<?php echo $imagePath['id'] ?>" page-id="<?=$model->id?>">
                                                                <input type="hidden" value="pages" id="category">
                                                                <div class="col-xs-8 va-m pln">
                                                                    <h6><?= $model->title . '.jpg' ?></h6>
                                                                </div>
                                                                <div
                                                                    class="col-xs-4 text-right va-m prn">
                                                                    <span
                                                                        class="fa fa-eye-slash fs12 text-muted"></span>
                                                                    <span
                                                                        class="fa fa-circle fs10 text-info ml10"></span>
                                                                </div>
                                                            </div>
                                                        </div>
														<?php else:?>
														<div class="of-h">
									<a href="<?='/uploads/images/pages/'.$model->id.'/' . $imagePath['path']?>" target='_blank'>
												
												<?php
                                                            echo Html::img('/images/pdf.png', [
                                                                'class' => 'img-responsive',
                                                                'title' => $model->title,
                                                                'alt' => '',
                                                            ])
                                                            ?>
															<?=Yii::t('app','Presentation PDF')?>
												</a>
												<div class="row table-layout change_image"
                                                                 data-key="<?php echo $imagePath['id'] ?>" product-id="<?=$model->id?>">
                                                                <input type="hidden" value="pages" id="category">
                                                                <div class="col-xs-8 va-m pln">
                                                                    <h6><?= $model->title . '.jpg' ?></h6>
                                                                </div>
                                                                <div
                                                                    class="col-xs-4 text-right va-m prn">
                                                                    <span
                                                                        class="fa fa-eye-slash fs12 text-muted"></span>
                                                                    <span
                                                                        class="fa fa-circle fs10 text-info ml10"></span>
                                                                </div>
                                                            </div>
												</div>
												<?php endif;?>
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

                    <div class="form-group col-md-12">
                        <?=
                        Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                            'class' => $model->isNewRecord ? 'btn btn-sm btn-primary pull-right ' : 'btn btn-sm btn-success pull-right',
                            'id' => $formId,
                            'type' => 'button'
                        ])
                        ?>
                        <?php if (!$model->isNewRecord) {
                            echo Html::a(Yii::t('app', 'Reset'), Url::to('/' . Yii::$app->language . '/brand/index', true), ['class' => 'btn btn-default btn-sm ph25 reste-button pull-right']);
                        }
                        ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </div>

</div>
<?php
$this->registerJs("
$('#pages-title').on('focusout',function(){
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
   
   $('#pages-route_name').val(rout_name);
})
")
?>
<?php echo $this->registerJs("
 var editor = CKEDITOR.replace('pages-content');
"); ?>

