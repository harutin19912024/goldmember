<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Bid;

/** @var yii\web\View $this */
/** @var backend\models\Auction $model */

$now   = time();
$start = $model->start_date ? strtotime($model->start_date) : null;
$end   = $model->end_date   ? strtotime($model->end_date)   : null;

if (!$start) {
    $statusLabel = '<span class="label label-default">No date</span>';
} elseif ($end && $now > $end) {
    $statusLabel = '<span class="label label-default">Ended</span>';
} elseif ($now >= $start && ($end === null || $now <= $end)) {
    $statusLabel = '<span class="label label-danger">&#9679; Live</span>';
} else {
    $statusLabel = '<span class="label label-warning">Upcoming</span>';
}

$snapshot = Bid::snapshot($model, 200);
$winner   = $snapshot['winner'] ?? null;
$winningBidId = null;
if ($winner) {
    foreach ($snapshot['bids'] as $b) {
        if ((int)$b['user_id'] === (int)$winner['user_id'] && abs((float)$b['amount'] - (float)$winner['amount']) < 0.005) {
            $winningBidId = (int)$b['id'];
            break;
        }
    }
}

$winnerUser = null;
if ($winner) {
    $winnerUser = \common\models\User::find()->with('customer')->where(['id' => $winner['user_id']])->one();
}

