<?php
/** @var backend\models\Auction $model */
use yii\helpers\Html;
?>
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
