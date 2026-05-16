<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Metals $model */

$this->title = Yii::t('app', 'Create Metals');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Metals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metals-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
