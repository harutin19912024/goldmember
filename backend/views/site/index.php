<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */

$this->title = Yii::t('app', "Settings");
$this->params['breadcrumbs'][] = Yii::t('app', "Settings");
?>

<div class="table-layout">
    <div class="tray tray-center filter">
        <div id="brand-form_cont">
            <?= Html::a('<span class="fa pr5"></span>' . Yii::t('app', 'Change password'), ['/user/change-password'], ['class' => 'btn btn-system mb15']) ?>
            <?= Html::a('<span class="fa pr5"></span>' . Yii::t('app', 'Change login'), ['/user/change-username'], ['class' => 'btn btn-system mb15']) ?>
        </div>
        <!-- recent orders table -->
        <div class="panel">
            <div class="panel-body pn">
                <div class="table-responsive">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table table-striped admin-form table-hover display dataTable no-footer',
                            'id' => 'tbl_brand'
                        ],
                        'filterRowOptions' => [
                            'role' => "row"
                        ],
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd ui-state-default'
                        ],
                        'summary' => false,
                        'options' => ['class' => 'br-r', 'id' => 'users'],
                        'columns' => [
                                ['attribute' => 'username',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                ],
                            ],
                                ['attribute' => 'email',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                ],
                            ],
                                ['class' => 'yii\grid\ActionColumn',
                                'template' => '{edit}{update}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        $url = "/user/change-username";
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>' . Yii::t('app', 'Edit'), $url, [
                                                    'title' => 'Edit',
                                                    'aria-label' => 'Edit',
                                                    'data-key' => $model->id,
                                                    'class' => 'btn btn-info btn-xs fs12 br2 ml5 pull-right'
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
<div class="table-layout">
    <div class="tray tray-center filter">
        <div class="panel">
            <div class="panel-body pn">
                <div class="table-responsive">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderSettings,
//                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table table-striped admin-form table-hover display dataTable no-footer',
                            'id' => 'tbl_brand'
                        ],
                        'filterRowOptions' => [
                            'role' => "row"
                        ],
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd ui-state-default'
                        ],
                        'summary' => false,
                        'options' => ['class' => 'br-r', 'id' => 'brand'],
                        'columns' => [
                            ['attribute' => 'work_time',
                                'format' => 'html',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                ],
                            ],
                                ['attribute' => 'meta_tag',
                                'format' => 'html',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                ],
                            ],
                                ['attribute' => 'meta_description',
                                'format' => 'html',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                ],
                            ],
                                ['attribute' => 'site_title',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                ],
                            ],
                                ['attribute' => 'site_email',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                ],
                            ],
                            ['attribute' => 'site_phone',
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                ],
                            ],
                                ['class' => 'yii\grid\ActionColumn',
                                'template' => '{edit}{update}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        $url = "/sitesettings/update?id=" . $model->id;
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>' . Yii::t('app', 'Edit'), $url, [
                                                    'title' => 'Edit',
                                                    'aria-label' => 'Edit',
                                                    'data-key' => $model->id,
                                                    'class' => 'btn btn-info btn-xs fs12 br2 ml5 pull-right'
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
<div class="table-layout">
    <div class="tray tray-center filter">
	  <div id="product-form_cont">
		<?= Html::a('<span class="fa fa-plus pr5"></span>' . Yii::t('app', 'Create Social'), ['/social-net/create'], ['class' => 'btn btn-system mb15']) ?>
        </div>
        <div class="panel">
            <div class="panel-body pn">
                <div class="table-responsive">
			  <?=
			  GridView::widget([
				'dataProvider' => $dataProviderSocialNet,
//                        'filterModel' => $searchModel,
				'tableOptions' => [
				    'class' => 'table table-striped admin-form table-hover display dataTable no-footer',
				    'id' => 'tbl_brand'
				],
				'filterRowOptions' => [
				    'role' => "row"
				],
				'rowOptions' => [
				    'role' => "row",
				    'class' => 'odd ui-state-default'
				],
				'summary' => false,
				'options' => ['class' => 'br-r', 'id' => 'brand'],
				'columns' => [
					  ['attribute' => 'link',
					  'format' => 'html',
					  'filterInputOptions' => [
						'class' => 'form-control',
					  ],
				    ],
					  ['attribute' => 'social_type',
					  'format' => 'html',
					  'value' => function ($model) {
						return ucfirst($model->social_type);
					  },
					  'contentOptions' => ['style' => 'width:15%; white-space: normal;'],
					  'filterInputOptions' => [
					  'class' => 'form-control',
					  ],
					  ],
					  ['attribute' => 'active',
					  'contentOptions' => function ($model) {
					  if ($model->active == 1) {
					  return ['class' => "list-status label label-rounded label-info"];
				    } elseif ($model->active == 0) {
					  return ['class' => "list-status label label-rounded label-success"];
				    }
				},
				'value' => function ($model) {
				    if ($model->active == 1) {
					  return Yii::t('app', "Active");
				    } elseif ($model->active == 0) {
					  return Yii::t('app', "Passive");
				    }
				}
			  ],
				['class' => 'yii\grid\ActionColumn',
				'template' => '{edit}{update}',
				'buttons' => [
				    'update' => function ($url, $model) {
					  $url = '/' . Yii::$app->language . "/social-net/update?id=" . $model->id;
					  return Html::a('<span class="glyphicon glyphicon-pencil"></span>' . Yii::t('app', 'Edit'), $url, [
							  'title' => 'Edit',
							  'aria-label' => 'Edit',
							  'data-key' => $model->id,
							  'class' => 'btn btn-info btn-xs fs12 br2 ml5 pull-right'
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
<h1 style="text-align:center;"><?=Yii::t('app','Home Page Settings')?></h1>
<div class="table-layout">
    <div class="tray tray-center filter">
        <div class="panel">
            <div class="panel-body pn">
                <div class="table-responsive">
			  <?=
			  GridView::widget([
				'dataProvider' => $dataProviderHome,
//                        'filterModel' => $searchModel,
				'tableOptions' => [
				    'class' => 'table table-striped admin-form table-hover display dataTable no-footer',
				    'id' => 'tbl_home'
				],
				'filterRowOptions' => [
				    'role' => "row"
				],
				'rowOptions' => [
				    'role' => "row",
				    'class' => 'odd ui-state-default'
				],
				'summary' => false,
				'options' => ['class' => 'br-r', 'id' => 'brand'],
				'columns' => [
				['attribute' => 'image',
                                'format' => 'html',
                                'label' => Yii::t('app', 'Image'),
                                'value' => function ($model) {

                                    if($model->path){ $path = 'uploads/images/homepage/'.$model->id.'/'.$model->path;}else{ $path = 'img/default.png';}

                                    return Html::img('/' . $path, ['style' => 'width: 40px !important']);
                                },
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
							
					  ['attribute' => 'title',
					  'format' => 'html',
					  'filterInputOptions' => [
						'class' => 'form-control',
					  ],
				    ],
					  ['attribute' => 'description',
					  'format' => 'html',
					  'filterInputOptions' => [
						'class' => 'form-control',
					  ],
				    ],
					  ['class' => 'yii\grid\ActionColumn',
					  'template' => '{edit}{update}',
					  'buttons' => [
						'update' => function ($url, $model) {
						    $url = '/' . Yii::$app->language . "/homepage/update?id=" . $model->id;
						    return Html::a('<span class="glyphicon glyphicon-pencil"></span>' . Yii::t('app', 'Edit'), $url, [
								    'title' => 'Edit',
								    'aria-label' => 'Edit',
								    'data-key' => $model->id,
								    'class' => 'btn btn-info btn-xs fs12 br2 ml5 pull-right'
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