<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
?>

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="font-size:0.92rem;">
        <thead>
            <tr style="border-bottom:2px solid rgba(19,178,173,0.2);">
                <th class="text-muted fw-semibold" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase; padding-bottom:12px;">#</th>
                <th class="text-muted fw-semibold" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;"><?= Yii::t('app', 'Order') ?></th>
                <th class="text-muted fw-semibold" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;"><?= Yii::t('app', 'Date') ?></th>
                <th class="text-muted fw-semibold" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;"><?= Yii::t('app', 'Price') ?></th>
                <th class="text-muted fw-semibold" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;"><?= Yii::t('app', 'Status') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-muted small">1</td>
                <td><a href="#" style="color:#13B2AD; font-weight:500;">#45006</a></td>
                <td class="text-muted">Sunday 18th March, 2015</td>
                <td class="fw-medium">275 AMD</td>
                <td><span class="badge px-3 py-1" style="background:#d1f5e0;color:#1a7a3a;font-size:0.75rem;border-radius:20px;"><?= Yii::t('app', 'Delivered') ?></span></td>
            </tr>
            <tr>
                <td class="text-muted small">2</td>
                <td><a href="#" style="color:#13B2AD; font-weight:500;">#46440</a></td>
                <td class="text-muted">Monday 14th April, 2015</td>
                <td class="fw-medium">575 AMD</td>
                <td><span class="badge px-3 py-1" style="background:#fde8e8;color:#a01414;font-size:0.75rem;border-radius:20px;"><?= Yii::t('app', 'Cancelled') ?></span></td>
            </tr>
            <tr>
                <td class="text-muted small">3</td>
                <td><a href="#" style="color:#13B2AD; font-weight:500;">#48700</a></td>
                <td class="text-muted">Friday 28th April, 2015</td>
                <td class="fw-medium">205 AMD</td>
                <td><span class="badge px-3 py-1" style="background:#d1f5e0;color:#1a7a3a;font-size:0.75rem;border-radius:20px;"><?= Yii::t('app', 'Delivered') ?></span></td>
            </tr>
            <tr>
                <td class="text-muted small">4</td>
                <td><a href="#" style="color:#13B2AD; font-weight:500;">#51280</a></td>
                <td class="text-muted">Sunday 26th June, 2015</td>
                <td class="fw-medium">455 AMD</td>
                <td><span class="badge px-3 py-1" style="background:#d1f5e0;color:#1a7a3a;font-size:0.75rem;border-radius:20px;"><?= Yii::t('app', 'Delivered') ?></span></td>
            </tr>
        </tbody>
    </table>
</div>
