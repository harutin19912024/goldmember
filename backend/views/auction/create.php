<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Auction $model */

$this->title = Yii::t('app', 'Create Auction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
