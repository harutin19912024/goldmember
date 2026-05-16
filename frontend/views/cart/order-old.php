<?php

use yii\helpers\Html;
use frontend\models\CustomerAddress;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Address */
/* @var $CustomerAddress frontend\models\CustomerAddress[] */

$this->title = 'Order';

?>
    <div class="blank-box"></div>
    <section class="step-heading">
        <div class="container">
            <div class="step-hinner">
                <h2>Où souhaitez-vous que l'on retourne votre appareil ?</h2>
                <h4>Vos informations de contact</h4>
            </div>
        </div>
    </section>
    <section class="select-block stp2-block step-arrow">
        <div class="container">
           <div id="usersConnected"></div>
            <div class="step2-head">
                <h4>votre adresse existante</h4>
            </div>
            <div class="step2-block">
                <div class="row">
                    <?php if(!Yii::$app->user->isGuest): ?>
                    <?php foreach ($CustomerAddress as $key => $address): ?>
                        <div class="col-sm-4">
                            <div class="step-2-inner">
                                <div class="step-2-img">
                                    <?php echo Html::img("@web/img/step-2-img.jpg") ?>
                                </div>
                                <div class="step-2-content">
                                    <h4><?php echo $address['name'].' '.$address['surname'] ?></h4>
                                    <address>
                                        <?php echo $address['address'] ?><br>
                                        <?php echo $address['city'] ?>
                                        <?php echo $address['state'] ?><br>
                                        <?php echo $address['country'] ?>
                                    </address>
                                    <h6><span>Phone</span><a href="tel:0987654321">: <?php echo $address['phone'] ?></a></h6>
                                    <h6><span>Email</span><a href="mailto:">: <?php echo $address['email'] ?></a></h6>
                                    <div class="step-2-btn">
                                        <div class="col-md-12 pn">
                                            <div class="col-md-3 pn mr5"><a href="#">Edit </a></div>
                                            <div class="col-md-3 pn mr5"><a href="#">Delete</a></div>
                                            <div class="col-md-3 pn">
                                                <?php echo Html::a("Choose",['/service/pay?address='.$address['id']]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
            </div>
        </div>
    </section>
    <section class="step2-accordion">
        <div class=container>
            <button class="accordion">Ajouter une autre adresse</button>
            <div class="step2-panel">
                <div class="step2-head">
                    <h4>votre adresse existante</h4>
                </div>
                <div class="row">
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <?php if (Yii::$app->user->identity->role == 20): ?>
                            <?php $customer_id = Yii::$app->user->identity->customer->id;
                            $addresses = CustomerAddress::getFullAddressesByCustomerId($customer_id)
                            ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'step-2-form frm-2 clearfix'
                        ]
                    ]) ?>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 20): ?>
                        <?php
                        $isCustomer = true;
                        $name = Yii::$app->user->identity->customer->name;
                        $surname = Yii::$app->user->identity->customer->surname;
                        $email = Yii::$app->user->identity->customer->email;
                        $phone = Yii::$app->user->identity->customer->phone;
                        ?>
                    <?php endif; ?>
                    <div class="col-sm-6">
                        <?php echo $form->field($model, 'name')->textInput([
                            'class' => 'input-inner form-control',
                            'placeholder' => 'Nom Complete',
                            'value' => ($isCustomer) ? $name . ' ' . $surname : ''
                        ])->label(false) ?>
                        <?php echo $form->field($model, 'addr2')->textInput([
                            'class' => 'input-inner form-control',
                            'placeholder' => 'Address2',
                        ])->label(false) ?>
                        <div class="col-md-12 pn" style="vertical-align: top">
                            <div class="col-md-6 pn pr5">
                                <?php echo $form->field($model, 'zip')->textInput([
                                    'class' => 'input-inner zip-inner w100p form-control',
                                    'placeholder' => 'Zip',
                                ])->label(false) ?>
                            </div>
                            <div class="col-md-6 pn pl5">
                                <?php echo $form->field($model, 'country')->dropDownList(
                                    [
                                        "USA" => "USA",
                                        "England" => "England",
                                        "Armenia" => "Armenia"
                                    ],
                                    ['prompt' => 'Country', 'class' => 'form-slct w100p']
                                )->label(false) ?>
                            </div>
                        </div>
                        <?php echo $form->field($model, 'email')->textInput([
                            'class' => 'input-inner form-control',
                            'placeholder' => 'email',
                            'value' => ($isCustomer) ? $email : ''
                        ])->label(false) ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo $form->field($model, 'addr1')->textInput(['class' => 'input-inner form-control', 'placeholder' => 'Address1'])->label(false) ?>
                        <div class="col-md-12 pn" style="vertical-align: top">
                            <div class="col-md-6 pn pr5">
                                <?php echo $form->field($model, 'city')->textInput(
                                    [
                                        'class' => 'input-inner zip-inner w100p form-control', 'placeholder' => 'City'
                                    ]
                                )->label(false) ?>
                            </div>
                            <div class="col-md-6 pn pl5">
                                <?php echo $form->field($model, 'state')->textInput(['class' => 'input-inner state-inner w100p form-control', 'placeholder' => 'State'])->label(false) ?>
                            </div>
                        </div>
                        <?php echo $form->field($model, 'phone')->textInput([
                            'class' => 'input-inner form-control',
                            'placeholder' => 'Phone',
                            'value' => ($isCustomer) ? $phone : ''
                        ])->label(false) ?>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 30px">
                        <?php echo Html::submitButton("enregistrer l'adresse", ['class' => 'submit-btn']) ?>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
                <div class="form-btn-ed">
                    <?php // echo Html::a("enregistrer l'adresse", ['/service/pay']) ?>
                </div>
            </div>
        </div>
    </section>
<?php
$this->registerJs('
$("#address-addr_id").on("change", function(){
    var val = $(this).val();
    var text = $(this).find("[value="+val+"]").text();
    var res = text.split(", ");
    $("#address-state").val(res[1]);
    $("#address-city").val(res[2]);
    $("#address-addr1").val(res[3]);
    $("#address-country").find("option").each(function(i, v){
        if($(v).text() == res[0]){
        console.log($(v));
            $(v).prop("selected", true)
        }
    });
    console.log(res);
});
var acc = document.getElementsByClassName("accordion");
            var i;
            for (i = 0; i < acc.length; i++) {
                acc[i].onclick = function(){
                this.classList.toggle("active");
                this.nextElementSibling.classList.toggle("show");
                }
            }
');
?>