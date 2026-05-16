<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use common\models\Language;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categoyr-form">
    <?= Html::a('Back to translation list', ['/source-message/index'], ['class' => 'btn btn-primary mb15']) ?>
    <?php if (empty($keywords)): ?>
        <div class="alert alert-danger" role="alert">
            <strong><?php echo Yii::t('app', 'Wrong!') ?></strong> <?php echo Yii::t('app', 'Need to create <a href="' . Url::to(['source-message/create']) . '">Source Message</a> first') ?>
        </div>
    <?php else: ?>
        <div class="panel sort-disable mb50" id="p2" data-panel-color="false" data-panel-fullscreen="false" data-panel-remove="false" data-panel-title="false">
            <div class="panel-heading">
                <span class="panel-title"><?php echo Yii::t('app', 'Add New Translation') ?></span>
                <span style="float: left;" class="panel-controls"><a href="#" class="panel-control-loader"></a><a href="#" style="margin-left: 5px" class="panel-control-collapse"></a></span>
                <ul class="nav panel-tabs-border panel-tabs">
                    <li >
                       <a href="#tab_default"  data-toggle="tab" disabled="disabled">
                                Translation Source
                        </a>  
                    </li>
                    <?php  foreach ($languages as $value):  ?>
                            <li class="<?php if ($value['is_default']) { $defoultLanguage->id = $value['id']; echo 'active'; } ?>">
                                <a href="#tab_<?php echo $value['id'] ?>"  data-toggle="tab" disabled="disabled">
                                    <span class="flag-xs flag-<?php echo $value['short_code'] ?>"></span>
                                </a>
                            </li>
                    <?php  endforeach; ?>
                </ul>
            </div>
            <div class="panel-body"  style="display: block;">
                <div class="tab-content pn br-n admin-form">
                    <div class="tab-pane" id="tab_default">
                        <?php
                        $formSource = ActiveForm::begin([
                                    'action' => '/'.Yii::$app->language.'/source-message/update?id='.$model->id,
                                    'id' => 'updateSource',
                        ]);
                        ?>
                        <?= $formSource->field($modelSource, 'category')->hiddenInput(['value'=>'app'])->label(false)?>
                        <div class="form-group">
                            <div class="col-md-6">
                                <?=
                                        $formSource->field($modelSource, 'message', ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
                                        ->textInput(['maxlength' => true, 'placeholder' => 'Word Translation','value'=>$keywords->message])->label(false)
                                ?>
                            </div>                       
                            <div class="col-md-6">
                            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary', 'type' => 'button']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                    </div>
                    <?php  foreach ($languages as $value):  ?>
                    <div class="tab-pane <?php if ($value['is_default']):?> active <?php endif;?>" id="tab_<?php echo $value['id']; ?>">
                        <?php
                            if (strlen($model->getTranslation($translationID,$value['short_code']))) {
                                $formId = 'messageUpdate';
                                $action = '/message/update?id=' . $translationID;
                                $isNewRecord = false;
                            } else {
                                $formId = 'messageCreate';
                                $action = '/message/create';
                                $isNewRecord = true;
                            }
                        ?>
                        <?php
                        $form = ActiveForm::begin([
                                    'action' => [$action],
                                    'id' => $formId,
                        ]);
                        ?>
                        <?= $form->field($model, 'language')->hiddenInput(['value'=>$value['short_code']])->label(false) ?>
                        <?= $form->field($model, 'id')->hiddenInput(['value'=>$translationID])->label(false) ?>
                        <div class="form-group">
                            <div class="col-md-6">
                                <?=
                                        $form->field($model, 'translation', ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
                                        ->textInput(['maxlength' => true, 'placeholder' => 'Word Translation','value'=>$model->getTranslation($translationID,$value['short_code'])])->label(false)
                                ?>
                            </div>                                                   
                            <div class="col-md-6">
                            <?= Html::submitButton($isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'type' => 'button']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                    </div>
                    <?php  endforeach; ?>
                </div>
            </div>
        </div>
<?php endif; ?>
</div>
