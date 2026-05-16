<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model backend\models\TrBlog */

?>
<div class="tr-blog-form">
    <?php $form = ActiveForm::begin(['action' => ['/tr-blog/update'], 'id' => 'trBlogUpdate']); ?>

    <label style="font-size:18px;color:#0a0e1b;">
        <?= Yii::t('app', 'Blog') ?>: <?= Html::encode($model->blog->title ?? '#' . $model->blog_id) ?>
    </label>
    <div class="clearfix mb10"></div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'short_description')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>
        </div>
    </div>

    <?= $form->field($model, 'language_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'blog_id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
