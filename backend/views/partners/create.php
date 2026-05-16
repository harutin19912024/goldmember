<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Partners $model */

$this->title = Yii::t('app', 'Create Partners');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Partners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
