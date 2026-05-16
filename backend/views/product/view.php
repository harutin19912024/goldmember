<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\ProductImage;
use backend\models\ProductAttribute;
use backend\models\Attribute;
use backend\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $model_attributes backend\models\ProductAttribute */
/* @var $model_parts backend\models\ProductParts */

$this->title = $model->title;
if(\Yii::$app->user->identity->role != 1) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}

?>

<?php

$id = $model->id;

$imagePaths = ProductImage::getImageByProductId($model->id);
$otherImagePaths = ProductImage::getImageByProductIdOther($model->id);
$defaultImage = ProductImage::getDefaultImageIdByProductId($model->id);

$attributesModel = new ProductAttribute();

$attributes = $attributesModel->getAttributesByProductId($model->id);
//$placeholder = Attribute::getAttributeByCategory($model->category_id);
$placeholder = [];

?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <a class="btn btn-sm btn-info" href="/am/product/index"><?= Yii::t('app', 'Back to Products') ?></a>
        <a class="btn btn-sm btn-success" href="<?=Yii::$app->params['baseUrlHome']?>apartments/<?= $model->id ?>" target="_blank"><?= Yii::t('app', 'See Product on First Page') ?></a>
    </p>


</div>
<div class="row">
    <div class="col-md-8">
        <div class="tab-block">
            <ul class="nav nav-tabs">
				<li class="active">
                    <a href="#tab4" data-toggle="tab">Images</a>
                </li>
                <li>
                    <a href="#tab1" data-toggle="tab">Product Details</a>
                </li>
                <?php if(\Yii::$app->user->identity->role == 0 || $model['broker_id'] == \Yii::$app->user->identity->id || \Yii::$app->user->identity->user_number == 101) : ?>
                <li>
                    <a href="#tab2" data-toggle="tab">More</a>
                </li>
                <?php endif; ?>
            </ul>
            <div class="tab-content p30">
                <div id="tab1" class="tab-pane">
                    <div class="panel-body pn">
                        <div class="table-responsive">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => [
                                    'class' => 'table table-striped table-hover display table-bordered dataTable no-footer',
                                    'id' => 'tbl_product'
                                ],
                                'template' => '<tr class="odd" role="row"><th scope="row">{label}</th><td>{value}</td></tr>',
                                'attributes' => [
                                    'title',
                                    'short_description:ntext',
                                   
                                    [
                                        'attribute' => 'price',
                                        'value' => ($model->price != '') ? $model->price . "$" : ''
                                    ],
                                    [
                                        'attribute' => 'category',
                                        'label' => 'Կատեգորիա',
                                        'value' => $model->category && $model->category->name ? $model->category->name : ''
                                    ],
                                ],
                            ]) ?>
                            <table class="table table-striped table-hover display table-bordered dataTable no-footer" style="margin-top: 15px">
                                <tbody>
                                <?php foreach ($filters as $key => $value) : ?>
                                    <tr class="odd" role="row">
                                        <th scope="row"><?= $value['attribute'] ?></td>
                                        <td><?= $value['filter'] ? $value['filter'] : $value['value'] ?></td>
                                    </tr>
                                <?php endforeach; ?>

                                </tbody>
                            </table>
                            <table class="table table-striped table-hover display table-bordered dataTable no-footer">
                                <tbody>

                                <?php foreach ($productDetails as $key => $value) : ?>
                                    <tr class="odd" role="row">
                                        <th scope="row"><?= $value['name'] ?></td>
                                        <td><?= $value['value'] ?></td>
                                    </tr>
                                <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab-pane">
                    <div class="panel-body pn">
                        
						<div>
						<div class="gallery-page sb-l-o sb-r-c onload-check">
                        <div class="tray tray-center" style="height: 700px">
                            <div class="mix-container">
							<?php if (!empty($otherImagePaths)) : ?>
                                    <?php foreach ($otherImagePaths as $key => $imagePath): ?>
                                        <div style="display: inline-block;"
                                             class="mix2 label1 folder1 <?= (isset($defaultImage[$key]) && $defaultImage[$key] == $key) ? 'default-view' : '' ?>">
                            <span class="close remove hidden">
                                <i class="fa fa-close icon-close"></i>
                            </span>
                                            <div class="panel p6 pbn">
                                                <div class="of-h">
                                                    <?php echo Html::img('/uploads/images/' . $imagePath['name'],
                                                        [
                                                            'class' => 'img-responsive',
                                                            'title' => $model->title,
                                                            'alt' => '',
                                                        ]) ?>
                                                    <div class="row table-layout change_image"
                                                         data-key="<?php echo $imagePath['id'] ?>">
                                                        <div class="col-xs-8 va-m pln">
                                                            <h6><?= $model->title . '.jpg' ?></h6>
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
                    </div>
                </div>
                <div id="tab4" class="tab-pane active">
                    <div class="gallery-page sb-l-o sb-r-c onload-check">
                        <div class="tray tray-center" style="height: 700px">
                            <div class="mix-container">
                                <div class="fail-message">
                                    <span>No Images</pan>
                                </div>

                                <?php if (!empty($imagePaths)) : ?>
                                    <?php foreach ($imagePaths as $key => $imagePath): ?>
                                        <div style="display: inline-block;"
                                             class="mix label1 folder1 <?= (isset($defaultImage[$key]) && $defaultImage[$key] == $key) ? 'default-view' : '' ?>">
                            <span class="close remove hidden">
                                <i class="fa fa-close icon-close"></i>
                            </span>
                                            <div class="panel p6 pbn">
                                                <div class="of-h">
                                                    <?php echo Html::img('/uploads/images/' . $imagePath['name'],
                                                        [
                                                            'class' => 'img-responsive',
                                                            'title' => $model->title,
                                                            'alt' => '',
                                                        ]) ?>
                                                    <div class="row table-layout change_image"
                                                         data-key="<?php echo $imagePath['id'] ?>">
                                                        <div class="col-xs-8 va-m pln">
                                                            <h6><?= $model->title . '.jpg' ?></h6>
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
            </div>
        </div>
    </div>
</div>

<script>
    let elem = $('#tbl_product > tbody > tr:nth-child(2) > td')
    let html = elem.html();
    html = html.replace(/&lt;/gi, '<');
    html = html.replace(/&gt;/gi, '>');
    if(html != '44') elem.html(html);


    let elem2 = $('#tbl_product > tbody > tr:nth-child(3) > td')
    let html2 = elem2.text();
    elem2.html(html2);
</script>