$this->title = $model->product ? Html::encode($model->product->title) : ('Auction #' . $model->id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$fmt = function ($n) { return '֏ ' . number_format((float)$n, 0, '', '.'); };
?>

<div class="admin-form">

    <div class="panel mb30">
        <div class="panel-heading">
            <span class="panel-title"><?= $this->title ?> &nbsp; <?= $statusLabel ?></span>
        </div>
        <div class="panel-body">

            <?= DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'product_id',
                        'label'     => Yii::t('app', 'Product'),
                        'value'     => $model->product ? $model->product->title : '—',
                    ],
                    'lot_number',
                    'start_date:datetime',
                    'end_date:datetime',
                    [
                        'attribute' => 'start_price',
                        'value'     => $fmt($model->start_price),
                    ],
                    [
                        'label' => Yii::t('app', 'Bid increment'),
                        'value' => $fmt(Bid::safeAttr($model, 'bid_increment', 50)),
                    ],
                    [
                        'label' => Yii::t('app', 'Current / final price'),
                        'value' => $fmt($snapshot['current_price']),
                    ],
                    'video_link',
                    [
                        'attribute' => 'user_id',
                        'label'     => Yii::t('app', 'Host'),
                        'value'     => $model->user ? $model->user->username : '—',
                    ],
                    'created_date:datetime',
                    'updated_at:datetime',
                    [
                        'label' => Yii::t('app', 'Closed at'),
                        'value' => Bid::safeAttr($model, 'closed_at') ?: ($snapshot['closed'] ? Yii::t('app', '(implied from end date)') : '—'),
                    ],
                ],
            ]) ?>

        </div>
        <div class="panel-footer text-right">
            <?= Html::a(
                '<span class="glyphicon glyphicon-edit"></span> ' . Yii::t('app', 'Update'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-primary']
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('app', 'Delete'),
                ['delete', 'id' => $model->id],
                [
                    'class'        => 'btn btn-danger ml5',
                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this auction?'),
                    'data-method'  => 'post',
                ]
            ) ?>
        </div>
    </div>

    <?php if ($snapshot['closed'] && $winner): ?>
        <div class="panel mb20" style="border:2px solid #D4A017;">
            <div class="panel-heading" style="background:linear-gradient(135deg,#D4A017 0%,#b8870a 100%);color:#000;">
                <span class="panel-title" style="color:#000;font-weight:700;">
                    🔨 <?= Yii::t('app', 'Winner') ?>
                </span>
            </div>
            <div class="panel-body" style="background:#fffbea;">
                <div style="display:flex;gap:24px;flex-wrap:wrap;align-items:center;">
                    <div style="width:64px;height:64px;border-radius:50%;background:hsl(<?= ($winner['user_id'] * 47) % 360 ?>,55%,45%);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:28px;flex-shrink:0;">
                        <?= Html::encode(mb_strtoupper(mb_substr($winner['name'], 0, 1) ?: '?')) ?>
                    </div>
                    <div style="flex:1;min-width:240px;">
                        <div style="font-size:20px;font-weight:700;color:#222;">
                            <?= Html::encode($winner['name']) ?>
                        </div>
                        <div style="color:#666;font-size:13px;margin-top:2px;">
                            <?= Yii::t('app', 'User ID') ?>: #<?= (int)$winner['user_id'] ?>
                            <?php if ($winnerUser): ?>
                                · <?= Html::encode($winnerUser->username ?? '') ?>
                                <?php if ($winnerUser->email): ?>
                                    · <?= Html::encode($winnerUser->email) ?>
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                        <?php if ($winnerUser && $winnerUser->customer): ?>
                            <div style="color:#666;font-size:13px;margin-top:2px;">
                                <?php if ($winnerUser->customer->phone): ?>
                                    <span><i class="fa fa-phone"></i> <?= Html::encode($winnerUser->customer->phone) ?></span>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </div>
                    <div style="text-align:right;min-width:160px;">
                        <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:1px;">
                            <?= Yii::t('app', 'Winning bid') ?>
                        </div>
                        <div style="font-size:32px;font-weight:700;color:#D4A017;line-height:1;">
                            <?= $fmt($winner['amount']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($snapshot['closed']): ?>
        <div class="alert alert-default" style="background:#f5f5f5;border:1px solid #ddd;">
            <i class="fa fa-info-circle"></i> <?= Yii::t('app', 'Auction closed — no bids were placed.') ?>
        </div>
    <?php endif ?>

    <div class="panel mb30">
        <div class="panel-heading">
            <span class="panel-title">
                <?= Yii::t('app', 'Bid History') ?>
                <span style="color:#999;font-size:12px;font-weight:normal;margin-left:8px;">
                    <?= count($snapshot['bids']) ?> <?= Yii::t('app', 'bids') ?>
                </span>
            </span>
        </div>
        <div class="panel-body" style="padding:0;">
            <?php if (empty($snapshot['bids'])): ?>
                <div style="padding:24px;color:#999;text-align:center;">
                    <?= Yii::t('app', 'No bids were placed on this auction.') ?>
                </div>
            <?php else: ?>
                <table class="table table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th><?= Yii::t('app', 'Bidder') ?></th>
                            <th style="width:140px;text-align:right;"><?= Yii::t('app', 'Amount') ?></th>
                            <th style="width:160px;"><?= Yii::t('app', 'Placed at') ?></th>
                            <th style="width:120px;"><?= Yii::t('app', 'Result') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($snapshot['bids'] as $i => $b): ?>
                            <?php
                                $isWinner = $winningBidId
                                    ? ((int)$b['id'] === $winningBidId)
                                    : ($winner && (int)$b['user_id'] === (int)$winner['user_id'] && abs((float)$b['amount'] - (float)$winner['amount']) < 0.005);
                                $rowStyle = $isWinner
                                    ? 'background:#fffbea;border-left:4px solid #D4A017;font-weight:600;'
                                    : '';
                            ?>
                            <tr style="<?= $rowStyle ?>">
                                <td><?= (int)$b['id'] ?></td>
                                <td>
                                    <?php if ($isWinner): ?>
                                        <span style="color:#D4A017;font-size:16px;">🔨</span>
                                    <?php endif ?>
                                    <?= Html::encode($b['name']) ?>
                                    <span style="color:#999;font-size:11px;">#<?= (int)$b['user_id'] ?></span>
                                </td>
                                <td style="text-align:right;color:<?= $isWinner ? '#D4A017' : '#13B2AD' ?>;font-weight:700;">
                                    <?= $fmt($b['amount']) ?>
                                </td>
                                <td style="color:#666;font-size:12px;"><?= Html::encode($b['created_at']) ?></td>
                                <td>
                                    <?php if ($isWinner): ?>
                                        <span class="label" style="background:#D4A017;color:#000;"><?= Yii::t('app', 'Winner') ?></span>
                                    <?php else: ?>
                                        <span class="label label-default"><?= Yii::t('app', 'Outbid') ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif ?>
        </div>
    </div>

</div>
