<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TrBrand */

$this->title = Yii::t('app', 'Create Tr Brand');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tr Brands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-brand-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
