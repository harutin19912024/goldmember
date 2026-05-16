<?php

/* @var $this \yii\web\View */
/* @var $model \common\models\LoginForm */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\authclient\widgets\AuthChoice;
use kartik\growl\Growl;
$this->title = Yii::t('app','SANSTROY').' | '.Yii::t('app','Email');
?>

     
		<div class="container">
                <div class="row wrapper-main">
				       <?php
                $mess = Yii::$app->session->getFlash('notvalid');
                if (isset($mess) && $mess) {
                    echo Growl::widget([
                        'type' => Growl::TYPE_SUCCESS,
                        'title' => '',
                        'icon' => 'fa fa-check-square-o',
                        'body' => $mess,
                        'showSeparator' => true,
                        'delay' => 0,
                        'pluginOptions' => [
                            'showProgressbar' => false,
                            'placement' => [
                                'from' => 'top',
                                'align' => 'right',
                            ]
                        ]
                    ]);
                }
                ?>
            <?php
        $error = Yii::$app->session->getFlash('error');

        if (isset($error) && $error) {
            echo Growl::widget([
                'type' => Growl::TYPE_DANGER,
                'title' => '',
                'icon' => 'fa fa-exclamation-triangle',
                'body' => $error,
                'showSeparator' => true,
                'delay' => 1000,
                'pluginOptions' => [
                    'showProgressbar' => false,
                    'placement' => [
                        'from' => 'top',
                        'align' => 'right',
                    ]
                ]
            ]);
        }
        ?>
		<?= $this->render('/site/category',['choosen_category'=>0]) ?>
			<div class="col-lg-9 col-sm-8">
                <div class="row">
                    <div class="sign-wrap">
                        <h4 class="text-center" style="color:#000;">Пожалуйста Заполните ваш емайл</h4>
                        <?php $form = ActiveForm::begin(['action'=>'/'.Yii::$app->language.'/site/zapolnit-email','options' => ['class'=>'form-signin']]) ?>
                        
                        <div class="form-group">
                            <i class="material-icons">mail</i>
                            <label for="inputEmail" class="sr-only"><?=Yii::t('app','Email')?></label>
                            <?= $form->field($model, 'email',
                                ['template' => '{input}{label}{error}'])
                                ->textInput(["placeholder" => Yii::t('app','Email'),'type'=>'email','id'=>'inputEmail', "class" => "form-control",'required'=>true])
                                ->label(false) ?>
                        </div>
                        <?php echo Html::submitButton('Отправить',['class'=>'btn btn-lg btn-primary btn-block']) ?>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


