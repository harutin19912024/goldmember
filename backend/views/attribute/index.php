<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use backend\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AttributeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Filters');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);

$categories_model = new Category();
$categories = $categories_model->getAllCategories();
?>
<div class="table-layout">
    <div class="tray tray-center filter">
        
        <div id="attr-form_cont">
            <?= Html::a(Yii::t('app','<span class="fa fa-plus pr5"></span>'.Yii::t('app', 'Create Filter')), ['/attribute/create'], ['class'=>'btn btn-system mb15']) ?>
        </div>
        
        <!-- recent orders table -->
        <div class="panel">
            <div class="panel-body pn">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table table-striped admin-form table-hover display dataTable no-footer',
                            'id' => 'tbl_attr'
                        ],
                        'filterRowOptions' => [
                            'role' => "row",
                        ],
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => false,
                        'options' => ['class' => 'br-r', 'id' => 'product'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'checkboxOptions' => [
                                    'style' => 'display:none',
                                    'label' => '<span class="checkbox mn"></span>',
                                    'class' => 'option block mn chk-usr',
                                    'value' => $model->id
                                ],
                                'header' => '<label for="select-all-users" class="option block mn chk-usrs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Select All Products">
                                              <input id="select-all-users" type="checkbox" name="select-all" class="select-on-check-all">
                                              <span class="checkbox mn"></span>
                                            </label>',
                            ],
							['attribute' => 'image',
                                'format' => 'html',
                                'label' => Yii::t('app','Image'),
                                'value' => function ($model) {
                                        $path = 'uploads/images/attribute/'.$model->id.'/' . $model->path;
                                    return Html::img('/' . $path, ['style' => 'width: 40px !important']);
                                },
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
                            ['attribute' => 'name',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
//                            ['attribute' => 'value',
//                                'filterInputOptions' => [
//                                    'class' => 'form-control',
//                                    'placeholder' => 'Search'
//                                ],
//                            ],
//            'created_date',
//            'updated_date',
                            
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{delete}{update}',
                                'buttons' =>[
                                    'update' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>'.Yii::t('app','Edit'),
                                            $url,
                                            [
                                                'title' => Yii::t('app', 'Edit'),
                                                'aria-label' => Yii::t('app', 'Edit'),
                                                //'data-confirm' =>Yii::t('app', 'Are you sure! You whant delete this item?'),
                                                //'data-method' =>'post',
                                                //'data-pjax' => '0',
                                                'data-key' => $model->id,
                                                'class' => 'btn btn-info btn-xs fs12 br2 ml5 pull-right'
                                            ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>'.Yii::t('app','Delete'),
                                            $url,
                                            [
                                                'title' => Yii::t('app', 'Delete'),
                                                'aria-label' => Yii::t('app', 'Delete'),
                                                'data-confirm' =>Yii::t('app', 'Are you sure! You whant delete this item?'),
                                                'data-method' =>'post',
                                                'data-pjax' => '0',
                                                'data-key' => $model->id,
                                                'class' => 'btn btn-danger btn-xs fs12 br2 ml5 pull-right'
                                            ]);
                                    },
                                ]
                            ],
                        ],
                    ]); ?>
                    <div class="conteiner"></div>
                    <div class="action-block row col-lg-6">
                        <select id="checkbox-actions" data-action="attribute">
                            <option selected class="delete"><?=Yii::t('app','Delete Items')?></option>
                        </select>
                        <input type="button" class="btn btn-xs btn-info" value="<?=Yii::t('app','accept')?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- begin: .tray-right -->
    <aside class="tray tray-right tray290 filter">
        <!-- menu quick links -->
        <div class="admin-form mw250">

            <h4>Найти</h4>

            <hr class="short">
            <?php echo $this->render('_search', ['model' => $searchModel,'categories'=>$categories]); ?>
        </div>
    </aside>
</div>