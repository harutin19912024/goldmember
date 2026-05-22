<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $auctions backend\models\Auction[] */
/* @var $pages yii\data\Pagination */

$now = time();

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Auction');
?>

<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100">
        <img class="w-100 h-100" src="/images/auction.png" alt="Banner Image" height="400" width="1442"/>
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-4 col-12 px-0">
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title">
                <?= Yii::t('app', 'Todays Auction') ?>
            </h1>
            <p class="text-content fs-4 pb-1 white-color fw-normal mb-5 h-base">
                <?= Yii::t('app', 'Get the best prices for buying and selling gold with trust and transparency.') ?>
            </p>
        </div>
    </div>
</section>

<section class="container breadcrumb-container py-2 px-sm-0">
    <div class="row px-0">
        <div class="col-sm-12">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1><?= Yii::t('app', 'Make Your Choose') ?></h1>
                        <p class="mb-0"><?= Yii::t('app', 'Your Next Treasure Awaits.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->render('partials/breadcrumb', ['prev' => 'Home', 'current' => 'Auction']) ?>

<section class="container py-2 px-sm-0">
    <?php if (empty($auctions)): ?>
        <div class="text-center py-5">
            <i class="bi bi-hammer text-muted" style="font-size:3rem;"></i>
            <p class="mt-3 text-muted"><?= Yii::t('app', 'No auctions available at the moment.') ?></p>
        </div>
    <?php else: ?>
    <ul class="row products px-0">
        <?php foreach ($auctions as $auction): ?>
            <?php
            $product   = $auction->product;
            $imagePath = '';
            if ($product->image) {
                $imagePath = Yii::$app->params['adminUrl'] . 'uploads/images/' . $product->image->name;
            }

            $startTime = strtotime($auction->start_date);
            $endTime   = $auction->end_date ? strtotime($auction->end_date) : null;

            if ($now >= $startTime && ($endTime === null || $now <= $endTime)) {
                $aStatus = 'live';
            } elseif ($endTime && $now > $endTime) {
                $aStatus = 'ended';
            } else {
                $aStatus = 'upcoming';
            }

            $detailUrl = Yii::$app->user->isGuest
                ? Url::to(['/site/login'])
                : '/auction/' . $auction->id;
            ?>
            <li class="col-lg-4 col-sm-12 col-md-6 product-item">
                <div class="w-100 shadow h-100">
                    <a href="<?= Html::encode($detailUrl) ?>" aria-label="View auction" class="position-relative product-link">
                        <div class="position-relative product-img img-box">
                            <?php if ($imagePath): ?>
                                <img src="<?= Html::encode($imagePath) ?>" alt="" class="img-fluid">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center w-100 h-100">
                                    <i class="bi bi-image text-muted" style="font-size:2rem;"></i>
                                </div>
                            <?php endif ?>

                            <!-- Status badge -->
                            <span class="position-absolute top-0 end-0 m-2 badge
                                <?php if ($aStatus === 'live'): ?>bg-danger
                                <?php elseif ($aStatus === 'ended'): ?>bg-secondary
                                <?php else: ?>bg-warning text-dark<?php endif ?>">
                                <?php if ($aStatus === 'live'): ?>
                                    <span style="display:inline-block;width:7px;height:7px;background:#fff;border-radius:50%;animation:blink 1s step-start infinite;" class="me-1"></span>
                                    Live
                                <?php elseif ($aStatus === 'ended'): ?>
                                    Ended
                                <?php else: ?>
                                    Upcoming
                                <?php endif ?>
                            </span>

                            <?php $isFaved = in_array($product->id, $favoritedIds ?? []); ?>
                            <button type="button"
                                    data-fav-id="<?= $product->id ?>"
                                    aria-label="Toggle Favourite"
                                    title="<?= $isFaved ? Yii::t('app', 'Remove from favourites') : Yii::t('app', 'Add to favourites') ?>"
                                    style="top:8px; left:8px; right:auto;"
                                    class="bg-white-color button icon-button position-absolute top-0 start-0 m-2 zindex-offcanvas-backdrop<?= $isFaved ? ' faved' : '' ?>">
                                <i class="bi <?= $isFaved ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                            </button>
                        </div>
                        <div class="content">
                            <div class="description">
                                <h3 class="product-name"><?= Html::encode($product->title) ?></h3>
                                <div class="d-flex justify-content-between">
                                    <span class="category">
                                        <?= $product->category ? Html::encode($product->category->name) : '' ?>
                                    </span>
                                    <div class="star-rating">
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <input type="radio" id="star<?= $i ?>_<?= $auction->id ?>" name="rating_<?= $auction->id ?>" value="<?= $i ?>" disabled>
                                            <label for="star<?= $i ?>_<?= $auction->id ?>"><i class="bi bi-star"></i><i class="bi bi-star-fill"></i></label>
                                        <?php endfor ?>
                                    </div>
                                </div>
                            </div>
                            <div class="deadline-more-box">
                                <div class="deadline-box danger-text">
                                    <i class="bi bi-clock"></i>
                                    <?php if ($aStatus === 'live'): ?>
                                        <span class="auction-countdown text-danger fw-bold">LIVE NOW</span>
                                    <?php elseif ($aStatus === 'ended'): ?>
                                        <span class="text-muted">Ended</span>
                                    <?php else: ?>
                                        <span id="count_down_<?= $auction->id ?>"
                                              data-timestamp="<?= $startTime ?>"
                                              class="auction-countdown">
                                            --
                                        </span>
                                    <?php endif ?>
                                </div>
                                <a href="<?= Html::encode($detailUrl) ?>" class="button secondary-button view-more-button">
                                    <?= Yii::t('app', 'More') ?><i class="bi bi-eye ms-1"></i>
                                </a>
                                <?php if ($aStatus !== 'ended'): ?>
                                <a href="<?= Html::encode($detailUrl) ?>" class="button primary-button auction-button">
                                    <span>֏</span>
                                    <span><?= $auction->start_price !== null ? number_format($auction->start_price, 0, '', '.') : '—' ?></span>
                                    <i class="bi bi-hammer ms-1"></i>
                                </a>
                                <?php endif ?>
                            </div>
                        </div>
                    </a>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>
</section>

<!-- Pagination -->
<?php if ($pages->pageCount > 1): ?>
<section class="container pagination-section">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($pages->page > 0): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= Url::current(['page' => $pages->page]) ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif ?>

                    <?php for ($p = 0; $p < $pages->pageCount; $p++): ?>
                    <li class="page-item <?= $p === $pages->page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= Url::current(['page' => $p + 1]) ?>"><?= $p + 1 ?></a>
                    </li>
                    <?php endfor ?>

                    <?php if ($pages->page < $pages->pageCount - 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= Url::current(['page' => $pages->page + 2]) ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    <?php endif ?>
                </ul>
            </nav>
        </div>
    </div>
</section>
<?php endif ?>

<style>
@keyframes blink {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0; }
}
</style>

<?php
$this->registerJs("
    document.addEventListener('DOMContentLoaded', function () {
        function startCountdown(span, timestampSec) {
            // Use Unix timestamp (seconds) to avoid timezone mismatch between PHP and JS
            var target = parseInt(timestampSec, 10) * 1000;
            function update() {
                var diff = target - Date.now();
                if (diff <= 0) {
                    span.textContent = 'LIVE NOW';
                    span.classList.add('text-danger', 'fw-bold');
                    clearInterval(timer);
                    return;
                }
                var s = Math.floor(diff / 1000);
                var d = Math.floor(s / 86400), h = Math.floor((s % 86400) / 3600),
                    m = Math.floor((s % 3600) / 60), sec = s % 60;
                span.textContent =
                    String(d).padStart(2,'0')+'D '+
                    String(h).padStart(2,'0')+'H '+
                    String(m).padStart(2,'0')+'M '+
                    String(sec).padStart(2,'0')+'S';
            }
            var timer = setInterval(update, 1000);
            update();
        }
        document.querySelectorAll('.auction-countdown[data-timestamp]').forEach(function (el) {
            startCountdown(el, el.dataset.timestamp);
        });
    });
", \yii\web\View::POS_END);
?>
