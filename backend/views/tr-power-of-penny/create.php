<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TrPowerOfPenny $model */

$this->title = 'Create Tr Power Of Penny';
$this->params['breadcrumbs'][] = ['label' => 'Tr Power Of Pennies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-power-of-penny-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
