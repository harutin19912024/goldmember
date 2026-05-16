<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TrAttribute */

$this->title = Yii::t('app', 'Create Tr Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tr Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-attribute-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
