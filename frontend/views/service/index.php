<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Services';
?>

    <div class="blank-box"></div>
    <section class="step-heading">
        <div class="container">
            <div class="step-hinner">
                <h2>Reparez votre smartphone en moins d'une heure. </h2>
                <h4>Réparez votre iPhone, iPad, iPod ou Samsung Galaxy</h4>
            </div>
        </div>
    </section>
    <section class="select-block step-arrow">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="select-heading">
                        <h2>sélectionnez votre modèle</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                    </div>
                    <div class="select-inner">
                        <select id="select-brand">
                            <option value="">Select brand ...</option>
                            <?php foreach ($Brands as $key => $value): ?>
                                <?php if ($brand && $brand->id == $key): ?>
                                    <option value="<?= $key ?>" selected><?php echo $value ?></option>
                                <?php else: ?>
                                    <option value="<?= $key ?>"><?php echo $value ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <select id="select-product">
                            <option value="">
                                Select product ...
                            </option>
                            <?php if ($brand && $brand->id): ?>
                                <?php $products = $ProductModel->getProductsByBrand($brand->id); ?>
                                <?php foreach ($products as $id => $product): ?>
                                    <option value="<?= $id; ?>"><?= $product ?></option>
                                    <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="loder-icon" style="display: none">
                            <i class="fa fa-spinner fa-spin" style="font-size:32px"></i>
                        </div>
                    </div>
                    <div id="serv-container">
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$this->registerJs('
     
');
?>