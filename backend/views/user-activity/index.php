<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\UserActivitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'User Activities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-layout">
    <div class="tray tray-center">
        <div class="panel">
            <div class="panel-body pn">
                <div class="table table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table admin-form theme-warning tc-checkbox-1 fs13',
                            'id' => 'tbl_user_activity'
                        ],
                        'layout' => "{pager}\n{items}\n{pager}",
                        'rowOptions' => [
                            'role' => "row",
                            'class' => 'odd'
                        ],
                        'summary' => true,
                        'options' => ['class' => 'br-r', 'id' => 'user-activity'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            'user_id',
                            'session_id',
                            'ip_address',
                            'last_activity',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
