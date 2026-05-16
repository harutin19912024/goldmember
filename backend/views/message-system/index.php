<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Customer;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MessageSystemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Message Systems');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-system-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="table-layout">
        <div class="tray tray-center">

            <div id="form_message">
                <?php echo $_Form; ?>
            </div>
            <div class="panel">
                <div class="panel-body pn">
                    <div class="table-responsive">
                        <?php Pjax::begin(); ?>


                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => [
                                'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                                'id' => 'tbl_message_send'
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
                                ['attribute' => 'title',

                                ],
                                ['attribute' => 'recipient_user_id',
                                    'value' => function ($model) {
                                        $user = Customer::find()->where(['id' => $model->recipient_user_id])->asArray()->all();
                                        if(!empty($user)){
                                            return $user[0]['name'] . ' ' . $user[0]['surname'];
                                        }
                                    },

                                ],

                                // 'status',
                                ['attribute' => 'status',
                                    'contentOptions' => function ($model) {
                                        if ($model->status == 0) {
                                            return ['class' => "text-danger"];
                                        } elseif ($model->status == 1) {
                                            return ['class' => "text-success"];
                                        }
                                    },
                                    'value' => function ($model) {
                                        if ($model->status == 0) {
                                            return Yii::t('app', "Read");
                                        } else {
                                            return Yii::t('app', "Unread");
                                        }
                                    },

                                ],

                                ['class' => 'yii\grid\ActionColumn',
                                    'template' => '{delete}',
                                    'buttons' => [
                                        'delete' => function ($url, $model) {
                                            return Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                                                'class' => 'btn btn-danger btn-xs fs12 br2 ml5',
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        }
                                    ],
                                ],
                            ],
                        ]); ?>

                        <?php Pjax::end(); ?>

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
