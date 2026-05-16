<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\MessageSystem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-system-form">
    <?php
    if (!$model->isNewRecord) {
        $formId = 'message-system-Update';
        $action = '/message-system/update?id=' . $model->id;
    } else {
        $formId = 'message-system-Create';
        $action = '/message-system/create';
    }


    ?>

    <?php $form = ActiveForm::begin([
        'action' => [$action],
        'id' => $formId,
    ]); ?>
    <div class="panel mb25">
        <div class="panel-heading">
            <span class="panel-title hidden-xs"><?php echo Yii::t('app','Send New Message')?></span>

        </div>
        <div class="panel-body p20 pb10">
            <div class="tab-content pn br-n admin-form">
                <div id="tab1_1" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section">
                                <?= $form->field($model, 'title',
                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                            {input}<label for="customer-name" class="field-icon"><i class="fa fa-user"></i></label></label>{error}</div>'
                                    ])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app','Message Title')])->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section">
                                <?= $form->field($model, 'content',
                                    ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                            {input}<label for="customer-name" class="field-icon"><i class="fa fa-phone"></i></label></label>{error}</div>'
                                    ])->textarea(['rows' => 6, 'placeholder' =>  Yii::t('app','Message Body')])->label(false) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="panel">
        <div class="panel-body pn">
            <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProviderUser,
        'tableOptions' => [
            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
        ],
        'filterRowOptions' => [
            'role' => "row",
        ],
        'summary' => false,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions'=>[

                ],
            ],
            ['attribute' => 'name',

            ],
            ['attribute' => 'surname',

            ],
            ['attribute' => 'email',
                'format' => 'email',

            ],
            // 'status',
            ['attribute' => 'status',
                'contentOptions' => function ($model) {
                    if ($model->status == 0) {
                        return ['class' => "text-danger"];
                    } elseif ($model->status == 1) {
                        return ['class' => "text-success"];
                    }
                },
                'value' => function ($model) {
                    if ($model->status == 0) {
                        return Yii::t('app',"Pasive");
                    } else {
                        return Yii::t('app',"Active");
                    }
                },

            ],
        ],
    ]); ?>

            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Send') : Yii::t('app', 'Resend'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php
        if(!$model->isNewRecord) {
            echo Html::a(Yii::t('app', 'Reset'), Url::to('/' . Yii::$app->language . '/product/index', true), ['class' => 'btn btn-default btn-sm ph25 reste-button-messageSystem']);

        }?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
