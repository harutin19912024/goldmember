<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */
/* @var $customerAddress_model backend\models\CustomerAddress */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$address = $customerAddress_model->getCustomerAddressByCustomerId($model->id);
?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Back to Customers'), ['index'], ['class' => 'btn btn-sm btn-info']) ?>
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
                    <a href="#tab1" data-toggle="tab">Customer Details</a>
                </li>
                <li>
                    <a href="#tab2" data-toggle="tab">Customer Address</a>
                </li>
            </ul>
            <div class="tab-content p30">
                <div id="tab1" class="tab-pane active">
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
//            'id',
                                    'name',
                                    'surname',
                                    'email:email',
                                    'phone',
//            'status',
                                    [
                                        'attribute' => 'status',
                                        'value' => ($model->status == 0) ? 'Pasive' : 'Active'
                                    ],
//            'user_id',
                                   /*  [
                                        'attribute' => 'user',
                                        'label' => 'User',
                                        'value' => function($model){
											echo "<pre>"; print_r($model->user_id);die;
											//return isset($model->user->username)?$model->user->username: 0;
										},
                                    ], */
                                    'last_ip',
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
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Address</th>
                                    <th>State</th>
                                    <th>Long</th>
                                    <th>Lat</th>
                                    <th>Default Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($address as $key => $values) : ?>
                                    <tr>
                                        <?php unset($values['id']); unset($values['customer_id']) ?>
                                        <?php foreach ($values as $item => $value): ?>
                                            <?php if($item == 'default_address' && $values[$item] == 1): ?>
                                                <td>Default Address</td>
                                                <?php endif;?>
                                            <?php if($item != 'default_address'): ?>
                                                <td><?php echo $value ?></td>
                                            <?php endif;?>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>