<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PowerOfPenny $model */

$this->title = 'Create Power Of Penny';
$this->params['breadcrumbs'][] = ['label' => 'Power Of Pennies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="power-of-penny-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
