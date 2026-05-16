<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model frontend\models\ServiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blank-box"></div>
<section class="step-heading">
    <div class="container">
        <div class="step-hinner">
            <h2>Vous ne serez prélevé qu'une fois laréparation validée</h2>
            <h4>Votre informations sur la carte</h4>
        </div>
    </div>
</section>
<?php // Alert::widget() ?>

<section class="select-block step-arrow">
    <div class="container">
        <div class="select-heading">
            <h2>Votre devis : €<?php echo $Cost ?></h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
        </div>
        <div class="step-3">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-6">
                    <div class="step-2-inner step-3-inner">
                        <div class="addres-box step-2-content">
                            <h4>Votre réparation</h4>
                            <?php foreach ($Products as $product):?>
                            <p><?php echo $product?></p>
                            <?php endforeach; ?>
                            <h4>Vos information</h4>
                            <p>Kyle Roger<br>
                                +1 675432789<br>
                                Kyle@testemail.com
                            </p>
                            <h4>Addresse de retour</h4>
                            <p>123 Boulevard Haussmann,75008<br>
                                Paris, France</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 pull-right">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'step-3-form clearfix'
                        ]
                    ]) ?>
                    <?php echo $form->field($model, 'card_nuber')->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Credit Card No',
                        'class' => 'input-inner'
                    ])->label(false) ?>
                    <div class="col-md-12 pn">
                        <div class="col-md-6 pn pr5">
                            <?php echo $form->field($model, 'date_to')->textInput([
                                'placeholder' => 'Valide to mm/yy',
                                'class' => 'input-inner'
                            ])->label(false) ?>
                        </div>
                        <div class="col-md-6 pn">
                            <?php echo $form->field($model, 'cv_code')->textInput([
                                'placeholder' => 'CV Code',
                                'class' => 'input-inner'
                            ])->label(false) ?>
                        </div>
                    </div>
                    <?php echo Html::submitButton('VALIDER', ['class' => 'valid-btn']) ?>
                    <?php ActiveForm::end() ?>

                    <div class="symn-img">
                        <?php echo Html::img("@web/img/symantec.png") ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>