<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\newsImage;
use backend\models\Attribute;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\news */
/* @var $model_attributes backend\models\newsAttribute */
/* @var $model_parts backend\models\newsParts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'newss'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$imagePaths = NewsImage::getImageBynewsId($model->id);
$defaultImage = NewsImage::getDefaultImageIdBynewsId($model->id);
$parts = $model_parts->getnewsParts($model->id);
$partsIds = ArrayHelper::map($parts, 'id', 'id');
$PartsName = ArrayHelper::map($parts, 'id', 'name');
$Parts = ArrayHelper::index($parts, 'id');
$attributes = $model_attributes->getAttributesBynewsId($model->id);
$placeholder = Attribute::getAttributeByCategory($model->category_id);
?>

<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Back to newss'), ['index',], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>


</div>
<div class="row">
    <div class="col-md-8">
        <div class="tab-block">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab1" data-toggle="tab">news Details</a>
                </li>
                <li>
                    <a href="#tab2" data-toggle="tab">Attribute Details</a>
                </li>
<!--                <li>-->
<!--                    <a href="#tab3" data-toggle="tab">news Parts</a>-->
<!--                </li>-->
                <li>
                    <a href="#tab4" data-toggle="tab">news Images</a>
                </li>
<!--                <li>-->
<!--                    <a href="#tab5" data-toggle="tab">news Parts Images</a>-->
<!--                </li>-->
            </ul>
            <div class="tab-content p30">
                <div id="tab1" class="tab-pane active">
                    <div class="panel-body pn">
                        <div class="table-responsive">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => [
                                    'class' => 'table table-striped table-hover display table-bordered dataTable no-footer',
                                    'id' => 'tbl_news'
                                ],
                                'template' => '<tr class="odd" role="row"><th scope="row">{label}</th><td>{value}</td></tr>',
                                'attributes' => [
//            'id',
                                    'name',
                                    'description:ntext',
                                    'short_description',
                                    'news_sku',
                                    [
                                        'attribute' => 'brand',
                                        'label' => 'Brand',
                                        'value' => $model->brand->name
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'value' => ($model->status == 0) ? 'Pasive' : 'Active'
                                    ],
                                    [
                                        'attribute' => 'price',
                                        'value' => ($model->price != '') ? $model->price . "\xE2\x82\xAc" : ''
                                    ],
//                                    [
//                                        'attribute' => 'price_start',
//                                        'value' => ($model->price_start != '') ? $model->price_start . "\xE2\x82\xAc" : ''
//                                    ],
//                                    [
//                                        'attribute' => 'price_end',
//                                        'value' => ($model->price_end != '') ? $model->price_end . "\xE2\x82\xAc" : ''
//                                    ],
//            'category_id',
                                    [
                                        'attribute' => 'category',
                                        'label' => 'Category',
                                        'value' => $model->category->name
                                    ]
//            'created_date',
//            'updated_date',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab-pane">
                    <div class="panel-body pn">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="editable-table1">
                                <thead>
                                <tr>
                                    <?php foreach ($placeholder as $item => $value) : ?>
                                        <th><?php echo $value ?></th>
                                    <?php endforeach; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php foreach ($attributes as $key => $value) : ?>
                                        <td><?php echo $value ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
<!--                <div id="tab3" class="tab-pane">-->
<!--                    <div class="panel-body pn">-->
<!--                        <div class="table-responsive">-->
<!--                            <table class="table table-bordered table-striped" id="editable-table2">-->
<!--                                <thead>-->
<!--                                <tr>-->
<!--                                    <th>Name</th>-->
<!--                                    <th>Description</th>-->
<!--                                    <th>Prise</th>-->
<!--                                    <th>Amount in stock</th>-->
<!---->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody>-->
<!--                                --><?php //foreach ($Parts as $item => $part): ?>
<!--                                    --><?php //unset($part['id']);
//                                    unset($part['news_id']) ?>
<!--                                    <tr>-->
<!--                                        --><?php //foreach ($part as $item): ?>
<!--                                            <td>--><?php //echo $item ?><!--</td>-->
<!--                                        --><?php //endforeach; ?>
<!--                                    </tr>-->
<!--                                --><?php //endforeach; ?>
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div id="tab4" class="tab-pane">
                    <div class="gallery-page sb-l-o sb-r-c onload-check">
                        <div class="tray tray-center" style="height: 700px">
                            <div class="mix-container">
                                <div class="fail-message">
                                    <span>No images were found for the selected news</span>
                                </div>

                                <?php if (!empty($imagePaths)) : ?>
                                    <!--                    --><?php //var_dump($imagePaths, $defaultImage); die; ?>
                                    <?php foreach ($imagePaths as $key => $imagePath): ?>
                                        <div style="display: inline-block;"
                                             class="mix label1 folder1 <?= ($defaultImage[$key] == $key) ? 'default-view' : '' ?>">
                            <span class="close remove hidden">
                                <i class="fa fa-close icon-close"></i>
                            </span>
                                            <div class="panel p6 pbn">
                                                <div class="of-h">
                                                    <?php echo Html::img('/uploads/images/' . $imagePath,
                                                        [
                                                            'class' => 'img-responsive',
                                                            'title' => $model->name,
                                                            'alt' => '',
                                                        ]) ?>
                                                    <div class="row table-layout change_image"
                                                         data-key="<?php echo $key ?>">
                                                        <div class="col-xs-8 va-m pln">
                                                            <h6><?= $model->name . '.jpg' ?></h6>
                                                        </div>
                                                        <div class="col-xs-4 text-right va-m prn">
                                                            <span class="fa fa-eye-slash fs12 text-muted"></span>
                                                            <span class="fa fa-circle fs10 text-info ml10"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="gap"></div>
                                <div class="gap"></div>
                                <div class="gap"></div>
                                <div class="gap"></div>

                            </div>
                        </div>
                    </div>
                </div>
<!--                <div id="tab5" class="tab-pane">-->
<!--                    <div class="gallery-page sb-l-o sb-r-c onload-check">-->
<!--                        <div class="">-->
<!--                            <div class="mix-container">-->
<!--                                <div class="fail-message">-->
<!--                                    <span>No images were found for the selected news</span>-->
<!--                                </div>-->
<!---->
<!--                                --><?php //if (!empty($PartsName)) : ?>
<!--                                    --><?php //foreach ($PartsName as $key => $Name): ?>
<!--                                        --><?php //$images = $model_parts->getImageByPartId($key) ?>
<!--                                        <div class="admin-form">-->
<!--                                            <div class="section-divider mb40" id="spy1">-->
<!--                                                <span>--><?php //echo $Name ?><!--</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        --><?php //foreach ($images as $item => $val) : ?>
<!--                                            <div style="display: inline-block;"-->
<!--                                                 class="mix label--><?php //echo $key ?><!-- folder--><?php //echo $key ?><!-- --><?//= ($val['default_image_id'] == 1) ? 'default-view' : '' ?><!--">-->
<!--                            <span class="close remove hidden">-->
<!--                                <i class="fa fa-close icon-close"></i>-->
<!--                            </span>-->
<!--                                                <div class="panel p6 pbn">-->
<!--                                                    <div class="of-h">-->
<!--                                                        --><?php //echo Html::img('/' . $val['name'],
//                                                            [
//                                                                'class' => 'img-responsive',
//                                                                'title' => $Name,
//                                                                'alt' => '',
//                                                            ]) ?>
<!--                                                        <div class="row table-layout change_image"-->
<!--                                                             data-key="--><?php //echo $val['id'] ?><!--">-->
<!--                                                            <div class="col-xs-8 va-m pln">-->
<!--                                                                <h6>--><?//= $Name . '.jpg' ?><!--</h6>-->
<!--                                                            </div>-->
<!--                                                            <div class="col-xs-4 text-right va-m prn">-->
<!--                                                                <span class="fa fa-eye-slash fs12 text-muted"></span>-->
<!--                                                                <span class="fa fa-circle fs10 text-info ml10"></span>-->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!---->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        --><?php //endforeach; ?>
<!--                                    --><?php //endforeach; ?>
<!--                                --><?php //endif; ?>
<!--                                <div class="gap"></div>-->
<!--                                <div class="gap"></div>-->
<!--                                <div class="gap"></div>-->
<!--                                <div class="gap"></div>-->
<!---->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</div>
