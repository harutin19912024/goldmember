<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\Attribute;
use backend\models\ProductAttribute;
use backend\models\Slider;
use frontend\models\Category;
use backend\models\ProductAddress;
use backend\models\Product;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$template = '<div class="">{label}<div class="">{input}{error}</div></div>';

$productAddress = new ProductAddress();
$product = new Product();
$attribute = new Attribute();

$parentAttributes = Attribute::find()->where(['parent_id' => NUll])->asArray()->all();
$childAttributes = Attribute::find()->where('parent_id IS NOT NULL')->asArray()->all();
$attributes = [];

foreach ($parentAttributes as $v) {

    $childAttr = array_filter(
        $childAttributes,
        function ($e) use ($v) {
            return $e['parent_id'] == $v['id'];
        }
    );

    $attributes[$v['id']] = ['id' => $v['id'], 'name' => $v['name'], 'childAttributes' => $childAttr];
}

?>
<div class="booking-wrapper">
    <div class="booking">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => '/' . Yii::$app->language . '/filter-product', 'class' => "form1"]); ?>
        <div class="row">
            <div class="col1">
                <div class="select1_wrapper">
                    <div class="select1_inner">
                        <?=
                        $form->field($product, 'category_id', ['template' => $template])->widget(Select2::className(), [
                            'data' => $product->getAllCategories(),
                            'language' => Yii::$app->language,
                            'options' => ['placeholder' => Yii::t('app', 'Select Category'),
                                'value' => Yii::$app->request->getQueryParam('Product', []) && isset(Yii::$app->request->getQueryParam('Product', [])['category_id']) ? Yii::$app->request->getQueryParam('Product')['category_id'] : '',
                                'class' => 'select2 select',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => false,
                            ],
                            'pluginLoading' => false,
                        ])->label(Yii::t('app', 'Category'))
                        ?>
                    </div>
                    <a href="#" class="more">MISSING MANUFACTURER?</a>
                </div>
            </div>
            <div class="col1">
                <div class="select1_wrapper">
                    <label>Model</label>
                    <div class="select1_inner">
                        <?=
                        $form->field($productAddress, 'state', ['template' => $template])->widget(Select2::className(), [
                            'data' => $productAddress->getStates(),
                            'language' => Yii::$app->language,
                            'options' => ['placeholder' => Yii::t('app', 'Select State'), 'onchange' => 'seacrhFillRegion(this)',
                                'value' => Yii::$app->request->getQueryParam('ProductAddress', []) && Yii::$app->request->getQueryParam('ProductAddress', [])['state'] ? Yii::$app->request->getQueryParam('ProductAddress', '')['state'] : '',
                                'class' => 'select2 select',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => false,
                            ],
                            'pluginLoading' => false,
                        ])->label(Yii::t('app','Select State'))
                        ?>
                    </div>
                    <a href="#" class="more">MISSING MODEL?</a>
                </div>
            </div>
            <div class="col1">
                <div class="select1_wrapper">
                    <label>Status</label>
                    <div class="select1_inner">
                        <select class="select2 select" style="width: 100%">
                            <option value="1">Vehicle Status</option>
                            <option value="2">Status 1</option>
                            <option value="3">Status 2</option>
                            <option value="4">Status 3</option>
                            <option value="5">Status 4</option>
                            <option value="6">Status 5</option>
                            <option value="7">Status 6</option>
                        </select>
                    </div>
                    <a href="#" class="more">E.G: NEW, USED, CERTIFIED</a>
                </div>
            </div>
            <div class="col1">
                <div class="select1_wrapper">
                    <label>Min Year</label>
                    <div class="select1_inner">
                        <select class="select2 select" style="width: 100%">
                            <option value="1">Min Year</option>
                            <option value="2">2018</option>
                            <option value="3">2017</option>
                            <option value="4">2016</option>
                            <option value="5">2015</option>
                            <option value="6">2014</option>
                            <option value="7">2013</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col1">
                <div class="select1_wrapper">
                    <label>Max Year</label>
                    <div class="select1_inner">
                        <select class="select2 select" style="width: 100%">
                            <option value="1">Max Year</option>
                            <option value="2">2018</option>
                            <option value="3">2017</option>
                            <option value="4">2016</option>
                            <option value="5">2015</option>
                            <option value="6">2014</option>
                            <option value="7">2013</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col2">
                <div id="slider-range-wrapper">
                    <div class="txt">PRICE RANGE</div>

                    <div id="slider-range"></div>

                    <div class="clearfix">
                        <input type="text" id="amount" readonly>
                        <input type="text" id="amount2" readonly>
                    </div>
                </div>
            </div>
            <div class="col3">
                <div class="adv-serach"><a href="#">ADVANCED SEARCH</a></div>
                <button type="submit" class="btn-default btn-form1-submit"><span>SEARCH THE VEHICLE</span>
                </button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
