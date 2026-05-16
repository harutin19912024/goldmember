<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Product;
use kartik\select2\Select2;

$auctionProducts = [];
$products = Product::find()->where(['is_auction' => 1])->all();
foreach ($products as $product) {
    $auctionProducts[$product->id] = $product->title;
}

$formId     = $model->isNewRecord ? 'auctionCreate' : 'auctionUpdate';
$formAction = $model->isNewRecord ? ['/auction/create'] : ['/auction/update', 'id' => $model->id];

// Compute live status for existing auctions
$auctionStatus = null;
$channel       = null;
if (!$model->isNewRecord) {
    $now   = time();
    $start = $model->start_date ? strtotime($model->start_date) : null;
    $end   = $model->end_date   ? strtotime($model->end_date)   : null;
    $channel = 'auction-' . $model->id;

    if (!$start) {
        $auctionStatus = 'no-date';
    } elseif ($end && $now > $end) {
        $auctionStatus = 'ended';
    } elseif ($now >= $start && ($end === null || $now <= $end)) {
        $auctionStatus = 'live';
    } else {
        $auctionStatus = 'upcoming';
    }
}

/** @var yii\web\View $this */
/** @var backend\models\Auction $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="admin-form">

    <?= Html::a(Yii::t('app', 'Back to list'), ['/auction/index'], ['class' => 'btn btn-primary mb15']) ?>

    <?php $form = ActiveForm::begin(['action' => $formAction, 'id' => $formId]); ?>

    <!-- Auction details panel -->
    <div class="panel sort-disable mb30" data-panel-color="false" data-panel-fullscreen="false"
         data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title"><?= Yii::t('app', 'Auction Details') ?></span>
            <?php if ($auctionStatus): ?>
                <span style="margin-left:10px;">
                    <?php if ($auctionStatus === 'live'): ?>
                        <span class="label label-danger"><span style="display:inline-block;width:8px;height:8px;background:#fff;border-radius:50%;margin-right:4px;animation:blink 1s step-start infinite;"></span>LIVE</span>
                    <?php elseif ($auctionStatus === 'upcoming'): ?>
                        <span class="label label-warning">Upcoming</span>
                    <?php elseif ($auctionStatus === 'ended'): ?>
                        <span class="label label-default">Ended</span>
                    <?php else: ?>
                        <span class="label label-default">No date set</span>
                    <?php endif ?>
                </span>
                <span style="margin-left:8px; color:#999; font-size:12px;">Channel: <code><?= Html::encode($channel) ?></code></span>
            <?php endif ?>
            <span style="float:left;" class="panel-controls">
                <a href="#" class="panel-control-loader"></a>
                <a href="#" style="margin-left:5px" class="panel-control-collapse"></a>
            </span>
        </div>
        <div class="panel-body" style="display:block;">
            <div class="tab-content pn br-n admin-form">

                <!-- Row 1: Product + Lot Number + Start Price -->
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'product_id')->widget(Select2::class, [
                            'data'          => $auctionProducts,
                            'language'      => Yii::$app->language,
                            'options'       => ['placeholder' => Yii::t('app', 'Select Product')],
                            'pluginOptions' => ['allowClear' => true, 'multiple' => false],
                            'pluginLoading' => false,
                        ])->label(Yii::t('app', 'Product')) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'lot_number', [
                            'template' => '<div class="col-md-12" style="padding:0"><label for="auction-lot_number" class="field prepend-icon">
                                {input}<label for="auction-lot_number" class="field-icon"><i class="fa fa-tag"></i></label></label>{error}</div>'
                        ])->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Lot Number')])->label(false) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'start_price', [
                            'template' => '<div class="col-md-12" style="padding:0"><label for="auction-start_price" class="field prepend-icon">
                                {input}<label for="auction-start_price" class="field-icon"><i class="fa fa-money"></i></label></label>{error}</div>'
                        ])->textInput(['placeholder' => Yii::t('app', 'Start Price (֏)')])->label(false) ?>
                    </div>
                </div>

                <!-- Row 2: Start Date + End Date -->
                <div class="row mt10">
                    <div class="col-md-6">
                        <?= $form->field($model, 'start_date', [
                            'template' => '{label}<div class="input-group date" id="start-date-picker">{input}<span class="input-group-addon" style="cursor:pointer;"><span class="glyphicon glyphicon-calendar"></span></span></div>{error}',
                        ])->textInput([
                            'placeholder'  => 'YYYY-MM-DD HH:mm:ss',
                            'autocomplete' => 'off',
                        ])->label(Yii::t('app', 'Start Date & Time')) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'end_date', [
                            'template' => '{label}<div class="input-group date" id="end-date-picker">{input}<span class="input-group-addon" style="cursor:pointer;"><span class="glyphicon glyphicon-calendar"></span></span></div>{error}',
                        ])->textInput([
                            'placeholder'  => 'YYYY-MM-DD HH:mm:ss',
                            'autocomplete' => 'off',
                        ])->label(Yii::t('app', 'End Date & Time')) ?>
                    </div>
                </div>

                <!-- Row 3: Video Link -->
                <div class="row mt15">
                    <div class="col-md-12">
                        <?= $form->field($model, 'video_link', [
                            'template' => '<div class="col-md-12" style="padding:0"><label for="auction-video_link" class="field prepend-icon">
                                {input}<label for="auction-video_link" class="field-icon"><i class="fa fa-video-camera"></i></label></label>{error}</div>'
                        ])->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'External Video Link (optional — overrides Agora stream)')])->label(false) ?>
                    </div>
                </div>

            </div>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton(
                $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                ['class' => $model->isNewRecord ? 'btn btn-lg btn-primary' : 'btn btn-lg btn-success']
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord): ?>
    <!-- Agora live stream panel — only available for saved auctions -->
    <div class="panel sort-disable" data-panel-color="false" data-panel-fullscreen="false"
         data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span class="panel-title">
                <?= Yii::t('app', 'Live Stream (Admin Host)') ?>
                &nbsp;
                <span id="admin-stream-status" style="font-size:12px; color:#999;">Offline</span>
            </span>
            <span style="float:left;" class="panel-controls">
                <a href="#" class="panel-control-loader"></a>
                <a href="#" style="margin-left:5px" class="panel-control-collapse"></a>
            </span>
        </div>
        <div class="panel-body" style="display:block;">

            <?php if ($auctionStatus === 'ended'): ?>
                <div class="alert alert-default" style="background:#f5f5f5; border:1px solid #ddd; padding:12px; border-radius:4px;">
                    <i class="fa fa-info-circle"></i> <?= Yii::t('app', 'This auction has ended. Live streaming is no longer available.') ?>
                </div>

            <?php elseif ($auctionStatus === 'upcoming'): ?>
                <div class="alert alert-warning" style="padding:12px;">
                    <i class="fa fa-clock-o"></i>
                    <?= Yii::t('app', 'Auction starts at') ?>
                    <strong><?= date('d M Y, H:i', strtotime($model->start_date)) ?></strong>.
                    <?= Yii::t('app', 'You can pre-join to test your stream before it goes live.') ?>
                </div>
                <?= $this->renderFile('@backend/views/auction/_agora_host.php', ['model' => $model]) ?>

            <?php else: /* live or no-date — allow joining */ ?>
                <?= $this->renderFile('@backend/views/auction/_agora_host.php', ['model' => $model]) ?>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

</div>

<?php
$this->registerJs("
    $('#start-date-picker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        useSeconds: false,
        sideBySide: true
    });
    $('#end-date-picker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        useSeconds: false,
        sideBySide: true
    });
    $('#start-date-picker').on('dp.change', function(e) {
        if (e.date) {
            $('#end-date-picker').data('DateTimePicker').setMinDate(e.date);
        }
    });
");

if (!$model->isNewRecord) {
    $this->registerJsFile('/agora/videoSDK/AgoraRTC.js', ['depends' => [\yii\web\JqueryAsset::class]]);
    $this->registerJs('window.AGORA_CHANNEL = ' . json_encode($channel) . ';', \yii\web\View::POS_BEGIN);
    $this->registerJsFile('/js/agora-admin.js', ['depends' => [\yii\web\JqueryAsset::class]]);
}
?>

<style>
@keyframes blink {
    0%, 100% { opacity:1; }
    50%       { opacity:0; }
}
</style>
