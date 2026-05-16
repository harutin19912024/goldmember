<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\TrProduct;

/* @var $this yii\web\View */
/* @var $model backend\models\AttributeSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$template = '<div class="col-md-12" style="padding: 0"><label for="repairer-lat" class="field prepend-icon">
         {input}<label for="repairer-lat" class="field-icon"><i class="fa fa-tags"></i></label></label>{error}</div>';
?>
<div class="attribute-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'id' => 'attr-search'
    ]);
    ?>
    <div class="section row">
        <div class="col-md-12">
            <?= $form->field($model, 'name', ['template' => $template])->textInput(['placeholder' => Yii::t('app', 'Name')])->label(false) ?>
        </div>
    </div>
    <hr class="short">
    <div class="section">
        <div class="form-group">
<?= Html::submitButton('Поиск', ['class' => 'btn btn-default btn-sm ph25']) ?>
<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default btn-sm ph25']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>

</div>
