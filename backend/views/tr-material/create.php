<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TrMaterial $model */

$this->title = Yii::t('app', 'Create Tr Material');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tr Materials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-material-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
