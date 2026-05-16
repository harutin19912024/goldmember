<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\Marks $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="marks-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-612">
     <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>
    
    <?php if($isGallery):?>
    <div class="section row mbn">
                    <div class="col-md-12 pt15">
                        <?=
                        $form->field($model, 'path[]', ['template' => '<div><div class="box">{input}{label}{error}</div></div>'])
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
    <?php else:?>
     <div class="col-md-612">
     <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
    </div>
    <?php endif;?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
