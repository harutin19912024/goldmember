<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Team */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="team-form">

    <?php $form = ActiveForm::begin([
        'id' => 'team-form',
    ]); ?>
    
    <?= Html::hiddenInput('Crop[x]', '', ['id' => 'crop-x']) ?>
    <?= Html::hiddenInput('Crop[y]', '', ['id' => 'crop-y']) ?>
    <?= Html::hiddenInput('Crop[width]', '', ['id' => 'crop-width']) ?>
    <?= Html::hiddenInput('Crop[height]', '', ['id' => 'crop-height']) ?>

    <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'profession')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
	
	<div class="section row">
                        <div class="col-md-6 pt15">
                            <p>Image Size(960X1280)</p>
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

                                        <?php if (!empty($model->image)) : ?>
                                                <div style="display: inline-block;"
                                                     class="mix label1 folder1 default-view"
                                                     id="image_<?php echo $model->id ?>">
                                                    <span class="close remove">
                                                                    <i class="fa fa-close page-remove-image"></i>
                                                                </span>
                                                    <div class="panel p6 pbn">
                                                        <div class="of-h">
                                                            <?php
                                                            echo Html::img('/uploads/images/team/'.$model->id.'/' . $model->image, [
                                                                'class' => 'img-responsive',
                                                                'id' => 'crop-image',
                                                                'title' => $model->fname,
                                                                'alt' => '',
                                                            ])
                                                            ?>
                                                            <div class="row table-layout change_image" page-id="<?=$model->id?>">
                                                                <input type="hidden" value="pages" id="category">
                                                                <div class="col-xs-8 va-m pln">
                                                                    <h6><?= $model->fname . '.jpg' ?></h6>
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
														
                                                    </div>
                                                </div>
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
<button id="getData">Get Data</button>
    <div class="form-group pull-right">
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

$this->registerCssFile('https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);
?>
<?php
$this->registerJs("
var image = document.getElementById('crop-image');
let cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 1,
            onChange: function() {
                console.log(123)
            }
        });
$('#team-form').on('submit', function () {
    if (typeof cropper !== 'undefined') {
        const cropData = cropper.getData(true);
        $('#crop-x').val(cropData.x);
        $('#crop-y').val(cropData.y);
        $('#crop-width').val(cropData.width);
        $('#crop-height').val(cropData.height);
    }
});
")
?>
