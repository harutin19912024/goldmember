<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use backend\models\ProductAddress;
use backend\models\Product;
use backend\models\Attribute;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$template = '<div class="">{label}<div class="">{input}{error}</div></div>';

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
if(\Yii::$app->user->identity->role != 1) {
    $this->params['breadcrumbs'][] = $this->title;
}

$brockers = User::find()->where(['role' => 1])->orderBy('ordering ASC')->asArray()->all();

$productAddress = new ProductAddress();
$product = new Product();

$search = '';

if(isset(explode('?',Yii::$app->request->url)[1])) {
	$search = '?'.explode('?',Yii::$app->request->url)[1];
}

$subcategory = null;
if(Yii::$app->user->identity->user_number == 101) {
	$subcategory = 0;
} elseif(Yii::$app->request->getQueryParam('Product', []) && Yii::$app->request->getQueryParam('Product')['sub_category'] !== 0) {
	$subcategory = Yii::$app->request->getQueryParam('Product')['sub_category'];
}

$attribute = new Attribute();
?>
<style>
    .flex-div {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    @media only screen and (max-width: 765px) {
        .mobile-100 {
            width: 100% !important;
        }
    }
	
	select2-container {
  min-width: 400px;
}

.select2-results__option {
  padding-right: 20px;
  vertical-align: middle;
}
.select2-results__option:before {
  content: "";
  display: inline-block;
  position: relative;
  height: 20px;
  width: 20px;
  border: 2px solid #e9e9e9;
  border-radius: 4px;
  background-color: #fff;
  margin-right: 20px;
  vertical-align: middle;
}
.select2-results__option[aria-selected=true]:before {
  font-family:fontAwesome;
  content: "\f00c";
  color: #fff;
  background-color: #f77750;
  border: 0;
  display: inline-block;
  padding-left: 3px;
}
.select2-container--default .select2-results__option[aria-selected=true] {
	background-color: #fff;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
	background-color: #eaeaeb;
	color: #272727;
}
.select2-container--default .select2-selection--multiple {
	margin-bottom: 10px;
}
.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
	border-radius: 4px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
	border-color: #f77750;
	border-width: 2px;
}
.select2-container--default .select2-selection--multiple {
	border-width: 2px;
}
.select2-container--open .select2-dropdown--below {
	
	border-radius: 6px;
	box-shadow: 0 0 10px rgba(0,0,0,0.5);

}
.select2-selection .select2-selection--multiple:after {
	content: 'hhghgh';
}
/* select with icons badges single*/
.select-icon .select2-selection__placeholder .badge {
	display: none;
}
.select-icon .placeholder {
	display: none;
}
.select-icon .select2-results__option:before,
.select-icon .select2-results__option[aria-selected=true]:before {
	display: none !important;
	/* content: "" !important; */
}
.select-icon  .select2-search--dropdown {
	display: none;
}
</style>

<div class="table-layout">
    <div class="tray tray-center">
        <!-- create new order panel -->

        <div class="row">
                <div id="product-form_cont" class="col-lg-1 col-sm-2">
                    <?= Html::a(Yii::t('app', '<span class="fa fa-plus pr5"></span>' . Yii::t('app', 'Create Product')), ['/product/create?search='.$search], ['class' => 'btn btn-system mb15']) ?>
                </div>
        </div>
        <!-- recent orders table -->
        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_product'
                        ],
						'layout' => "{pager}\n{items}\n{pager}",
                        'filterRowOptions' => [
                            'role' => "row",
                        ],
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => true,
                        'options' => ['class' => 'br-r', 'id' => 'product'],
                        'columns' => [
                            /* [
                                'class' => 'yii\grid\CheckboxColumn',
                                'checkboxOptions' => [
                                    'style' => 'display:none',
                                    'label' => '<span class="checkbox mn"></span>',
                                    'class' => 'option block mn chk-usr',
                                ],
                                'header' => !\Yii::$app->user->identity->role ?'<label for="select-all-users" class="option block mn chk-usrs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Select All Products">
                                              <input id="select-all-users" type="checkbox" name="select-all" class="select-on-check-all">
                                              <span class="checkbox mn"></span>
                                            </label>' : '',
                            ], */
                            ['attribute' => 'image',
                                'format' => 'html',
                                'label' => '',
								'headerOptions' => ['style' => 'width: 5%;'],
                                'value' => function ($model) {
                                    $image = $model->getDefaultImage($model->id);

                                    if (isset($image[1])) {
                                        $path = 'uploads/images/' . $image[1];
                                    } else {
                                        $path = '';
                                    }

                                    return Html::img('/' . $path, ['style' => 'width: 40px !important']);
                                },
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
                            ['attribute' => 'product_sku',
                                'format' => 'html',
								'headerOptions' => ['style' => 'width: 6%;'],
								'value' => function ($model) {
                                    return "<span>".$model->product_sku."</span>";
                                },
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
                            ['attribute' => 'category',
							'headerOptions' => ['style' => 'width: 5%;'],
                                'label' => 'Կատեգորիա',
                                'value' => 'category.name',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}',
                                'contentOptions' => ['style' => 'white-space: normal;'],
								'headerOptions' => ['style' => 'width: 9%;'],
                                'buttons' => [
                                    /* 'view' => function ($url, $model) {
                                        $search = '';

                                        if(isset(explode('?',Yii::$app->request->url)[1])) {
                                            $search = '?'.explode('?',Yii::$app->request->url)[1];
                                        }

                                        return Html::a('<span class="glyphicon glyphicon-view"></span>' . Yii::t('app', 'View'), $url.$search, [
                                            'title' => Yii::t('app', 'View'),
                                            'aria-label' => 'View',
                                            'data-key' => $model->id,
                                            'class' => 'btn btn-primary btn-xs fs12 br2 ml5'
                                        ]);
                                    },  */
                                    'update' => function ($url, $model) {
                                        if (\Yii::$app->user->identity->role == 1 && !\Yii::$app->user->identity->allow_create) return '';
                                        return Html::a('<span class="glyphicon glyphicon-edit"></span>' . Yii::t('app', 'Edit'), $url, [
                                            'title' => Yii::t('app', 'Edit'),
                                            'aria-label' => 'Edit',
                                            'data-key' => $model->id,
                                            'class' => 'btn btn-info btn-xs fs12 br2 ml5'
                                        ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        if (\Yii::$app->user->identity->role == 1) return '';
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>' . Yii::t('app', 'Delete'), $url, [
                                            'title' => Yii::t('app', 'Delete'),
                                            'aria-label' => Yii::t('app', 'Delete'),
                                            'data-confirm' => 'Are you sure! You whant delete this item?',
                                            'data-method' => 'post',
                                            'data-pjax' => '0',
                                            'data-key' => $model->id,
                                            'class' => 'btn btn-danger btn-xs fs12 br2 ml5'
                                        ]);
                                    },
                                ]
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').data('key');
        if(e.target == this)
			window.open('" . Url::to(['product/view']) . "?id=' + id+'".$search."','_blank')
    });

");

?>
