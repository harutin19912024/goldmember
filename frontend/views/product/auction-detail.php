<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $auction backend\models\Auction */
/* @var $status string  upcoming|live|ended */

$product = $auction->product;
$imagePath = '';
if ($product->image) {
    $imagePath = Yii::$app->params['adminUrl'] . 'uploads/images/' . $product->image->name;
}

$lotNumber = !empty($auction->lot_number)
    ? $auction->lot_number
    : \backend\models\Auction::generateAuctionLotNumber($auction->id);
$channel   = 'auction-' . $auction->id;
$agoraAppId = Yii::$app->params['agoraAppId'];

$this->title = Html::encode($product->title) . ' | ' . Yii::t('app', 'Auction');

$statusLabel = [
    'upcoming' => ['text' => Yii::t('app', 'Upcoming'), 'class' => 'bg-warning text-dark'],
    'live'     => ['text' => Yii::t('app', 'Live Now'), 'class' => 'bg-danger text-white'],
    'ended'    => ['text' => Yii::t('app', 'Ended'),    'class' => 'bg-secondary text-white'],
][$status];
?>

<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 w-100 h-100">
        <img class="w-100 h-100" src="/images/auction.png" alt="Auction Banner" height="300" width="1442">
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-6 col-12 px-0">
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title">
                <?= Html::encode($product->title) ?>
            </h1>
            <span class="badge fs-6 <?= $statusLabel['class'] ?> mb-4">
                <?php if ($status === 'live'): ?>
                    <span class="me-1" style="display:inline-block;width:8px;height:8px;background:#fff;border-radius:50%;animation:blink 1s step-start infinite;"></span>
                <?php endif ?>
                <?= $statusLabel['text'] ?>
            </span>
        </div>
    </div>
</section>

<?= $this->render('/site/partials/breadcrumb', ['prev' => Yii::t('app', 'Auction'), 'prevUrl' => '/auction', 'current' => Html::encode($product->title)]) ?>

<section class="container py-4 px-sm-0">
    <div class="row g-4">

        <!-- Left: Product info -->
        <div class="col-lg-5 col-12">
            <div class="shadow rounded overflow-hidden">
                <?php if ($imagePath): ?>
                    <img src="<?= Html::encode($imagePath) ?>" alt="<?= Html::encode($product->title) ?>" class="img-fluid w-100" style="max-height:400px;object-fit:cover;">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:300px;">
                        <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                    </div>
                <?php endif ?>

                <div class="p-3">
                    <h4 class="mb-1"><?= Html::encode($product->title) ?></h4>
                    <?php if ($product->category): ?>
                        <p class="text-muted small mb-2"><?= Html::encode($product->category->name) ?></p>
                    <?php endif ?>

                    <hr>

                    <dl class="row mb-0 small">
                        <dt class="col-5"><?= Yii::t('app', 'Lot Number') ?></dt>
                        <dd class="col-7"><?= Html::encode($lotNumber) ?></dd>

                        <?php if ($product->material): ?>
                        <dt class="col-5"><?= Yii::t('app', 'Material') ?></dt>
                        <dd class="col-7"><?= Html::encode($product->material->name) ?></dd>
                        <?php endif ?>

                        <?php if ($product->fineness): ?>
                        <dt class="col-5"><?= Yii::t('app', 'Fineness') ?></dt>
                        <dd class="col-7"><?= Html::encode($product->fineness) ?></dd>
                        <?php endif ?>

                        <?php if ($product->weight): ?>
                        <dt class="col-5"><?= Yii::t('app', 'Weight') ?></dt>
                        <dd class="col-7"><?= Html::encode($product->weight) ?> g</dd>
                        <?php endif ?>

                        <?php if ($product->color): ?>
                        <dt class="col-5"><?= Yii::t('app', 'Color') ?></dt>
                        <dd class="col-7"><?= Html::encode($product->color) ?></dd>
                        <?php endif ?>

                        <dt class="col-5"><?= Yii::t('app', 'Start Price') ?></dt>
                        <dd class="col-7 fw-bold">֏ <?= $auction->start_price !== null ? number_format($auction->start_price, 0, '', '.') : '—' ?></dd>

                        <dt class="col-5"><?= Yii::t('app', 'Start Date') ?></dt>
                        <dd class="col-7"><?= date('d M Y, H:i', strtotime($auction->start_date)) ?></dd>

                        <?php if ($auction->end_date): ?>
                        <dt class="col-5"><?= Yii::t('app', 'End Date') ?></dt>
                        <dd class="col-7"><?= date('d M Y, H:i', strtotime($auction->end_date)) ?></dd>
                        <?php endif ?>
                    </dl>

                    <?php if ($product->short_description): ?>
                        <hr>
                        <p class="small text-muted mb-0"><?= Html::encode(strip_tags($product->short_description)) ?></p>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <!-- Right: Stream / status -->
        <div class="col-lg-7 col-12">

            <?php if ($status === 'upcoming'): ?>
                <!-- Upcoming countdown -->
                <div class="shadow rounded p-4 text-center">
                    <i class="bi bi-hourglass-split text-warning" style="font-size:3rem;"></i>
                    <h4 class="mt-3"><?= Yii::t('app', 'Auction starts in') ?></h4>
                    <div id="auction-countdown" class="display-5 fw-bold primary-color my-3"
                         data-timestamp="<?= strtotime($auction->start_date) ?>">
                        --
                    </div>
                    <p class="text-muted"><?= date('d M Y \a\t H:i', strtotime($auction->start_date)) ?></p>
                    <a href="/auction" class="btn btn-outline-secondary mt-2">
                        <i class="bi bi-arrow-left me-1"></i><?= Yii::t('app', 'Back to Auctions') ?>
                    </a>
                </div>

            <?php elseif ($status === 'ended'): ?>
                <!-- Ended -->
                <div class="shadow rounded p-4 text-center">
                    <i class="bi bi-slash-circle text-secondary" style="font-size:3rem;"></i>
                    <h4 class="mt-3"><?= Yii::t('app', 'This auction has ended') ?></h4>
                    <p class="text-muted">
                        <?= Yii::t('app', 'Ended on') ?> <?= date('d M Y, H:i', strtotime($auction->end_date)) ?>
                    </p>
                    <a href="/auction" class="btn btn-outline-secondary mt-2">
                        <i class="bi bi-arrow-left me-1"></i><?= Yii::t('app', 'Back to Auctions') ?>
                    </a>
                </div>

            <?php else: /* live */ ?>
                <!-- Live stream -->
                <div class="shadow rounded overflow-hidden">
                    <div class="bg-danger text-white px-3 py-2 d-flex align-items-center justify-content-between">
                        <span>
                            <span style="display:inline-block;width:8px;height:8px;background:#fff;border-radius:50%;animation:blink 1s step-start infinite;" class="me-2"></span>
                            <strong><?= Yii::t('app', 'LIVE AUCTION') ?></strong>
                        </span>
                        <span id="stream-status" class="small">Offline</span>
                    </div>

                    <?php if ($auction->video_link): ?>
                        <!-- External video link (YouTube / other) -->
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= Html::encode($auction->video_link) ?>"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen></iframe>
                        </div>
                    <?php else: ?>
                        <!-- Agora live stream -->
                        <div id="remote-player" style="width:100%;height:380px;background:#111;display:flex;align-items:center;justify-content:center;">
                            <p class="text-muted">Click <strong>Join Stream</strong> to watch live.</p>
                        </div>

                        <div class="p-3 d-flex gap-2 align-items-center border-top">
                            <button id="btn-join-stream" onclick="joinVideo()" class="button primary-button">
                                <i class="bi bi-play-circle me-1"></i><?= Yii::t('app', 'Join Stream') ?>
                            </button>
                            <button id="btn-leave-stream" onclick="leaveVideo()" class="button secondary-button" style="display:none;">
                                <i class="bi bi-stop-circle me-1"></i><?= Yii::t('app', 'Leave Stream') ?>
                            </button>
                        </div>
                    <?php endif ?>

                    <!-- Participants -->
                    <div class="border-top p-3">
                        <p class="small fw-semibold mb-2"><?= Yii::t('app', 'Participants') ?></p>
                        <div id="user-participants"></div>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>
