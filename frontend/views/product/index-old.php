<?php
use yii\data\ArrayDataProvider;
$this->title = Yii::t('app','Mobile-Centre.Shop').' | '.Yii::t('app','Products');
?>
<section id="content">
    <!-- horizonal filter -->
    <?php $view_type = @$_COOKIE['view']?>
    <?= $this->render('forms/product-filtr', ['active' => $view_type]) ?>
    <div class="container product_wrapper">
        <div class="row">
            <div class="col-md-3">
                <?= $this->render('forms/brand-form', ['brands' => $brands,'cat_id'=>$cat_id,'checked_brands'=>$checked_brands]) ?>
            </div>
            <div class="col-md-9" id="product_content">

                <?php

                    $provider = new ArrayDataProvider([
                        'allModels' => $products,
                        'pagination' => [
                            'pageSize' => 12,
                        ],
                        'sort' => [
                            'attributes' => ['id', 'name'],
                        ],
                    ]);
                    $rows = $provider->getModels();
                ?>

                <?php if($view_type != "list"): ?>
                    <?= $this->render('forms/products-grid-view', [
                        'products' => $rows,
                        'active' => $active,
                        'page'=>$page,
                        'currency_details'=>$currency_details,
                        'provider'=>$provider
                        ]);
                    ?>
                <?php else:?>
                    <?= $this->render('forms/products-list-view', [
                        'products' => $rows,
                        'active' => $active,
                        'page'=>$page,
                        'currency_details'=>$currency_details,
                        'provider'=>$provider
                    ]) ?>
                <?php endif;?>

            </div>
    </div>

</section>