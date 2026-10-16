<?php

use yii\helpers\Html;

/* @var $stats array from backend\models\DashboardStats::collect() */

$num = fn($v, $dec = 0) => $v === null ? '—' : number_format((float)$v, $dec, '.', ',');
$tiles = [
    ['label' => Yii::t('app', 'Visitors today'), 'value' => $num($stats['visitorsToday']),
     'sub' => Yii::t('app', '{n} in the last 7 days', ['n' => $num($stats['visitorsWeek'])]), 'icon' => 'fa-users'],
    ['label' => Yii::t('app', 'New customers (7 days)'), 'value' => $num($stats['newCustomersWeek']),
     'sub' => Yii::t('app', '{n} customers in total', ['n' => $num($stats['customersTotal'])]), 'icon' => 'fa-user-plus', 'url' => ['/user/index']],
    ['label' => Yii::t('app', 'Live auctions'), 'value' => $num($stats['auctionsLive']),
     'sub' => Yii::t('app', '{n} upcoming', ['n' => $num($stats['auctionsUpcoming'])]), 'icon' => 'fa-gavel', 'url' => ['/auction/index']],
    ['label' => Yii::t('app', 'Bids today'), 'value' => $num($stats['bidsToday']), 'sub' => Yii::t('app', 'across all auctions'), 'icon' => 'fa-hand-paper-o'],
    ['label' => Yii::t('app', 'Sold this month'), 'value' => '֏' . $num($stats['soldValueThisMonth'] ?? 0),
     'sub' => Yii::t('app', '{n} auctions', ['n' => $num($stats['soldThisMonth'])]), 'icon' => 'fa-trophy'],
    ['label' => Yii::t('app', 'Donations this month'), 'value' => '֏' . $num($stats['donationsSumThisMonth'] ?? 0),
     'sub' => Yii::t('app', '{n} donations', ['n' => $num($stats['donationsThisMonth'])]), 'icon' => 'fa-heart', 'url' => ['/donate/donation-info']],
];
$gold = $stats['gold'];
?>
<div class="row dashboard-kpis mb20">
    <?php foreach ($tiles as $t): ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb15">
            <div class="panel mbn" style="border-left:3px solid #D4A017;">
                <div class="panel-body p15">
                    <div class="text-muted fs12 text-uppercase"><i class="fa <?= $t['icon'] ?> mr5"></i><?= Html::encode($t['label']) ?></div>
                    <div class="fs30 fw600 mt5" style="line-height:1.1;">
                        <?= isset($t['url']) ? Html::a(Html::encode($t['value']), $t['url'], ['style' => 'color:inherit']) : Html::encode($t['value']) ?>
                    </div>
                    <div class="text-muted fs12 mt5"><?= Html::encode($t['sub']) ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="col-sm-6 col-md-4 col-lg-3 mb15">
        <div class="panel mbn" style="border-left:3px solid #D4A017;">
            <div class="panel-body p15">
                <div class="text-muted fs12 text-uppercase"><i class="fa fa-line-chart mr5"></i><?= Yii::t('app', 'Gold spot (USD/g)') ?></div>
                <?php if ($gold): ?>
                    <div class="fs30 fw600 mt5" style="line-height:1.1;"><?= $num($gold['perGram'], 2) ?></div>
                    <div class="fs12 mt5">
                        <?php if ($gold['changePct'] !== null && abs($gold['changePct']) >= 0.005): ?>
                            <span class="<?= $gold['changePct'] > 0 ? 'text-success' : 'text-danger' ?>"><?= $gold['changePct'] > 0 ? '▲' : '▼' ?> <?= $num(abs($gold['changePct']), 2) ?>%</span>
                        <?php endif; ?>
                        <span class="text-muted"><?= Yii::t('app', 'fetched {date}', ['date' => Html::encode($gold['fetchedAt'])]) ?></span>
                    </div>
                <?php else: ?>
                    <div class="fs30 fw600 mt5">—</div>
                    <div class="text-muted fs12 mt5"><?= Html::a(Yii::t('app', 'Fetch prices'), ['/metal-prices/create']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
