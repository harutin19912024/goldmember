<?php
/** @var backend\models\MetalPrices $model */
/** @var array $apiPrices */
/** @var array $existingByKarat */
/** @var string|null $apiTimestamp */
/** @var float $original_buy_price */
/** @var float $original_sell_price */

use yii\helpers\Html;
?>

<?php if (empty($apiPrices) || empty($apiPrices['prices'])): ?>
    <div class="alert alert-warning" style="margin-top:15px;">
        <strong><?= Yii::t('app', 'No API price data available.') ?></strong>
        <?= Yii::t('app', 'Click') ?> <em>"<?= Yii::t('app', 'Fetch latest from API') ?>"</em>
        <?= Yii::t('app', 'to load current bid/ask from metalpriceapi.com, then prices will appear here.') ?>
    </div>
<?php else: ?>
    <div class="row" style="margin-top:10px;">
        <div class="col-md-6">
            <label><?= Yii::t('app', 'Original Buy / gram (after currency conversion)') ?></label>
            <input type="text" class="form-control" value="<?= $original_buy_price ?>" readonly>
        </div>
        <div class="col-md-6">
            <label><?= Yii::t('app', 'Original Sell / gram (after currency conversion)') ?></label>
            <input type="text" class="form-control" value="<?= $original_sell_price ?>" readonly>
        </div>
    </div>

    <?php if ($apiTimestamp): ?>
        <div class="alert alert-info" style="margin-top:10px;">
            <i class="fa fa-info-circle"></i>
            <?= Yii::t('app', 'API data from') ?> <strong><?= $apiTimestamp ?></strong>.
            <?= Yii::t('app', 'Metal:') ?> <strong><?= Html::encode($apiPrices['metalName']) ?></strong>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-condensed" style="margin-top:15px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="width:120px;"><?= Yii::t('app', 'Karat') ?></th>
                <th><?= Yii::t('app', 'Sell Price') ?></th>
                <th><?= Yii::t('app', 'Buy Price') ?></th>
                <th><?= Yii::t('app', 'Today already saved?') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apiPrices['prices'] as $karat => $price): ?>
                <?php $today = $existingByKarat[$karat] ?? null; ?>
                <tr>
                    <td>
                        <strong><?= $karat ?></strong>
                        <input type="hidden" name="MetalPrices[karat][]" value="<?= $karat ?>">
                    </td>
                    <td>
                        <input type="number" step="0.0001" class="form-control prices-sell"
                               name="MetalPrices[sell_price][]" value="<?= $today ? $today->sell_price : $price['sell'] ?>">
                        <input type="hidden" name="MetalPrices[original_sell_price][]" value="<?= $price['sell'] ?>">
                    </td>
                    <td>
                        <input type="number" step="0.0001" class="form-control prices-buy"
                               name="MetalPrices[buy_price][]" value="<?= $today ? $today->buy_price : $price['buy'] ?>">
                        <input type="hidden" name="MetalPrices[original_buy_price][]" value="<?= $price['buy'] ?>">
                    </td>
                    <td>
                        <?php if ($today): ?>
                            <span class="label label-warning"><?= Yii::t('app', 'Will be updated (upsert)') ?></span>
                        <?php else: ?>
                            <span class="label label-success"><?= Yii::t('app', 'New') ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
