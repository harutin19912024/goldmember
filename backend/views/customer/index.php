<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerSearch */
/* @var $model backend\models\Customer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = Yii::t('app',$this->title);
?>
<div class="table-layout">
    <div class="tray tray-center">

<h3><?php echo Yii::t('app','Customers List'); ?></h3>
        <div class="panel">
            <div class="panel-body pn">
                <div class="table-responsive">
                    <?php Pjax::begin(['id' => 'customerPjaxtbl']) ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_customer'
                        ],
                        'filterRowOptions' => [
                            'role' => "row",
                        ],
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => false,
                        'columns' => [

                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'checkboxOptions' => [
                                    'style' => 'display:none',
                                    'label' => '<span class="checkbox mn"></span>',
                                    'class' => 'option block mn chk-usr',
                                    'value' => $model->id
                                ],
                                'header' => '<label for="select-all-users" class="option block mn chk-usrs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Select All Users">
                                              <input id="select-all-users" type="checkbox" name="select-all" class="select-on-check-all">
                                              <span class="checkbox mn"></span>
                                            </label>',
                            ],

                            ['attribute' => 'name',

                            ],
                            ['attribute' => 'surname',

                            ],
                            ['attribute' => 'email',
                                'format' => 'email',

                            ],
                            ['attribute' => 'phone',
                            ],
                            // 'status',
                            ['attribute' => 'status',
                                'contentOptions' => function ($model) {
                                    if ($model->status == 0) {
                                        return ['class' => "label list-status label-rounded label-danger"];
                                    } elseif ($model->status == 1) {
                                        return ['class' => "label list-status label-rounded label-system"];
                                    }
                                },
                                'value' => function ($model) {
                                    if ($model->status == 0) {
                                        return Yii::t('app',"Pasive");
                                    } else {
                                        return Yii::t('app',"Active");
                                    }
                                },

                            ],
                            // 'user_id',
                            ['attribute' => 'user',
                                'value' => 'user.username',
                            ],
                            ['attribute' => 'address',
                                'value' => function ($model) {
                                    $address = $model->getFullAddress($model->id);
                                    $addr = array_filter(array_values($address));
                                    return implode(', ', $addr);
                                },
                            ],
                            // 'last_ip',
                            // 'created_date',
                            // 'updated_date',

                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{edit}{view}{delete}',
                                'buttons' => [
                                    'edit' => function ($url, $model) {
                                        $title = '';
                                        $btn_class = "";
                                        $data_pjax = '';
                                        if ($model['status'] == 0) {
                                            $data_pjax = json_encode(['id' => $model['id'], 'status' => 1]);
                                            $title = Yii::t('app',"Enable <i class=\"fa fa-toggle-on text-success\"></i>");
                                            $btn_class = "btn-success";
                                            $cust_status = false;

                                        } elseif ($model['status'] == 1) {
                                            $data_pjax = json_encode(['id' => $model['id'], 'status' => 0]);
                                            $title = Yii::t('app',"Disable <i class=\"fa fa-toggle-off\"></i>");
                                            $btn_class = "btn-danger";
                                            $cust_status = true;
                                        }
                                        $link = '<div class="chsts switch switch-xs switch-primary round switch-inline">'.
                                            Html::checkbox($title,$cust_status,
                                            ['title' => Yii::t('app', $title),
                                                'class' => 'btn ' . $btn_class . ' br2 btn-xs fs12 cust_change_status',
                                                'id'=>'chk_sts'.$model->id,
                                                'data-pjax' => $data_pjax
                                            ]).'<label for="chk_sts'.$model->id.'" ></label></div>';
                                        return $link;
                                    },
                                    'view'=>function($url,$model){
                                        return Html::a(
                                            '<div class="btn-group">
                                                  <button type="button" class="btn btn-xs btn-info">
                                                    <i class="fa fa-eye"></i>
                                                  </button>
                                                </div>',
                                            $url,
                                            [
                                                'title' => Yii::t('app','View'),
                                                'aria-label' => Yii::t('app','View'),
                                                'data-pjax' => '0',
//                                                'class' =>'btn btn-info btn-xs fs12 br2 ml5',
                                            ]);
                                    },
                                    'delete' => function ($url, $model) {

                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> ',
                                            $url,
                                            [
                                                'title' => 'Delete',
                                                'aria-label' => 'Delete',
                                                'data-confirm' => 'Are you sure! You whant delete this item?',
                                                'data-method' => 'post',
                                                'data-pjax' => '0',
                                                'data-key' => $model->id,
                                                'class' => 'btn btn-danger btn-xs fs12 br2 ml5'
                                            ]);
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end() ?>
                    <div class="conteiner"></div>
                    <div class="action-block row col-lg-6">
                        <select id="checkbox-actions" data-action="customer">
                            <option class="activating">activating</option>
                            <option selected class="delete">Delete Items</option>
                        </select>
                        <input type="button" class="btn btn-info" value="accept">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: .tray-center -->

    <!-- begin: .tray-right -->
    <aside class="tray tray-right tray290 filter">
        <!-- menu quick links -->
        <div class="admin-form mw250">
            <h4> Filter Customers</h4>
            <hr class="short">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </aside>
    <!-- end: .tray-right -->
</div>
