<?php
use yii\helpers\Html;

?>

<h2>Choose payment type</h2>
<div class="row">
    <div class="col-md-12">
        <ul class="radios">
            <li>
                <input type="radio" name="payment_type" id="card-payment" checked>
                <label for="card-payment"><?= Html::img('@web/images/dibs.jpg'); ?> Card payment</label>
                <div class="pull-right pd10">-</div>
                <div class="clearfix"></div>
            </li>
            <li>
                <input type="radio" name="payment_type" id="bank-wire">
                <label for="bank-wire"><?= Html::img('@web/images/bank.jpg'); ?> Bank Wire</label>

                <div class="clearfix"></div>
            </li>
            <li>
                <input type="radio" name="payment_type" id="cash-on">
                <label for="cash-on"><?= Html::img('@web/images/cash.jpg'); ?>
                    Cash on delivery</label>

                <div class="clearfix"></div>
            </li>
        </ul>
    </div>
</div>