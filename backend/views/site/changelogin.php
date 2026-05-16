<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categoyr-form">
    <?= Html::a('Back to translation list', ['/source-message/index'], ['class' => 'btn btn-primary mb15']) ?>
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
                        <div class="form-group">
                            <div class="col-md-6">
                                <?=
                                        $formSource->field($model, 'email', ['template' => '<div class="col-md-12" style="padding: 0"><label for="repairer-zip" class="field prepend-icon">
                                    {input}<label for="repairer-zip" class="field-icon"><i class="fa fa-tags" aria-hidden="true"></i></label></label>{error}</div>'])
                                        ->textInput(['maxlength' => true, 'placeholder' => 'Word Translation','value'=>$model->email])->label(false)
                                ?>
                            </div>                       
                            <div class="col-md-6">
                            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary', 'type' => 'button']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                    </div>
                   
                </div>
            </div>
        </div>
</div>
