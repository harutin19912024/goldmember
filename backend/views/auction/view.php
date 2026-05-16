<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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

$this->title = $model->product ? Html::encode($model->product->title) : 'Auction #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
                    'start_price:decimal',
                    'video_link',
                    [
                        'attribute' => 'user_id',
                        'value'     => $model->user ? $model->user->username : '—',
                    ],
                    'created_date:datetime',
                    'updated_at:datetime',
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

</div>
