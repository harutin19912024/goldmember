<?php

use yii\helpers\Html;

/**
 * @var $Orders frontend\models\Order[]
 */
?>
<div class="blank-box"></div>
<section class="step-heading">
    <div class="container">
        <div class="step-hinner">
            <h2>état de votre commande</h2>
            <h4>Réparez votre iPhone, iPad, iPod ou Samsung Galaxy</h4>
        </div>
    </div>
</section>
<section  class="select-block stp2-block step-arrow">
    <div class="container">
        <div class="step-5">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="step-2-inner step-5-inner">
                                <div class="step-innr">
                                   <?php $total =0;
                                   $orderIds = [];
                                   ?>
                                    <?php foreach ($Orders as $key => $order):?>
                                        <?php $total += $order->service[0]->price;
                                        $orderIds[]=$order->id;
                                        ?>
                                    <h3>Order #<?php echo $order->id; ?></h3>
                                    <p><?php echo $order->service[0]->name; ?></p>
                                    <p><span>Date Placed :</span><?php echo $order->created_date ?></p>
                                    <?php endforeach;?>
                                    <p><span>Status :</span><?php if($Orders[0]->status == 0) echo "Witing for Technician Accept"?>
                                        <?php if($order->status == 1) echo "In proces"?>
                                        <?php if($order->status == 2) echo "Done"?>
                                    </p>
                                    <p><span>Total :</span> <strong class="price">€<?php echo $total;?></strong></p>
                                    <h6>To make changes to your order, please
                                        cancel it, re-add item to your card and resubmit</h6>
                                    <?php echo Html::a('Cancel Or ReOrder', 'javascript: void(0);',
                                        [
                                            'id'=>'cancl-ordr',
                                            'class'=>'cancl-ordr',
                                        'data-key'=>json_encode($orderIds, JSON_NUMERIC_CHECK)
                                        ])?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="step-2-inner step-5-inner">
                                <div class="step-innr">
                                    <h3>Adresse de retour</h3>
                                    <p><span>Via Capo le Case, 91<br>
                                                25125-Brescia BS<br>
                                                Italy</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="process-bar">
                            <span class="bg-line">
                                <p id="line" style="border: 2px solid #cc0101; width: 68%; background: #cc0102"></p>
                            </span>
                            <div id="complete" class="steps-dots">
                                <span></span>
                                <p>Réparation complète </p>
                            </div>
                            <div id="despatch" class="steps-dots">
                                <span></span>
                                <p>Dispatch</p>
                            </div>
                            <div class="steps-dots blank">
                                <span></span>
                            </div>
                            <div class="steps-dots blank">
                                <span></span>
                            </div>

                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="step-2-inner step-5-inner">
                                <div class="step-innr">
                                    <h3>état de votre commande</h3>
                                    <ul class="comment-list">
                                        <li><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent</a>
                                            <span>08/23/2016  01:03 PM</span>
                                        </li>
                                        <li><a href="#">Aenean vulputate mi libero, vel ullamcorper mauris pulvinar sit ametInteger. </a>
                                            <span>08/22/2016  09:24 AM</span>
                                        </li>
                                        <li><a href="#">Aliquam fringilla vel ligula eu porta. Quisque hendrerit id mauris at volutpat.</a>
                                            <span>08/21/2016  11:15 AM</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="step-2-inner step-5-inner">
                                <div class="step-innr">
                                    <h3>délai de livraison <br>estimé</h3>
                                    <p class="estime"><strong class="price">€<?= $total; ?></strong>
                                        Friday 23 August 2016 till 07:30 PM
                                    </p>
                                    <?php echo Html::a('View Order',['service/map'],['class'=>'view-order'])?>
<!--                                    <input type="button" class="view-order" value="">-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
