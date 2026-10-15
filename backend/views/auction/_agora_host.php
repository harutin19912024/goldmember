<?php
/** @var backend\models\Auction $model */
/** @var string|null $auctionStatus  one of 'upcoming' | 'live' | 'ended' */
use yii\helpers\Html;

$auctionStatus = $auctionStatus ?? null;
$isEnded = $auctionStatus === 'ended';
?>

<?php if (!$isEnded): ?>
    <div style="display:flex; gap:20px;">
        <div style="width:220px; min-height:320px; border:1px solid #ddd; padding:10px; border-radius:4px;">
            <h5 style="margin-top:0; font-weight:600;"><?= Yii::t('app', 'Viewers') ?></h5>
            <div id="admin-participants"></div>
        </div>
        <div style="flex:1;">
            <div id="local-player"
                 style="width:100%; height:320px; border:2px solid #ccc; border-radius:4px; background:#1a1a1a; display:flex; align-items:center; justify-content:center;">
                <span style="color:#666;">Click <strong>Join as Host</strong> to start streaming</span>
            </div>
        </div>
    </div>
    <div style="margin-top:12px;">
        <button id="btn-admin-join" type="button" class="btn btn-success" onclick="joinVideo()">
            <span class="glyphicon glyphicon-facetime-video"></span> <?= Yii::t('app', 'Join as Host') ?>
        </button>
        <button id="btn-admin-mic" type="button" class="btn btn-default ml10" onclick="toggleAdminMic()" style="display:none;">
            <span class="glyphicon glyphicon-volume-up"></span> <?= Yii::t('app', 'Mute') ?>
        </button>
        <button id="btn-admin-mute-all" type="button" class="btn btn-default ml10" onclick="muteAllViewers()" style="display:none;">
            <span class="glyphicon glyphicon-volume-off"></span> <?= Yii::t('app', 'Mute All Viewers') ?>
        </button>
        <button id="btn-admin-leave" type="button" class="btn btn-danger ml10" onclick="leaveVideo()" style="display:none;">
            <span class="glyphicon glyphicon-off"></span> <?= Yii::t('app', 'Leave Stream') ?>
        </button>
        <span style="margin-left:15px; color:#999; font-size:12px;">
            Channel: <code><?= Html::encode('auction-' . $model->id) ?></code>
        </span>
    </div>
<?php else: ?>
    <div class="alert alert-default" style="background:#f5f5f5;border:1px solid #ddd;padding:12px;border-radius:4px;margin-bottom:0;">
        <i class="fa fa-flag"></i>
        <?= Yii::t('app', 'This auction has ended. Live streaming is no longer available — bid history is shown below.') ?>
    </div>
<?php endif ?>

<!-- Live bids panel — fed by the same stream-message events the audience uses. -->
<div class="admin-bid-panel" data-auction-id="<?= (int)$model->id ?>" style="margin-top:18px;border:1px solid #e1e1e1;border-radius:6px;overflow:hidden;background:#fff;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#0d2020;color:#fff;">
        <div>
            <div style="font-size:11px;letter-spacing:1px;color:#53E2D9;text-transform:uppercase;">Current bid</div>
            <div id="admin-bid-current" style="font-size:22px;font-weight:700;color:#D4A017;">—</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:11px;letter-spacing:1px;color:#53E2D9;text-transform:uppercase;">Next min</div>
            <div id="admin-bid-next" style="font-size:14px;">—</div>
        </div>
    </div>
    <div style="padding:8px 14px;background:#fafafa;border-bottom:1px solid #eee;font-size:12px;font-weight:600;color:#333;display:flex;justify-content:space-between;">
        <span><?= $isEnded ? Yii::t('app', 'Bid history') : Yii::t('app', 'Live bid feed') ?></span>
        <span id="admin-bid-count" style="color:#999;">0</span>
    </div>
    <ul id="admin-bid-feed" style="list-style:none;padding:0;margin:0;max-height:280px;overflow-y:auto;">
        <li id="admin-bid-empty" style="padding:14px;color:#999;font-size:13px;text-align:center;">
            <?= $isEnded ? Yii::t('app', 'No bids were placed.') : Yii::t('app', 'Waiting for the first bid…') ?>
        </li>
    </ul>

    <?php if (!$isEnded): ?>
        <div id="admin-close-actions" style="padding:12px 14px;background:#fffdf2;border-top:1px solid #f0e9c0;display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <button id="btn-close-auction" type="button" class="btn btn-danger" onclick="closeAuctionToHighest()">
                <span class="glyphicon glyphicon-flag"></span> <?= Yii::t('app', 'Finish Auction (Sell to highest)') ?>
            </button>
            <span id="admin-close-hint" style="color:#888;font-size:12px;">
                <?= Yii::t('app', 'Or click ✓ Sell on any specific bid above.') ?>
            </span>
        </div>
    <?php endif ?>

    <div id="admin-winner-banner" style="display:none;padding:14px;background:linear-gradient(135deg,#D4A017 0%,#b8870a 100%);color:#000;font-weight:600;text-align:center;border-top:1px solid #b8870a;">
        <span style="font-size:22px;">🔨</span>
        <span id="admin-winner-text" style="margin-left:8px;">Sold</span>
    </div>
</div>
