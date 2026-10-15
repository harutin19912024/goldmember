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
                        <div id="remote-player" style="width:100%;height:380px;background:#111;color:#bbb;display:flex;align-items:center;justify-content:center;text-align:center;padding:20px;">
                            <p class="mb-0">
                                <i class="bi bi-broadcast" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                                <strong>Click "Join Stream"</strong> to connect.<br>
                                <span class="small">If the host has already started, you'll see the live video right away.</span>
                            </p>
                        </div>

                        <div class="p-3 d-flex gap-2 align-items-center border-top flex-wrap">
                            <button id="btn-join-stream" onclick="joinVideo()" class="button primary-button">
                                <i class="bi bi-play-circle me-1"></i><?= Yii::t('app', 'Join Stream') ?>
                            </button>
                            <button id="btn-leave-stream" onclick="leaveVideo()" class="button secondary-button" style="display:none;">
                                <i class="bi bi-stop-circle me-1"></i><?= Yii::t('app', 'Leave Stream') ?>
                            </button>
                            <button id="btn-share-camera" onclick="shareMyCamera()" class="button secondary-button" style="display:none;">
                                <i class="bi bi-camera-video me-1"></i><?= Yii::t('app', 'Share my camera') ?>
                            </button>
                            <button id="btn-toggle-mic" onclick="toggleMyMic()" class="button secondary-button" style="display:none;">
                                <i class="bi bi-mic me-1"></i><span data-mic-label><?= Yii::t('app', 'Mute') ?></span>
                            </button>
                            <button id="btn-stop-camera" onclick="stopMyCamera()" class="button secondary-button" style="display:none;">
                                <i class="bi bi-camera-video-off me-1"></i><?= Yii::t('app', 'Stop my camera') ?>
                            </button>
                        </div>
                    <?php endif ?>

                    <!-- Participants -->
                    <div class="border-top p-3">
                        <p class="small fw-semibold mb-2"><?= Yii::t('app', 'Participants') ?></p>
                        <div id="user-participants"></div>
                    </div>
                </div>

                <?php
                    $isHostUser = !Yii::$app->user->isGuest && (int)Yii::$app->user->id === (int)$auction->user_id;
                ?>
                <!-- Bidding panel — Copart-style -->
                <div class="shadow rounded mt-3 overflow-hidden bid-panel" data-auction-id="<?= (int)$auction->id ?>">
                    <div class="px-3 py-3 bid-header d-flex align-items-center justify-content-between">
                        <div>
                            <div class="bid-label text-uppercase small"><?= Yii::t('app', 'Current Bid') ?></div>
                            <?php
                                $initialPrice = \common\models\Bid::safeAttr($auction, 'current_price');
                                if (!$initialPrice) $initialPrice = \common\models\Bid::safeAttr($auction, 'start_price', 0);
                            ?>
                            <div class="bid-current" id="bid-current-amount">֏ <?= number_format((float)$initialPrice, 0, '', '.') ?></div>
                        </div>
                        <div class="text-end">
                            <div class="bid-label text-uppercase small"><?= Yii::t('app', 'Next minimum') ?></div>
                            <div class="bid-next" id="bid-next-min">—</div>
                        </div>
                    </div>

                    <?php if ($isHostUser): ?>
                        <div class="p-3 small text-muted text-center">
                            <i class="bi bi-info-circle me-1"></i>
                            <?= Yii::t('app', 'You are hosting this auction — bidding is disabled.') ?>
                        </div>
                    <?php else: ?>
                        <div class="p-3 d-flex flex-wrap gap-2 align-items-stretch border-top">
                            <button id="btn-quick-bid" class="button primary-button flex-grow-1" onclick="placeQuickBid()">
                                <i class="bi bi-hammer me-1"></i>
                                <span data-bid-quick-label><?= Yii::t('app', 'Bid') ?> ֏ —</span>
                            </button>
                            <div class="input-group" style="max-width:240px;">
                                <span class="input-group-text">֏</span>
                                <input type="number" id="bid-custom-input" class="form-control" min="0" step="any" placeholder="<?= Yii::t('app', 'Custom amount') ?>">
                                <button class="button secondary-button" onclick="placeCustomBid()" type="button">
                                    <?= Yii::t('app', 'Place') ?>
                                </button>
                            </div>
                        </div>
                        <div id="bid-error" class="px-3 pb-2 small text-danger" style="display:none;"></div>
                    <?php endif ?>

                    <div class="border-top">
                        <div class="px-3 py-2 small fw-semibold d-flex align-items-center justify-content-between bid-feed-header">
                            <span><?= Yii::t('app', 'Recent bids') ?></span>
                            <span id="bid-count" class="text-muted small">0</span>
                        </div>
                        <ul id="bid-feed" class="list-unstyled mb-0 bid-feed">
                            <li class="text-muted small px-3 py-3" id="bid-empty"><?= Yii::t('app', 'No bids yet — be the first!') ?></li>
                        </ul>
                    </div>

                    <!-- Hammer / winner banner — shown after admin closes the auction -->
                    <div id="auction-closed-banner" class="closed-banner" style="display:none;">
                        <div class="closed-hammer">🔨</div>
                        <div class="closed-title"><?= Yii::t('app', 'Sold!') ?></div>
                        <div class="closed-winner" id="closed-winner-text"></div>
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
.bid-panel { background:#fff; }
.bid-header {
    background: linear-gradient(135deg, #0d2020 0%, #0a1818 100%);
    color: #fff;
}
.bid-label { color:#53E2D9; letter-spacing:1px; font-size:.7rem; }
.bid-current { font-size:1.9rem; font-weight:700; color:#D4A017; line-height:1.1; }
.bid-next { font-size:1rem; color:#fff; opacity:.85; }
#btn-quick-bid {
    background:#D4A017; border:none; color:#000;
    padding:14px 16px; font-weight:700; font-size:1rem;
    transition: filter .15s ease;
}
#btn-quick-bid:hover { filter: brightness(1.07); }
#btn-quick-bid:disabled { opacity:.5; cursor:not-allowed; }
.bid-feed { max-height:260px; overflow-y:auto; }
.bid-feed li {
    display:flex; align-items:center; justify-content:space-between;
    padding:8px 12px; border-bottom:1px solid #f0f0f0; font-size:.9rem;
}
.bid-feed li:last-child { border-bottom:none; }
.bid-feed li.fresh {
    background:#fffbea;
    animation: bidFlash 1.5s ease-out forwards;
}
@keyframes bidFlash {
    0%   { background:#fff3a8; }
    100% { background:#fff; }
}
.bid-feed .bidder { font-weight:600; color:#222; }
.bid-feed .bid-amount { font-weight:700; color:#13B2AD; }
.bid-feed .bid-time { color:#999; font-size:.78rem; margin-left:8px; }

.closed-banner {
    padding: 28px 20px;
    background: linear-gradient(135deg, #D4A017 0%, #b8870a 100%);
    color: #000;
    text-align: center;
    border-top: 2px solid #b8870a;
}
.closed-hammer { font-size: 3rem; line-height: 1; animation: hammerDrop .9s cubic-bezier(.34,1.6,.64,1) 1; }
.closed-title  { font-size: 1.8rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; margin-top: 6px; }
.closed-winner { font-size: 1rem; font-weight: 600; margin-top: 4px; }
@keyframes hammerDrop {
    0%   { transform: translateY(-30px) rotate(-30deg); opacity: 0; }
    60%  { transform: translateY(8px)  rotate(15deg);  opacity: 1; }
    100% { transform: translateY(0)    rotate(0);                  }
}
.bid-panel.closed-state .bid-current,
.bid-panel.closed-state .bid-next { opacity: .55; }
.bid-panel.closed-state #btn-quick-bid,
.bid-panel.closed-state #bid-custom-input,
.bid-panel.closed-state .input-group { display: none !important; }
</style>

<?php
// Inject Agora SDK and our script only when the auction is live and no external video link
if ($status === 'live' && !$auction->video_link):
    $agoraJsVer = @filemtime(Yii::getAlias('@webroot/js/agora.js')) ?: time();
    $lang = Yii::$app->language;
    $urls = [
        'token'     => "/$lang/agora/get-token",
        'hostInfo'  => "/$lang/agora/host-info",
        'userInfo'  => "/$lang/agora/user-info",
        'bids'      => "/$lang/agora/bids",
        'placeBid'  => "/$lang/agora/place-bid",
    ];
    $this->registerJsFile('https://download.agora.io/sdk/release/AgoraRTC_N.js', ['position' => \yii\web\View::POS_HEAD]);
    $this->registerJs(
        "window.AGORA_CHANNEL = " . json_encode($channel) . ";\n"
        . "window.AGORA_URLS = " . json_encode($urls) . ";",
        \yii\web\View::POS_BEGIN
    );
    $this->registerJsFile('/js/agora.js?v=' . $agoraJsVer, ['depends' => \frontend\assets\AppAsset::class]);
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