</section>

<style>
@keyframes blink {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0; }
}
</style>

<?php
// Inject Agora SDK and our script only when the auction is live and no external video link
if ($status === 'live' && !$auction->video_link):
    $this->registerJsFile('https://download.agora.io/sdk/release/AgoraRTC_N.js', ['position' => \yii\web\View::POS_HEAD]);
    $this->registerJs("window.AGORA_CHANNEL = " . json_encode($channel) . ";", \yii\web\View::POS_BEGIN);
    $this->registerJsFile('/js/agora.js', ['depends' => \frontend\assets\AppAsset::class]);
endif;

// Countdown for upcoming auctions — reloads once when it hits zero so the live stream appears
if ($status === 'upcoming'):
    $this->registerJs("
        (function() {
            var el = document.getElementById('auction-countdown');
            if (!el) return;

            // Use Unix timestamp (seconds) — avoids timezone mismatch between PHP and JS
            var target = parseInt(el.dataset.timestamp, 10) * 1000;

            // Reload-loop guard: only reload once per page load
            var reloadKey = 'auction_reloaded_" . $auction->id . "';
            var alreadyReloaded = sessionStorage.getItem(reloadKey);

            function tick() {
                var diff = target - Date.now();
                if (diff <= 0) {
                    el.textContent = 'Starting now…';
                    clearInterval(t);
                    if (!alreadyReloaded) {
                        sessionStorage.setItem(reloadKey, '1');
                        setTimeout(function() { location.reload(); }, 2000);
                    }
                    return;
                }
                // Clear the guard once the countdown is still running (fresh page load)
                sessionStorage.removeItem(reloadKey);
                alreadyReloaded = null;

                var d = Math.floor(diff / 86400000),
                    h = Math.floor((diff % 86400000) / 3600000),
                    m = Math.floor((diff % 3600000) / 60000),
                    s = Math.floor((diff % 60000) / 1000);
                el.textContent = String(d).padStart(2,'0')+'D '+String(h).padStart(2,'0')+'H '+String(m).padStart(2,'0')+'M '+String(s).padStart(2,'0')+'S';
            }
            var t = setInterval(tick, 1000);
            tick();
        })();
    ", \yii\web\View::POS_END);
endif;
?>
