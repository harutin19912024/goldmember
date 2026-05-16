<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use common\models\Language;

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'Products');

$provider = new ArrayDataProvider([
    'allModels' => $products,
    'pagination' => ['pageSize' => 16],
    'sort' => ['attributes' => ['id']],
]);
$rows = $provider->getModels();

?>

<div id="content" class="mt-4">
    <div class="container">
        <div class="row">
            <?php if ($view_type != 'list'): ?>
                <?= $this->render('forms/products-grid-view', [
                    'products' => $rows,
                    'active'   => $active,
                    'page'     => $page,
                    'provider' => $provider,
                ]) ?>
            <?php else: ?>
                <?= $this->render('forms/products-list-view', [
                    'products' => $rows,
                    'active'   => $active,
                    'page'     => $page,
                    'provider' => $provider,
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
