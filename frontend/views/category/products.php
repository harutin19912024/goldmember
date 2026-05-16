<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<section id="content">
    <!-- horizonal filter -->
    <?= $this->render('product-filtr', ['active' => $active]) ?>
    <div id="products-part">
        <!-- products grid -->
        <?= $this->render($view, [
            'products' => $products,
        ])
        ?>
    </div>
</section>