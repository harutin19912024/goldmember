<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\newsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
//?>
<div class="table-layout">
    <div class="tray tray-center">
        <!-- create new order panel -->
        <div id="news-form_cont">

            <?= Html::a(Yii::t('app','<span class="fa fa-plus pr5"></span>'.Yii::t('app', 'Create News')), ['/news/create'], ['class'=>'btn btn-system mb15']) ?>
        </div>
        <!-- recent orders table -->
        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_news'
                        ],
                        'filterRowOptions' => [
                            'role' => "row",
                        ],
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => false,
                        'options' => ['class' => 'br-r', 'id' => 'news'],
                        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'checkboxOptions' => [
                                    'style' => 'display:none',
                                    'label' => '<span class="checkbox mn"></span>',
                                    'class' => 'option block mn chk-usr',
                                ],
                                'header' => '<label for="select-all-users" class="option block mn chk-usrs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Select All newss">
                                              <input id="select-all-users" type="checkbox" name="select-all" class="select-on-check-all">
                                              <span class="checkbox mn"></span>
                                            </label>',
                            ],
                            ['attribute' => 'image',
                                'format' => 'html',
                                'label' => Yii::t('app', 'Image'),
                                'value' => function ($model) {
                                    $image = $model->getDefaultImage($model->id);

                                    if(isset($image[1])){ $path = 'uploads/images/news/'.$model->id.'/'.$image[1];}else{ $path = 'img/default.png';}

                                    return Html::img('/' . $path, ['style' => 'width: 40px !important']);
                                },
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
                            ['attribute' => 'title',
                                'format' => 'html',
                                'value' => function ($model) {
                                    $url = \yii\helpers\Url::toRoute(['news/index', 'id' => $model->id]);
                                    return Html::a($model->title, 'javascript: void(0);', ['class' => 'link']);
                                },
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
							['attribute' => 'category',
                                'value' => 'category.name',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}',
								'contentOptions' => ['style' => 'width:28%; white-space: normal;'],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                         return Html::a('<span class="glyphicon glyphicon-edit"></span>'.Yii::t('app','Edit'),
                                            $url,
                                            [
                                                'title' => Yii::t('app','Edit'),
                                                'aria-label' => 'Edit',
                                                'data-key' => $model->id,
                                                'class' => 'btn btn-info btn-xs fs12 br2 ml5'
                                            ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>'.Yii::t('app','Delete'),
                                            $url,
                                            [
                                                'title' => Yii::t('app','Delete'),
                                                'aria-label' => Yii::t('app','Delete'),
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
                    ]); ?>
                    <div class="conteiner"></div>
					<div class="action-block row col-lg-6">
                        <select id="checkbox-actions" data-action="news" data-style="btn-primary">
                            <option selected class="delete">Delete Items</option>
                        </select>
                        <input type="button" class="btn btn-xs btn-info" value="accept">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>