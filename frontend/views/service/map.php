<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $repairer frontend\models\Repairer */
/* @var $model frontend\models\Service */

$this->title = 'Map';
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
    <section class="map">
        <input type="hidden" name="" id="rep" data-id='<?= json_encode([
            'o' => $Orders,
        ]) ?>'>
        <div class="col-md-12 pn">
            <div class="col-md-12 col-sm-12 pn">
                <div id="google_Map" style="height:630px;width:100%;">
                </div>
                <button style="display: none" id="details" class="collapsed btn btn-alert details"
                        type="button" data-toggle="collapse"
                        data-target="#collapseExample" aria-expanded="false"
                        aria-controls="collapseExample">Show Details</button>
            </div>
            <div id="collapseExample" class="col-md-4 col-sm-4 pb10 collapse" style="">
                <div id="result">
                    <div class="" id="" style="background: #333333; color: white">
                        <div class="col-md-12 pn pt15">
                            <div class="col-md-1 pn">
                                <i class="fa fa-car fa-2x fs1-6"></i>
                            </div>
                            <div class="col-md-10 pn">
                                <div class="col-md-12 pn">
                                    <div class="col-md-8 pn">
                                        <p>
                                            <span class="inl_bl pull-left">From: </span>
                                            <span id="from" class="pull-left pn pl5 from"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-4 pn">
                                        <p>
                                            <span class="inl_bl pull-left">Time: </span>
                                            <span id="time1" class="pull-left pn pr5 pl5 pull-right pn" style="color: #5a9d00"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-12 pn">
                                    <div class="col-md-8 pn">
                                        <p>
                                            <span class="inl_bl pull-left">To: </span>
                                            <span id="too" class="pull-left pn pl5 to"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-4 pn">
                                        <p>
                                            <span class="inl_bl pull-left">Distance: </span>
                                            <span id="dist" class="pull-left pn pr5 pl5" style="color: #5a9d00"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="container-fluid ptb15 pr15">
            <div class="col-md-12 pn">
                <?php echo Html::a('Order status', ['/service/status'], ['class' => 'pull-right btn mt0']) ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDM2FbeNj2Sw_odQ6WkfYjJ2fF1OkBGEr0&signed_in=false"
            defer></script>

<?php
$this->registerAssetBundle(yii\web\JqueryAsset::className(), $this::POS_HEAD);
$this->registerJsFile('@web/js/client.js');
?>