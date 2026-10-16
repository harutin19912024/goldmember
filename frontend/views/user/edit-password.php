<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\EditPassword */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Change Password');
?>

<?= $this->render('/site/partials/breadcrumb', ['prev' => 'My Profile', 'prevUrl' => Url::to(['/user/profile']), 'current' => 'Change Password']) ?>

<section class="container py-5 px-sm-0">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
                <div class="px-4 py-3" style="background: linear-gradient(135deg,#0d2020 0%,#0a1818 100%);">
                    <h5 class="mb-0 fw-semibold" style="color:#53E2D9;">
                        <i class="bi bi-key me-2"></i><?= Yii::t('app', 'Change Password') ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php $form = ActiveForm::begin(['id' => 'edit-password-form']); ?>
                    <?= $form->field($model, 'old_password')->passwordInput(['autocomplete' => 'current-password']) ?>
                    <?= $form->field($model, 'new_password')->passwordInput(['autocomplete' => 'new-password']) ?>
                    <?= $form->field($model, 'confirm_password')->passwordInput(['autocomplete' => 'new-password']) ?>
                    <div class="d-flex gap-2 mt-4">
                        <?= Html::submitButton(Yii::t('app', 'Update Password'), [
                            'class' => 'btn fw-semibold px-4',
                            'style' => 'background:#D4A017;color:#000;',
                        ]) ?>
                        <?= Html::a(Yii::t('app', 'Cancel'), ['/user/profile'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
