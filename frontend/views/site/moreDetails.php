<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Trading');

?>

<!-- Page Title -->
<div class="page-title">
    <div class="heading">
        <div class="container">
            <div class="row d-flex justify-content-center text-center">
                <div class="col-lg-8">
                    <h1><?= Yii::t('app', 'Metals Prices') ?></h1>
                    <p class="mb-0 text-muted">
                        <?= Yii::t('app', 'Live and locally-set gold prices per gram by fineness, plus currency exchange rates updated from the admin panel.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <nav class="breadcrumbs">
        <div class="container">
            <ol>
                <li><a href="/"><?= Yii::t('app', 'Home') ?></a></li>
                <li class="current"><?= Yii::t('app', 'Metals Prices') ?></li>
            </ol>
        </div>
    </nav>
</div><!-- End Page Title -->

<section id="prices-section" class="blog-posts section">
    <div class="container" style="box-shadow: 4px 40px 61px 20px; padding: 2rem;">

        <!-- ===== LOCAL PRICES (from admin → Metal Prices) ===== -->
        <div class="mb-5">
            <h2><?= Yii::t('app', 'Local Prices') ?></h2>
            <p class="text-muted small mb-2">
                <?= Yii::t('app', 'Buy and sell prices set by Goldmember (AMD per gram)') ?>
            </p>

            <?php if (empty($localGoldPrices)): ?>
                <div class="alert alert-info">
                    <?= Yii::t('app', 'No local prices available at this time. Please check back later.') ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><?= Yii::t('app', 'Fineness (‰)') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Buy') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Sell') ?></th>
                                <th><?= Yii::t('app', 'Currency') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($localGoldPrices as $price): ?>
                                <tr>
                                    <td><strong><?= Html::encode($price->karat) ?></strong></td>
                                    <td class="text-end text-success fw-bold"><?= number_format((float)$price->buy_price, 4) ?></td>
                                    <td class="text-end text-danger fw-bold"><?= number_format((float)$price->sell_price, 4) ?></td>
                                    <td><?= Html::encode($price->currency->code ?? 'USD') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small">
                    <?= Yii::t('app', 'Last updated:') ?>
                    <?= Html::encode(date('Y-m-d', strtotime($localGoldPrices[0]->created_at))) ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- ===== GLOBAL PRICES (computed from API snapshot) ===== -->
        <div class="mb-5">
            <h2><?= Yii::t('app', 'Global Prices') ?></h2>
            <p class="text-muted small mb-2">
                <?= Yii::t('app', 'Calculated from the international spot price (USD per gram)') ?>
            </p>

            <?php if (empty($globalPrices)): ?>
                <div class="alert alert-info">
                    <?= Yii::t('app', 'Global price data is currently unavailable.') ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><?= Yii::t('app', 'Gold') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Buy') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Sell') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($globalPrices as $label => $p): ?>
                                <tr>
                                    <td><strong><?= Html::encode($label) ?></strong></td>
                                    <td class="text-end"><?= number_format($p['buy'], 2) ?></td>
                                    <td class="text-end"><?= number_format($p['sell'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== GLOBAL SPOT PRICES (API snapshot summary) ===== -->
        <div class="mb-5">
            <h2><?= Yii::t('app', 'Global Spot Prices') ?></h2>
            <p class="text-muted small mb-2">
                <?= Yii::t('app', 'International market reference price') ?>
            </p>

            <?php if (empty($spotPrice)): ?>
                <div class="alert alert-info">
                    <?= Yii::t('app', 'Spot price data is currently unavailable.') ?>
                </div>
            <?php else: ?>
                <?php
                    $chg    = (float)($spotPrice['change'] ?? 0);
                    $chgPct = (float)($spotPrice['change_pct'] ?? 0);
                    $chgClass = $chg >= 0 ? 'text-success' : 'text-danger';
                    $chgSign  = $chg >= 0 ? '+' : '';
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><?= Yii::t('app', 'Metal Name') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Price (USD/g)') ?></th>
                                <th class="text-end"><?= Yii::t('app', '+/- (USD/oz)') ?></th>
                                <th><?= Yii::t('app', 'Date') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong><?= Html::encode($spotPrice['metal']) ?></strong></td>
                                <td class="text-end fw-bold">
                                    <?= number_format((float)$spotPrice['price'], 4) ?>
                                </td>
                                <td class="text-end <?= $chgClass ?> fw-bold">
                                    <?= $chgSign . number_format($chg, 2) ?>
                                    (<?= $chgSign . number_format($chgPct, 2) ?>%)
                                </td>
                                <td><?= Html::encode(date('Y-m-d H:i', strtotime($spotPrice['date']))) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== EXCHANGE RATES (from admin → Exchange Rates) ===== -->
        <div class="mb-5">
            <h2><?= Yii::t('app', 'Exchange Rates') ?></h2>
            <p class="text-muted small mb-2">
                <?= Yii::t('app', 'Currency exchange rates vs Armenian Dram (AMD), set by Goldmember') ?>
            </p>

            <?php if (empty($exchangeRates)): ?>
                <div class="alert alert-info">
                    <?= Yii::t('app', 'No exchange rate data available.') ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><?= Yii::t('app', 'Currency') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Buy (AMD)') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Sell (AMD)') ?></th>
                                <th><?= Yii::t('app', 'Updated') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exchangeRates as $rate): ?>
                                <tr>
                                    <td>
                                        <strong><?= Html::encode($rate->currency->code ?? '—') ?></strong>
                                        <span class="text-muted ms-1"><?= Html::encode($rate->currency->name ?? '') ?></span>
                                    </td>
                                    <td class="text-end text-success fw-bold"><?= number_format((float)$rate->buy_rate, 2) ?></td>
                                    <td class="text-end text-danger fw-bold"><?= number_format((float)$rate->sell_rate, 2) ?></td>
                                    <td><?= Html::encode(date('Y-m-d', strtotime($rate->updated_at))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div><!-- /container -->

    <!-- TradingView live gold chart -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-3"><?= Yii::t('app', 'Live Gold Chart') ?></h3>
                <iframe
                    title="Gold price chart"
                    frameborder="0"
                    allowtransparency="true"
                    scrolling="no"
                    allowfullscreen="true"
                    src="https://s.tradingview.com/kitco/widgetembed/?hideideas=1&overrides=%7B%7D&enabled_features=%5B%5D&disabled_features=%5B%5D&locale=en#%7B%22symbol%22%3A%22XAUUSD%22%2C%22frameElementId%22%3A%22tv_gold%22%2C%22interval%22%3A%221%22%2C%22hide_side_toolbar%22%3A%221%22%2C%22allow_symbol_change%22%3A%221%22%2C%22save_image%22%3A%220%22%2C%22theme%22%3A%22light%22%2C%22style%22%3A%221%22%2C%22timezone%22%3A%22America%2FNew_York%22%2C%22withdateranges%22%3A%221%22%7D"
                    style="width: 100%; height: 500px;">
                </iframe>
            </div>
        </div>
    </div>

</section>
