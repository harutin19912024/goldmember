<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\SocialNet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="social-net-form">
<div class="panel sort-disable mb50" id="p2" data-panel-color="false" data-panel-fullscreen="false" data-panel-remove="false" data-panel-title="false">
        <div class="panel-body"  style="display: block;">
            <div class="tab-content pn br-n admin-form">
                <div class="tab-pane active">
			<?php $form = ActiveForm::begin(); ?>
				<div class="tab-content row">
						<div class="col-md-4">
                            <?=
                                    $form->field($model, 'link', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                    {input}<label for="customer-name" class="field-icon"><i class="fa fa-link"></i></label></label>{error}</div>'])
                                    ->textInput(['placeholder' => Yii::t('app', 'Link')])->label(false)
                            ?>

                        </div>
						<div class="form-group col-md-4">
							<?=
							$form->field($model, 'social_type')->widget(Select2::className(), [
								'data' => ['facebook'=>Yii::t('app', "Facebook"), 'google'=>Yii::t('app','Google'),
											'vk'=>Yii::t('app','Vkontakte'),'youtube'=>Yii::t('app','Youtube'),'tiktok'=>Yii::t('app','Tik Tok'),'instagram'=>Yii::t('app','Instagram')
											,'whatsapp'=>Yii::t('app','Viber')],
								'language' => Yii::$app->language,
								'options' => ['placeholder' => Yii::t('app', 'Select Social Type')],
								'pluginOptions' => [
									'allowClear' => true,
									'multiple' => false,
									'selected' => ['1'],
								],
								'pluginLoading' => false,
							])->label(false)
							?>
						</div>
						<div class="form-group col-md-4">
							<?php $model->active = 1 ?>
							<?=
							$form->field($model, 'active')->widget(Select2::className(), [
								'data' => [Yii::t('app', "Passive"), Yii::t('app', "Active")],
								'language' => Yii::$app->language,
								'options' => ['placeholder' => Yii::t('app', 'Select Status ...')],
								'pluginOptions' => [
									'allowClear' => true,
									'multiple' => false,
									'selected' => ['1'],
								],
								'pluginLoading' => false,
							])->label(false)
							?>
						</div>
				</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
