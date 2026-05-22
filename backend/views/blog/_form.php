<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use common\models\Language;

$languages = Language::find()->asArray()->all();

/* @var $this yii\web\View */
/* @var $model backend\models\Blog */
/* @var $form yii\widgets\ActiveForm */


if (!$model->isNewRecord) {
    $formId = 'blogUpdate';
    $action = '/blog/update?id=' . $model->id;

} else {
    $formId = 'blogCreate';
    $action = '/blog/create';
}
?>

<div class="blog-form admin-form">
    <?= Html::a(Yii::t('app', 'Back to list'), ['/blog/index'], ['class' => 'btn btn-primary mb15']) ?>

    <?php $form = ActiveForm::begin([
        'action' => [$action],
        'id' => $formId,
    ]); ?>

    <div class="panel sort-disable mb50" id="p2" data-panel-color="false"
         data-panel-fullscreen="false" data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title"><?= Yii::t('app', 'Blog') ?></span>
            <?php if (!$model->isNewRecord): ?>
            <ul class="nav panel-tabs-border panel-tabs">
                <?php foreach ($languages as $lang): ?>
                    <li class="<?= $lang['is_default'] ? 'active' : '' ?>">
                        <a href="#tab_bl_<?= $lang['id'] ?>" data-toggle="tab"
                           onclick="editBlogTr(<?= $lang['id'] ?>, <?= $model->id ?>, <?= $lang['is_default'] ?>)">
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
                <div class="tab-pane" id="tr_blog"></div>
                <div class="section row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'title',
                            ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
                            ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')])->label(false) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'short_description',
                            ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
                            ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Short Description')])->label(false) ?>
                    </div>
                </div>
                <div class="section row">
                    <div class="col-md-12"><?= $form->field($model, 'description',
                            ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-comments-o" aria-hidden="true"></i></label></label>{error}</div>'])
                            ->textarea(['rows' => 6, 'placeholder' => Yii::t('app', 'Description')])->label(false) ?></div>
                </div>
                <div class="section row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'meta_key',
                            ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
                            ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Meta Key')])->label(false) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'meta_description',
                            ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
                            ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Meta Description')])->label(false) ?>
                    </div>
                    <div class="col-md-4">
                        <?php $model->status = 1; ?>
                        <?= $form->field($model, 'status')->widget(Select2::className(), [
                            'data' => ["Pasive", "Active"],
                            'language' => Yii::$app->language,
                            'options' => ['placeholder' => 'Select Status ...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => false
                            ],
                        ])->label(false) ?>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                        [
                            'class' => $model->isNewRecord ? 'btn btn-sm btn-primary pull-right ' : 'btn btn-sm btn-success pull-right',
                            'id' => $formId,
                            'type' => 'button'
                        ]) ?>
                    <?php if (!$model->isNewRecord) {
                        echo Html::a(Yii::t('app', 'Reset'), Url::to('/' . Yii::$app->language . '/blog/index', true), ['class' => 'btn btn-default btn-sm ph25 reste-button pull-right']);
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

