<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\BodyTypes $model */

$this->title = Yii::t('app', 'Todays Exchange');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Todays Exchange'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
