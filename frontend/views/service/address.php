<?php

use yii\helpers\Html;
use frontend\models\CustomerAddress;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Address */
/* @var $CustomerAddress frontend\models\CustomerAddress[] */

$this->title = 'Address';

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
            <div class="step2-head">
                <h4>votre adresse existante</h4>
            </div>
            <div class="step2-block">
                <div class="row" id="address-cont">
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <?php foreach ($CustomerAddress as $key => $address): ?>
                            <div class="col-md-4 pt15" id="<?php echo $address['id'] ?>">
                                <div class="step-2-inner">
                                    <div class="step-2-content">
                                        <h4><?php echo $address['name'] . ' ' . $address['surname'] ?></h4>
                                        <address>
                                            <div class="addr">
                                                <?php echo $address['address'] ?>
                                            </div>
                                            <div class="cs">
                                                <?php echo $address['city'] ?>
                                                <?php echo $address['state'] ?>
                                            </div>
                                            <div class="co">
                                                <?php echo $address['country'] ?>
                                            </div>
                                        </address>
                                        <h6><span>Phone</span><a class="phone"
                                                                 href="tel:0987654321">: <?php echo $address['phone'] ?></a>
                                        </h6>
                                        <h6><span>Email</span><a class="email"
                                                                 href="mailto:">: <?php echo $address['email'] ?></a>
                                        </h6>
                                        <div class="step-2-btn">
                                            <div class="col-md-12 pn">
                                                <div class="col-md-2 pn" style="margin-right: 12px">
                                                    <a href="javasqript: void(0)"
                                                       data-key="<?php echo $address['id'] ?>"
                                                       class="edit plr15">Edit </a>
                                                </div>
                                                <div class="col-md-3 pn mr5">
                                                    <a href="javasqript: void(0)"
                                                       data-key="<?php echo $address['id'] ?>"
                                                       class="remove dlt-btn plr15">Delete</a>
                                                </div>
                                                <div class="col-md-6 pn">
                                                    <?php echo Html::a("Use this Address", ['/service/pay?address=' . $address['id']], ['class' => 'url plr15']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Update Address</h4>
                                    <div class="error-block pt10"></div>
                                </div>
                                <div class="modal-body" id="myModal-body">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section class="step2-accordion">
        <div class=container>
            <button data-toggle="collapse" data-target="#toggleDemo" class="accordion">Ajouter une autre adresse
            </button>
            <div class="collapse" id="toggleDemo">
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
                            'class' => 'step-2-form frm-2 clearfix',
                            'id' => 'add_new-address',

                        ],
                        'action' => '/customer/add-address'
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
                    <?php echo $form->field($model, 'addr1', ['template' => "{input}{label}"])
                        ->hiddenInput([
                            'id' => 'route',
                            'disabled' => 'disabled'
                        ])->label(false) ?>
                    <?php echo $form->field($model, 'strnumb', ['template' => "{input}{label}"])
                        ->hiddenInput([
                            'id' => 'street_number',
                            'disabled' => 'disabled'
                        ])->label(false) ?>
                    <?php echo $form->field($model, 'city', ['template' => "{input}{label}"])->hiddenInput([
                            'id' => 'locality',
                            'disabled' => 'disabled'
                        ]
                    )->label(false) ?>
                    <?php echo $form->field($model, 'state', ['template' => "{input}{label}"])->hiddenInput([
                        'id' => 'administrative_area_level_1',
                        'disabled' => 'disabled'
                    ])->label(false) ?>
                    <?php echo $form->field($model, 'country', ['template' => "{input}{label}"])->hiddenInput([
                            'id' => 'country',
                            'disabled' => 'disabled'
                        ]
                    )->label(false) ?>
                    <?php echo $form->field($model, 'zip', ['template' => "{input}{label}"])->hiddenInput([
                        'id' => 'postal_code',
                        'disabled' => 'disabled'
                    ])->label(false) ?>
                    <?php echo $form->field($model, 'phone', ['template' => "{input}{label}"])->hiddenInput([
                            'id' => 'phone',
                            'value' => $phone
                        ]
                    )->label(false) ?>
                    <?php echo $form->field($model, 'email', ['template' => "{input}{label}"])->hiddenInput([
                        'id' => 'email',
                        'value' => $email
                    ])->label(false) ?>
                    <?php echo $form->field($model, 'name', ['template' => "{input}{label}"])->hiddenInput([
                        'id' => 'name',
                        'value' => $name
                    ])->label(false) ?>

                    <div class="col-md-12" style="padding-top: 40px;">
                        <div class="col-md-6 col-md-offset-3">
                            <input id="autocomplete" placeholder="Add new Address" type="text" class="input-inner">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6 col-md-offset-3" style="margin-bottom: 70px">
                            <?php echo Html::submitButton("enregistrer l'adresse", ['class' => 'submit-btn']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </section>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDM2FbeNj2Sw_odQ6WkfYjJ2fF1OkBGEr0&signed_in=false&libraries=places"
        defer></script>
<?php
$this->registerAssetBundle(yii\web\JqueryAsset::className(), $this::POS_HEAD);
$this->registerJsFile('@web/js/autocomplete.js');
?>