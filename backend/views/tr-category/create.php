<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TrCategory */

$this->title = Yii::t('app', 'Create Tr Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tr Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
