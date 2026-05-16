<?php
use yii\bootstrap\ActiveForm;
//var_dump($countries); die;
?>

<h3>Адрес</h3>
<?php $form = ActiveForm::begin(['options' => ['class'=>'col-xs-12']]) ?>
    <div class="clearfix"></div>
    <?= $form->field($model, 'address',
        ['template' => '<div class="form-group">
                        <fieldset class="form-fieldset ui-input">
                            {input}
                            {label}
                            <div class="input_error">{error}</div>
                        </fieldset>                                    
                     </div>'])
        ->Input([ "class" => "form-control", 'required'=>true])
    ?>
    <div class="clearfix"></div>
    <?= $form->field($model, 'zip',
        ['template' => '<div class="form-group">
                        <fieldset class="form-fieldset ui-input">
                            {input}
                            {label}
                            <div class="input_error">{error}</div>
                        </fieldset>                                    
                     </div>'])
        ->Input([ "class" => "form-control", 'required'=>true])
    ?>
    <div class="clearfix"></div>

    <button class="btn btn-success pull-right" type="submit">Сохранить</button>
<?php ActiveForm::end() ?>

