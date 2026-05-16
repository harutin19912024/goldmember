<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TrPowerOfPenny $model */

$this->title = 'Update Tr Power Of Penny: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tr Power Of Pennies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-power-of-penny-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
