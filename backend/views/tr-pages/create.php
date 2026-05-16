<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TrPages */

$this->title = Yii::t('app', 'Create Tr Pages');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tr Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-pages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
