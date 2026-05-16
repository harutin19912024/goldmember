<?php

use yii\helpers\Html;
use frontend\models\ProductOrder;

/* @var $orders ProductOrder[] */

?>

<?php if (empty($orders)): ?>
    <div class="text-center py-5">
        <i class="bi bi-bag" style="font-size:3rem;color:#13B2AD;opacity:.4;"></i>
        <p class="text-muted mt-3 mb-1"><?= Yii::t('app', 'No orders yet.') ?></p>
        <p class="text-muted small"><?= Yii::t('app', 'Your purchase history will appear here once you place an order.') ?></p>
        <a href="/<?= Yii::$app->language ?>/search" class="btn btn-sm mt-2"
           style="background:#13B2AD;color:#fff;border-radius:20px;padding:6px 20px;">
            <?= Yii::t('app', 'Browse Products') ?>
        </a>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.92rem;">
            <thead>
                <tr style="border-bottom:2px solid rgba(19,178,173,0.2);">
                    <th class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:1px;text-transform:uppercase;padding-bottom:12px;">#</th>
                    <th class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:1px;text-transform:uppercase;"><?= Yii::t('app', 'Product') ?></th>
                    <th class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:1px;text-transform:uppercase;"><?= Yii::t('app', 'Date') ?></th>
                    <th class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:1px;text-transform:uppercase;"><?= Yii::t('app', 'Price') ?></th>
                    <th class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:1px;text-transform:uppercase;"><?= Yii::t('app', 'Qty') ?></th>
                    <th class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:1px;text-transform:uppercase;"><?= Yii::t('app', 'Status') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $i => $order): ?>
                    <tr>
                        <td class="text-muted small"><?= $i + 1 ?></td>
                        <td class="fw-medium">
                            <?= Html::encode($order->product->title ?? '—') ?>
                        </td>
                        <td class="text-muted"><?= date('d M Y', strtotime($order->created_at)) ?></td>
                        <td class="fw-medium"><?= number_format($order->price_amd, 0, '.', ',') ?> AMD</td>
                        <td class="text-muted"><?= $order->quantity ?></td>
                        <td>
                            <span class="badge px-3 py-1"
                                  style="<?= ProductOrder::getStatusBadgeStyle($order->status) ?>border-radius:20px;font-size:0.75rem;">
                                <?= Yii::t('app', ProductOrder::getStatusLabel($order->status)) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
