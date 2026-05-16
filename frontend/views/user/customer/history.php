<?php
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Order</th>
                <th>Date</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td><a href="#">#45006</a></td>
                <td>Sunday 18th March, 2015</td>
                <td>275$</td>
                <td class="text-success">Delivered</td>
            </tr>
            <tr>
                <td>2</td>
                <td><a href="#">#46440</a></td>
                <td>Monday 14th April, 2015</td>
                <td>575$</td>
                <td class="text-danger">Canceled</td>
            </tr>
            <tr>
                <td>2</td>
                <td><a href="#">#48700</a></td>
                <td>Friday 28th April, 2015</td>
                <td>205$</td>
                <td class="text-success">Delivered</td>
            </tr>
            <tr>
                <td>2</td>
                <td><a href="#">#51280</a></td>
                <td>Sunday 26th June, 2015</td>
                <td>455$</td>
                <td class="text-success">Delivered</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>